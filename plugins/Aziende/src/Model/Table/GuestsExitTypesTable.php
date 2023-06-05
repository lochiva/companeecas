<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Guests Exit Types  (https://www.companee.it)
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
 * GuestsExitTypes Model
 *
 * @method \Aziende\Model\Entity\GuestsExitType get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\GuestsExitType newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\GuestsExitType[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsExitType|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsExitType saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsExitType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsExitType[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsExitType findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GuestsExitTypesTable extends AppTable
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

        $this->setTable('guests_exit_types');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Tipi', [
            'foreignKey' => 'ente_type',
            'className' => 'Aziende.AziendeTipi',
            'propertyName' => 'tipo'
        ]);
        $this->belongsTo('Decreti', [
            'foreignKey' => 'modello_decreto',
            'className' => 'Surveys.Surveys',
            'bindingKey' => 'id',
            'propertyName' => 'decreto'
        ]);
        $this->belongsTo('Notifiche', [
            'foreignKey' => 'modello_notifica',
            'className' => 'Surveys.Surveys',
            'bindingKey' => 'id',
            'propertyName' => 'notifica'
        ]);
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
            ->boolean('required_request');

        $validator
            ->boolean('required_request_file');

        $validator
            ->boolean('required_request_note');

        $validator
            ->boolean('required_confirmation');

        $validator
            ->boolean('required_file');

        $validator
            ->boolean('required_note');

        $validator
            ->boolean('startable_by_ente');

        $validator
            ->boolean('toSAI');

        $validator
            ->integer('ente_type');

        $validator
            ->integer('ordering');

        return $validator;
    }
}
