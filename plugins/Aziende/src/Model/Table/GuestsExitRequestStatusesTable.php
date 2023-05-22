<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Guests Exit Request Statuses (https://www.companee.it)
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
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GuestsExitRequestStatuses Model
 *
 * @method \Aziende\Model\Entity\GuestsExitRequestStatus get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\GuestsExitRequestStatus newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\GuestsExitRequestStatus[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsExitRequestStatus|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsExitRequestStatus saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsExitRequestStatus patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsExitRequestStatus[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsExitRequestStatus findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GuestsExitRequestStatusesTable extends Table
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

        $this->setTable('guests_exit_request_statuses');
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
            ->scalar('color')
            ->maxLength('color', 7)
            ->requirePresence('color', 'create')
            ->notEmptyString('color');

        return $validator;
    }
}
