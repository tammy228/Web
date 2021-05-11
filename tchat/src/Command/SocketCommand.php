<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

// Include ratchet libs
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

use App\Socket\ChatRoom;
use App\Service\Socket\SocketService;

class SocketCommand extends Command
{
    private $ss;
    public function __construct(SocketService $socketService)
    {
        parent::__construct();
        $this->ss = $socketService;
    }

    protected function configure()
    {
        $this->setName('sockets:start-chat')
            // the short description shown while running "php bin/console list"
            ->setHelp("Starts the chat socket demo")
            // the full command description shown when running the command with
            ->setDescription('Starts the chat socket demo')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Websocket chat',
            '============',
            'Starting chat, open your browser.'
        ]);

        $server = IoServer::factory(
            new HttpServer(
                new WsServer(
                    new ChatRoom($this->ss)
                )
            ),
            8080
        );

        $server->run();
    }
}