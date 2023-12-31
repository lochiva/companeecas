<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Guests Notifications Types  (https://www.companee.it)
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
use App\Model\Table\AppTable;

/**
 * GuestsNotificationsTypes Model
 *
 * @method \Aziende\Model\Entity\GuestsNotificationsType get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\GuestsNotificationsType newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\GuestsNotificationsType[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsNotificationsType|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsNotificationsType saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsNotificationsType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsNotificationsType[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsNotificationsType findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GuestsNotificationsTypesTable extends AppTable
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

        $this->setTable('guests_notifications_types');
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
            ->scalar('msg_singular')
            ->requirePresence('msg_singular', 'create')
            ->notEmptyString('msg_singular');

        $validator
            ->scalar('msg_plural')
            ->requirePresence('msg_plural', 'create')
            ->notEmptyString('msg_plural');

        $validator
            ->integer('ente_type')
            ->allowEmptyString('ente_type');

        return $validator;
    }
}
