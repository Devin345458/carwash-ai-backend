<?php

namespace App\Classes;

use App\Model\Entity\User;

interface ActivityLoggableInterface
{
    /**
     * @param User $user
     * @param string $action
     * @return string
     */
    public function getMessage($user, string $action): string;
}
