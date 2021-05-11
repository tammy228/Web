<?php
/**
 * Created by PhpStorm.
 * User: floatflower
 * Date: 2020/2/20
 * Time: 12:02 AM
 */

namespace App\Tests\Entity;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EntityTest extends KernelTestCase
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    protected $entityManager;

    public function setUp()
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        exec("php ./bin/console doctrine:database:create --env=test");
        exec("php ./bin/console doctrine:migrations:migrate -q --env=test");



    }

    public function tearDown(): void
    {
        exec("php ./bin/console doctrine:database:drop --force --env=test");
    }
}