<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\UserEmailVerifyToken;
use App\Form\UserType;
use App\Form\VerifyType;
use App\Repository\UserEmailVerifyTokenRepository;
use App\Security\LoginFormAuthenticator;
use PHPUnit\Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface ;
use App\Repository\UserRepository;
use Ramsey\Uuid\Uuid;
use \SendGrid\Mail\Mail;



class UserController extends AbstractController
{

    /**
     * @Route("/login", name="app.login")
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
     * @Route("/logout", name="app.logout")
     */
    public function logout()
    {
        return $this->redirect('/login');
    }

    /**
     * @Route("/register", name="user.register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param \Swift_Mailer $mailer
     * @return Response
     */
    public function register(Request $request,
                             UserPasswordEncoderInterface $passwordEncoder,
                             GuardAuthenticatorHandler $guardHandler,
                            \Swift_Mailer $mailer): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            if (!filter_var($form['email']->getData(), FILTER_VALIDATE_EMAIL)) {
                return $this->render(
                    'security/register.html.twig',
                    array('form' => $form->createView(),
                        'error'=> '電子郵件錯誤')
                );
            }
            $uuid = Uuid::uuid4();
            $user->setUuid($uuid);
            $user->setCreateAt(new \Datetime('now + 8hours'));
            $user->setRoles(['ROLE_USER']);
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);

            $name = $form['name']->getData();
            $random = (string)random_int(100000, 999999);
            $token = new UserEmailVerifyToken();
            $token->setUser($uuid);
            $token->setToken($random);

            $token->setCreateAt(new \Datetime('now + 8hours'));
            $token->setExpireAt(new \Datetime('now + 8hours 10 minutes'));

            $email= new Mail();
            $email->setFrom("shopping@example.com", "購物網");
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

            return $this->redirectToRoute('app.login');

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
     * @Route("verify", name="user.verify")
     * @param Request $request
     */
    public function EmailVerify(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'Unable to access this page!');
        $user = $this->getUser();
        $uuid = $user->getUuid();
        if($user->getEmailVerified()){return $this->redirectToRoute('app.login');}

        $message='';
        $form = $this->createForm(VerifyType::class);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $verify = $em->getRepository(UserEmailVerifyToken::class)->findOneBy(array('user'=>$uuid));
        $now = new \Datetime('now + 8hours');
        $deadline = $verify->getExpireAt();
        if($now>$deadline)
        {
            $random = (string)random_int(100000, 999999);
            $token = new UserEmailVerifyToken();
            $token->setUser($uuid);
            $token->setToken($random);
            $token->setCreateAt(new \Datetime('now + 8hours'));
            $token->setExpireAt(new \Datetime('now + 8hours 10 minutes'));

            $email= new Mail();
            $email->setFrom("shopping@example.com", "購物網");
            $email->setSubject('驗證電子郵件');
            $email->addTo($user->getEmail(),'user');
            $email->addContent(
                "text/html", "重新登入並填入驗證碼 \n 驗證碼 :".$random
            );
            $sendgrid = new \SendGrid($_ENV['SENDGRID_KEY']);

            $sendgrid->send($email);
            $em->remove($verify);
            $em->persist($token);

            $em->flush();

            return $this->render(
                'security/send.html.twig',array(
                    'error'=>'',
                )
            );
        }

        if($form->isSubmitted() && $form->isValid()){
            $token = $form['token']->getData();
            if($token==$verify->getToken()){
                $user->setEmailVerified(True);
                $em->persist($user);
                $em->remove($verify);
                $em->flush();

                return $this->render('index.html.twig');
            }
            $message = '驗證碼錯誤';
            return $this->render(
                'security/verify.html.twig',
                array('form'=> $form->createView(),
                    'message'=>$message,
                    'error'=>'')
            );
        }

        return $this->render(
            'security/verify.html.twig',
            array('form'=> $form->createView(),
                'message'=>$message,
                'error'=>'')
        );
    }

    /**
     * @Route("{id}/update/", name="user.update", requirements={"id"="\d+"})
     */
    public function userUpdate($id,Request $request)
    {
        if($this->getUser() and $id == $this->getUser()->getId())
        {
            $form = $this->createFormBuilder(null)
                ->setAction($this->generateUrl('user.update',array('id'=>$id)))
                ->add('name', TextType::class,array(
                    'label'=> '名稱',
                    'attr' => [
                        'value'=> $this->getUser()->getName()
                    ]
                    ))
                ->add('mobile', TelType::class,array(
                    'required' => false,
                    'attr' => [
                        'value'=> $this->getUser()->getMobile()
                    ]
                ))
                ->add('submit', SubmitType::class,array(
                        'label' => '送出',
                        'attr' => [
                            'class' => 'btn btn-primary'
                        ]
                    )
                )
                ->getForm();
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $user = $this->getUser();
                $user->setName($form['name']->getData());
                if($form['mobile']->getData()!=null)
                    $user->setMobile($form['mobile']->getData());
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();

                return $this->redirectToRoute('index');
            }
            return $this->render('/user/update.html.twig',array(
                'form' => $form->createView(),
            ));
        }
        else{
            return $this->redirectToRoute('index');
        }
    }

    /**
     * @Route("/index", name="index")
     */
    public function index()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/admin/index", name="admin.index")
     */
    public function adminIndex()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("admin/users", name="admin.user.list")
     */
    public function adminUsersList()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $userRepository = $entityManager->getRepository(User::class);


        $users = $userRepository->findBy(array(), array("id" => "ASC"));


        return $this->render("admin/user/list.html.twig", array(
            "users" => $users
        ));
    }

    /**
     * @Route("/admin/users/{id}/delete", name="admin.users.delete")
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminDeleteUser(Request $request, $id)
    {

        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->findOneBy(array('uuid'=>$id));
        if(!$user)
        {
            return $this->redirectToRoute("admin.user.list");
        }
        $entityManager->remove($user);
        $entityManager->flush();

        return $this->redirectToRoute("admin.user.list");
    }

    /**
     * @Route("/admin/user/{id}/fetch", name="admin.user.fetch", requirements={"id"="\d+"})
     * @param $id
     * @return Response
     */
     public function adminUserFetch($id)
     {
         $entityManager = $this->getDoctrine()->getManager();
         $userRepository = $entityManager->getRepository(User::class);

         $user = $userRepository->find($id);
         $orders=$user->getOrder();


         return $this->render('/admin/user/fetch.html.twig',array(
             'orders'=>$orders,
             'user'=>$user,
         ));
     }

}
