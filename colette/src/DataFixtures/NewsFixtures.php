<?php

namespace App\DataFixtures;

use App\Entity\News;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NewsFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $news1 = new News();
        $news1->setUuid();
        $news1->setZhTitle("消息一標題");
        $news1->setZhContent('<p>現在有些書店甚至分出了一個新的流派 ——「超自然浪漫文學」。也許這種傳統由來已久，首部吸血鬼題材文學作品《吸血鬼》的作者波里道利，就將他的主要病人——拜倫——作為主角的原型。也是從 那時起，拜倫式的「瘋狂、邪惡和危險」便成了這類充滿浪漫和血腥的文學的主題。吸血鬼伯爵、希思克利夫、羅切斯特、達西更別提Channel 4新劇《真愛如血》中的吸血鬼比爾了，他們實際都同根同源。梅爾甚至聲稱《暮光之城》第一部的靈感來源於《傲慢與偏見》，但電影版的主演羅伯特·帕丁森中 長相卻酷似《無因的反抗》中的詹姆斯·狄恩。總之，吸血鬼就等於性感的反抗者。</p>');
        $news1->setEnContent("There are now even whole sections of bookshops given over to the new genre of \"supernatural romance\". Maybe it was ever thus. Dr Polidori, who wrote the very first vampire novel, The Vampyr, based his central character very much on his chief patient, Lord Byron, and the Byronic \"mad, bad and dangerous to know\" archetype has been at the centre of both romantic and blood-sucking fiction ever since. Dracula, Heathcliffe, Rochester, Darcy and not to mention chief vampire Bill in Channel 4's new series True Blood are all cut from the same cloth. Meyer even claims that she based her first Twilight book on Pride and Prejudice, although Robert Pattinson, who plays the lead in the movie version, looks like James Dean in Rebel Without A Cause. Either way, vampire = sexy rebel.");
        $news1->setImages(["/colette/img/news_2.png"]);

        $manager->persist($news1);

        $news2 = new News();
        $news2->setUuid();
        $news2->setZhTitle("消息二標題");
        $news2->setZhContent('<p>現在有些書店甚至分出了一個新的流派 ——「超自然浪漫文學」。也許這種傳統由來已久，首部吸血鬼題材文學作品《吸血鬼》的作者波里道利，就將他的主要病人——拜倫——作為主角的原型。也是從 那時起，拜倫式的「瘋狂、邪惡和危險」便成了這類充滿浪漫和血腥的文學的主題。吸血鬼伯爵、希思克利夫、羅切斯特、達西更別提Channel 4新劇《真愛如血》中的吸血鬼比爾了，他們實際都同根同源。梅爾甚至聲稱《暮光之城》第一部的靈感來源於《傲慢與偏見》，但電影版的主演羅伯特·帕丁森中 長相卻酷似《無因的反抗》中的詹姆斯·狄恩。總之，吸血鬼就等於性感的反抗者。</p>');
        $news2->setEnContent("There are now even whole sections of bookshops given over to the new genre of \"supernatural romance\". Maybe it was ever thus. Dr Polidori, who wrote the very first vampire novel, The Vampyr, based his central character very much on his chief patient, Lord Byron, and the Byronic \"mad, bad and dangerous to know\" archetype has been at the centre of both romantic and blood-sucking fiction ever since. Dracula, Heathcliffe, Rochester, Darcy and not to mention chief vampire Bill in Channel 4's new series True Blood are all cut from the same cloth. Meyer even claims that she based her first Twilight book on Pride and Prejudice, although Robert Pattinson, who plays the lead in the movie version, looks like James Dean in Rebel Without A Cause. Either way, vampire = sexy rebel.");
        $news2->setImages(["/colette/img/news_2.png"]);

        $manager->persist($news2);

        $news3 = new News();
        $news3->setUuid();
        $news3->setZhTitle("消息三標題");
        $news3->setZhContent('<p>現在有些書店甚至分出了一個新的流派 ——「超自然浪漫文學」。也許這種傳統由來已久，首部吸血鬼題材文學作品《吸血鬼》的作者波里道利，就將他的主要病人——拜倫——作為主角的原型。也是從 那時起，拜倫式的「瘋狂、邪惡和危險」便成了這類充滿浪漫和血腥的文學的主題。吸血鬼伯爵、希思克利夫、羅切斯特、達西更別提Channel 4新劇《真愛如血》中的吸血鬼比爾了，他們實際都同根同源。梅爾甚至聲稱《暮光之城》第一部的靈感來源於《傲慢與偏見》，但電影版的主演羅伯特·帕丁森中 長相卻酷似《無因的反抗》中的詹姆斯·狄恩。總之，吸血鬼就等於性感的反抗者。</p>');
        $news3->setEnContent("There are now even whole sections of bookshops given over to the new genre of \"supernatural romance\". Maybe it was ever thus. Dr Polidori, who wrote the very first vampire novel, The Vampyr, based his central character very much on his chief patient, Lord Byron, and the Byronic \"mad, bad and dangerous to know\" archetype has been at the centre of both romantic and blood-sucking fiction ever since. Dracula, Heathcliffe, Rochester, Darcy and not to mention chief vampire Bill in Channel 4's new series True Blood are all cut from the same cloth. Meyer even claims that she based her first Twilight book on Pride and Prejudice, although Robert Pattinson, who plays the lead in the movie version, looks like James Dean in Rebel Without A Cause. Either way, vampire = sexy rebel.");
        $news3->setImages(["/colette/img/news_2.png"]);

        $manager->persist($news3);

        $news4 = new News();
        $news4->setUuid();
        $news4->setZhTitle("消息四標題");
        $news4->setZhContent('<p>現在有些書店甚至分出了一個新的流派 ——「超自然浪漫文學」。也許這種傳統由來已久，首部吸血鬼題材文學作品《吸血鬼》的作者波里道利，就將他的主要病人——拜倫——作為主角的原型。也是從 那時起，拜倫式的「瘋狂、邪惡和危險」便成了這類充滿浪漫和血腥的文學的主題。吸血鬼伯爵、希思克利夫、羅切斯特、達西更別提Channel 4新劇《真愛如血》中的吸血鬼比爾了，他們實際都同根同源。梅爾甚至聲稱《暮光之城》第一部的靈感來源於《傲慢與偏見》，但電影版的主演羅伯特·帕丁森中 長相卻酷似《無因的反抗》中的詹姆斯·狄恩。總之，吸血鬼就等於性感的反抗者。</p>');
        $news4->setEnContent("There are now even whole sections of bookshops given over to the new genre of \"supernatural romance\". Maybe it was ever thus. Dr Polidori, who wrote the very first vampire novel, The Vampyr, based his central character very much on his chief patient, Lord Byron, and the Byronic \"mad, bad and dangerous to know\" archetype has been at the centre of both romantic and blood-sucking fiction ever since. Dracula, Heathcliffe, Rochester, Darcy and not to mention chief vampire Bill in Channel 4's new series True Blood are all cut from the same cloth. Meyer even claims that she based her first Twilight book on Pride and Prejudice, although Robert Pattinson, who plays the lead in the movie version, looks like James Dean in Rebel Without A Cause. Either way, vampire = sexy rebel.");
        $news4->setImages(["/colette/img/news_2.png"]);

        $manager->persist($news4);

        $news5 = new News();
        $news5->setUuid();
        $news5->setZhTitle("消息五標題");
        $news5->setZhContent('<p>現在有些書店甚至分出了一個新的流派 ——「超自然浪漫文學」。也許這種傳統由來已久，首部吸血鬼題材文學作品《吸血鬼》的作者波里道利，就將他的主要病人——拜倫——作為主角的原型。也是從 那時起，拜倫式的「瘋狂、邪惡和危險」便成了這類充滿浪漫和血腥的文學的主題。吸血鬼伯爵、希思克利夫、羅切斯特、達西更別提Channel 4新劇《真愛如血》中的吸血鬼比爾了，他們實際都同根同源。梅爾甚至聲稱《暮光之城》第一部的靈感來源於《傲慢與偏見》，但電影版的主演羅伯特·帕丁森中 長相卻酷似《無因的反抗》中的詹姆斯·狄恩。總之，吸血鬼就等於性感的反抗者。</p>');
        $news5->setEnContent("There are now even whole sections of bookshops given over to the new genre of \"supernatural romance\". Maybe it was ever thus. Dr Polidori, who wrote the very first vampire novel, The Vampyr, based his central character very much on his chief patient, Lord Byron, and the Byronic \"mad, bad and dangerous to know\" archetype has been at the centre of both romantic and blood-sucking fiction ever since. Dracula, Heathcliffe, Rochester, Darcy and not to mention chief vampire Bill in Channel 4's new series True Blood are all cut from the same cloth. Meyer even claims that she based her first Twilight book on Pride and Prejudice, although Robert Pattinson, who plays the lead in the movie version, looks like James Dean in Rebel Without A Cause. Either way, vampire = sexy rebel.");
        $news5->setImages(["/colette/img/news_2.png"]);

        $manager->persist($news5);

        $news6 = new News();
        $news6->setUuid();
        $news6->setZhTitle("消息六標題");
        $news6->setZhContent('<p>現在有些書店甚至分出了一個新的流派 ——「超自然浪漫文學」。也許這種傳統由來已久，首部吸血鬼題材文學作品《吸血鬼》的作者波里道利，就將他的主要病人——拜倫——作為主角的原型。也是從 那時起，拜倫式的「瘋狂、邪惡和危險」便成了這類充滿浪漫和血腥的文學的主題。吸血鬼伯爵、希思克利夫、羅切斯特、達西更別提Channel 4新劇《真愛如血》中的吸血鬼比爾了，他們實際都同根同源。梅爾甚至聲稱《暮光之城》第一部的靈感來源於《傲慢與偏見》，但電影版的主演羅伯特·帕丁森中 長相卻酷似《無因的反抗》中的詹姆斯·狄恩。總之，吸血鬼就等於性感的反抗者。</p>');
        $news6->setEnContent("There are now even whole sections of bookshops given over to the new genre of \"supernatural romance\". Maybe it was ever thus. Dr Polidori, who wrote the very first vampire novel, The Vampyr, based his central character very much on his chief patient, Lord Byron, and the Byronic \"mad, bad and dangerous to know\" archetype has been at the centre of both romantic and blood-sucking fiction ever since. Dracula, Heathcliffe, Rochester, Darcy and not to mention chief vampire Bill in Channel 4's new series True Blood are all cut from the same cloth. Meyer even claims that she based her first Twilight book on Pride and Prejudice, although Robert Pattinson, who plays the lead in the movie version, looks like James Dean in Rebel Without A Cause. Either way, vampire = sexy rebel.");
        $news6->setImages(["/colette/img/news_2.png"]);

        $manager->persist($news6);

        $news7 = new News();
        $news7->setUuid();
        $news7->setZhTitle("消息七標題");
        $news7->setZhContent('<p>現在有些書店甚至分出了一個新的流派 ——「超自然浪漫文學」。也許這種傳統由來已久，首部吸血鬼題材文學作品《吸血鬼》的作者波里道利，就將他的主要病人——拜倫——作為主角的原型。也是從 那時起，拜倫式的「瘋狂、邪惡和危險」便成了這類充滿浪漫和血腥的文學的主題。吸血鬼伯爵、希思克利夫、羅切斯特、達西更別提Channel 4新劇《真愛如血》中的吸血鬼比爾了，他們實際都同根同源。梅爾甚至聲稱《暮光之城》第一部的靈感來源於《傲慢與偏見》，但電影版的主演羅伯特·帕丁森中 長相卻酷似《無因的反抗》中的詹姆斯·狄恩。總之，吸血鬼就等於性感的反抗者。</p>');
        $news7->setEnContent("There are now even whole sections of bookshops given over to the new genre of \"supernatural romance\". Maybe it was ever thus. Dr Polidori, who wrote the very first vampire novel, The Vampyr, based his central character very much on his chief patient, Lord Byron, and the Byronic \"mad, bad and dangerous to know\" archetype has been at the centre of both romantic and blood-sucking fiction ever since. Dracula, Heathcliffe, Rochester, Darcy and not to mention chief vampire Bill in Channel 4's new series True Blood are all cut from the same cloth. Meyer even claims that she based her first Twilight book on Pride and Prejudice, although Robert Pattinson, who plays the lead in the movie version, looks like James Dean in Rebel Without A Cause. Either way, vampire = sexy rebel.");
        $news7->setImages(["/colette/img/news_2.png"]);

        $manager->persist($news7);

        $news8 = new News();
        $news8->setUuid();
        $news8->setZhTitle("消息八標題");
        $news8->setZhContent('<p>現在有些書店甚至分出了一個新的流派 ——「超自然浪漫文學」。也許這種傳統由來已久，首部吸血鬼題材文學作品《吸血鬼》的作者波里道利，就將他的主要病人——拜倫——作為主角的原型。也是從 那時起，拜倫式的「瘋狂、邪惡和危險」便成了這類充滿浪漫和血腥的文學的主題。吸血鬼伯爵、希思克利夫、羅切斯特、達西更別提Channel 4新劇《真愛如血》中的吸血鬼比爾了，他們實際都同根同源。梅爾甚至聲稱《暮光之城》第一部的靈感來源於《傲慢與偏見》，但電影版的主演羅伯特·帕丁森中 長相卻酷似《無因的反抗》中的詹姆斯·狄恩。總之，吸血鬼就等於性感的反抗者。</p>');
        $news8->setEnContent("There are now even whole sections of bookshops given over to the new genre of \"supernatural romance\". Maybe it was ever thus. Dr Polidori, who wrote the very first vampire novel, The Vampyr, based his central character very much on his chief patient, Lord Byron, and the Byronic \"mad, bad and dangerous to know\" archetype has been at the centre of both romantic and blood-sucking fiction ever since. Dracula, Heathcliffe, Rochester, Darcy and not to mention chief vampire Bill in Channel 4's new series True Blood are all cut from the same cloth. Meyer even claims that she based her first Twilight book on Pride and Prejudice, although Robert Pattinson, who plays the lead in the movie version, looks like James Dean in Rebel Without A Cause. Either way, vampire = sexy rebel.");
        $news8->setImages(["/colette/img/news_2.png"]);

        $manager->persist($news8);

        $news9 = new News();
        $news9->setUuid();
        $news9->setZhTitle("消息九標題");
        $news9->setZhContent('<p>現在有些書店甚至分出了一個新的流派 ——「超自然浪漫文學」。也許這種傳統由來已久，首部吸血鬼題材文學作品《吸血鬼》的作者波里道利，就將他的主要病人——拜倫——作為主角的原型。也是從 那時起，拜倫式的「瘋狂、邪惡和危險」便成了這類充滿浪漫和血腥的文學的主題。吸血鬼伯爵、希思克利夫、羅切斯特、達西更別提Channel 4新劇《真愛如血》中的吸血鬼比爾了，他們實際都同根同源。梅爾甚至聲稱《暮光之城》第一部的靈感來源於《傲慢與偏見》，但電影版的主演羅伯特·帕丁森中 長相卻酷似《無因的反抗》中的詹姆斯·狄恩。總之，吸血鬼就等於性感的反抗者。</p>');
        $news9->setEnContent("There are now even whole sections of bookshops given over to the new genre of \"supernatural romance\". Maybe it was ever thus. Dr Polidori, who wrote the very first vampire novel, The Vampyr, based his central character very much on his chief patient, Lord Byron, and the Byronic \"mad, bad and dangerous to know\" archetype has been at the centre of both romantic and blood-sucking fiction ever since. Dracula, Heathcliffe, Rochester, Darcy and not to mention chief vampire Bill in Channel 4's new series True Blood are all cut from the same cloth. Meyer even claims that she based her first Twilight book on Pride and Prejudice, although Robert Pattinson, who plays the lead in the movie version, looks like James Dean in Rebel Without A Cause. Either way, vampire = sexy rebel.");
        $news9->setImages(["/colette/img/news_2.png"]);

        $manager->persist($news9);

        $news10 = new News();
        $news10->setUuid();
        $news10->setZhTitle("消息十標題");
        $news10->setZhContent('<p>現在有些書店甚至分出了一個新的流派 ——「超自然浪漫文學」。也許這種傳統由來已久，首部吸血鬼題材文學作品《吸血鬼》的作者波里道利，就將他的主要病人——拜倫——作為主角的原型。也是從 那時起，拜倫式的「瘋狂、邪惡和危險」便成了這類充滿浪漫和血腥的文學的主題。吸血鬼伯爵、希思克利夫、羅切斯特、達西更別提Channel 4新劇《真愛如血》中的吸血鬼比爾了，他們實際都同根同源。梅爾甚至聲稱《暮光之城》第一部的靈感來源於《傲慢與偏見》，但電影版的主演羅伯特·帕丁森中 長相卻酷似《無因的反抗》中的詹姆斯·狄恩。總之，吸血鬼就等於性感的反抗者。</p>');
        $news10->setEnContent("There are now even whole sections of bookshops given over to the new genre of \"supernatural romance\". Maybe it was ever thus. Dr Polidori, who wrote the very first vampire novel, The Vampyr, based his central character very much on his chief patient, Lord Byron, and the Byronic \"mad, bad and dangerous to know\" archetype has been at the centre of both romantic and blood-sucking fiction ever since. Dracula, Heathcliffe, Rochester, Darcy and not to mention chief vampire Bill in Channel 4's new series True Blood are all cut from the same cloth. Meyer even claims that she based her first Twilight book on Pride and Prejudice, although Robert Pattinson, who plays the lead in the movie version, looks like James Dean in Rebel Without A Cause. Either way, vampire = sexy rebel.");
        $news10->setImages(["/colette/img/news_2.png"]);

        $manager->persist($news10);

        $news11 = new News();
        $news11->setUuid();
        $news11->setZhTitle("消息十一標題");
        $news11->setZhContent('<p>現在有些書店甚至分出了一個新的流派 ——「超自然浪漫文學」。也許這種傳統由來已久，首部吸血鬼題材文學作品《吸血鬼》的作者波里道利，就將他的主要病人——拜倫——作為主角的原型。也是從 那時起，拜倫式的「瘋狂、邪惡和危險」便成了這類充滿浪漫和血腥的文學的主題。吸血鬼伯爵、希思克利夫、羅切斯特、達西更別提Channel 4新劇《真愛如血》中的吸血鬼比爾了，他們實際都同根同源。梅爾甚至聲稱《暮光之城》第一部的靈感來源於《傲慢與偏見》，但電影版的主演羅伯特·帕丁森中 長相卻酷似《無因的反抗》中的詹姆斯·狄恩。總之，吸血鬼就等於性感的反抗者。</p>');
        $news11->setEnContent("There are now even whole sections of bookshops given over to the new genre of \"supernatural romance\". Maybe it was ever thus. Dr Polidori, who wrote the very first vampire novel, The Vampyr, based his central character very much on his chief patient, Lord Byron, and the Byronic \"mad, bad and dangerous to know\" archetype has been at the centre of both romantic and blood-sucking fiction ever since. Dracula, Heathcliffe, Rochester, Darcy and not to mention chief vampire Bill in Channel 4's new series True Blood are all cut from the same cloth. Meyer even claims that she based her first Twilight book on Pride and Prejudice, although Robert Pattinson, who plays the lead in the movie version, looks like James Dean in Rebel Without A Cause. Either way, vampire = sexy rebel.");
        $news11->setImages(["/colette/img/news_2.png"]);

        $manager->persist($news11);

        $manager->flush();
    }
}
