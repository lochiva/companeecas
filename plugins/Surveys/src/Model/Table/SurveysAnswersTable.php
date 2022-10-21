<?php
namespace Surveys\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * SurveysAnswers Model
 *
 * @method \Surveys\Model\Entity\SurveysAnswer get($primaryKey, $options = [])
 * @method \Surveys\Model\Entity\SurveysAnswer newEntity($data = null, array $options = [])
 * @method \Surveys\Model\Entity\SurveysAnswer[] newEntities(array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysAnswer|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysAnswer|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysAnswer patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysAnswer[] patchEntities($entities, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysAnswer findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurveysAnswersTable extends AppTable
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

        $this->setTable('surveys_answers');
        $this->setDisplayField('id');
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
            ->allowEmpty('id', 'create');

/*         $validator
            ->integer('id_interview')
            ->requirePresence('id_interview', 'create')
            ->allowEmpty('id_interview', false); */

        $validator
            ->integer('chapter')
            ->requirePresence('chapter', 'create')
            ->allowEmpty('chapter', false);

        $validator
            ->scalar('chapter_data')
            ->requirePresence('chapter_data', 'create')
            ->allowEmpty('chapter_data', false);

        return $validator;
    }

    public function getAnswersByInterview($interviewId, $decode = true)
    {
        $res = $this->find()
			->where(['id_interview' => $interviewId, 'deleted' => 0])
			->order(['chapter ASC'])
            ->toArray();
            
        if($decode){
            $answers = [];

            foreach($res as $a){
                $answers[] = json_decode($a['chapter_data']);
            }

            return $answers;
        }

        return $res;
    }

}
