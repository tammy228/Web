<?php

namespace App\DataFixtures;

use App\Entity\UserOrder;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class OrderFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {

        $order1 = new UserOrder();
        $order1->setUser(null);
        $order1->setProductId([1]);
        $order1->setProduct(['衣服']);
        $order1->setStatus(1);
        $order1->setTradeNo('asdasdadas');
        $order1->setFormat(['XL']);
        $order1->setQuantity(['1']);
        $order1->setAddress('Taiwan');

        $manager->persist($order1);

        $order2 = new UserOrder();
        $order2->setUser(null);
        $order2->setProductId([1]);
        $order2->setProduct(['衣服']);
        $order2->setStatus(2);
        $order2->setTradeNo('asdasdadas');
        $order2->setFormat(['XL']);
        $order2->setQuantity(['1']);
        $order2->setAddress('Taiwan');

        $manager->persist($order2);

        $order3 = new UserOrder();
        $order3->setUser(null);
        $order3->setProductId([1]);
        $order3->setProduct(['衣服']);
        $order3->setStatus(3);
        $order3->setTradeNo('asdasdadas');
        $order3->setFormat(['XL']);
        $order3->setQuantity(['1']);
        $order3->setAddress('Taiwan');

        $manager->persist($order3);

        $order4 = new UserOrder();
        $order4->setUser(null);
        $order4->setProductId([1]);
        $order4->setProduct(['衣服']);
        $order4->setStatus(4);
        $order4->setTradeNo('asdasdadas');
        $order4->setFormat(['XL']);
        $order4->setQuantity(['1']);
        $order4->setAddress('Taiwan');

        $manager->persist($order4);

        $manager->flush();
    }
}
