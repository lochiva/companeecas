<?php
namespace Surveys\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * SurveysAnswerData Model
 *
 * @method \Surveys\Model\Entity\SurveysAnswerData get($primaryKey, $options = [])
 * @method \Surveys\Model\Entity\SurveysAnswerData newEntity($data = null, array $options = [])
 * @method \Surveys\Model\Entity\SurveysAnswerData[] newEntities(array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysAnswerData|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysAnswerData|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysAnswerData patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysAnswerData[] patchEntities($entities, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysAnswerData findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurveysAnswerDataTable extends AppTable
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

        $this->setTable('surveys_answer_data');
        $this->setPrimaryKey('id');
        $this->setEntityClass('Surveys.SurveysAnswerData');

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
            ->integer('interview_id')
            ->requirePresence('interview_id', 'create')
            ->allowEmpty('interview_id', false);

        $validator
            ->integer('question_id')
            ->requirePresence('question_id', 'create')
            ->allowEmpty('question_id', false);

        $validator
            ->scalar('value')
            ->allowEmpty('value', true);

        $validator
            ->scalar('options')
            ->allowEmpty('options', true);

        $validator
            ->scalar('type')
            ->maxLength('type', 64)
            ->allowEmpty('type', false);

        $validator
            ->scalar('final_value')
            ->allowEmpty('final_value', true);
            
        return $validator;
    }
}
