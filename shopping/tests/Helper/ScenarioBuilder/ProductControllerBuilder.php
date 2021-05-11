<?php


namespace App\Tests\Helper\ScenarioBuilder;


use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;

class ProductControllerBuilder extends AbstractScenarioBuilder
{
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct($entityManager);
    }

    /**
     * 建立測試 ProductController::fetchProduct() 的環境
     *
     * @throws \Exception
     */
    public function build()
    {
        $now = new \Datetime('now + 8hours');

        $testProduct1 = new Product();
        $testProduct1->setName("product1");
        $testProduct1->setCreateAt($now);
        $testProduct1->setUpdateAt($now);
        $testProduct1->setDescription("Test User 1");
        $testProduct1->setFormat(['XL','L','M']);
        $testProduct1->setPrice([100,100,100]);
        $testProduct1->setStock([100,100,100]);

        $this->entityManager->persist($testProduct1);

        $testProduct2 = new Product();
        $testProduct2->setName("product2");
        $testProduct2->setCreateAt($now);
        $testProduct2->setUpdateAt($now);
        $testProduct2->setDescription("Test User 2");
        $testProduct2->setFormat(['XL','L','M']);
        $testProduct2->setPrice([100,100,100]);
        $testProduct2->setStock([100,100,100]);

        $this->entityManager->persist($testProduct2);

        $testProduct3 = new Product();
        $testProduct3->setName("product3");
        $testProduct3->setCreateAt($now);
        $testProduct3->setUpdateAt($now);
        $testProduct3->setDescription("Test User 3");
        $testProduct3->setFormat(['XL','L','M']);
        $testProduct3->setPrice([100,100,100]);
        $testProduct3->setStock([100,100,100]);

        $this->entityManager->persist($testProduct3);

        $this->entityManager->flush();
    }
}