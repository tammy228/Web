<?php

namespace App\Twig;

use App\Entity\ProductToUserOrder;
use App\Entity\UserOrder;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class OrderExtension extends AbstractExtension
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

            new TwigFunction('status', [$this, 'status']),
            new TwigFunction('getTotalPrice', [$this, 'getTotalPrice']),
            new TwigFunction('getAllProducts', [$this, 'getAllProducts']),
            new TwigFunction('getProductToOrderRelations', [$this, 'getProductToOrderRelations']),

        ];
    }

    public function status($key)
    {
       if($key == 0)
       {
           $msg = '已下單';
       }
       elseif($key == 1)
       {
           $msg = '訂單處理中';
       }
       elseif($key == 2)
       {
           $msg = '配送中';
       }
       elseif($key == 3)
       {
           $msg = '已到貨';
       }
       elseif($key == 4)
       {
           $msg = '已取貨';
       }

       return $msg;
    }

    public function getTotalPrice($order)
    {
        $productToUserOrderRepo = $this->em->getRepository(ProductToUserOrder::class);
        $relations = $productToUserOrderRepo->findBy(array(
            "userOrder" => $order
        ));
        $total = 0;
        foreach ($relations as $relation)
        {
            $total += $relation->getProduct()->getPrice() * $relation->getQuantity() ;
        }
        return $total;
    }

    public function getAllProducts($order)
    {
        $relations = $this->em->getRepository(ProductToUserOrder::class)->findBy(array(
            "userOrder" => $order
        ));

        $productName = "";
        foreach ($relations as $relation)
            $productName .= $relation->getProduct()->getZhName()."  ".$relation->getSize()." x ".$relation->getQuantity()."<br>";

        return $productName;

    }

    public function getProductToOrderRelations($order)
    {
        $relations = $this->em->getRepository(ProductToUserOrder::class)->findBy(array(
            "userOrder" => $order
        ));

        return $relations;
    }

}