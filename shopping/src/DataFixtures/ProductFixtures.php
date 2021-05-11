<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProductFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $product = new Product();
        $product->setName('衣服');
        $product->setDescription('衣服');
        $now = new \Datetime('now + 8hours');
        $product->setCreateAt($now);
        $product->setUpdateAt($now);
        $product->setFormat(['L','M','S']);
        $product->setStock([100,100,100]);
        $product->setPrice([250,250,250]);
        $product->setImage(["/uploads/default/test.jpg",]);

        $manager->persist($product);

        $manager->flush();
    }
}
