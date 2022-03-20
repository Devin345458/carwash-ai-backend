<?php

namespace App\Mailer;

use Cake\Datasource\EntityInterface;
use CakeDC\Users\Mailer\UsersMailer;

class UsersMailer extends UsersMailer
{
    public function resetPassword(EntityInterface $user)
    {
        parent::resetPassword($user);
        $this->setTemplate('reset_password');
        $this->setEmailFormat('html');
    }
}
