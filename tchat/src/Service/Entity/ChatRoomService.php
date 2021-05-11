<?php


namespace App\Service\Entity;


use App\Entity\ChatRoom;
use App\Entity\User;
use App\Entity\UserToUser;
use Doctrine\ORM\EntityManagerInterface;

class ChatRoomService
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function fetchChatRoom($roomId)
    {
        $chatRoomRepository = $this->em->getRepository(ChatRoom::class);
        $chatRoom = $chatRoomRepository->find($roomId);

        return $chatRoom->getMessages();
    }

    public function checkMultipleMembers($roomId)
    {
        $multiple = 0;
        $chatRoomRepository = $this->em->getRepository(ChatRoom::class);
        $chatRoom = $chatRoomRepository->find($roomId);
        if(count($chatRoom->getUsers()) > 2)
            $multiple = 1;

        return $multiple;
    }

    public function createChatRoom($request, $form)
    {
        $memberIds = $request->get('chatRoom')['friends'];
        array_push($memberIds, strval($this->getUser()->getId()));

        $userRepository = $this->em->getRepository(User::class);

        $chatRoom = $form->getData();
        $this->em->persist($chatRoom);
        foreach($memberIds as $userId)
        {
            //users_to_chatrooms
            $user = $userRepository->find($userId);
            $user->addChatRooms($chatRoom);

            $this->em->persist($user);
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

                    $this->em->persist($userToUser);
                }
            }
        }

        $this->em->flush();
    }
}