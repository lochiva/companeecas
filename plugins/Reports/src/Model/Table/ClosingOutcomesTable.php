<?php
namespace Reports\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * ClosingOutcomes Model
 *
 * @method \Reports\Model\Entity\ClosingOutcome get($primaryKey, $options = [])
 * @method \Reports\Model\Entity\ClosingOutcome newEntity($data = null, array $options = [])
 * @method \Reports\Model\Entity\ClosingOutcome[] newEntities(array $data, array $options = [])
 * @method \Reports\Model\Entity\ClosingOutcome|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Reports\Model\Entity\ClosingOutcome|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Reports\Model\Entity\ClosingOutcome patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Reports\Model\Entity\ClosingOutcome[] patchEntities($entities, array $data, array $options = [])
 * @method \Reports\Model\Entity\ClosingOutcome findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ClosingOutcomesTable extends AppTable
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

        $this->setTable('reports_closing_outcomes');
        $this->setPrimaryKey('id');
        $this->setEntityClass('Reports.ClosingOutcome');

        $this->addBehavior('Timestamp');
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
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 32)
            ->requirePresence('name')
            ->allowEmptyString('name', false);

        $validator
            ->integer('ordering')
            ->allowEmpty('ordering', true);

        return $validator;
    }

}
