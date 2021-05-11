<?php

namespace App\DataFixtures;

use App\Entity\Config;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ConfigFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $config = new Config();

        $config->setTitle('Collete');
        $config->setKeyword('Collete');
        $config->setDescription('Collete');
        $config->setShippingStandard(8);
        $config->setOwner('admin');

        $manager->persist($config);
        $manager->flush();
    }
}
