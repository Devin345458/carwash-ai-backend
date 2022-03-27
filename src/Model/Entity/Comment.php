<?php
namespace App\Model\Entity;

use App\Classes\ActivityLoggableInterface;
use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Comment Entity
 *
 * @property int $id
 * @property string $content
 * @property string $created_by_id
 * @property string $modified_by_id
 * @property int commentable_id
 * @property string commentable_type
 * @property FrozenTime $created
 * @property FrozenTime $modified
 * @property int $repair_id
 *
 * @property User $user
 */
class Comment extends Entity implements ActivityLoggableInterface
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'content' => true,
        'commentable_id' => true,
        'commentable_type' => true,
        'created' => true,
        'modified' => true,
        'created_by_id' => true,
        'modified_by_id' => true,
        'created_by' => true,
        'modified_by' => true,
    ];


    public function getMessage($user, string $action): string
    {
        switch ($action) {
            case 'created':
                return $user->full_name . ' made a comment "' . $this->content .'"';
            case 'deleted':
                return $user->full_name . ' deleted a comment "' . $this->content .'"';
            default:
                return 'No Details';
        }
    }
}
