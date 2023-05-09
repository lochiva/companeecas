<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Surveys Interviews   (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
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

        $this->belongsTo('Surveys')
            ->setForeignKey('id_survey')
            ->setProperty('surveys');

        $this->belongsTo('SurveysInterviewsStatuses')
            ->setForeignKey('status')
            ->setProperty('status_details');

        $this->hasMany('SurveysAnswers', ['className' => 'Surveys.SurveysAnswers'])
            ->setForeignKey('id_interview')
            ->setConditions(['SurveysAnswers.deleted' => false])
            ->setSort(['SurveysAnswers.chapter' => 'ASC'])
            ->setProperty('answers')
            ->setDependent(true);

        $this->hasOne('SurveysInterviewsGuests', ['className' => 'Surveys.SurveysInterviewsGuests'])
            ->setProperty('guest')
            ->setForeignKey('interview_id')
            ->setDependent(true);
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
            ->allowEmpty('version', true); 

        return $validator;
    }
}