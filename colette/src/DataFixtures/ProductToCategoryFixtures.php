<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Product;
use App\Entity\ProductToCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProductToCategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        /***
         *       ______      __
         *      / ____/___ _/ /____  ____ _____  _______  __
         *     / /   / __ `/ __/ _ \/ __ `/ __ \/ ___/ / / /
         *    / /___/ /_/ / /_/  __/ /_/ / /_/ / /  / /_/ /
         *    \____/\__,_/\__/\___/\__, /\____/_/   \__, /
         *                        /____/           /____/
         */

        /***
         *       ______        __
         *      / ____/____ _ / /__ ___
         *     / /    / __ `// //_// _ \
         *    / /___ / /_/ // ,<  /  __/
         *    \____/ \__,_//_/|_| \___/
         *
         */

        $category2 = new Category();

        $category2->setUuid();
        $category2->setZhName("蛋糕");
        $category2->setEnName("cake");
        $category2->setThumbnail("");

        $manager->persist($category2);

        $category2Son1 = new Category();

        $category2Son1->setUuid();
        $category2Son1->setZhName("果香飄飄");
        $category2Son1->setEnName("fruit");
        $category2Son1->setThumbnail("");
        $category2Son1->setParent($category2);

        $manager->persist($category2Son1);

        $category2Son2 = new Category();

        $category2Son2->setUuid();
        $category2Son2->setZhName("深度濃郁");
        $category2Son2->setEnName("deep");
        $category2Son2->setThumbnail("");
        $category2Son2->setParent($category2);


        $manager->persist($category2Son2);

        $category2Son3 = new Category();

        $category2Son3->setUuid();
        $category2Son3->setZhName("法式小塔");
        $category2Son3->setEnName("french");
        $category2Son3->setThumbnail("");
        $category2Son3->setParent($category2);


        $manager->persist($category2Son3);

        $category2Son4 = new Category();

        $category2Son4->setUuid();
        $category2Son4->setZhName("禪風茶趣");
        $category2Son4->setEnName("Zen");
        $category2Son4->setThumbnail("");
        $category2Son4->setParent($category2);


        $manager->persist($category2Son4);

        $category2Son5 = new Category();

        $category2Son5->setUuid();
        $category2Son5->setZhName("乳芳核果");
        $category2Son5->setEnName("nuts");
        $category2Son5->setThumbnail("");
        $category2Son5->setParent($category2);


        $manager->persist($category2Son5);


        /***
         *                _
         *        ____   (_)___
         *       / __ \ / // _ \
         *      / /_/ // //  __/
         *     / .___//_/ \___/
         *    /_/
         */


        $category3 = new Category();

        $category3->setUuid();
        $category3->setZhName("鹹派");
        $category3->setEnName("pie");
        $category3->setThumbnail("");

        $manager->persist($category3);

        $category3Son1 = new Category();

        $category3Son1->setUuid();
        $category3Son1->setZhName("果香飄飄");
        $category3Son1->setEnName("fruit");
        $category3Son1->setThumbnail("");
        $category3Son1->setParent($category3);


        $manager->persist($category3Son1);

        $category3Son2 = new Category();

        $category3Son2->setUuid();
        $category3Son2->setZhName("深度濃郁");
        $category3Son2->setEnName("deep");
        $category3Son2->setThumbnail("");
        $category3Son2->setParent($category3);


        $manager->persist($category3Son2);

        $category3Son3 = new Category();

        $category3Son3->setUuid();
        $category3Son3->setZhName("法式小塔");
        $category3Son3->setEnName("french");
        $category3Son3->setThumbnail("");
        $category3Son3->setParent($category3);


        $manager->persist($category3Son3);

        $category3Son4 = new Category();

        $category3Son4->setUuid();
        $category3Son4->setZhName("禪風茶趣");
        $category3Son4->setEnName("Zen");
        $category3Son4->setThumbnail("");
        $category3Son4->setParent($category3);


        $manager->persist($category3Son4);

        $category3Son5 = new Category();

        $category3Son5->setUuid();
        $category3Son5->setZhName("乳芳核果");
        $category3Son5->setEnName("nuts");
        $category3Son5->setThumbnail("");
        $category3Son5->setParent($category3);


        $manager->persist($category3Son4);


        /***
         *
         *       ____ ___   ____ _ _____ ____ _ _____ ____   ____   ____
         *      / __ `__ \ / __ `// ___// __ `// ___// __ \ / __ \ / __ \
         *     / / / / / // /_/ // /__ / /_/ // /   / /_/ // /_/ // / / /
         *    /_/ /_/ /_/ \__,_/ \___/ \__,_//_/    \____/ \____//_/ /_/
         *
         */

        $category4 = new Category();

        $category4->setUuid();
        $category4->setZhName("馬卡龍");
        $category4->setEnName("macaroon");
        $category4->setThumbnail("");

        $manager->persist($category4);

        $category4Son1 = new Category();

        $category4Son1->setUuid();
        $category4Son1->setZhName("果香飄飄");
        $category4Son1->setEnName("fruit");
        $category4Son1->setThumbnail("");
        $category4Son1->setParent($category4);

        $manager->persist($category4Son1);

        $category4Son2 = new Category();

        $category4Son2->setUuid();
        $category4Son2->setZhName("深度濃郁");
        $category4Son2->setEnName("deep");
        $category4Son2->setThumbnail("");
        $category4Son2->setParent($category4);


        $manager->persist($category4Son2);

        $category4Son3 = new Category();

        $category4Son3->setUuid();
        $category4Son3->setZhName("法式小塔");
        $category4Son3->setEnName("french");
        $category4Son3->setThumbnail("");
        $category4Son3->setParent($category4);


        $manager->persist($category4Son3);

        $category4Son4 = new Category();

        $category4Son4->setUuid();
        $category4Son4->setZhName("禪風茶趣");
        $category4Son4->setEnName("Zen");
        $category4Son4->setThumbnail("");
        $category4Son4->setParent($category4);


        $manager->persist($category4Son4);

        $category4Son5 = new Category();

        $category4Son5->setUuid();
        $category4Son5->setZhName("乳芳核果");
        $category4Son5->setEnName("nuts");
        $category4Son5->setThumbnail("");
        $category4Son5->setParent($category4);

        $manager->persist($category4Son5);


        /***
         *                           __    _
         *      _____ ____   ____   / /__ (_)___
         *     / ___// __ \ / __ \ / //_// // _ \
         *    / /__ / /_/ // /_/ // ,<  / //  __/
         *    \___/ \____/ \____//_/|_|/_/ \___/
         *
         */


        $category5 = new Category();

        $category5->setUuid();
        $category5->setZhName("餅乾");
        $category5->setEnName("cookie");
        $category5->setThumbnail("");

        $manager->persist($category5);

        $category5Son1 = new Category();

        $category5Son1->setUuid();
        $category5Son1->setZhName("果香飄飄");
        $category5Son1->setEnName("fruit");
        $category5Son1->setThumbnail("");
        $category5Son1->setParent($category5);

        $manager->persist($category5Son1);

        $category5Son2 = new Category();

        $category5Son2->setUuid();
        $category5Son2->setZhName("深度濃郁");
        $category5Son2->setEnName("deep");
        $category5Son2->setThumbnail("");
        $category5Son2->setParent($category5);

        $manager->persist($category5Son2);

        $category5Son3 = new Category();

        $category5Son3->setUuid();
        $category5Son3->setZhName("法式小塔");
        $category5Son3->setEnName("french");
        $category5Son3->setThumbnail("");
        $category5Son3->setParent($category5);

        $manager->persist($category5Son3);

        $category5Son4 = new Category();

        $category5Son4->setUuid();
        $category5Son4->setZhName("禪風茶趣");
        $category5Son4->setEnName("Zen");
        $category5Son4->setThumbnail("");
        $category5Son4->setParent($category5);

        $manager->persist($category5Son4);

        $category5Son5 = new Category();

        $category5Son5->setUuid();
        $category5Son5->setZhName("乳芳核果");
        $category5Son5->setEnName("nuts");
        $category5Son5->setThumbnail("");
        $category5Son5->setParent($category5);

        $manager->persist($category5Son5);


        $category1 = new Category();

        $category1->setUuid();
        $category1->setZhName("未分類");
        $category1->setEnName("cat0");
        $category1->setThumbnail("");

        $manager->persist($category1);


        $manager->flush();


        /***
         *        ____                 __           __
         *       / __ \_________  ____/ /_  _______/ /_
         *      / /_/ / ___/ __ \/ __  / / / / ___/ __/
         *     / ____/ /  / /_/ / /_/ / /_/ / /__/ /_
         *    /_/   /_/   \____/\__,_/\__,_/\___/\__/
         *
         */

        $product1 = new Product();

        $product1->setUuid();
        $product1->setZhName("test1");
        $product1->setEnName("test1");
        $product1->setThumbNail("/colette/img/featured/1.png");
        $product1->setImages(['/colette/img/store2.png','/colette/img/store4.png','/colette/img/store5.png']);
        $product1->setZhDescription("test1Description");
        $product1->setEnDescription("test1Description");
        $product1->setPrice([100,200]);
        $product1->setStock([10,20]);
        $product1->setSize(["八吋","六吋"]);
        $product1->setTemperature("常溫");


        $manager->persist($product1);

        $product2 = new Product();

        $product2->setUuid();
        $product2->setZhName("test2");
        $product2->setEnName("test2");
        $product2->setThumbNail("/colette/img/featured/2.png");
        $product2->setImages(array());
        $product2->setZhDescription("test2Description");
        $product2->setEnDescription("test2Description");
        $product2->setPrice([100,200]);
        $product2->setStock([10,20]);
        $product2->setSize(["八吋","六吋"]);
        $product2->setTemperature("冷藏");

        $manager->persist($product2);

        $product3 = new Product();

        $product3->setUuid();
        $product3->setZhName("test3");
        $product3->setEnName("test3");
        $product3->setThumbNail("/colette/img/featured/2.png");
        $product3->setImages(array());
        $product3->setZhDescription("test3Description");
        $product3->setEnDescription("test3Description");
        $product3->setPrice([100,200]);
        $product3->setStock([10,20]);
        $product3->setSize(["八吋","六吋"]);
        $product3->setTemperature("冷藏");

        $manager->persist($product3);

        $product4 = new Product();

        $product4->setUuid();
        $product4->setZhName("test4");
        $product4->setEnName("test4");
        $product4->setThumbNail("/colette/img/featured/3.png");
        $product4->setImages(array());
        $product4->setZhDescription("test4Description");
        $product4->setEnDescription("test4Description");
        $product4->setPrice([100,200]);
        $product4->setStock([10,20]);
        $product4->setSize(["八吋","六吋"]);
        $product4->setTemperature("常溫");

        $manager->persist($product4);

        $product5 = new Product();

        $product5->setUuid();
        $product5->setZhName("test5");
        $product5->setEnName("test5");
        $product5->setThumbNail("/colette/img/featured/4.png");
        $product5->setImages(array());
        $product5->setZhDescription("test5Description");
        $product5->setEnDescription("test5Description");
        $product5->setPrice([100,200]);
        $product5->setStock([10,20]);
        $product5->setSize(["八吋","六吋"]);
        $product5->setTemperature("常溫");

        $manager->persist($product5);

        $product6 = new Product();

        $product6->setUuid();
        $product6->setZhName("test6");
        $product6->setEnName("test6");
        $product6->setThumbNail("/colette/img/featured/4.png");
        $product6->setImages(array());
        $product6->setZhDescription("test6Description");
        $product6->setEnDescription("test6Description");
        $product6->setPrice([100,200]);
        $product6->setStock([10,20]);
        $product6->setSize(["八吋","六吋"]);
        $product6->setTemperature("常溫");

        $manager->persist($product6);


        /***
         *        ____                 __           __     ______         ______      __
         *       / __ \_________  ____/ /_  _______/ /_   /_  __/___     / ____/___ _/ /____  ____ _____  _______  __
         *      / /_/ / ___/ __ \/ __  / / / / ___/ __/    / / / __ \   / /   / __ `/ __/ _ \/ __ `/ __ \/ ___/ / / /
         *     / ____/ /  / /_/ / /_/ / /_/ / /__/ /_     / / / /_/ /  / /___/ /_/ / /_/  __/ /_/ / /_/ / /  / /_/ /
         *    /_/   /_/   \____/\__,_/\__,_/\___/\__/    /_/  \____/   \____/\__,_/\__/\___/\__, /\____/_/   \__, /
         *                                                                                 /____/           /____/
         */

        $ptc = new ProductToCategory();

        $ptc->setCategory($category2Son1);
        $ptc->setProduct($product1);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category2Son1);
        $ptc->setProduct($product2);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category2Son1);
        $ptc->setProduct($product3);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category2Son1);
        $ptc->setProduct($product4);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category2Son1);
        $ptc->setProduct($product5);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category2Son1);
        $ptc->setProduct($product6);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category2Son2);
        $ptc->setProduct($product1);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category2Son2);
        $ptc->setProduct($product2);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category2Son2);
        $ptc->setProduct($product3);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category2Son2);
        $ptc->setProduct($product4);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category3Son1);
        $ptc->setProduct($product1);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category3Son1);
        $ptc->setProduct($product2);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category3Son1);
        $ptc->setProduct($product3);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category4Son1);
        $ptc->setProduct($product1);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category4Son1);
        $ptc->setProduct($product2);

        $manager->persist($ptc);

        $ptc = new ProductToCategory();

        $ptc->setCategory($category4Son1);
        $ptc->setProduct($product3);

        $manager->persist($ptc);


        $manager->flush();
    }
}
