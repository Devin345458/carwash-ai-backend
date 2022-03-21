<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link      https://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   https://opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use App\Controller\Component\AuthenticationComponent;
use Authorization\Controller\Component\AuthorizationComponent;
use Cake\Controller\Controller;
use Cake\Core\Configure;
use Cake\Event\EventInterface;
use Cake\Http\Response;
use Cake\Routing\Router;
use Exception;
use Queue\Model\Table\QueuedJobsTable;

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link     https://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 * @property QueuedJobsTable $QueuedJobs
 * @property AuthenticationComponent Authentication
 * @property AuthorizationComponent Authorization
 */
class AppController extends Controller
{
    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * e.g. `$this->loadComponent('Security');`
     *
     * @return void
     * @throws Exception
     */
    public function initialize(): void
    {
        parent::initialize();

        // Force every request to be json
        $this->setRequest($this->getRequest()->withParam('_ext', 'json'));
        Router::setRequest($this->getRequest());

        $this->loadComponent('RequestHandler');

        $this->loadComponent('Paginator');

        // Formats pagination details as JSON
        $this->loadComponent('ApiPagination');

        $this->loadComponent('Authentication');
        $this->loadComponent('Authorization.Authorization');

        $this->loadModel('Queue.QueuedJobs');
    }

    /**
     * @param  EventInterface $event
     * @return Response|void|null
     */
    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        //setting the timezone for all dates using TimeHelper
        if ($this->Authentication->getIdentity()) {
            Configure::write('Config.timezone', $this->Authentication->getIdentityData('time_zone'));
        }
    }

    public function beforeRender(EventInterface $event)
    {
        parent::beforeRender($event);

        if ($this->getRequest()->getParam('_ext') === 'json' && $this->getResponse()->getStatusCode() < 203) {
            $data = $this->viewBuilder()->getVars();
            $_default = ['success' => true];
            $array = array_merge($_default, $data);
            if (isset($array['_serialize'])) {
                unset($array['_serialize']);
            }
            $this->viewBuilder()->setOption('serialize', array_keys($array));
            $this->set($array);
        }
    }

    public function json(array $data) {
        $this->set($data);
        $this->viewBuilder()->setOption('serialize', array_keys($data));
    }
}
