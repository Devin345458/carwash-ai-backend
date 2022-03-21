<?php
/**
 * Created by PhpStorm.
 * User: Devinhollister-graham
 * Date: 11/8/18
 * Time: 7:28 PM
 */

namespace App\Controller\Component;

use App\Utility\NotificationManager;
use Cake\Controller\Component;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;

class notificationComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [
        'UsersModel' => 'Users',
    ];
    /**
     * The controller.
     *
     * @var \Cake\Controller\Controller
     */
    private $Controller = null;
    /**
     * @var \App\Model\Table\NotificationsTable
     */
    private $notificationTable;

    /**
     * initialize
     *
     * @param  array $config Config.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);
        $this->notificationTable = TableRegistry::getTableLocator()->get('Notifications');
        $this->Controller = $this->_registry->getController();
    }

    /**
     * @param  string $level - Options = 'store', 'company', 'user'
     * @param  array  $roles - List of roles
     * @param  array  $data  - required
     */
    public function sendNotification($level, array $roles, array $data)
    {
        $channel = [];
        $user_list = [];
        if ($level === 'company') {
            if (!isset($data['company_id'])) {
                return;
            }
            $channel = $data['company_id'] . '-company-notification';
        } elseif ($level === 'store') {
            if (!isset($data['store_id'])) {
                return;
            }
            $channel = $data['store_id'] . '-store-notification';
        } elseif ($level === 'user') {
            if (!isset($data['user_id'])) {
                return;
            }
             $channel = $data['user_id'] . '-notification';
        }

        foreach ($send_list as $id) {
        }
    }

    /**
     * setController
     *
     * Setter for the Controller property.
     *
     * @param  \Cake\Controller\Controller $controller Controller.
     * @return void
     */
    public function setController($controller)
    {
        $this->Controller = $controller;
    }

    /**
     * getNotifications
     *
     * Returns a list of notifications.
     *
     * ### Examples
     * ```
     *  // if the user is logged in, this is the way to get all notifications
     *  $this->Notifier->getNotifications();
     *
     *  // for a specific user, use the first parameter for the user_id
     *  $this->Notifier->getNotifications(1);
     *
     *  // default all notifications are returned. Use the second parameter to define read / unread:
     *
     *  // get all unread notifications
     *  $this->Notifier->getNotifications(1, true);
     *
     *  // get all read notifications
     *  $this->Notifier->getNotifications(1, false);
     * ```
     *
     * @param  int|null  $userId Id of the user.
     * @param  bool|null $state  The state of notifications: `true` for unread, `false` for read, `null` for all.
     * @return Query
     */
    public function getNotifications($userId = null, $state = null)
    {
        if (!$userId) {
            $userId = $this->Controller->Auth->user('id');
        }

        $query = $this->notificationTable->find()->where(['Notifications.user_id' => $userId])->order(['created' => 'desc']);

        if (!is_null($state)) {
            $query->where(['Notifications.state' => $state]);
        }

        return $query;
    }

    /**
     * countNotifications
     *
     * Returns a number of notifications.
     *
     * ### Examples
     * ```
     *  // if the user is logged in, this is the way to count all notifications
     *  $this->Notifier->countNotifications();
     *
     *  // for a specific user, use the first parameter for the user_id
     *  $this->Notifier->countNotifications(1);
     *
     *  // default all notifications are counted. Use the second parameter to define read / unread:
     *
     *  // count all unread notifications
     *  $this->Notifier->countNotifications(1, true);
     *
     *  // count all read notifications
     *  $this->Notifier->countNotifications(1, false);
     * ```
     *
     * @param  int|null  $userId Id of the user.
     * @param  bool|null $state  The state of notifications: `true` for unread, `false` for read, `null` for all.
     * @return int
     */
    public function countNotifications($userId = null, $state = null)
    {
        if (!$userId) {
            $userId = $this->Controller->Auth->user('id');
        }

        $model = TableRegistry::get('Notifications');

        $query = $model->find()->where(['Notifications.user_id' => $userId]);

        if (!is_null($state)) {
            $query->where(['Notifications.state' => $state]);
        }

        return $query->count();
    }

    /**
     * markAsRead
     *
     * Used to mark a notification as read.
     * If no notificationId is given, all notifications of the chosen user will be marked as read.
     *
     * @param  int      $notificationId Id of the notification.
     * @param  int|null $user           Id of the user. Else the id of the session will be taken.
     * @return void
     */
    public function markAsRead($notificationId = null, $user = null)
    {
        if (!$user) {
            $user = $this->Controller->Auth->user('id');
        }

        if (!$notificationId) {
            $query = $this->notificationTable->find('all')->where(
                [
                'user_id' => $user,
                'state' => 1,
                ]
            );
        } else {
            $query = $this->notificationTable->find('all')->where(
                [
                'user_id' => $user,
                'id' => $notificationId,
                ]
            );
        }

        foreach ($query as $item) {
            $item->set('state', 0);
            $this->notificationTable->save($item);
        }
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
     *      'data' => [
     *          'receiver' => $receiver->name
     *          'total' => $order->total
     *      ],
     *  ]);
     * ```
     *
     * @param  array $data Data with options.
     * @return string
     */
    public function notify($data)
    {
        return NotificationManager::instance()->notify($data);
    }
}
