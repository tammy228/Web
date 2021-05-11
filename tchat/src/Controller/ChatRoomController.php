<?php

namespace App\Controller;



use App\Entity\Image;
use App\Entity\Message;
use App\Entity\User;
use App\Entity\UserToUser;
use App\Form\ChatRoomType;
use App\Entity\ChatRoom;
use App\Form\ImageType;
use Knp\Component\Pager\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\Entity\ChatRoomService;
use App\Service\Entity\UserService;

class ChatRoomController extends AbstractController {

    /**
     * @Route("/chat-rooms/{roomId}", name="chat_room.fetch",requirements={"roomId"="\d+"} )
     * @param Request $request
     * @param $roomId
     * @param ChatRoomService $chatRoomService
     * @return Response
     */
    public function fetchChatRoom(Request $request,
                                  $roomId,
                                  ChatRoomService $chatRoomService)
    {
        //檢查是否為多人聊天室
        $multiple = $chatRoomService->checkMultipleMembers($roomId);
        //列出該房間的所有訊息
        $messages = $chatRoomService->fetchChatRoom($roomId);

//        /**
//         * @var Paginator $paginator
//         */
//        $paginator = $this->get('knp_paginator');
//        $result = $paginator->paginate(
//            $messages,
//            $request->query->getInt('page',1),
//            $request->query->getInt('limit', 5)
//        );

        return $this->render('chat-room/fetch.html.twig', array(
            "roomId" => $roomId,
            "messages" => $messages,
            "multiple" => $multiple
        ));
    }


    /**
     * 給多人聊天使用的
     * @Route("/chat-rooms/create", name="create.chat-room")
     * @param Request $request
     * @param UserService $userService
     * @param ChatRoomService $chatRoomService
     * @return Response
     */
    public function createChatRoom(Request $request,
                                   UserService $userService,
                                   ChatRoomService $chatRoomService)
    {
        $chatRoom = new ChatRoom();
        $form = $this->createForm(ChatRoomType::class, $chatRoom);
        $form->handleRequest($request);

        //列出該user的朋友，再去創chatRoom
        $friends = $userService->getFriends($this->getUser());

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $memberIds = $request->get('chatRoom')['friends'];
            array_push($memberIds, strval($this->getUser()->getId()));

            $userRepository = $em->getRepository(User::class);

            $chatRoom = $form->getData();
            $em->persist($chatRoom);
            foreach($memberIds as $userId)
            {
                //users_to_chatrooms
                $user = $userRepository->find($userId);
                $user->addChatRooms($chatRoom);

                $em->persist($user);
                foreach($memberIds as $friendId)
                {
                    if($friendId != $userId)
                    {
                        $friend = $userRepository->find($friendId);
                        //users_to_users
                        $userToUser = new UserToUser();
                        $userToUser->setUuid();
                        $userToUser->setUser($user);
                        $userToUser->setFriend($friend);
                        $userToUser->setRoom($chatRoom);
                        $userToUser->setPrivate(0);

                        $em->persist($userToUser);
                    }
                }
            }

            $em->flush();

            return $this->redirectToRoute("chat_room.fetch", array(
                "roomId" => $chatRoom->getId()
            ));
        }
        return $this->render("chat-room/create.html.twig", array(
            "form" => $form->createView(),
            "friends" => $friends,
            "chatRoom" => $chatRoom,
        ));

    }

}
