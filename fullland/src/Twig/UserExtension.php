<?php

namespace App\Twig;

use App\Entity\News;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Entity\UserEntityService;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{
    private $entityManager;

    protected $userEntityService;

    public function __construct(EntityManagerInterface $entityManager,
                                UserEntityService $userEntityService)
    {
        $this->entityManager = $entityManager;
        $this->userEntityService = $userEntityService;

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getUserList', [$this, 'getUserList']),
        ];
    }

    /**
     * @param int $limit
     * @param int $page
     * @return News[]|object[]
     */
    public function getUserList($limit = 6, $page = 1)
    {
        return $this->userEntityService->listUser($limit, $page);
    }
}