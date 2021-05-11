<?php

namespace App\DataFixtures;

use App\Entity\Banner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BannerFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $banner = new Banner();
        $banner->setZhTitle("時尚潮流");
        $banner->setEnTitle("The Brand Carrier");
        $banner->setImages(["/assets/image/index/header0.jpg"]);

        $manager->persist($banner);

        $banner = new Banner();
        $banner->setZhTitle("時尚潮流");
        $banner->setEnTitle("The Brand Carrier");
        $banner->setImages(["/assets/image/index/header1.jpg"]);

        $manager->persist($banner);

        $banner = new Banner();
        $banner->setZhTitle("時尚潮流");
        $banner->setEnTitle("The Brand Carrier");
        $banner->setImages(["/assets/image/index/header2.jpg"]);

        $manager->persist($banner);

        $manager->flush();
    }
}
