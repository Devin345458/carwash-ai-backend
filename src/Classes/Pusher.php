<?php


namespace App\Classes;

use Cake\Console\ConsoleIo;
use Cake\Log\Log;
use Ratchet\ConnectionInterface;
use React\ZMQ\Context;
use Thruway\ClientSession;
use Thruway\Peer\Client;
use Thruway\Transport\TransportInterface;
use ZMQ;

/**
 * When a user publishes to a topic all clients who have subscribed
 * to that topic will receive the message/event from the publisher
 */
class Pusher extends Client
{
    /**
     * A lookup of all the topics clients have subscribed to
     */
    protected $subscribedTopics = [];

    protected $io;

    public function setIO(ConsoleIo $io)
    {
        $this->io = $io;
    }

    /**
     * This is meant to be overridden so that the client can do its
     * thing
     *
     * @param ClientSession      $session
     * @param TransportInterface $transport
     */
    public function onSessionStart($session, $transport)
    {
        Log::info('Session Start');
        $context = new Context($this->getLoop());
        $pull    = $context->getSocket(ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:5555');
        $pull->on('message', [$this, 'forwardMessage']);
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function forwardMessage($message)
    {
        Log::info($message);
        $message = json_decode($message, true);
        $this->getSession()->publish($message['topic'], [], $message['data']);
    }

    public function onUnSubscribe(ConnectionInterface $conn, $topic)
    {
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $topic, 'RPC not supported')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        Log::info('Publish' . json_encode($topic));
        $topic->broadcast($event);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }
}
