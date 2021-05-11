<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CategoryFixtures extends Fixture
{
    private $passwordEncoder;
    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('衣服');
        $category->setUpdateAt();
        $category->setCreateAt();

        $manager->persist($category);

        $category1 = new Category();
        $category1->setName('褲子');
        $category1->setUpdateAt();
        $category1->setCreateAt();

        $manager->persist($category1);

        $category2 = new Category();
        $category2->setName('配件');
        $category2->setUpdateAt();
        $category2->setCreateAt();

        $manager->persist($category2);

        $manager->flush();
    }
}
