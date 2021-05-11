<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController{

    /**
     * @Route("/admin/user", name="admin.user.list")
     */
    public function adminListUser()
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository(User::class);
        $users = $userRepository->findBy([
            "deleted" => 0,
            "roleCodes" => 1
        ]);

        return $this->render("admin/user/list.html.twig",array(
            "users" => $users
        ));
    }

    /**
     * @Route("admin/user/{id}", name="admin.user.delete")
     * @param $id
     * @return RedirectResponse
     */
    public function adminDeleteUser($id)
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository(User::class);

        /**
         * @var User $user
         */
        $user = $userRepository->find($id);

        $user->setDeleted(1);
        $em->persist($user);
        $em->flush();

        return $this->redirectToRoute("admin.user.list");

    }
}