<?php

namespace App\DataFixtures;

use App\Entity\ProductionRange;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $pr = new ProductionRange();
        $pr->setZhName("扁把提繩 袋口鋸齒");
        $pr->setEnName("Flat Folded Paper Handle");
        $pr->setZhDescription("任意百搭，隨心所欲地創造你的袋型");
        $pr->setEnDescription("what ever you want");
        $pr->setImages([]);
        $pr->setShowCase(true);
        $pr->setSort(1);

        $manager->persist($pr);

        $pr = new ProductionRange();
        $pr->setZhName("捲把提繩 袋口鋸齒");
        $pr->setEnName("Flat Folded Paper Handle");
        $pr->setZhDescription("任意百搭，隨心所欲地創造你的袋型");
        $pr->setEnDescription("what ever you want");
        $pr->setImages([]);
        $pr->setShowCase(true);
        $pr->setSort(2);

        $manager->persist($pr);

        $pr = new ProductionRange();
        $pr->setZhName("捲把提繩 袋口反摺");
        $pr->setEnName("Flat Folded Paper Handle");
        $pr->setZhDescription("任意百搭，隨心所欲地創造你的袋型");
        $pr->setEnDescription("what ever you want");
        $pr->setImages([]);
        $pr->setShowCase(true);
        $pr->setSort(3);

        $manager->persist($pr);

        $manager->flush();
    }
}
