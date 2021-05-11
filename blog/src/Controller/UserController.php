<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\UserType;
use App\Form\AdminUserType;
use App\Security\LoginFormAuthenticator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Doctrine\ORM\EntityManagerInterface ;
use App\Repository\UserRepository;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="user.index")
     */
    public function index()
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }


    /**
     * @Route("/user/create", name="user.create")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function userCreate(Request $request,
                               UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email
            return $this->redirect('/user/login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/user/login", name="user.login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function userLogin(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/user/logout", name="user.logout")
     */
    public function out()
    {
    
        return $this->redirect('/login');
        
    }
    
    /**
     * @Route("/user/{id}/edit", name = "user.edit", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function userEdit(Request $request, $id)
    {
        $userId = intval($id);
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository(User::class);
        $user = $userRepository->find($userId);

        if(!$user) return $this->redirectToRoute("users.login");

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);

            return $this->redirectToRoute("admin.users.list");
        }
        return $this->render("user/update.html.twig", array(
            "form" => $form->createView(),
            "user" => $user
        ));
    }
    
    /**
     * @Route("/admin/users", name = "admin.users.list")
     */
    public function adminUserList(Request $request)
    {

        $entityManager = $this->getDoctrine()->getManager();

        $userRepository = $entityManager->getRepository(User::class);


        $users = $userRepository->findBy(array(), array("id" => "ASC"));


        return $this->render("admin/user/list.html.twig", array(

            "users" => $users
        ));
    }

    /**
     * @Route("/admin/users/{id}/edit", name="admin.users.edit", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminEditUser(Request $request, $id)
    {
        $userId = intval($id);
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository(User::class);

        $user = $userRepository->find($userId);

        if(!$user) return $this->redirectToRoute("admin.users.list");

        $form = $this->createForm(AdminUserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute("admin.users.list");
        }
        return $this->render("admin/user/update.html.twig", array(
            "form" => $form->createView(),
            "user" => $user
            
        ));

    }

    /**
     * @Route("/admin/users/{id}/delete", name="admin.users.delete", requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return Response
     */
    public function adminDeleteUser(Request $request, $id)
    {
        $userId = intval($id);
        $entityManager = $this->getDoctrine()->getManager();
        $userRepository = $entityManager->getRepository(User::class);

        $user = $userRepository->find($userId);

        if(!$user) 
        {
            return $this->redirectToRoute("admin.users.list");
        }
        $entityManager->remove($user);

        $entityManager->flush();

        return $this->redirectToRoute("admin.users.list");
    }

    /**
     * @Route("admin/index", name ="admin.index")
     */
    public function adminIndex()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    
}
