<?php

namespace App\Utility\Pusher;

use Pusher\Pusher;

class PusherSdkClient implements Client
{
    private $pusher;

    /**
     * PusherSdkClient constructor.
     *
     * @param  string $appKey
     * @param  string $appSecret
     * @param  string $appId
     * @param  array  $options
     * @throws \Pusher\PusherException
     */
    public function __construct($appKey, $appSecret, $appId, array $options)
    {
        $this->pusher = new Pusher($appKey, $appSecret, $appId, $options);
    }

    /**
     * @param  string $channel
     * @param  string $event
     * @param  array  $data
     * @return mixed
     * @throws \Pusher\PusherException
     */
    public function publish($channel, $event, array $data)
    {
        return $this->pusher->trigger($channel, $event, $data);
    }

    /**
     * @param  $channelName
     * @param  $socketId
     * @return string
     * @throws \Pusher\PusherException
     */
    public function authenticate($channelName, $socketId)
    {
        return $this->pusher->socket_auth($channelName, $socketId);
    }
}
