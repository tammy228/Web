<?php

namespace App\DataFixtures;

use App\Entity\Album;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AlbumFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $album = new Album();
        $album->setName("未分類");
        $album->setOfflineAt(new \DateTime('9999-12-31 23:59:59'));
        $album->setDeletable(false);
        $album->setDescription("This is uncategorized album");

        $manager->persist($album);
        $manager->flush();
    }
}
