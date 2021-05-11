<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserVerify;
use App\Form\AdminLoginType;
use App\Form\ForgetPasswordEmailType;
use App\Form\ForgetPasswordType;
use App\Form\RegisterType;
use App\Form\VerifyType;
use App\Repository\UserRepository;
use App\Repository\UserVerifyRepository;
use Exception;
use Ramsey\Uuid\Uuid;
use SendGrid\Mail\Mail;
use SendGrid\Mail\TypeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /***
     *       __  __
     *      / / / /_____ ___   _____
     *     / / / // ___// _ \ / ___/
     *    / /_/ /(__  )/  __// /
     *    \____//____/ \___//_/
     *
     */

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

        return $this->render('admin/security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
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
            $em = $this->getDoctrine()->getManager();

            if (!filter_var($form['email']->getData(), FILTER_VALIDATE_EMAIL)) {
                return $this->render(
                    'security/register.html.twig',
                    array('form' => $form->createView(),
                        'error'=> '電子郵件錯誤',
                        'error_mobile' => '')
                );
            }
            if (preg_match("/^09[0-9]{2}-[0-9]{3}-[0-9]{3}$/", $form['mobile']->getData())) {
                $i=0;    // 09xx-xxx-xxx
            } else if(preg_match("/^09[0-9]{2}-[0-9]{6}$/", $form['mobile']->getData())) {
                $i=0;     // 09xx-xxxxxx
            } else if(preg_match("/^09[0-9]{8}$/", $form['mobile']->getData())) {
                $i=0;    // 09xxxxxxxx
            } else {
                return $this->render(
                    'security/register.html.twig',
                    array('form' => $form->createView(),
                        'error'=> '',
                        'error_mobile' => '電話錯誤'
                            )
                );
            }
            $user->setAddress($_POST['county'].';'.$_POST['township'].';'.$_POST['postalCode'].';'.$form['address']->getData());

            $user->setUuid();
            $user->setCreateAt();
            $user->setRoles(['ROLE_USER']);

            $password = $passwordEncoder->encodePassword($user, $form['plainPassword']->getData());
            $user->setPassword($password);
            $user->setRoleCodes(1);

            $uuid = $user->getUuid();

            $this->sendVerify($uuid, "註冊驗證信", new UserVerify(),1,$form['email']->getData());

            $em->persist($user);

            $em->flush();

            $token = new UsernamePasswordToken(
                $user,
                $password,
                'main',
                $user->getRoles()
            );

            $this->get('security.token_storage')->setToken($token);
            $this->get('session')->set('_security_main', serialize($token));

            return $this->redirectToRoute('user.verify');


        }
        return $this->render(
            'security/register.html.twig',
            array(
                'form' => $form->createView(),
                'error'=>null,
                'error_mobile' => null
            )
        );
    }

    /**
     * @Route("auth/forgetPassword", name="user.forgetPassword")
     * @param UserVerifyRepository $userVerifyRepository
     * @param Request $request
     * @param UserRepository $userRepository
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse|Response
     * @throws TypeException
     */
    public function userForgetPassword(UserVerifyRepository $userVerifyRepository, Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $passwordEncoder)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ForgetPasswordType::class, null);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(array('email'=>$form['email']->getData()));
            if(!$user) return $this->render('security/forgetPassword.html.twig', [
                'form' => $form->createView(),
                'message' => '無此信箱',
            ]);
            $uuid = $user->getUuid();

            $verify = $userVerifyRepository->findOneBy(array('userId'=>$uuid));
            $now =new \Datetime('now + 8hours');
            if($now > $verify->getExpireAt() )
            {
                $this->sendVerify($uuid, '忘記密碼', new UserVerify(),0, $user->getEmail());
                $em->remove($verify);
                $em->flush();
                return $this->render('security/forgetPassword.html.twig', [
                    'form' => $form->createView(),
                    'message' => '時間超過，重寄驗證碼',
                ]);
            }

            $token = $form['verify']->getData();
            if($token != $verify->getToken())
            {
                return $this->render('security/forgetPassword.html.twig', [
                    'form' => $form->createView(),
                    'message' => '驗證碼錯誤',
                ]);
            }
            $password = $_POST['password'];
            $user -> setPassword($passwordEncoder->encodePassword(
                $user,
                $password
            ));

            $em->remove($verify);
            $em->flush();

            echo "<script>alert('修改密碼成功');</script>";
            return $this->redirectToRoute('user.index');
        }

        return $this->render('security/forgetPassword.html.twig', [
            'form' => $form->createView(),
            'message' => '',
        ]);
    }

    /**
     * @param $uuid
     * @param $subject
     * @param $token
     * @param $id
     * @param $userMail
     * @throws TypeException
     * @throws Exception
     */
    public function sendVerify($uuid, $subject, $token, $id, $userMail)
    {
        $url  = $this->generateUrl('user.forgetPassword',array('uuid'=>$uuid));
        $user = $this->userRepository->findOneBy(array('uuid'=>$uuid));

        $random = (string)random_int(100000, 999999);

        $token->setUserId($uuid);
        $token->setToken($random);
        $token->setCreateAt();
        $token->setExpireAt();
        $token->setVerifyCode($id);

        $em = $this->getDoctrine()->getManager();
        $em->persist($token);
        $em->flush();

        $email= new Mail();
        $email->setFrom("no-reply@collete.com", "格蕾朵");
        $email->setSubject($subject);
        $email->addTo($userMail,'user');
        $email->addContent(
            "text/html", '驗證碼:'.$random.'<br>'.$url
        );

        $sendgrid = new \SendGrid($_ENV['SENDGRID_KEY']);

        $sendgrid->send($email);
    }

    /**
     * @Route("user/verify", name="user.verify")
     * @param Request $request
     * @param UserVerifyRepository $userVerifyRepository
     * @return RedirectResponse|Response
     * @throws TypeException
     */
    public function userVerify(Request $request, UserVerifyRepository $userVerifyRepository)
    {
        $form = $this->createForm(VerifyType::class);
        $form -> handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if(!$user) return $this->redirectToRoute('user.index');
        $uuid = $user->getUuid();

        $verify = $userVerifyRepository->findOneBy(['userId'=>$uuid , 'verifyCode'=>1]);
        if(!$verify){
            $this->sendVerify($uuid, "註冊驗證信", new UserVerify(),1,$user->getEmail());
            return $this->redirectToRoute('user.verify');
        }

        if(new \DateTime('now + 8hours')>$verify->getExpireAt())
        {
            $em->remove($verify);
            $em->flush();
            $this->sendVerify($uuid, "註冊驗證信", new UserVerify(),1,$user->getEmail());
            return $this->redirectToRoute('user.verify');
        }

        if($form->isSubmitted() && $form-> isValid())
        {
            if($verify->getToken() == $form['verify']->getData())
            {
                $user->validateEmail();
                $em->persist($user);
                $em->remove($verify);
                $em->flush();

                return $this->redirectToRoute('user.index');
            }
            else{
                return $this->render('user/verify.html.twig', [
                    'form' => $form->createView(),
                    'message' => '驗證碼錯誤'
                ]);
            }
        }
        return $this->render('user/verify.html.twig', [
            'form' => $form->createView(),
            'message' => ''
        ]);
    }

    /**
     * @Route("user/edit", name="user.edit")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return RedirectResponse
     */
    public function userEdit(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $user= $this->getUser();

        $user->setName($_POST['name']);
        $user->setMobile($_POST['mobile']);
        $user->setAddress($_POST['county'].';'.$_POST['township'].';'.$_POST['postalCode'].';'.$_POST['address']);

        if($_POST['password'] and $_POST['password_two'])
        {
            $user -> setPassword($passwordEncoder->encodePassword(
                $user,
                $_POST['password']
            ));
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);

        $em->flush();


        return $this->redirectToRoute('user.info');
    }



    /**
     * @Route("forgetPassword/", name="forgetPassword")
     * @param UserRepository $userRepository
     * @return Response
     * @throws TypeException
     */
    public function forgetPassword(UserRepository $userRepository)
    {
        $email =$_POST['email'];
        $user = $userRepository->findOneBy(array('email'=>$email));
        if(!$user)
            return $this->render('security/send.html.twig',[
                'message' => '無此信箱',
                ]);
        $uuid = $user->getUuid();

        $this->sendVerify($uuid, "忘記密碼", new UserVerify(),0,$user->getEmail());
        return $this->render('security/send.html.twig',[
            'message' => '已發送驗證碼'
        ]);

    }

}
