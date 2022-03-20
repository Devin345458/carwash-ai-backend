<?php
namespace App\Model\Entity;

use Cake\Log\Log;
use Cake\ORM\Entity;

/**
 * MaintenancesPart Entity
 *
 * @property int $maintenances_id
 * @property int $parts_id
 * @property int $quantity
 *
 * @property Maintenance $maintenance
 * @property Part $part
 */
class Notification extends Entity
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
        'data' => true,
        'tracking_id' => true,
        'user_id' => true,
        'state' => false,
        'user' => false,
    ];

    /**
     * _getData
     *
     * Getter for the vars-column.
     *
     * @param  $data
     * @return mixed
     */
    protected function _getData($data)
    {
        $array = json_decode($data, true);

        if (is_object($array)) {
            return $array;
        }

        return $data;
    }

    /**
     * _setData
     *
     * Setter for the vars-column
     *
     * @param  $data
     * @return string
     */
    protected function _setData($data)
    {
        if (is_array($data)) {
            return json_encode($data);
        }

        return $data;
    }

    /**
     * _getTitle
     *
     * Getter for the title.
     * Data is used from the data-column.
     * The template is used from the configurations.
     *
     * @return string
     */
    protected function _getTitle()
    {
        $data = json_decode($this->_properties['data'], true);
        if (isset($data['title'])) {
            return $data['title'];
        }

        return '';
    }

    /**
     * _getTo
     *
     * Getter for the title.
     * Data is used from the data-column.
     * The template is used from the configurations.
     *
     * @return string
     */
    protected function _getTo()
    {
        $data = json_decode($this->_properties['data'], true);
        if (isset($data['to'])) {
            return $data['to'];
        }

        return '';
    }

    /**
     * _getDescription
     *
     * Getter for the title.
     * Data is used from the data-column.
     *
     * @return string
     */
    protected function _getDescription()
    {
        $data = json_decode($this->_properties['data'], true);
        if (isset($data['description'])) {
            return $data['description'];
        }

        return '';
    }

    /**
     * _getImage_url
     *
     * Getter for the title.
     * Data is used from the data-column.
     *
     * @return string
     */
    protected function _getImage_url()
    {
        $data = json_decode($this->_properties['data'], true);
        if (isset($data['image_url'])) {
            return $data['image_url'];
        }

        return '';
    }

    /**
     * _getUnread
     *
     * Boolean if the notification is read or not.
     *
     * @return bool
     */
    protected function _getUnread()
    {
        if ($this->_properties['state'] === 1) {
            return true;
        }

        return false;
    }

    /**
     * _getRead
     *
     * Boolean if the notification is read or not.
     *
     * @return bool
     */
    protected function _getRead()
    {
        if ($this->_properties['state'] === 0) {
            return true;
        }

        return false;
    }

    /**
     * Virtual fields
     *
     * @var array
     */
    protected $_virtual = ['title', 'description', 'image_url', 'unread', 'read', 'to'];
}
