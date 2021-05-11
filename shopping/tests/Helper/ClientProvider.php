<?php


namespace App\Tests\Helper;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClientProvider extends WebTestCase
{
    /**
     * @var ClientProvider $singleton
     */
    public static $singleton = null;

    protected $client = null;

    public static function getInstance()
    {
        if(ClientProvider::$singleton === null)
            ClientProvider::$singleton = new ClientProvider();

        return ClientProvider::$singleton;
    }

    public function getSingletonClient()
    {
        if($this->client === null)
            $this->client = self::createClient();

        return clone $this->client;
    }
}