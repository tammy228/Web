<?php

namespace App\Tests\Entity\User;

use App\Tests\Entity\EntityTest;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ActivateUserTest extends EntityTest
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    protected $entityManager;

    public function testAction()
    {
        $kernel = self::bootKernel();
        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        $user = new User();
        $uuid = Uuid::uuid4();
        $user->setUuid($uuid);
        $user->setName("test_user");
        $user->setPassword('12345');
        $user->setEmail("testuser@gmail.com");
        $user->setCreateAt(new \Datetime('now + 8hours'));
        $user->setEmailVerified(false);
        $user->setRoles(["ROLE_USER"]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $userRepository
            = $this->entityManager->getRepository(User::class);

        $userFromDB = $userRepository->findOneBy(array("uuid" => $uuid));
        $this->assertNotEquals(null, $userFromDB, "User Insertion Failed.");

        $this->assertEquals($uuid, $userFromDB->getUuid(), "User UUID Insertion Failed");
        $this->assertEquals("test_user", $userFromDB->getName());

        $this->assertEquals("testuser@gmail.com", $userFromDB->getEmail());
        $this->assertNotEquals(null, $userFromDB->getCreateAt());
    }
}