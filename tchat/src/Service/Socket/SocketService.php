<?php


namespace App\Service\Socket;

use App\Entity\ChatRoom;
use App\Entity\Image;
use App\Entity\Message;
use App\Entity\Text;
use App\Entity\User;
use App\Entity\UserToUser;
use Doctrine\ORM\EntityManagerInterface;
use DOMDocument;
use DOMXPath;

class SocketService
{
    protected $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function parseUri($socket)
    {
        $url = $socket->httpRequest->getUri();
        $str = parse_url($url, PHP_URL_PATH);
        $roomId = explode("/",$str)[1];
        $userId = explode("/",$str)[2];

        return [$roomId, $userId];
    }

    public function updateReader($roomId, $userId)
    {
        $currentUser = $this->em->getRepository(User::class)->find($userId);
        $chatRoom = $this->em->getRepository(\App\Entity\ChatRoom::class)->find($roomId);

        $userToUserRepository = $this->em->getRepository(UserToUser::class);
        $relation = $userToUserRepository->findOneBy(array(
            "user" => $currentUser,
            "rooms" => $chatRoom
        ));

        if($relation->getMessage())
            $messages = $this->em->getRepository(Message::class)
                ->findAllUnreadMessage($relation->getMessage()->getId());
        else
            $messages = $this->em->getRepository(Message::class)
                ->findBy(array("chatRoom" => $chatRoom));

        foreach ($messages as $message)
        {
            $reader = $message->getReader();
            $message->setReader(++$reader);
            $this->updateReadMessage($roomId,$userId, $message);
            $this->em->persist($message);
        }
        $this->em->flush();
    }

    public function updateReadMessage($roomId, $userId, $readMessage)
    {
        $currentUser = $this->em->getRepository(User::class)->find($userId);
        $chatRoom = $this->em->getRepository(\App\Entity\ChatRoom::class)->find($roomId);

        $userToUserRepository = $this->em->getRepository(UserToUser::class);
        $relations = $userToUserRepository->findBy(array(
            "user" => $currentUser,
            "rooms" => $chatRoom
        ));

        if(!$readMessage) {
            $messageRepository = $this->em->getRepository(Message::class);
            $message = $messageRepository->findBy(array("chatRoom" => $chatRoom), array('createAt' => 'DESC'));
            $readMessage = $message[0];
        }
        foreach ($relations as $relation) {
            $relation->setMessage($readMessage);
            $this->em->persist($relation);

        }

        $this->em->flush();
    }

    public function createMessage($dataMessage, $currentUserId, $reader, $chatRoomId)
    {
        $message = new Message();
        //check dataMessage is Text or Image
        if($this->isHTML($dataMessage)) {
            //extract src part from html code
            $xpath = new DOMXPath(@DOMDocument::loadHTML($dataMessage));
            $src = $xpath->evaluate("string(//img/@src)");

            //insert image table
            $image = new Image();
            $image->setUrl($src);
            $message->setImage($image);
            $this->em->persist($image);
        }else {
            // insert text table
            $text = new Text();
            $text->setText($dataMessage);
            $message->setText($text);
            $this->em->persist($text);
        }
        $currentUser = $this->em->getRepository(User::class)->find($currentUserId);
        $chatRoom = $this->em->getRepository(ChatRoom::class)->find($chatRoomId);

        // insert message table
        $message->setUser($currentUser);
        $message->setReader($reader);
        $message->setChatRoom($chatRoom);
        $this->em->persist($message);

        $this->em->flush();
    }

    public function isHTML($string){
        return $string != strip_tags($string) ? true:false;
    }
}