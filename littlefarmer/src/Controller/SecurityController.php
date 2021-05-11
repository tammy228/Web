<?php

namespace App\Controller;

use App\Entity\ForgetPasswordVerify;
use App\Entity\User;
use App\Entity\UserEmailVerifyToken;
use App\Form\FarmerType;
use App\Form\ForgetPasswordEmailType;
use App\Form\ForgetPasswordType;
use App\Form\ProductType;
use App\Form\RegisterType;
use App\Form\ResetPasswordType;
use App\Form\UserType;
use App\Form\VerifyType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Exception;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Ramsey\Uuid\Uuid;
use SendGrid\Mail\TypeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use \SendGrid\Mail\Mail;


class SecurityController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @Route("/auth/login", name="auth.login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/facebook", name="connect.facebook.start")
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     */
    public function facebookLogin(ClientRegistry $clientRegistry)
    {
        // will redirect to Facebook!
        return $clientRegistry
            ->getClient('facebook_main') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'public_profile' // the scopes you want to access
            ],[]);

    }

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/facebook/check", name="connect.facebook.check")
     * @param Request $request
     * @param ClientRegistry $clientRegistry
     */
    public function facebookCheckLogin(Request $request, ClientRegistry $clientRegistry)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)
    }

    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/google", name="connect.google.start")
     * @param ClientRegistry $clientRegistry
     * @return RedirectResponse
     */
    public function googleLogin(ClientRegistry $clientRegistry)
    {
        // will redirect to Google!
        return $clientRegistry
            ->getClient('google') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'profile' // the scopes you want to access
            ],[]);

    }

    /**
     * After going to Google, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/google/check", name="connect.google.check")
     * @param Request $request
     * @param ClientRegistry $clientRegistry
     */
    public function googleCheckLogin(Request $request, ClientRegistry $clientRegistry)
    {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)
    }

    /**
     * @Route("/auth/logout", name="auth.logout")
     */
    public function logout()
    {
        return $this->redirect('/auth/login');
    }

    /**
     * @Route("/register", name="auth.register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @return Response
     * @throws TypeException
     * @throws Exception
     */
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler
                             ): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if (!filter_var($form['email']->getData(), FILTER_VALIDATE_EMAIL)) {
                return $this->render(
                    'security/register.html.twig',
                    array('form' => $form->createView(),
                        'error'=> '電子郵件錯誤')
                );
            }

            $user->setUuid();
            $user->setCreateAt();
            $user->setRoles(['ROLE_USER']);

            $password = $passwordEncoder->encodePassword($user, $form['plainPassword']->getData());
            $user->setPassword($password);
            $user->setRoleCodes(2);

            $name = $form['name']->getData();
            $random = (string)random_int(100000, 999999);
            $token = new UserEmailVerifyToken();
            $token->setUser($user->getUuid());
            $token->setToken($random);

            $token->setCreateAt(new \Datetime('now + 8hours'));
            $token->setExpireAt(new \Datetime('now + 8hours 10 minutes'));


            $email= new Mail();
            $email->setFrom("no-reply@littlefarmer.com", "小農點點");
            $email->setSubject('驗證電子郵件');
            $email->addTo($form['email']->getData(),'user');
            $email->addContent(
                "text/html", "重新登入並填入驗證碼 \n 驗證碼 :".$random
            );
            $sendgrid = new \SendGrid($_ENV['SENDGRID_KEY']);

            $sendgrid->send($email);


            $em = $this->getDoctrine()->getManager();
            $em->persist($token);
            $em->persist($user);

            $em->flush();

            echo "<script>alert('成功註冊');</script>";;
            return $this->redirectToRoute('auth.login');

        }
        return $this->render(
            'security/register.html.twig',
            array(
                'form' => $form->createView(),
                'error'=>null
            )
        );
    }

    /**
     * @Route("user/register/success", name="user.register.success")
     */
    public function emailVerify()
    {
        return $this->render('user/user/success.html.twig');
    }

    /**
     * @Route("user/verify", name="user.verify")
     * @param Request $request
     * @return RedirectResponse|Response
     * @throws TypeException
     */
    public function verify(Request $request)
    {
        if(!$this->getUser())
            return $this->redirectToRoute('auth.login');
        $user = $this->getUser();
        $uuid = $user->getUuid();
        if($user->getEmailVerified()){return $this->redirectToRoute('auth.login');}

        $message='';
        $form = $this->createForm(VerifyType::class);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $verify = $em->getRepository(UserEmailVerifyToken::class)->findOneBy(array('user'=>$uuid));
        if(!$verify)
        {
            $this->sendVerify($uuid,'小農點點驗證信', new UserEmailVerifyToken());
            return $this->redirectToRoute('user.verify');
        }

        $now = new \Datetime('now + 8hours');

        if($verify and $now>$verify->getExpireAt())
        {
            $em->remove($verify);
            $em->flush();
            $this->sendVerify($uuid,'小農點點驗證信', new UserEmailVerifyToken());

            return $this->render(
                'user/user/send.html.twig',array(
                    'error'=>'',
                )
            );
        }

        if($form->isSubmitted() && $form->isValid()){
            $token = $form['token']->getData();
            if($token==$verify->getToken()){
                $user->validateEmail();
                $em->persist($user);
                $em->remove($verify);
                $em->flush();

                return $this->redirectToRoute('user.index');
            }
            $message = '驗證碼錯誤';
            return $this->render(
                'user/user/verify.html.twig',
                array('form'=> $form->createView(),
                    'message'=>$message,
                    'error'=>'')
            );
        }

        return $this->render(
            'user/user/verify.html.twig',
            array('form'=> $form->createView(),
                'message'=>$message,
                'error'=>'')
        );
    }

    /**
     * @Route("/user/resetPassword/", name="user.resetPassword")
     */
    public function userResetPassword(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = $this->getUser();
        $uuid = $user->getUuid();
        $form = $this->createForm(ResetPasswordType::class,null);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $oldPassword = $form['oldPassword']->getData();

            if(!$passwordEncoder->isPasswordValid($user, $oldPassword))
            {
                return $this->render('user/user/changePassword.html.twig',array(
                    'form'=>$form->createView(),
                    'msg'=>'密碼錯誤'
                ));
            }
            $newPassword = $form['newPassword']->getData();
            $user -> setPassword($passwordEncoder->encodePassword(
                $user,
                $newPassword
            ));
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('user.info',array('uuid'=>$uuid));

        }
        return $this->render('user/user/changePassword.html.twig',array(
            'form'=>$form->createView(),
            'msg' => '',
        ));
    }


    /**
     * @Route("/auth/forgetPassword", name="auth.forgetPassword")
     * @param Request $request
     * @param UserRepository $userRepository
     * @return RedirectResponse|Response
     * @throws TypeException
     */
    public function forgetPassword(Request $request, UserRepository $userRepository)
    {
        $form = $this->createForm(ForgetPasswordEmailType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $email =$form['email']->getData();
            $user = $userRepository->findOneBy(array('email'=>$email));
            if(!$user)
                return $this->render('user/user/forgetPasswordEmail.html.twig', [
                    'form' => $form->createView(),
                    'message' => '無此信箱',
                ]);
            $uuid = $user->getUuid();

            $this->sendVerify($uuid, "忘記密碼", new ForgetPasswordVerify());

            return $this->redirectToRoute('user.forgetPassword',array('uuid'=>$uuid));
        }

        return $this->render('user/user/forgetPasswordEmail.html.twig', [
            'form' => $form->createView(),
            'message' => '',
        ]);
    }

    /**
     * @Route("auth/forgetPassword/{uuid}", name="user.forgetPassword")
     * @param $uuid
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     * @throws TypeException
     */
    public function userForgetPassword($uuid, Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ForgetPasswordType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(array('uuid'=>$uuid));
            $verify = $user->getFPVerify();
            $now =new \Datetime('now + 8hours');
            if($now > $verify->getExpireAt() )
            {
                $this->sendVerify($uuid, '忘記密碼', new ForgetPasswordVerify());
                $em->remove($verify);
                $em->flush();
                return $this->render('user/user/forgetPassword.html.twig', [
                    'form' => $form->createView(),
                    'message' => '時間超過，重寄驗證碼',
                    'uuid' => $uuid,
                ]);
            }

            $token = $form['verify']->getData();
            if($token != $verify->getVerify())
            {
                return $this->render('user/user/forgetPassword.html.twig', [
                    'form' => $form->createView(),
                    'message' => '驗證碼錯誤',
                    'uuid' => $uuid,
                ]);
            }
            $password = $form['password']->getData();
            $user -> setPassword($passwordEncoder->encodePassword(
                $user,
                $password
            ));

            $em->remove($verify);
            $em->flush();

            echo "<script>alert('修改密碼成功');</script>";
            return $this->redirectToRoute('user.index');
        }

        return $this->render('user/user/forgetPassword.html.twig', [
            'form' => $form->createView(),
            'message' => '',
            'uuid' => $uuid,
        ]);
    }

    /**
     * @param $uuid
     * @param $subject
     * @param $token
     * @throws TypeException
     * @throws Exception
     */
    public function sendVerify($uuid, $subject, $token)
    {
        $url  = $this->generateUrl('user.forgetPassword',array('uuid'=>$uuid));
        $user = $this->userRepository->findOneBy(array('uuid'=>$uuid));

        $random = (string)random_int(100000, 999999);
        if($subject == '忘記密碼')
            $token->setUser($user);
        else
            $token->setUser($uuid);
        $token->setVerify($random);
        $token->setCreateAt();
        $token->setExpireAt();

        $em = $this->getDoctrine()->getManager();
        $em->persist($token);
        $em->flush();

        $email= new Mail();
        $email->setFrom("no-reply@littleFarmer.com", "小農點點");
        $email->setSubject($subject);
        $email->addTo($user->getEmail(),'user');
        $email->addContent(
            "text/html", '驗證碼:'.$random.'<br>'.$url
        );

        $sendgrid = new \SendGrid($_ENV['SENDGRID_KEY']);

        $sendgrid->send($email);
    }





}
