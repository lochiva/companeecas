<?php
namespace Surveys\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * SurveysInterviews Model
 *
 * @method \Surveys\Model\Entity\SurveysInterview get($primaryKey, $options = [])
 * @method \Surveys\Model\Entity\SurveysInterview newEntity($data = null, array $options = [])
 * @method \Surveys\Model\Entity\SurveysInterview[] newEntities(array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysInterview|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysInterview|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysInterview patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysInterview[] patchEntities($entities, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysInterview findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurveysInterviewsTable extends AppTable
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

        $this->setTable('surveys_interviews');
        $this->setDisplayField('title');
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
            ->integer('id_azienda')
            ->allowEmpty('id_azienda', true);

        $validator
            ->integer('id_sede')
            ->allowEmpty('id_sede', true);

        $validator
            ->integer('id_user')
            ->requirePresence('id_user', 'create')
            ->allowEmpty('id_user', false);

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->allowEmpty('title', true);

        $validator
            ->scalar('subtitle')
            ->maxLength('subtitle', 255)
            ->allowEmpty('subtitle', true);

        $validator
            ->scalar('description')
            ->allowEmpty('description', true);

        $validator
            ->integer('status');  

        $validator
            ->boolean('not_valid'); 
        
        $validator
            ->date('signature_date')
            ->allowEmpty('signature_date', true); 

        $validator
            ->integer('cloned_by')
            ->allowEmpty('cloned_by', true); 

        $validator
            ->scalar('version')
            ->maxLength('version', 64)
            ->allowEmpty('version', false); 

        return $validator;
    }
}
