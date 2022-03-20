<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Category;
use App\Model\Table\CategoriesTable;
use Cake\Datasource\ResultSetInterface;

/**
 * Categories Controller
 *
 * @property CategoriesTable $Categories
 * @method Category[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class CategoriesController extends AppController
{
    public function getCompanyCategories($model = null)
    {
        $this->getRequest()->allowMethod('GET');
        $categories = $this->Categories->find()
            ->where(
                [
                    'Categories.company_id =' => $this->Authentication->getUser()->company_id,
                    'model =' => $model,
                ]
            );
        $categories = $categories->toArray();
        $this->set(compact('categories'));
    }
}
