<?php

namespace App\Service\Entity;

use App\Entity\News;
use App\Entity\User;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserEntityService extends AbstractEntityService
{
    /**
     * @var UserRepository $userRepository
     */
    protected $userRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->userRepository
            = $entityManager->getRepository(User::class);
    }

    /**
     * @param int $limit
     * @param int $page
     * @return News[]|object[]
     */
    public function listUser(int $limit, int $page)
    {
        return $this->userRepository->findBy(
            array(),
            array(),
            self::handleLimit($limit),
            self::handleOffset($limit,$page)
        );
    }


    public function fetchById($id)
    {
        return $this->userRepository->find($id);
    }


}