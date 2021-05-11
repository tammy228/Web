<?php

namespace App\Twig;

use App\Entity\Banner;
use App\Entity\Category;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class IndexExtension extends AbstractExtension
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

            new TwigFunction('loc', [$this, 'loc']),
            new TwigFunction('getBanner', [$this, 'getBanner']),
            new TwigFunction('splitAddress',[$this,'splitAddress']),
            new TwigFunction('getFatherCategories',[$this,'getFatherCategories']),
            new TwigFunction('getFiveProducts',[$this,'getFiveProducts']),
            new TwigFunction('getRangePrice',[$this,'getRangePrice'])


        ];
    }

    public function loc($id)
    {
        $x = '?';
        if($id == 0)
            $x = '首頁第一張';
        elseif ($id ==  1)
            $x = '首頁第二張';
        elseif ($id ==  2)
            $x = '關於我們';
        elseif ($id ==  3)
            $x = '最新消息';
        return $x;
    }

    public function getBanner($id)
    {
        $bannerRepository = $this->em->getRepository(Banner::class);
        $banner = $bannerRepository->findOneBy(array('name'=>'admin'));

        return $banner->getImages()[$id];
    }

    public function splitAddress($address)
    {

        if(!$address)
            return array('','','','');
        return explode(';',$address);

    }

    public function getFatherCategories()
    {
        $categoryRepository = $this->em->getRepository(Category::class);
        $categories = $categoryRepository->findBy(array());

        $fatherCategories = [];
        foreach ($categories as $category)
        {
            /**
             * @var Category $category
             */
            if($category->getParent() == NULL)
                array_push($fatherCategories, $category);
        }

        return $fatherCategories;
    }

    public function getFiveProducts()
    {
        $productRepository = $this->em->getRepository(Product::class);
        if($productRepository->findBy(array(),['createAt'=>'DESC'],5))
            return $productRepository->findBy(array(),['createAt'=>'DESC'],5);
        else
            return null;
    }

    public function getRangePrice($id)
    {
        $productRepository = $this->em->getRepository(Product::class);
        $product = $productRepository->find($id);

        $priceArray = $product->getPrice();
        $maxPrice = max($priceArray);
        $minPrice = min($priceArray);


        if($maxPrice != $minPrice)
            return "$".$minPrice."~"."$".$maxPrice;
        else
            return "$".$minPrice;
    }
}