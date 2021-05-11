<?php


namespace App\Service\Entity;


use App\Entity\ChatRoom;
use App\Entity\Message;
use App\Entity\User;
use App\Entity\UserToUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService
{
    protected $em;
    protected $passwordEncoder;
    public function __construct(EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->em = $entityManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function getFriends($user)
    {
        $userToUserRepository = $this->em->getRepository(UserToUser::class);
        $relations = $userToUserRepository->findBy(array("user" => $user));

        $friends = [];
        foreach($relations as $index => $relation)
        {
            array_push($friends, $relation->getFriend());
        }

        return $friends;
    }

    public function createUser($user, $form)
    {
        // encode the plain password
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                $form->get('plainPassword')->getData()
            )
        );

        $this->em->persist($user);
        $this->em->flush();
    }

    public function listFriends($currentUser)
    {
        $userToUserRepository = $this->em->getRepository(UserToUser::class);
        $relations = $userToUserRepository->findBy(array("user" => $currentUser));

        $friends = [];
        foreach($relations as $index => $relation)
        {
            array_push($friends, $relation->getFriend());
        }

        return $friends;
    }

    public function addFriends($currentUser, $form)
    {
        $userRepository = $this->em->getRepository(User::class);


        $name = $form['name']->getData();
        $friend = $userRepository->findOneBy(array("name" => $name));

        //創好友就直接創房間XD
        $chatRoom = new chatRoom();
        $this->em->persist($chatRoom);

        $members = [
            $currentUser,
            $friend
        ];

        foreach($members as $user)
        {
            //users_to_chatrooms
            $user->addChatRooms($chatRoom);

            $this->em->persist($user);
            foreach($members as $friend)
            {
                if($friend->getId() != $user->getId())
                {
                    //users_to_users
                    $userToUser = new UserToUser();
                    $userToUser->setUuid();
                    $userToUser->setUser($user);
                    $userToUser->setFriend($friend);
                    $userToUser->setRoom($chatRoom);
                    $userToUser->setPrivate(1);
                    $this->em->persist($userToUser);
                }
            }
        }
        $this->em->flush();

        return $chatRoom;
    }

    public function fetchFriends($currentUser)
    {
        $userToUserRepository = $this->em->getRepository(UserToUser::class);
        $relations = $userToUserRepository->findBy(array("user" => $currentUser));

        $friends = [];
        foreach($relations as $index => $relation)
        {
            if(!in_array($relation->getFriend()->getId(), $friends, true))
                array_push($friends, $relation->getFriend()->getId());
        }
        return $friends;
    }

    public function deleteFriends($currentUser, $name)
    {
        $userRepository = $this->em->getRepository(User::class);

        $friend = $userRepository->findOneBy(array("name" => $name));
        if($friend)
        {
            //先找到 userToUser currentUser 跟 friend 所有的 relations
            $userToUserRepository = $this->em->getRepository(UserToUser::class);
            $userToFriendRelation = $userToUserRepository->findOneBy(array(
                "user"   => $currentUser,
                "friend" => $friend,
                "private" => 1
            ));

            $friendToUserRelation = $userToUserRepository->findOneBy(array(
                "user"   => $friend,
                "friend" => $currentUser,
                "private" => 1
            ));
            //先處理Message
            $chatRoom = $friendToUserRelation->getRoom();
            $messages = $this->em->getRepository(Message::class)->findBy(array("chatRoom" => $chatRoom));

            foreach($messages as $message)
                $this->em->remove($message);
            $this->em->flush();

            //處理user_to_chatRoom
            $currentUser->removeChatRooms($userToFriendRelation->getRoom());
            $friend->removeChatRooms($friendToUserRelation->getRoom());


            //處理 user_to_user
            $this->em->remove($userToFriendRelation);
            $this->em->remove($friendToUserRelation);

//            //刪除chatRoom，因為user_to_user user_to_chatRoom 都有relate到chatRoom 所以無法cascade 刪除chatRoom
            $chatRoom = $userToFriendRelation->getRoom();
            $this->em->remove($chatRoom);

            $this->em->flush();

        }
    }
}