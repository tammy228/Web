<?php

namespace App\Controller;

use App\Entity\ChatRoom;
use App\Entity\Message;
use App\Entity\User;
use App\Entity\UserToUser;
use App\Form\FriendType;
use App\Form\RegistrationType;
use App\Service\Entity\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('user/index.html.twig');
    }

    /**
     * @Route("/json/friends/fetch")
     * @param Request $request
     * @return JsonResponse
     */
    public function ajaxFetchFriends(Request $request, UserService $userService)
    {
        $friends = $userService->fetchFriends($this->getUser());

        return new JsonResponse($friends);
    }

    /**
     * @Route("/register", name="user.register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function createUser(Request $request,
                               UserService $userService): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userService->createUser($user,$form);

            return $this->redirect('/login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/friends/add", name="friends.add")
     * @param Request $request
     * @return Response
     */
    public function addFriends(Request $request, UserService $userService)
    {
        $user = new User();
        $form = $this->createForm(FriendType::class, $user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $chatRoom = $userService->addFriends($this->getUser(), $form);

            return $this->redirectToRoute("chat_room.fetch", array(
                "roomId" => $chatRoom->getId()
            ));
        }
        return $this->render("/user/add-friends.html.twig", array(
            "form" => $form->createView()
        ));
    }

    /**
     * @Route("/friends/list", name="friends.list")
     */
    public function listFriends(UserService $userService)
    {
        $friends = $userService->listFriends($this->getUser());

        return $this->render("user/list-friends.html.twig", array(
            "friends" => $friends,
            "userId" => $this->getUser()->getId()
        ));
    }

    /**
     * @Route("/friends/delete", name="friends.delete")
     * @param Request $request
     * @return RedirectResponse
     */
    public function deleteFriends(Request $request, UserService $userService)
    {
        $form = $this->createForm(FriendType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $name = $request->get('friend')['name'];
            $chatRoom = $userService->deleteFriends($this->getUser(), $name);

            return $this->redirectToRoute("homepage");
        }
        return $this->render("/user/delete-friends.html.twig", array(
            "form" => $form->createView()
        ));
    }

}
