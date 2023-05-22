<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Surveys Chapters Contents  (https://www.companee.it)
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
 * SurveysChaptersContents Model
 *
 * @method \Surveys\Model\Entity\SurveysChaptersContent get($primaryKey, $options = [])
 * @method \Surveys\Model\Entity\SurveysChaptersContent newEntity($data = null, array $options = [])
 * @method \Surveys\Model\Entity\SurveysChaptersContent[] newEntities(array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysChaptersContent|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysChaptersContent saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Surveys\Model\Entity\SurveysChaptersContent patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysChaptersContent[] patchEntities($entities, array $data, array $options = [])
 * @method \Surveys\Model\Entity\SurveysChaptersContent findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SurveysChaptersContentsTable extends AppTable
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

        $this->setTable('surveys_chapters_contents');
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
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->scalar('content')
            ->requirePresence('content', 'create')
            ->notEmptyString('content');

        return $validator;
    }
}