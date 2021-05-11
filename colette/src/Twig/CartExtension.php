<?php

namespace App\Twig;

use App\Entity\Category;
use App\Entity\Cart;
use App\Entity\Coupon;
use App\Entity\Product;
use App\Entity\ProductToCart;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CartExtension extends AbstractExtension
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

            new TwigFunction('getRelations', [$this, 'getRelations']),
            new TwigFunction('getNewQuantityIndex', [$this, 'getNewQuantityIndex']),
            new TwigFunction('useCoupon', [$this, 'useCoupon']),
            new TwigFunction('getUnitPrice', [$this, 'getUnitPrice']),

        ];
    }


    public function getRelations($userId)
    {
        $user = $this->em->getRepository(User::class)->find($userId);
        $cart = $user->getCart();

        $productToCartRepo = $this->em->getRepository(ProductToCart::class);
        return $productToCartRepo->findBy(array("cart" => $cart));
    }

    public function getNewQuantityIndex($relation)
    {
        /**
         * @var ProductToCart $relation
         */
        $sizeArray = $relation->getProduct()->getSize();
        $index = array_search($relation->getSize(), $sizeArray);

        return $relation->getProduct()->getId()."/".$index;
    }

    public function getUnitPrice($name)
    {
        $pieces = explode("/",$name);
        $productId = $pieces[0];
        $priceIndex = $pieces[1];

        $productRepository = $this->em->getRepository(Product::class);
        $product = $productRepository->find($productId);
        $price = $product->getPrice()[$priceIndex];

        return $price;
    }
}