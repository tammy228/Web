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

        $manager->persist($user);

        $user1 = new User();

        $user1->setUuid();
        $user1->setEmail("user@gmail.com");
        $user1->validateEmail();
        $user1->setName("user");
        $user1->setRoles(['ROLE_USER']);
        $user1->setRoleCodes(2);
        $user1 -> setPassword($this->passwordEncoder->encodePassword(
            $user1,
            '12345'
        ));
        $user->setMobile("1234567890");

        $manager->persist($user1);

        $user2 = new User();

        $user2->setUuid();
        $user2->setEmail("farmer@gmail.com");
        $user2->validateEmail();
        $user2->setName("farmer");
        $user2->setRoles(['ROLE_FARMER']);
        $user2->setRoleCodes(1);
        $user2 -> setPassword($this->passwordEncoder->encodePassword(
            $user2,
            '12345'
        ));
        $user->setMobile("1234567890");

        $manager->persist($user2);

        $manager->flush();
    }
}


