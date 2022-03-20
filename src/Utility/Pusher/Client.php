<?php

namespace App\Utility\Pusher;

interface Client
{
    public function publish($channel, $event, array $data);

    public function authenticate($channelName, $socketId);
}
