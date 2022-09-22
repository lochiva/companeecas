<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * StatementsStatusHistory Model
 *
 * @property \Aziende\Model\Table\StatementCompanyTable&\Cake\ORM\Association\BelongsTo $StatementCompany
 * @property \Registration\Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Users
 * @property \Aziende\Model\Table\StatusesTable&\Cake\ORM\Association\BelongsTo $Statuses
 *
 * @method \Aziende\Model\Entity\StatementsStatusHistory get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\StatementsStatusHistory newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\StatementsStatusHistory[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\StatementsStatusHistory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\StatementsStatusHistory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\StatementsStatusHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\StatementsStatusHistory[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\StatementsStatusHistory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StatementsStatusHistoryTable extends Table
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

        $this->setTable('statements_status_history');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('StatementCompany', [
            'foreignKey' => 'statement_company_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.StatementCompany'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'user_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Users'
        ]);
        $this->belongsTo('Status', [
            'foreignKey' => 'status_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Status'
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
            ->scalar('note')
            ->allowEmptyString('note');

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
        $rules->add($rules->existsIn(['statement_company_id'], 'StatementCompany'));
        $rules->add($rules->existsIn(['user_id'], 'Users'));
        $rules->add($rules->existsIn(['status_id'], 'Status'));

        return $rules;
    }
}
