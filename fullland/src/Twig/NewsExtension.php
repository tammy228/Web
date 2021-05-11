<?php

namespace App\Twig;

use App\Entity\News;
use App\Repository\NewsRepository;
use App\Service\Entity\NewsEntityService;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class NewsExtension extends AbstractExtension
{
    private $entityManager;

    protected $newsEntityService;

    public function __construct(EntityManagerInterface $entityManager,
                                NewsEntityService $newsEntityService)
    {
        $this->entityManager = $entityManager;
        $this->newsEntityService = $newsEntityService;

    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getNewsList', [$this, 'getNewsList']),
        ];
    }

    /**
     * @param int $limit
     * @param int $page
     * @return News[]
     */
    public function getNewsList($limit = 6, $page = 1)
    {
        return $this->newsEntityService->listNews($limit, $page);
    }
}