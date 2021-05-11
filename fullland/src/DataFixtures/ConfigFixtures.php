<?php

namespace App\DataFixtures;

use App\Entity\Config;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ConfigFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $config = new Config();

        $config->setTitle('FullLand');
        $config->setKeyword('FullLand');
        $config->setDescription('FullLand');
        $config->setShippingStandard(8);
        $config->setOwner('admin');

        $manager->persist($config);

        $manager->flush();
    }
}
