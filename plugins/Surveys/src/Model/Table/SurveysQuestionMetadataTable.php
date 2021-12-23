<?php
namespace Surveys\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;

/**
 * SurveysQuestionMetadata Model
 *
 * @method \Surveys\Model\Entity\SurveysQuestionMetadata get($primaryKey, $options = [])
 * @method \Surveys\Model\Entity\SurveysQuestionMetadata newEntity($data = null, array $options = [])
 * @method \Surveys\Model\Entity\SurveysQuestionMetadata[] newEntities(array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysQuestionMetadata|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysQuestionMetadata|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysQuestionMetadata patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysQuestionMetadata[] patchEntities($entities, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysQuestionMetadata findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurveysQuestionMetadataTable extends AppTable
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

        $this->setTable('surveys_question_metadata');
        $this->setPrimaryKey('id');
        $this->setEntityClass('Surveys.SurveysQuestionMetadata');

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
            ->integer('survey_id')
            ->requirePresence('survey_id', 'create')
            ->allowEmpty('survey_id', false);

        $validator
            ->integer('question_id')
            ->requirePresence('question_id', 'create')
            ->allowEmpty('question_id', false);

        $validator
            ->boolean('show_in_table')
            ->allowEmpty('show_in_table', true);

        $validator
            ->boolean('show_in_export')
            ->allowEmpty('show_in_export', true);

        $validator
            ->scalar('label_table')
            ->maxLength('label_table', 64)
            ->allowEmpty('label_table', true);

        $validator
            ->scalar('label_export')
            ->maxLength('label_export', 64)
            ->allowEmpty('label_export', true);

        return $validator;
    }

    public function getTableQuestions() {
        $survey = TableRegistry::get('Surveys.Surveys')->find()->first();

        return $this->find()
			->where(['survey_id' => $survey['id'], 'show_in_table' => 1])
			->toArray();
    }

    public function getExportQuestions() {
        $survey = TableRegistry::get('Surveys.Surveys')->find()->first();
        
        return $this->find()
			->where(['survey_id' => $survey['id'], 'show_in_export' => 1])
			->toArray();
    }
}
