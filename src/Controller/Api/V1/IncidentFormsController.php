<?php

namespace App\Controller\Api\V1;

use App\Controller\AppController;
use App\Error\Exception\ValidationException;
use App\Model\Entity\IncidentForm;
use App\Model\Table\IncidentFormsTable;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Datasource\ResultSetInterface;
use Cake\Http\Response;

/**
 * IncidentForms Controller
 *
 * @property IncidentFormsTable $IncidentForms
 *
 * @method IncidentForm[]|ResultSetInterface paginate($object = null, array $settings = [])
 */
class IncidentFormsController extends AppController
{
    /**
     * View method
     * @param string|null $storeId The store id to get the incident form for
     * @return void
     * @throws RecordNotFoundException When record not found.
     */
    public function view(string $storeId = null)
    {
        $incidentForm = $this->IncidentForms->findOrCreate(['store_id' => $storeId], function (IncidentForm $incidentForm) {
            $incidentForm->name = 'Incident Form';
            $incidentForm->version = 1;
            $incidentFormVersion = $this->IncidentForms->IncidentFormVersions->newEntity([
                'version' => 1,
                'data' => [
                    'tabs' => [
                        [
                            'title' => 'Customer Information',
                            'mandatory' => true,
                            'controls' => [
                                'first_name',
                                'last_name',
                                'incident_datetime',
                                'report_datetime',
                                'customer_address',
                                'customer_city',
                                'customer_state',
                                'customer_zip',
                                'customer_email',
                            ]
                        ],
                        [
                            'title' => 'Vehicle Information',
                            'mandatory' => true,
                            'controls' => [
                                'vehicle_make',
                                'vehicle_model',
                                'vehicle_year',
                                'vehicle_color',
                                'vehicle_vin',
                                'vehicle_mileage',
                                'vehicle_plate',
                                'vehicle_state',
                                'affected_area_of_vehicle',
                                'customer_statement',
                                'prior_damage',
                                'prior_damage_description',
                                'employee_notes',
                            ]
                        ]
                    ],
                    'controls' => [
                        'first_name' => [
                            'uniqueId' => 'first_name',
                            'mandatory' => true,
                            'type' => 'input',
                            'label' => 'Fist Name',
                            'columns' => 12
                        ],
                        'last_name' => [
                            'uniqueId' => 'last_name',
                            'mandatory' => true,
                            'type' => 'input',
                            'label' => 'Last Name',
                            'columns' => 12
                        ],
                        'incident_datetime' => [
                            'uniqueId' => 'incident_datetime',
                            'mandatory' => true,
                            'type' => 'date',
                            'time' => true,
                            'label' => 'Incident Date/Time',
                            'columns' => 12
                        ],
                        'report_datetime' => [
                            'uniqueId' => 'report_datetime',
                            'mandatory' => true,
                            'type' => 'date',
                            'time' => true,
                            'label' => 'Report Date/Time',
                            'columns' => 12
                        ],
                        'customer_address' => [
                            'uniqueId' => 'customer_address',
                            'mandatory' => true,
                            'type' => 'input',
                            'label' => 'Customer Address',
                            'columns' => 7
                        ],
                        'customer_city' => [
                            'uniqueId' => 'customer_city',
                            'mandatory' => true,
                            'type' => 'input',
                            'label' => 'Customer City',
                            'columns' => 5
                        ],
                        'customer_state' => [
                            'uniqueId' => 'customer_state',
                            'mandatory' => true,
                            'type' => 'input',
                            'label' => 'Customer State',
                            'columns' => 6
                        ],
                        'customer_zip' => [
                            'uniqueId' => 'customer_zip',
                            'mandatory' => true,
                            'type' => 'number',
                            'label' => 'Customer Zip',
                            'columns' => 6
                        ],
                        'customer_email' => [
                            'uniqueId' => 'customer_email',
                            'mandatory' => true,
                            'type' => 'input',
                            'label' => 'Email',
                            'columns' => 12
                        ],
                        'vehicle_make' => [
                            'uniqueId' => 'vehicle_make',
                            'mandatory' => true,
                            'type' => 'input',
                            'label' => 'Vehicle Make',
                            'columns' => 12
                        ],
                        'vehicle_model' => [
                            'uniqueId' => 'vehicle_model',
                            'mandatory' => true,
                            'type' => 'input',
                            'label' => 'Vehicle Model',
                            'columns' => 12
                        ],
                        'vehicle_year' => [
                            'uniqueId' => 'vehicle_year',
                            'mandatory' => true,
                            'type' => 'number',
                            'label' => 'Vehicle Year',
                            'columns' => 12
                        ],
                        'vehicle_color' => [
                            'uniqueId' => 'vehicle_color',
                            'mandatory' => true,
                            'type' => 'input',
                            'label' => 'Vehicle Color',
                            'columns' => 12
                        ],
                        'vehicle_vin' => [
                            'uniqueId' => 'vehicle_vin',
                            'mandatory' => true,
                            'type' => 'input',
                            'label' => 'Vehicle VIN',
                            'columns' => 12
                        ],
                        'vehicle_mileage' => [
                            'uniqueId' => 'vehicle_mileage',
                            'mandatory' => true,
                            'type' => 'number',
                            'label' => 'Vehicle Mileage',
                            'columns' => 12
                        ],
                        'vehicle_plate' => [
                            'uniqueId' => 'vehicle_plate',
                            'mandatory' => true,
                            'type' => 'input',
                            'label' => 'Vehicle Plate Number',
                            'columns' => 6
                        ],
                        'vehicle_state' => [
                            'uniqueId' => 'vehicle_state',
                            'mandatory' => true,
                            'type' => 'input',
                            'label' => 'Vehicle State',
                            'columns' => 6
                        ],
                        'affected_area_of_vehicle' => [
                            'uniqueId' => 'affected_area_of_vehicle',
                            'mandatory' => true,
                            'type' => 'input',
                            'label' => 'Affected Area of Vehicle',
                            'columns' => 12
                        ],
                        'customer_statement' => [
                            'uniqueId' => 'customer_statement',
                            'type' => 'input',
                            'label' => 'Customer Statement',
                            'columns' => 12
                        ],
                        'prior_damage' => [
                            'uniqueId' => 'prior_damage',
                            'mandatory' => true,
                            'type' => 'checkbox',
                            'label' => 'Prior Damage',
                            'columns' => 12
                        ],
                        'prior_damage_description' => [
                            'uniqueId' => 'prior_damage_description',
                            'type' => 'text',
                            'label' => 'Prior Damage Description',
                            'columns' => 12
                        ],
                        'employee_notes' => [
                            'uniqueId' => 'employee_notes',
                            'type' => 'text',
                            'label' => 'Employee Notes',
                            'columns' => 12
                        ],

                    ]
                ]
            ]);
            $incidentForm->incident_form_versions = [$incidentFormVersion];
            return $incidentForm;
        });

        $this->IncidentForms->loadInto($incidentForm, ['CurrentVersions']);

        $this->set(compact('incidentForm'));
    }

    /**
     * Add method
     * @return void
     */
    public function update(string $storeId = null)
    {
        /** @var IncidentForm $incidentForm */
        $incidentForm = $this->IncidentForms->findByStoreId($storeId)->firstOrFail();
        $incidentForm->version++;
        $incidentFormVersion = $this->IncidentForms->IncidentFormVersions->newEntity([
            'incident_form_id' => $incidentForm->id,
            'version' => $incidentForm->version,
            'data' => $this->getRequest()->getData('form')
        ]);

        $this->IncidentForms->getConnection()->begin();

        if (!$this->IncidentForms->save($incidentForm)) {
            $this->IncidentForms->getConnection()->rollback();
            throw new ValidationException($incidentFormVersion);
        }

        if (!$this->IncidentForms->IncidentFormVersions->save($incidentFormVersion)) {
            $this->IncidentForms->getConnection()->rollback();
            throw new ValidationException($incidentFormVersion);
        }

        $this->IncidentForms->getConnection()->commit();

        $this->getRequest()->allowMethod(['post']);
        $incidentForm = $this->IncidentForms->findByStoreId($storeId)->contain(['CurrentVersions'])->firstOrFail();
        $this->set(compact('incidentForm'));
    }

    /**
     * Delete method
     * @param string|null $id Incident Form id.
     * @return void
     * @throws RecordNotFoundException When record not found.
     */
    public function revert(string $id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $incidentForm = $this->IncidentForms->get($id);
        if (!$this->IncidentForms->delete($incidentForm)) {
            throw new ValidationException($incidentForm);
        }
        $this->set([
            'success' => true,
            '_serialize' => ['success']
        ]);
    }
}
