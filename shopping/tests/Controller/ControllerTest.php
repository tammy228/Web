<?php

namespace App\Tests\Controller;

use App\Tests\Helper\ClientProvider;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class ControllerTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    protected $entityManager;

    /**
     * @var KernelBrowser $client
     */
    protected $client;

    public function setUp()
    {
        $this->client = ClientProvider::getInstance()->getSingletonClient();
        $this->entityManager = $this->client
            ->getContainer()
            ->get('doctrine')
            ->getManager();
        exec("php ./bin/console doctrine:database:create --env=test");
        exec("php ./bin/console doctrine:migrations:migrate -q --env=test");

    }

    public function tearDown(): void
    {
        exec('php ./bin/console cache:clear --env=test');
        exec("php ./bin/console doctrine:database:drop --force --env=test");
    }
}