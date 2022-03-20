<?php
/**
 * Copyright 2010 - 2017, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2017, Cake Development Corporation (https://www.cakedc.com)
 * @license   MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

namespace App\Controller\Traits;

use App\Error\Exception\ValidationException;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\Validation\Validator;
use CakeDC\Users\Controller\Component\UsersAuthComponent;
use CakeDC\Users\Exception\TokenExpiredException;
use CakeDC\Users\Exception\UserNotActiveException;
use CakeDC\Users\Exception\UserNotFoundException;
use CakeDC\Users\Exception\WrongPasswordException;
use Exception;

/**
 * Covers the password management: reset, change
 *
 * @property \Cake\Controller\Component\AuthComponent $Auth
 * @property \Cake\Http\ServerRequest $request
 */
trait PasswordManagementTrait
{
    use UserValidationTrait;

    /**
     * Change password
     *
     * @param  $token
     * @throws Exception
     */
    public function changePassword($token = null)
    {
        $user = $this->getUsersTable()->newEntity();
        $id = $this->Auth->user('id');
        if (!empty($id)) {
            $user->id = $this->Auth->user('id');
        } else {
            $user->id = $this->validate('password', $token, true);
        }
        $validator = $this->getUsersTable()->validationPasswordConfirm(new Validator());
        if (!empty($id)) {
            $validator = $this->getUsersTable()->validationCurrentPassword($validator);
        }
        $user = $this->getUsersTable()->patchEntity(
            $user,
            $this->request->getData(),
            ['validate' => $validator]
        );
        if ($user->getErrors()) {
            throw new ValidationException($user);
        } else {
            $user = $this->getUsersTable()->changePassword($user);
            if ($user) {
                if ($this->Auth->user('id')) {
                    $this->set(
                        [
                        'user' => $this->getUsersTable()->find('auth')->where(['Users.id' => $user->id])->first(),
                        '_serialize' => ['user'],
                        ]
                    );
                } else {
                    $this->set(
                        [
                        'success' => true,
                        'message' => 'Password has been changed successfully',
                        '_serialize' => ['success', 'message'],
                        ]
                    );
                }
            } else {
                throw new ValidationException($user);
            }
        }
    }

    /**
     * Logged In Change password
     *
     * @param  $token
     * @throws Exception
     */
    public function loggedChangePassword($token = null)
    {
        $user = $this->getUsersTable()->newEntity();
        $id = $this->Auth->user('id');
        if (!empty($id)) {
            $user->id = $this->Auth->user('id');
        } else {
            $user->id = $this->validate('password', $token, true);
        }
        $validator = $this->getUsersTable()->validationPasswordConfirm(new Validator());
        if (!empty($id)) {
            $validator = $this->getUsersTable()->validationCurrentPassword($validator);
        }
        $user = $this->getUsersTable()->patchEntity(
            $user,
            $this->request->getData(),
            ['validate' => $validator]
        );
        if ($user->getErrors()) {
            throw new ValidationException($user);
        } else {
            $user = $this->getUsersTable()->changePassword($user);
            if ($user) {
                if ($this->Auth->user('id')) {
                    $this->set(
                        [
                        'user' => $this->getUsersTable()->find('auth')->where(['Users.id' => $user->id])->first(),
                        '_serialize' => ['user'],
                        ]
                    );
                } else {
                    $this->set(
                        [
                        'success' => true,
                        'message' => 'Password has been changed successfully',
                        '_serialize' => ['success', 'message'],
                        ]
                    );
                }
            } else {
                throw new ValidationException($user);
            }
        }
    }

    /**
     * Reset password
     *
     * @param  null $token token data.
     * @return void
     */
    public function resetPassword($token = null)
    {
        $this->validate('password', $token);
    }

    /**
     * Reset password
     *
     * @return void|\Cake\Http\Response
     */
    public function requestResetPassword()
    {
        $reference = $this->request->getData('email');
        $resetUser = $this->getUsersTable()->resetToken(
            $reference,
            [
            'expiration' => Configure::read('Users.Token.expiration'),
            'checkActive' => false,
            'sendEmail' => true,
            'ensureActive' => Configure::read('Users.Registration.ensureActive'),
            ]
        );
        if ($resetUser) {
            $this->set(
                [
                'success' => true,
                '_serialize' => 'success',
                ]
            );
        } else {
            throw new Exception('The password token could not be generated. Please try again');
        }
    }
}
