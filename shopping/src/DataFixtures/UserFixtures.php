<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;
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
        $admin = new User();
        $admin->setUuid(Uuid::uuid4());
        $admin->setName("admin");
        $admin->setEmail("admin@gmail.com");
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword($this->passwordEncoder->encodePassword(
            $admin,
            '12345'
        ));
        $admin->setCreateAt(new \Datetime('now + 8hours'));
        $admin->setEmailVerified(true);

        $manager->persist($admin);

        $user = new User();
        $user->setUuid(Uuid::uuid4());
        $user->setName("user");
        $user->setEmail("user@gmail.com");
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($this->passwordEncoder->encodePassword(
            $user,
            '12345'
        ));
        $user->setCreateAt(new \Datetime('now + 8hours'));
        $user->setEmailVerified(false);

        $manager->persist($user);

        $manager->flush();
    }
}
