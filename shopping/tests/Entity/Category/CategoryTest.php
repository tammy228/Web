<?php

namespace App\Tests\Entity\User;

use App\Tests\Entity\EntityTest;
use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class CategoryTest extends EntityTest
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

        $category = new Category();
        $category->setName('衣服');
        $category->setCreateAt();
        $category->setUpdateAt();

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        $categoryRepository
            = $this->entityManager->getRepository(Category::class);
        $activateCategory = $categoryRepository->findOneBy(array("name" => '衣服'));

        $this->assertNotEquals(null, $activateCategory, "User Insertion Failed.");

        $this->assertEquals("衣服", $activateCategory->getName());
        $this->assertNotEquals(null, $activateCategory->getCreateAt());
        $this->assertNotEquals(null, $activateCategory->getUpdateAt());

        $this->tearDown();
    }
}