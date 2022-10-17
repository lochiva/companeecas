<?php
namespace Surveys\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SurveysInterviewsStatuses Model
 *
 * @method \Surveys\Model\Entity\SurveysInterviewsStatus get($primaryKey, $options = [])
 * @method \Surveys\Model\Entity\SurveysInterviewsStatus newEntity($data = null, array $options = [])
 * @method \Surveys\Model\Entity\SurveysInterviewsStatus[] newEntities(array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysInterviewsStatus|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysInterviewsStatus saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysInterviewsStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysInterviewsStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysInterviewsStatus findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurveysInterviewsStatusesTable extends Table
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

        $this->setTable('surveys_interviews_statuses');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 64)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('ordering')
            ->requirePresence('ordering', 'create')
            ->notEmptyString('ordering');

        return $validator;
    }
}
