<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {


        $category2 = new Category();

        $category2->setUuid();
        $category2->setName("test1");

        $manager->persist($category2);

        $category3 = new Category();

        $category3->setUuid();
        $category3->setName("test2");

        $manager->persist($category3);

        $category4 = new Category();

        $category4->setUuid();
        $category4->setName("test3");

        $manager->persist($category4);
        $manager->flush();
    }
}
