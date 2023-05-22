<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Surveys Statuses  (https://www.companee.it)
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

/**
 * SurveysStatuses Model
 *
 * @method \Surveys\Model\Entity\SurveysStatus get($primaryKey, $options = [])
 * @method \Surveys\Model\Entity\SurveysStatus newEntity($data = null, array $options = [])
 * @method \Surveys\Model\Entity\SurveysStatus[] newEntities(array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysStatus|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysStatus saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysStatus findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurveysStatusesTable extends Table
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

        $this->setTable('surveys_statuses');
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
