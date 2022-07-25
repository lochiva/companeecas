<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Periods Model
 *
 * @method \aziende\Model\Entity\Period get($primaryKey, $options = [])
 * @method \aziende\Model\Entity\Period newEntity($data = null, array $options = [])
 * @method \aziende\Model\Entity\Period[] newEntities(array $data, array $options = [])
 * @method \aziende\Model\Entity\Period|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \aziende\Model\Entity\Period saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \aziende\Model\Entity\Period patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \aziende\Model\Entity\Period[] patchEntities($entities, array $data, array $options = [])
 * @method \aziende\Model\Entity\Period findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PeriodsTable extends Table
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

        $this->setTable('periods');
        $this->setDisplayField('label');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('Statements', [
            'className' => 'Statements',
            'propertyName' => 'statements',
            'bindingKey' => 'id',
            'foreignKey' => 'period_id',
            'dependent' => true
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
            ->scalar('label')
            ->maxLength('label', 255)
            ->requirePresence('label', 'create')
            ->notEmptyString('label');

        $validator
            ->date('start_date')
            ->requirePresence('start_date', 'create')
            ->notEmptyString('start_date');

        $validator
            ->date('end_date')
            ->requirePresence('end_date', 'create')
            ->notEmptyString('end_date');

        $validator
            ->boolean('visible')
            ->allowEmptyString('id', null, 'create');

        return $validator;
    }
}
