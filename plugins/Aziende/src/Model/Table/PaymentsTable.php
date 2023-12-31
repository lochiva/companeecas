<?php

namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Event\Event;
use ArrayObject;
use App\Model\Table\AppTable;


/**
 * Payments Model
 *
 * @property \Aziende\Model\Table\StatementCompaniesTable&\Cake\ORM\Association\BelongsTo $StatementCompanies
 * @property \Aziende\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 *
 * @method \Aziende\Model\Entity\Payment get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\Payment newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\Payment[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\Payment|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\Payment saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\Payment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\Payment[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\Payment findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PaymentsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('payments');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('StatementCompanies', [
            'foreignKey' => 'statement_company_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.StatementCompany'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Users'
        ]);
        $this->hasMany('Documents', [
            'foreignKey' => 'payment_id',
            'joinType' => 'LEFT',
            'className' => 'Aziende.SurveysInterviewsPayments'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->decimal('net_amount')
            ->requirePresence('net_amount', 'create',)
            ->notEmptyString('net_amount');
        
        $validator
            ->decimal('billing_net_amount')
            ->requirePresence('billing_net_amount', 'create',)
            ->notEmptyString('billing_net_amount');

        $validator
            ->scalar('oa_number_net')
            ->maxLength('oa_number_net', 16)
            ->requirePresence('oa_number_net', 'create')
            ->notEmptyString('oa_number_net');

        $validator
            ->scalar('os_number_net')
            ->maxLength('os_number_net', 16)
            ->requirePresence('os_number_net', 'create')
            ->notEmptyString('os_number_net');

        $validator
            ->date('os_date_net')
            ->requirePresence('os_date_net', 'create')
            ->notEmptyDate('os_date_net');

        $validator
            ->decimal('vat_amount')
            ->requirePresence('vat_amount', 'create',)
            ->notEmptyString('vat_amount');

        $validator
            ->decimal('billing_vat_amount')
            ->requirePresence('billing_vat_amount', 'create',)
            ->notEmptyString('billing_vat_amount');

        $validator
            ->scalar('oa_number_vat')
            ->maxLength('oa_number_vat', 16)
            ->requirePresence('oa_number_vat', 'create')
            ->notEmptyString('oa_number_vat');

        $validator
            ->scalar('os_number_vat')
            ->maxLength('os_number_vat', 16)
            ->requirePresence('os_number_vat', 'create')
            ->notEmptyString('os_number_vat');

        $validator
            ->date('os_date_vat')
            ->requirePresence('os_date_vat', 'create')
            ->notEmptyDate('os_date_vat');

        $validator
            ->scalar('billing_reference')
            ->maxLength('billing_reference', 16)
            ->requirePresence('billing_reference', 'create')
            ->notEmptyString('billing_reference');

        $validator
            ->date('billing_date')
            ->requirePresence('billing_date', 'create')
            ->notEmptyDate('billing_date');

        $validator
            ->scalar('protocol')
            ->maxLength('protocol', 16)
            ->requirePresence('protocol', 'create')
            ->allowEmptyString('protocol');

        $validator
            ->scalar('cig')
            ->maxLength('cig', 10)
            ->requirePresence('cig', 'create')
            ->notEmptyString('cig');

        $validator
            ->scalar('notes')
            ->requirePresence('notes', 'create')
            ->notEmptyString('notes');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['statement_company_id'], 'StatementCompanies'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->isUnique(['oa_number_net', 'os_number_net']));
        $rules->add($rules->isUnique(['oa_number_vat', 'os_number_vat']));

        return $rules;
    }

    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary) {
        $query = $query->where([$this->getAlias().'.deleted IS' => null]);
        return $query;
    }
}
