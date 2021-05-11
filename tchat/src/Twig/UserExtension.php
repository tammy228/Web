<?php

namespace App\Twig;

use App\Entity\ChatRoom;
use App\Entity\User;
use App\Entity\UserToUser;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;
    private $security;

    public function __construct(EntityManagerInterface $entityManager, Security $security)
    {
        $this->em = $entityManager;
        $this->security = $security;
    }
    public function getFilters(): array
    {
        return [];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getChatRoomMember', [$this, 'getChatRoomMember']),
            new TwigFunction('updateLastActivity', [$this, 'updateLastActivity']),
            new TwigFunction('getLastActivity', [$this, 'getLastActivity']),
        ];
    }

    public function getChatRoomMember($roomId)
    {
        $chatRoomRepository = $this->em->getRepository(ChatRoom::class);
        $chatRoom = $chatRoomRepository->find($roomId);

        return $chatRoom->getUsers();
    }

    public function updateLastActivity($userId)
    {
        $userRepository = $this->em->getRepository(User::class);
        $user = $userRepository->find($userId);
        $user->setActivity(new \DateTime("now + 8 hours"));

        $this->em->persist($user);
        $this->em->flush();
    }

    public function getLastActivity($userId)
    {
        $userRepository = $this->em->getRepository(User::class);
        $user = $userRepository->find($userId);
        return $user->getActivity();
    }
}
