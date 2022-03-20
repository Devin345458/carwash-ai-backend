<?php
/**
 * Bakkerij (https://github.com/bakkerij)
 * Copyright (c) https://github.com/bakkerij
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) https://github.com/bakkerij
 * @link      https://github.com/bakkerij Bakkerij Project
 * @since     1.0
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Utility;

use App\Utility\Pusher\PusherSdkClient;
use Cake\Core\Configure;
use Cake\Log\Log;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Utility\Hash;

/**
 * Notifier component
 */
class NotificationManager
{
    protected static $_generalManager = null;

    /**
     * instance
     *
     * The singleton class uses the instance() method to return the instance of the NotificationManager.
     *
     * @param  null $manager Possible different manager. (Helpfull for testing).
     * @return NotificationManager
     */
    public static function instance($manager = null)
    {
        if ($manager instanceof NotificationManager) {
            static::$_generalManager = $manager;
        }
        if (empty(static::$_generalManager)) {
            static::$_generalManager = new NotificationManager();
        }

        return static::$_generalManager;
    }

    /**
     * notify
     *
     * Sends notifications to specific users.
     * The first parameter `$data` is an array with multiple options.
     *
     * ### Options
     * - `users` - An array or int with id's of users who will receive a notification.
     * - `roles` - An array or int with id's of roles which all users ill receive a notification.
     * - `template` - The template wich will be used.
     * - `vars` - The variables used in the template.
     *
     * ### Example
     * ```
     *  NotificationManager::instance()->notify([
     *      'users' => 1,
     *      'template' => 'newOrder',
     *      'vars' => [
     *          'receiver' => $receiver->name
     *          'total' => $order->total
     *      ],
     *  ]);
     * ```
     *
     * @param  array $data Data with options.
     * @return string The tracking_id to follow the notification.
     * @throws \Pusher\PusherException
     */
    public function notify(array $data)
    {
        $pusherClient = new PusherSdkClient(
            env('PUSHER_APPKEY'),
            env('PUSHER_SECRET'),
            env('PUSHER_APPID'),
            [
                'cluster' => 'us2',
                'useTLS' => true,
            ]
        );

        if (isset($data['data']['store_id']) && in_array('Store', $data['recipientLists'])) {
            $activeStoreUsers = Hash::extract(
                TableRegistry::getTableLocator()->get('Users')->find()->matching(
                    'Stores',
                    function (Query $query) use ($data) {
                        return $query->where(['Stores.id =' => $data['data']['store_id']]);
                    }
                )->select('Users.id')->toArray(),
                '{n}.id'
            );
            $this->addRecipientList('Store', $activeStoreUsers);
        } elseif (isset($data['data']['company_id']) && in_array('Company', $data['recipientLists'])) {
            $activeCompanyUsers = Hash::extract(
                TableRegistry::getTableLocator()->get('Users')->find()->matching(
                    'Stores',
                    function (Query $query) use ($data) {
                        return $query->where(['Stores.company_id =' => $data['data']['company_id']]);
                    }
                )->select('id')->toArray(),
                '{n}.id'
            );
            $this->addRecipientList('Company', $activeCompanyUsers);
        }

        $model = TableRegistry::getTableLocator()->get('Notifications');

        $_data = [
            'users' => [],
            'recipientLists' => [],
            'data' => [],
            'tracking_id' => $this->getTrackingId(),
        ];

        $data = array_merge($_data, $data);
        Log::debug(json_encode($data));

        foreach ((array)$data['recipientLists'] as $recipientList) {
            $list = (array)$this->getRecipientList($recipientList);
            $data['users'] = array_merge($data['users'], $list);
        }

        foreach ((array)$data['users'] as $user) {
            $entity = $model->newEntity([
                'tracking_id' => $data['tracking_id'],
                'data' => $data['data'],
                'state' => 1,
                'user_id' => $user
            ]);
            $model->save($entity);
            $pusherClient->publish($user . '-notification', 'newNotification', $entity->toArray());
        }

        return $data['tracking_id'];
    }

    /**
     * addRecipientList
     *
     * Method to add a new recipient list.
     * Recipient lists are used to create presets of users to write notifications to.
     *
     * ### Example
     * ```
     *  $notificationManager->addRecipientList('administrators', [1,2,3,4]);
     * ```
     *
     * The data will be stored in Cake's Configure: `Notifier.recipientLists.{name}`
     *
     * @param  string $name    Name of the list.
     * @param  array  $userIds Array with id's of users.
     * @return void
     */
    public function addRecipientList(string $name, array $userIds)
    {
        Configure::write('Notifier.recipientLists.' . $name, $userIds);
    }

    /**
     * getRecipientList
     *
     * Returns a requested recipient list from Cake's Configure.
     * Will return `null` if the list doesn't exist.
     *
     * @param  string $name The name of the list.
     * @return array|null
     */
    public function getRecipientList(string $name)
    {
        return Configure::read('Notifier.recipientLists.' . $name);
    }

    /**
     * getTrackingId
     *
     * Generates a tracking id for a notification.
     *
     * @return string
     */
    public function getTrackingId()
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $trackingId = '';
        for ($i = 0; $i < 10; $i++) {
            $trackingId .= $characters[rand(0, $charactersLength - 1)];
        }

        return $trackingId;
    }
}
