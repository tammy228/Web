<?php

namespace App\Twig;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CategoryExtension extends AbstractExtension
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('getChildCategories', [$this, 'getChildCategories']),
            new TwigFunction('getFather', [$this, 'getFather']),
            new TwigFunction('getFatherCategories', [$this, 'getFatherCategories']),
            new TwigFunction('getUncate', [$this, 'getUncate']),
            new TwigFunction('getFirstFatherCategoryId', [$this, 'getFirstFatherCategoryId']),
        ];
    }

    public function getChildCategories()
    {
        $categoryRepository = $this->em->getRepository(Category::class);

        $categories =  $categoryRepository->findAll();

        $childCategories = [];
        foreach ($categories as $category)
        {
            if($category->getParent() != NULL)
                array_push($childCategories, $category);
        }

        return $childCategories;
    }

    public function getFather($id,$x)
    {
        if($x == 0){
            $categoryRepository = $this->em->getRepository(Category::class);
            $category = $categoryRepository->find($id);
            $item = $category->getParent()->getZhName();
        }
        else{
            $categoryRepository = $this->em->getRepository(Category::class);
            $category = $categoryRepository->find($id);

            $y = strlen($category->getParent()->getZhName());
            $y = intval($y/3);
            $item='　';
            while ($y-1)
            {
                $item = $item.'　';
                $y--;
            }
        }
        return $item;
    }

    public function getFatherCategories()
    {
        $categoryRepository = $this->em->getRepository(Category::class);

        $categories =  $categoryRepository->findAll();

        $fatherCategories = [];
        foreach ($categories as $category)
        {
            if($category->getParent() == NULL)
                array_push($fatherCategories, $category);
        }

        return $fatherCategories;
    }

    public function getUncate(){
        $categoryRepository = $this->em->getRepository(Category::class);

        return $categoryRepository->findOneBy(['zhName'=>'未分類']);

    }
    public function getFirstFatherCategoryId()
    {
        $categoryRepository = $this->em->getRepository(Category::class);

        $fatherCategory = $categoryRepository->findOneBy(["parent" => NULL]);

        return $fatherCategory->getId();
    }
}
