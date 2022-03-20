<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Controller\Component\notificationComponent;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Comment;
use App\Model\Table\CommentsTable;
use Cake\Datasource\ResultSetInterface;
use Exception;

/**
 * Comments Controller
 *
 * @property CommentsTable $Comments
 * @property notificationComponent $notification
 * @method   Comment[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class CommentsController extends AppController
{

    /**
     * Index method
     * @throws Exception
     */
    public function index() {
        $id = $this->getRequest()->getQuery('commentable_id');
        $type = $this->getRequest()->getQuery('commentable_type');
        if (!$id) {
            throw new Exception('Must specify query parameters commentable_id');
        }
        if (!$type) {
            throw new Exception('Must specify query parameters commentable_type');
        }
        $comments = $this->Comments->find()->where([
            'commentable_id' => $id,
            'commentable_type' => $type
        ])->orderDesc('Comments.created')->toArray();
        $this->set(compact('comments'));
    }
    /**
     * Add method
     */
    public function add() {
        $comment = $this->Comments->newEntity($this->getRequest()->getData());
        if (!$this->Comments->save($comment)) {
            throw new ValidationException($comment);
        }
        $this->set(compact('comment'));
    }
}
