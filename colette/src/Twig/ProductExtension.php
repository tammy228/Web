<?php

namespace App\Twig;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductToCategory;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class ProductExtension extends AbstractExtension
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
            new TwigFunction('getCategory', [$this, 'getCategory']),
            new TwigFunction('getProductAllPrice', [$this, 'getProductAllPrice']),
            new TwigFunction('getProductByCategory', [$this, 'getProductByCategory']),

        ];
    }

    public function getCategory($id)
    {
        $productToCategoryRepo = $this->em->getRepository(ProductToCategory::class);
        $product = $this->em->getRepository(Product::class)->find($id);
        $relation = $productToCategoryRepo->findOneBy(array(
            "product" => $product
        ));

        return $relation->getCategory();
    }

    public function getProductAllPrice($id)
    {
        $product = $this->em->getRepository(Product::class)->find($id);
        $allPrice = $product->getPrice();
        $price = [];
        foreach ($allPrice as $temp)
        {
            array_push($price,$temp);
        }

        return $price;
    }

    public function getProductByCategory($id)
    {
        $category = $this->em->getRepository(Category::class)->find($id);

        $productToCategoryRepository = $this->em->getRepository(ProductToCategory::class);
        $relations = $productToCategoryRepository->findBy(array(
            "category" => $category
        ));

        $products = [];
        foreach ($relations as $relation)
        {
            /**
             * @var ProductToCategory $relation
             */
            array_push($products, $relation->getProduct());
        }

        return $products;
    }


}
