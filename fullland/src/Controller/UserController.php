<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    /**
     *     _      _       _
     *    /_\  __| |_ __ (_)_ _
     *   / _ \/ _` | '  \| | ' \
     *  /_/ \_\__,_|_|_|_|_|_||_|
     */

    /**
     * @Route("admin/user", name="admin.user.list")
     * @return Response
     */
    public function adminListUsers()
    {
        $em = $this->getDoctrine()->getManager();
        $userRepository = $em->getRepository(User::class);
        $users = $userRepository->findBy([
            "roleCodes" => 0,
        ]);

        return $this->render("admin/users/list.html.twig",array(
            "listType" => "all",
            "users" => $users
        ));
    }

    /**
     * @Route("admin/user/create", name="admin.user.create")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function adminCreateUsers(Request $request,UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $user->setRoles(['ROLE_ADMIN']);
            $user->setRoleCodes(0);
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('password')->getData()
                )
            );
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('admin.user.list');
        }

        return $this->render('admin/users/create.html.twig', [
            'form' => $form->createView(),
        ]);
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

        $em->remove($user);

        $em->flush();

        return $this->redirectToRoute("admin.user.list");

    }
}
