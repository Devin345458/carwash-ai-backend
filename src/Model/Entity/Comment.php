<?php
namespace App\Model\Entity;

use Cake\I18n\FrozenTime;
use Cake\ORM\Entity;

/**
 * Comment Entity
 *
 * @property int $id
 * @property string $content
 * @property string $myuser_id
 * @property FrozenTime $created
 * @property FrozenTime $modified
 * @property int $repair_id
 *
 * @property Myuser $myuser
 */
class Comment extends Entity
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
        'myuser_id' => true,
        'created' => true,
        'modified' => true,
        'commentable_id' => true,
        'commentable_type' => true,
        'myuser' => true,
    ];
}
