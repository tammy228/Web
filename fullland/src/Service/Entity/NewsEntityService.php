<?php

namespace App\Service\Entity;

use App\Entity\News;
use App\Repository\NewsRepository;
use Doctrine\ORM\EntityManagerInterface;

class NewsEntityService extends AbstractEntityService
{
    /**
     * @var NewsRepository $newsRepository
     */
    protected $newsRepository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
        $this->newsRepository
            = $entityManager->getRepository(News::class);
    }

    /**
     * @param int $limit
     * @param int $page
     * @return News[]
     */
    public function listNews(int $limit, int $page)
    {
        return $this->newsRepository->findBy(
            array(),
            array("createAt"=>"DESC"),
            self::handleLimit($limit),
            self::handleOffset($limit,$page)
        );
    }


    public function fetchById($id)
    {
        return $this->newsRepository->find($id);
    }


}