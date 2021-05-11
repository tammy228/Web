<?php

namespace App\Twig;

use App\Entity\ChatRoom;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ChatRoomExtension extends AbstractExtension
{
    private $security;
    private $em;

    public function __construct(Security $security, EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->em = $entityManager;
    }
    public function getFilters(): array
    {
        return [];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('listChatRooms', [$this, 'listChatRooms']),
            new TwigFunction('getChatRoom', [$this, 'getChatRoom']),

        ];
    }

    public function listChatRooms()
    {
        /**
         * User $currentUser
         */
        $currentUser = $this->security->getUser();
        $chatRooms = $currentUser->getChatRooms();

        return $chatRooms;
    }

    public function getChatRoom($roomId)
    {
        $chatRepository = $this->em->getRepository(ChatRoom::class);

        return $chatRepository->find($roomId);
    }
}
