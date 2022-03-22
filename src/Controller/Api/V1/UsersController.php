<?php

namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\User;
use App\Model\Table\UsersTable;
use Authentication\Authenticator\UnauthenticatedException;
use Authentication\PasswordHasher\DefaultPasswordHasher;
use Cake\Datasource\ResultSetInterface;
use Cake\Event\EventInterface;
use Cake\Http\Exception\UnauthorizedException;
use Cake\ORM\Query;
use Cake\Utility\Security;
use Exception;
use Firebase\JWT\JWT;

/**
 * Users Controller
 *
 * @property UsersTable Users
 * @method User[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class UsersController extends AppController
{
    /**
     * @inheritDoc
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        $this->Authentication->allowUnauthenticated(['login', 'register', 'logout']);
    }

    public function index($storeId = null) {
        $users = $this->Users->find();

        if ($storeId) {
            $users->innerJoinWith('Stores', function (Query $query) use ($storeId) {
                return $query->where(['Stores.id' => $storeId]);
            });
        } else {
            $users->where(['Users.company_id =' => $this->Authentication->getUser()->company_id]);
        }

        if ($this->getRequest()->getQuery('excludeOwners')) {
            $users->where(['Users.role !=' => 'owner']);
        }

        $this->set(compact('users'));
    }

    /**
     * Login a user
     *
     * @throws Exception
     * @return void
     */
    public function login()
    {
        $this->getRequest()->allowMethod(['POST']);

        // Check if valid login
        $results = $this->Authentication->getResult();

        if (!$results->isValid()) {
            throw new Exception($results->getStatus(), 401);
        } else {
            $this->json([
                'status' => 'success',
                'token' => JWT::encode(
                    [
                        'sub' => $results->getData()['id'],
                        'exp' => time() + 60 * 60 * 7, // 7 days
                    ],
                    Security::getSalt()
                ),
            ]);
        }
    }

    /**
     * Returns the logged in user
     *
     * @return void
     */
    public function loggedInUser()
    {
        $this->getRequest()->allowMethod(['GET']);
        $this->set(['user' => $this->Authentication->getIdentity()->getOriginalData()]);
    }

    /**
     * Logout user
     *
     * @throws Exception
     * @return void
     */
    public function logout()
    {
        $this->Authentication->logout();
    }

    /**
     * Initial Registration of a new customer and their first store and admin user
     *
     * @throws Exception
     * @return void
     */
    public function register()
    {
        $this->getRequest()->allowMethod(['POST']);

        $user = $this->Users->register($this->getRequest()->getData());

        $user = $this->Users->get($user->id, [
            'finder' => 'auth',
        ]);

        $this->Authentication->setIdentity($user);
        $this->set(compact($user));
    }

    public function edit() {
        $data = $this->getRequest()->getData();
        if ($data['id'] !== $this->Authentication->getUser()->id) {
            throw new UnauthorizedException('You may only edit your own profile');
        }
        $user = $this->Users->get($data['id']);
        $user = $this->Users->patchEntity($user, $data);
        if (!$this->Users->save($user)) {
            throw new ValidationException($user);
        }
        $this->set(compact('user'));
    }

    public function resetPassword() {
        $data = $this->getRequest()->getData();
        if (!(new DefaultPasswordHasher)->check($data['current_password'], $this->Authentication->getUser()->password)) {
            throw new UnauthenticatedException('Incorrect Current Password');
        }
        $user = $this->Authentication->getUser();
        $user->password = $data['password'];
        if (!$this->Users->save($user)) {
            throw new ValidationException($user);
        }

        $this->set(compact('user'));
    }

    /**
     * @throws Exception
     */
    public function add()
    {
        $user = $this->Users->newEntity($this->getRequest()->getData());
        $this->Users->touch($user, 'Users.activate');
        if (!$this->Users->save($user)) {
            throw new ValidationException($user);
        }
    }

    public function store($id) {
        $users = $this->Users->find();
        $users
            ->innerJoinWith('Stores', function(Query $query) use ($id) {
                return $query->where(['Stores.id' => $id]);
            })
            ->select(['store_count' => $users->func()->count('Stores.id')])
            ->group('Users.id')
            ->enableAutoFields()->toArray();

        $this->paginate($users);

        $this->set(compact('users'));
    }

    public function upsert($store_id) {
        $data = $this->getRequest()->getData();
        if ($data['id']) {
            $user = $this->Users->get($data['id']);
        } else {
            $user = $this->Users->newEntity([
                'company_id' => $this->Authentication->getUser()->company_id,
                'stores' => [
                    '_ids' => [$store_id]
                ]
            ]);
        }
        $user = $this->Users->patchEntity($user, $data);
        if (!$this->Users->save($user)) {
            throw new ValidationException($user);
        }
    }

    public function removeStore($userId, $storeId) {
        $user = $this->Users->get($userId);
        $store = $this->Users->Stores->get($storeId);
        $this->Users->Stores->unlink($user, [$store]);
    }
}
