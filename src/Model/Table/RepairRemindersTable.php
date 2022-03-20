<?php
namespace App\Model\Table;

use ArrayObject;
use Cake\Event\Event;
use Cake\I18n\Time;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * RepairReminders Model
 *
 * @property \App\Model\Table\RepairsTable|\Cake\ORM\Association\BelongsTo $Repairs
 * @property \App\Model\Table\UsersTable|\Cake\ORM\Association\BelongsTo $Users
 * @method \App\Model\Entity\RepairReminder get($primaryKey, $options = [])
 * @method \App\Model\Entity\RepairReminder newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\RepairReminder[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\RepairReminder|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RepairReminder saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\RepairReminder patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\RepairReminder[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\RepairReminder findOrCreate($search, callable $callback = null, $options = [])
 */
class RepairRemindersTable extends Table
{
    /**
     * Initialize method
     *
     * @param  array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('repair_reminders');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo(
            'Repairs',
            [
            'foreignKey' => 'repair_id',
            'joinType' => 'INNER',
            ]
        );
        $this->belongsTo(
            'Users',
            [
            'foreignKey' => 'myuser_id',
            'joinType' => 'INNER',
            ]
        );
    }

    /**
     * Default validation rules.
     *
     * @param  \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->dateTime('reminder')
            ->requirePresence('reminder', 'create')
            ->allowEmptyDateTime('reminder', false);

        $validator
            ->boolean('sent')
            ->allowEmptyString('sent');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param  \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules): RulesChecker
    {
        $rules->add($rules->existsIn(['repair_id'], 'Repairs'));
        $rules->add($rules->existsIn(['myuser_id'], 'Users'));

        return $rules;
    }

    public function beforeMarshal(Event $event, ArrayObject $data, ArrayObject $options)
    {
        if (isset($data['reminder'])) {
            $data['reminder'] = new Time($data['reminder']);
        }
    }
}
