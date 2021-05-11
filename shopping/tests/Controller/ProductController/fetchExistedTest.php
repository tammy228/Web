<?php

namespace App\Tests\Controller\ProductController;

use App\Tests\Controller\ControllerTest;
use App\Tests\Helper\ClientProvider;
use App\Tests\Helper\JsonValidator\UserController\CreateUserResponseValidator;
use App\Tests\Helper\JsonValidator\UserController\FetchUserResponseValidator;
use App\Tests\Helper\ScenarioBuilder\ProductControllerBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class fetchExistedTest extends ControllerTest
{
    public function testAction()
    {
        $scenarioBuilder = new ProductControllerBuilder($this->entityManager);
        $scenarioBuilder->build();

        $this->client->request('GET', '/product/1');

        $this->assertEquals(
            Response::HTTP_MOVED_PERMANENTLY,
            $this->client->getResponse()->getStatusCode(),
            "Fetch Product Failed");
    }
}