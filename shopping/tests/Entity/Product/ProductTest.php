<?php

namespace App\Tests\Entity\Product;

use App\Tests\Entity\EntityTest;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ProductTest extends EntityTest
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

        $product = new Product();
        $product->setName('衣服');
        $product->setDescription('衣服');
        $now = new \Datetime('now + 8hours');
        $product->setCreateAt($now);
        $product->setUpdateAt($now);
        $product->setFormat(['L','M','S']);
        $product->setStock([100,100,100]);
        $product->setPrice([250,250,250]);
        $product->setImage(["/uploads/photos/test.jpg",]);

        $this->entityManager->persist($product);
        $this->entityManager->flush();

        $productRepository
            = $this->entityManager->getRepository(Product::class);

        $productFromDB = $productRepository->findOneBy(array("create_at" => $now));
        $this->assertNotEquals(null, $productFromDB, "User Insertion Failed.");

        $this->assertEquals("衣服", $productFromDB->getName());
        $this->assertEquals("衣服", $productFromDB->getDescription());
        $this->assertNotEquals(null, $productFromDB->getCreateAt());
        $this->assertNotEquals(null, $productFromDB->getUpdateAt());

        $this->tearDown();
    }
}