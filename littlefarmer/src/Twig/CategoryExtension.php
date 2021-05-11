<?php

namespace App\Twig;

use App\Entity\Category;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CategoryExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    private $router;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $router)
    {
        $this->em = $entityManager;
        $this->router = $router;
    }

    public function getFilters(): array
    {
        return [];
    }

    public function getFunctions(): array
    {
        return [

            new TwigFunction('getCategories', [$this, 'getCategories']),
        ];
    }

    public function getCategories()
    {
        $categoryRepository = $this->em->getRepository(Category::class);

        return $categoryRepository->findAll();
    }

}