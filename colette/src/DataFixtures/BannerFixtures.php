<?php

namespace App\DataFixtures;

use App\Entity\Banner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BannerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $banner1 = new Banner();

        $banner1->setUuid();
        $banner1->setImages([["/img/banner-1.png","/img/banner-1.png","/img/banner-1.png"],["/img/banner-2.png","/img/banner-2.png","/img/banner-2.png"],["/img/about_1.png"],["/img/news_1.png"]]);
        $banner1->setName("admin");

        $manager->persist($banner1);


        $manager->flush();
    }
}
