<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $user = new User();

        $user->setName("tammy");
        $user->setEmail("tammy@gmail.com");
        $user->setRoles(['ROLE_USER']);
        $user -> setPassword($this->passwordEncoder->encodePassword(
            $user,
            '12345'
        ));

        $manager->persist($user);

        $test = new User();

        $test->setName("test");
        $test->setEmail("test@gmail.com");
        $test->setRoles(['ROLE_USER']);
        $test -> setPassword($this->passwordEncoder->encodePassword(
            $test,
            '11111'
        ));

        $manager->persist($test);

        $test2 = new User();

        $test2->setName("test2");
        $test2->setEmail("test2@gmail.com");
        $test2->setRoles(['ROLE_USER']);
        $test2 -> setPassword($this->passwordEncoder->encodePassword(
            $test2,
            '22222'
        ));

        $manager->persist($test2);

        $test3 = new User();

        $test3->setName("test3");
        $test3->setEmail("test3@gmail.com");
        $test3->setRoles(['ROLE_USER']);
        $test3 -> setPassword($this->passwordEncoder->encodePassword(
            $test3,
            '33333'
        ));

        $manager->persist($test3);

        $manager->flush();
    }
}
