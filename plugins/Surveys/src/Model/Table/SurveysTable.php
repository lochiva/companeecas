<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Surveys  (https://www.companee.it)
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
 * Surveys Model
 *
 * @method \Surveys\Model\Entity\Survey get($primaryKey, $options = [])
 * @method \Surveys\Model\Entity\Survey newEntity($data = null, array $options = [])
 * @method \Surveys\Model\Entity\Survey[] newEntities(array $data, array $options = [])
 * @method \Surveys\Model\Entity\Survey|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\Survey|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\Survey patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Surveys\Model\Entity\Survey[] patchEntities($entities, array $data, array $options = [])
 * @method \Surveys\Model\Entity\Survey findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurveysTable extends AppTable
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

        $this->setTable('surveys');
        $this->setDisplayField('title');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('SurveysChapters')
            ->setForeignKey('id_survey')
            ->setConditions(['SurveysChapters.deleted' => 0])
            ->setSort(['SurveysChapters.chapter' => 'ASC'])
            ->setProperty('chapters')
            ->setDependent(true);

        $this->hasMany('SurveyInterviews')
            ->setForeignKey('id_survey')
            ->setProperty('interviews')
            ->setDependent(true);

        $this->belongsTo('SurveysStatuses')
            ->setForeignKey('status')
            ->setProperty('status_name');

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
            ->integer('status')
            ->requirePresence('status', 'create')
            ->allowEmpty('status', false);

        $validator
            ->scalar('version')
            ->maxLength('version', 64)
            ->allowEmpty('version', false);

        return $validator;
    }


    public function verifySurveysStructure($partnerId, $managingEntityId, $structureId)
	{
        return $this->find()
            ->select(['Surveys.id', 'Surveys.title', 'sats.id'])
            ->where([
                'OR' => ['Surveys.status = 1', 'Surveys.status = 4'],
                'sats.id IS NOT NULL'
            ])
            ->join([
                [
                    'table' => 'surveys_to_aziende',
                    'alias' => 'sta',
                    'type' => 'LEFT',
                    'conditions' => ['sta.id_survey = Surveys.id', 'sta.id_azienda' => $partnerId]
                ],
                [
                    'table' => 'surveys_aziende_to_structures',
                    'alias' => 'sats',
                    'type' => 'LEFT',
                    'conditions' => ['sats.id_surveys_aziende = sta.id', 'sats.id_gestore' => $managingEntityId, 'sats.id_sede' => $structureId]
                ]
            ])
            ->order(['Surveys.title ASC'])
            ->toArray();
	}
}
