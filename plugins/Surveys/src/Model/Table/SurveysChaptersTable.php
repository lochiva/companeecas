<?php
namespace Surveys\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * SurveysChapters Model
 *
 * @method \Surveys\Model\Entity\SurveysChapter get($primaryKey, $options = [])
 * @method \Surveys\Model\Entity\SurveysChapter newEntity($data = null, array $options = [])
 * @method \Surveys\Model\Entity\SurveysChapter[] newEntities(array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysChapter|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysChapter|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysChapter patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysChapter[] patchEntities($entities, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysChapter findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurveysChaptersTable extends AppTable
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

        $this->setTable('surveys_chapters');
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

        $validator
            ->integer('id_survey')
            ->requirePresence('id_survey', 'create')
            ->allowEmpty('id_survey', false);

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

    public function getChaptersBySurvey($surveyId, $decode = true)
    {
        $res = $this->find()
			->where(['id_survey' => $surveyId, 'deleted' => 0])
			->order(['chapter ASC'])
            ->toArray();
            
        if($decode){
            $chapters = [];

            foreach($res as $c){
                $chapters[] = json_decode($c['chapter_data']);
            }

            return $chapters;
        }

        return $res;
    }
}
