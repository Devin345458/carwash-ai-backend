<?php
namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\Company;
use App\Model\Table\CompaniesTable;
use Cake\Datasource\ResultSetInterface;
use Cake\ORM\Query;
use ChargeBee_Customer;
use Exception;

/**
 * Companies Controller
 *
 * @property CompaniesTable $Companies
 * @method Company[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class CompaniesController extends AppController
{
    public function getCompany()
    {
        $this->getRequest()->allowMethod('GET');
        $company = $this->Companies->get($this->Authentication->getUser()->company_id);
        $this->set(compact('company'));
    }

    public function updateSettings() {
        $token = $this->getRequest()->getData('token');
        $company = $this->Companies->get($this->Authentication->getUser()->company_id);
        $company = $this->Companies->patchEntity($company, $this->getRequest()->getData());
        if (!$this->Companies->save($company)) {
            throw new ValidationException($company);
        }
        $data = [
            'firstName' => $company->billing_first_name,
            'lastName' => $company->billing_last_name,
            'email' => $company->email,
            'billingAddress' => [
                'firstName' => $company->billing_first_name,
                'lastName' => $company->billing_last_name,
                'line1' => $company->address,
                'city' => $company->city,
                'state' => $company->state,
                'zip' => $company->zip,
                'country' => $company->country,
            ],
        ];
        if ($token) {
            $data['card'] = [
                'gateway' => 'stripe',
                'tmpToken' => $token,
            ];
        }


        ChargeBee_Customer::updateBillingInfo($company->chargebee_customer_id, $data);
        $this->set(compact('company'));
    }
}
