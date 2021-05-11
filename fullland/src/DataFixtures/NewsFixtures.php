<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\News;

class NewsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $news = new News();
        $news->setZhTitle('測試1');
        $news->setZhContent('只是測試');
        $news->setEnTitle('test1');
        $news->setEnContent('just test');
        $news->setThumbNail("assets/image/news/7.jpg");
        $news->setImages(["assets/image/news_detail/7.jpg"]);

        $manager->persist($news);

        $news = new News();
        $news->setZhTitle('測試2');
        $news->setZhContent('只是測試');
        $news->setEnTitle('test2');
        $news->setEnContent('just test');
        $news->setThumbNail("assets/image/news/7.jpg");
        $news->setImages(["assets/image/news_detail/7.jpg"]);


        $manager->persist($news);

        $news = new News();
        $news->setZhTitle('測試3');
        $news->setZhContent('只是測試');
        $news->setEnTitle('test3');
        $news->setEnContent('just test');
        $news->setThumbNail("assets/image/news/7.jpg");
        $news->setImages(["assets/image/news_detail/7.jpg"]);

        $manager->persist($news);

        $news = new News();
        $news->setZhTitle('測試4');
        $news->setZhContent('只是測試');
        $news->setEnTitle('test4');
        $news->setEnContent('just test');
        $news->setThumbNail("assets/image/news/7.jpg");
        $news->setImages(["assets/image/news_detail/7.jpg"]);

        $manager->persist($news);

        $news = new News();
        $news->setZhTitle('測試5');
        $news->setZhContent('只是測試');
        $news->setEnTitle('test5');
        $news->setEnContent('just test');
        $news->setThumbNail("assets/image/news/7.jpg");
        $news->setImages(["assets/image/news_detail/7.jpg"]);


        $manager->persist($news);

        $news = new News();
        $news->setZhTitle('測試6');
        $news->setZhContent('只是測試');
        $news->setEnTitle('test6');
        $news->setEnContent('just test');
        $news->setThumbNail("assets/image/news/7.jpg");
        $news->setImages(["assets/image/news_detail/7.jpg"]);


        $manager->persist($news);

        $news = new News();
        $news->setZhTitle('測試7');
        $news->setZhContent('只是測試');
        $news->setEnTitle('test7');
        $news->setEnContent('just test');
        $news->setThumbNail("assets/image/news/7.jpg");
        $news->setImages(["assets/image/news_detail/7.jpg"]);


        $manager->persist($news);

        $manager->flush();
    }
}
