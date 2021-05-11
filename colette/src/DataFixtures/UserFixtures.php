<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
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

        $user->setUuid();
        $user->setEmail("admin@gmail.com");
        $user->validateEmail();
        $user->setName("admin");
        $user->setRoles(['ROLE_ADMIN']);
        $user->setRoleCodes(0);
        $user -> setPassword($this->passwordEncoder->encodePassword(
            $user,
            '12345'
        ));
        $user->setMobile("1234567890");
        $user->setAddress("臺北市;中山區;104;test");

        $manager->persist($user);

        $user1 = new User();

        $user1->setUuid();
        $user1->setEmail("user@gmail.com");
        $user1->validateEmail();
        $user1->setName("user");
        $user1->setRoles(['ROLE_USER']);
        $user1->setRoleCodes(1);
        $user1 -> setPassword($this->passwordEncoder->encodePassword(
            $user1,
            '12345'
        ));
        $user1->setMobile("1234567890");
        $user1->setAddress("臺北市;中山區;104;test");


        $manager->persist($user1);

        $manager->flush();
    }
}


