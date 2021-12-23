<?php
namespace Reports\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * Histories Model
 *
 * @method \Reports\Model\Entity\History get($primaryKey, $options = [])
 * @method \Reports\Model\Entity\History newEntity($data = null, array $options = [])
 * @method \Reports\Model\Entity\History[] newEntities(array $data, array $options = [])
 * @method \Reports\Model\Entity\History|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Reports\Model\Entity\History|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Reports\Model\Entity\History patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Reports\Model\Entity\History[] patchEntities($entities, array $data, array $options = [])
 * @method \Reports\Model\Entity\History findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class HistoriesTable extends AppTable
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

        $this->setTable('reports_history');
        $this->setPrimaryKey('id');
        $this->setEntityClass('Reports.History');

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
            ->integer('report_id')
            ->requirePresence('report_id')
            ->allowEmpty('report_id', false);

        $validator
            ->integer('node_id')
            ->allowEmpty('node_id', true);

        $validator
            ->date('date')
            ->requirePresence('date')
            ->allowEmptyDate('date', false);

        $validator
            ->scalar('event')
            ->maxLength('event', 32)
            ->requirePresence('event')
            ->allowEmptyString('event', false);

        $validator
            ->scalar('motivation')
            ->allowEmptyString('motivation', true);

        $validator
            ->integer('outcome_id')
            ->allowEmpty('outcome_id', true);

        $validator
            ->scalar('message')
            ->requirePresence('message')
            ->allowEmptyString('message', false);  

        return $validator;
    }

}
