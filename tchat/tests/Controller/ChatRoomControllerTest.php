<?php

namespace App\Tests;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ChatRoomControllerTest extends WebTestCase
{
    /**
     * @var EntityManagerInterface $em
     */
    private $em;

    public function resetDatabase()
    {
        exec("php ./bin/console d:d:d --force");
        exec("php ./bin/console d:d:c");
        exec("php ./bin/console d:m:m -q");
        exec("php ./bin/console d:f:l -q");

    }

    public function testGet()
    {
        $this->resetDatabase();
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'tammy',
            'PHP_AUTH_PW'   => '12345',
        ]);
        $client->request('GET', '/chat-rooms/create');

        $this->assertEquals(Response::HTTP_OK, $client->getResponse()->getStatusCode());
    }

}
