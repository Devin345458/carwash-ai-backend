<?php


namespace App\Classes;

use ZMQ;
use ZMQContext;
use ZMQSocket;
use ZMQSocketException;

class SocketClient
{
    /**
     * @var SocketClient
     */
    protected static $_instance;

    /**
     * @var ZMQSocket
     */
    protected $socket;

    /**
     * SocketClient constructor.
     *
     * @throws ZMQSocketException
     */
    private function __construct()
    {
        $context = new ZMQContext();
        $socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect('tcp://localhost:5555');
        $this->socket = $socket;
        self::$_instance = $this;
    }

    /**
     * @return SocketClient
     */
    public static function getInstance()
    {
        return self::$_instance ?? new SocketClient();
    }

    /**
     * @param  string $topic
     * @param  $data
     * @throws ZMQSocketException
     */
    public static function broadcast(string $topic, $data)
    {
        $send = ['topic' => $topic, 'data' => $data];
        self::getInstance()->socket->send(json_encode($send));
    }
}
