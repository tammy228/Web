<?php
namespace App\Socket;

use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;

use App\Service\Socket\SocketService;


class ChatRoom implements MessageComponentInterface{
    private $subscriptions;
    private $clients;
    private $users;

    private $ss;

    public function __construct(SocketService $socketService) {
        $this->subscriptions = [];
        $this->clients = [];
        $this->users = [];
        $this->ss = $socketService;
    }

    public function onOpen(ConnectionInterface $socket) {
        //parse uri
        $data = $this->ss->parseUri($socket);
        $roomId = $data[0];
        $userId = $data[1];

        //register
        $this->users[$socket->resourceId] = $userId;
        $this->clients[$socket->resourceId] = $socket;
        $this->subscriptions[$socket->resourceId] = $roomId;

        //做到朋友點進聊天室就顯示已讀
        $target = $this->subscriptions[$socket->resourceId];
        foreach ($this->subscriptions as $id=>$channel) {
            if ($channel == $target && $id != $socket->resourceId) {
                $this->clients[$id]->send("");
                //點進聊天室就更新reader
                $this->ss->updateReader($roomId, $userId);
            }
        }

        echo "New user! ({$socket->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $numRecv = array_count_values($this->subscriptions)[$this->subscriptions[$from->resourceId]] - 1;
        echo sprintf('Connection %d sending message "%s" to %d other socket connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        //parse data
        $data = json_decode($msg,true);
        $dataMessage = $data['message'];
        $chatRoomId = $data['roomId'];
        $currentUserId = $data['userId'];

        $this->ss->createMessage($dataMessage, $currentUserId,0, $chatRoomId);

        //聊天室subscribe 超過一人才send 否則直接寫資料庫，做到朋友在聊天室一直顯示已讀
        $target = $this->subscriptions[$from->resourceId];
        if (array_count_values($this->subscriptions)[$target] - 1) {
            foreach ($this->subscriptions as $id => $roomId) {
                if ($roomId == $target && $id != $from->resourceId) {
                    $this->clients[$id]->send($msg);
                    $this->ss->updateReader($roomId, $this->users[$id]);
                }elseif($id == $from->resourceId){
                    $this->clients[$id]->send("");
                }
            }
        }
    }

    public function onClose(ConnectionInterface $socket) {
        //parse uri
        $data = $this->ss->parseUri($socket);
        $roomId = $data[0];
        $userId = $data[1];

        $this->ss->updateReadMessage($roomId, $userId,null);

        unset($this->clients[$socket->resourceId]);
        unset($this->users[$socket->resourceId]);
        unset($this->subscriptions[$socket->resourceId]);
        echo "Connection {$socket->resourceId} was terminated\n";

    }

    public function onError(ConnectionInterface $socket, \Exception $e) {
        echo "You got an error: {$e->getMessage()}\n";
        $socket->close();
    }
}