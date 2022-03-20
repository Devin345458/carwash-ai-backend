<?php
/**
 * Created by PhpStorm.
 * User: Devinhollister-graham
 * Date: 12/8/18
 * Time: 4:03 PM
 */

/**
 * Created by PhpStorm.
 * User: Devinhollister-graham
 * Date: 11/8/18
 * Time: 7:28 PM
 */

namespace App\Controller\Component;

use App\Model\Table\MaintenancesTable;
use Cake\Controller\Component;
use Cake\I18n\Time;
use Cake\ORM\Locator\TableLocator;
use Cake\ORM\TableRegistry;

/**
 * @property MaintenancesTable Maintenances
 */
class recentSortComponent extends Component
{
    private $controller = null;
    private $session = null;
    public $component = [];

    /**
     * @param array $config
     */
    public function initialize(array $config): void
    {
    }

    public function recentSort($item1, $item2)
    {
        // check if there is a modified field
        if ($item1->modified) {
            $item1_time = $item1->modified;
        } else {
            $item1_time = $item1->created;
        }

        if ($item2->modified) {
            $item2_time = $item2->modified;
        } else {
            $item2_time = $item2->created;
        }
    }
}
