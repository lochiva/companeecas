<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Agreements Model
 *
 * @property \Aziende\Model\Table\ProceduresTable&\Cake\ORM\Association\BelongsTo $Procedures
 * @property \Aziende\Model\Table\AgreementsToSediTable&\Cake\ORM\Association\HasMany $AgreementsToSedi
 *
 * @method \Aziende\Model\Entity\Agreement get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\Agreement newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\Agreement[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\Agreement|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\Agreement saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\Agreement patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\Agreement[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\Agreement findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AgreementsTable extends Table
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

        $this->setTable('agreements');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Procedures', [
            'foreignKey' => 'procedure_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.SediProcedureAffidamento'
        ]);
        $this->hasMany('AgreementsToSedi', [
            'foreignKey' => 'agreement_id',
            'className' => 'Aziende.AgreementsToSedi'
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
            ->dateTime('date_agreement')
            ->requirePresence('date_agreement', 'create')
            ->notEmptyDateTime('date_agreement');

        $validator
            ->dateTime('date_agreement_expiration')
            ->requirePresence('date_agreement_expiration', 'create')
            ->notEmptyDateTime('date_agreement_expiration');

        $validator
            ->dateTime('date_extension_expiration')
            ->allowEmptyDateTime('date_extension_expiration');

        $validator
            ->decimal('guest_daily_price')
            ->notEmptyString('guest_daily_price');

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
        $rules->add($rules->existsIn(['procedure_id'], 'Procedures'));

        return $rules;
    }
}
