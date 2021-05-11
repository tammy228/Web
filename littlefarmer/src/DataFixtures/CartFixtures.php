<?php

namespace App\DataFixtures;

use App\Entity\Cart;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectManager;

class CartFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
//        $cart = new Cart();
//
//        $product4 = new Product();
//
//        $product4->setUuid();
//        $product4->setZhName("test1");
//        $product4->setEnName("test1");
//        $product4->setImages(array());
//        $product4->setZhDescription("test1");
//        $product4->setEnDescription("test1");
//        $product4->setPrice(100);
//        $product4->setStock(10);
//        $product4->setQRCode(array());
//        $product4->setGroupBuy(true);
//        $product4->setOnSale(true);
//        $product4->setExpired(false);
//        $product4->setDetail("detail1");
//
//        $manager->persist($product4);
//
//        $cart->setUuid();
//        $cart->addProducts($product4);
//        $cart->setQuantity(10);
//
//        $manager->persist($cart);
//        $manager->flush();
    }
}
