<?php

namespace App\DataFixtures;

use App\Entity\ProductionRange;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductionRangeFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $pr = new ProductionRange();
        $pr->setZhName("百貨類");
        $pr->setEnName("Department store");
        $pr->setZhDescription("任意百搭，隨心所欲地創造你的袋型");
        $pr->setEnDescription("what ever you want");
        $pr->setThumbNail("assets/image/products/5.jpg");
        $pr->setImages(["assets/image/products/p1.jpg","assets/image/products/p2.jpg","assets/image/products/p3.jpg",
                        "assets/image/products/p4.jpg","assets/image/products/p5.jpg","assets/image/products/p6.jpg",
                        "assets/image/products/p7.jpg","assets/image/products/p8.jpg"
        ]);
        $pr->setShowCase(false);
        $pr->setSort(1);

        $manager->persist($pr);

        $pr = new ProductionRange();
        $pr->setZhName("服飾類");
        $pr->setEnName("Clothing");
        $pr->setZhDescription("任意百搭，隨心所欲地創造你的袋型");
        $pr->setEnDescription("what ever you want");
        $pr->setThumbNail("assets/image/products/6.jpg");
        $pr->setImages(["assets/image/products/p9.jpg","assets/image/products/p10.jpg","assets/image/products/p11.jpg",
                        "assets/image/products/p12.jpg","assets/image/products/p13.jpg","assets/image/products/p14.jpg",
                        "assets/image/products/p15.jpg","assets/image/products/p16.jpg","assets/image/products/p17.jpg",
                        "assets/image/products/p18.jpg","assets/image/products/p19.jpg","assets/image/products/p20.jpg"
        ]);
        $pr->setShowCase(false);
        $pr->setSort(2);

        $manager->persist($pr);

        $pr = new ProductionRange();
        $pr->setZhName("其他");
        $pr->setEnName("Other");
        $pr->setZhDescription("任意百搭，隨心所欲地創造你的袋型");
        $pr->setEnDescription("what ever you want");
        $pr->setThumbNail("assets/image/products/10.jpg");
        $pr->setImages(["assets/image/products/p79.jpg","assets/image/products/p80.jpg","assets/image/products/p81.jpg",
                        "assets/image/products/p82.jpg","assets/image/products/p83.jpg","assets/image/products/p84.jpg",
                        "assets/image/products/p85.jpg","assets/image/products/p86.jpg","assets/image/products/p87.jpg",
                        "assets/image/products/p88.jpg","assets/image/products/p89.jpg","assets/image/products/p90.jpg"
        ]);
        $pr->setShowCase(false);
        $pr->setSort(6);

        $manager->persist($pr);

        $pr = new ProductionRange();
        $pr->setZhName("飲料與烘焙業");
        $pr->setEnName("Beverage and Bakery");
        $pr->setZhDescription("任意百搭，隨心所欲地創造你的袋型");
        $pr->setEnDescription("what ever you want");
        $pr->setThumbNail("assets/image/products/7.jpg");
        $pr->setImages(["assets/image/products/p21.jpg","assets/image/products/p22.jpg","assets/image/products/p23.jpg",
                        "assets/image/products/p24.jpg","assets/image/products/p25.jpg","assets/image/products/p26.jpg",
                        "assets/image/products/p27.jpg","assets/image/products/p28.jpg","assets/image/products/p29.jpg",
                        "assets/image/products/p30.jpg","assets/image/products/p31.jpg","assets/image/products/p32.jpg"
        ]);
        $pr->setShowCase(false);
        $pr->setSort(3);

        $manager->persist($pr);

        $pr = new ProductionRange();
        $pr->setZhName("餐廳與食品業");
        $pr->setEnName("Restaurant and Food");
        $pr->setZhDescription("任意百搭，隨心所欲地創造你的袋型");
        $pr->setEnDescription("what ever you want");
        $pr->setThumbNail("assets/image/products/8.jpg");
        $pr->setImages(["assets/image/products/p61.jpg","assets/image/products/p62.jpg","assets/image/products/p63.jpg",
                        "assets/image/products/p64.jpg","assets/image/products/p65.jpg","assets/image/products/p66.jpg",
                        "assets/image/products/p67.jpg"
        ]);
        $pr->setShowCase(false);
        $pr->setSort(4);

        $manager->persist($pr);

        $pr = new ProductionRange();
        $pr->setZhName("超市類");
        $pr->setEnName("Supermarket");
        $pr->setZhDescription("任意百搭，隨心所欲地創造你的袋型");
        $pr->setEnDescription("what ever you want");
        $pr->setThumbNail("assets/image/products/9.jpg");
        $pr->setImages(["assets/image/products/p68.jpg","assets/image/products/p69.jpg","assets/image/products/p70.jpg",
                        "assets/image/products/p71.jpg","assets/image/products/p72.jpg","assets/image/products/p73.jpg",
                        "assets/image/products/p74.jpg","assets/image/products/p75.jpg","assets/image/products/p76.jpg"
        ]);
        $pr->setShowCase(false);
        $pr->setSort(5);

        $manager->persist($pr);



        $manager->flush();
    }
}
