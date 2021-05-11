<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Report;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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
        $user = new User();

        $user->setUuid();
        $user->setEmail("test@gmail.com");
        $user->validateEmail();
        $user->setName("testFarmer");
        $user->setRoles(['ROLE_FARMER']);
        $user->setRoleCodes(0);
        $user -> setPassword($this->passwordEncoder->encodePassword(
            $user,
            '12345'
        ));
        $user->setMobile("1234567890");

        $manager->persist($user);

        $product1 = new Product();

        $product1->setUuid();
        $product1->setZhName("test1");
        $product1->setEnName("test1");
        $product1->setImages(array());
        $product1->setZhDescription("test1");
        $product1->setEnDescription("test1");
        $product1->setPrice(100);
        $product1->setStock(10);
        $product1->setQRCode(10);
        $product1->setGroupBuy(true);
        $product1->setOnSale(true);
        $product1->setExpired(false);
        $product1->setDetail("detail1");
        $product1->setUser($user);

        $manager->persist($product1);

        $product2 = new Product();

        $product2->setUuid();
        $product2->setZhName("test2");
        $product2->setEnName("test2");
        $product2->setImages(array());
        $product2->setZhDescription("test2");
        $product2->setEnDescription("test2");
        $product2->setPrice(100);
        $product2->setStock(10);
        $product2->setQRCode(10);
        $product2->setGroupBuy(true);
        $product2->setOnSale(true);
        $product2->setExpired(false);
        $product2->setDetail("detail2");
        $product2->setUser($user);

        $manager->persist($product2);

        $manager->flush();
    }
}
