<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Police Stations  (https://www.companee.it)
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
 * PoliceStations Model
 *
 * @property \Aziende\Model\Table\PoliceStationTypesTable&\Cake\ORM\Association\BelongsTo $PoliceStationTypes
 *
 * @method \Aziende\Model\Entity\PoliceStation get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\PoliceStation newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\PoliceStation[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\PoliceStation|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\PoliceStation saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\PoliceStation patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\PoliceStation[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\PoliceStation findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PoliceStationsTable extends AppTable
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

        $this->setTable('police_stations');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('PoliceStationTypes', [
            'foreignKey' => 'police_station_type_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.PoliceStationTypes',
            'propertyName' => 'type'
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
            ->scalar('description')
            ->requirePresence('description', 'create')
            ->notEmptyString('description');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['police_station_type_id'], 'PoliceStationTypes'));

        return $rules;
    }

    public function getAutocompletePoliceStations($search, $comune = '')
    {

        if ($comune == 401001272) {
            $type = 'commissariatoPS';
        } else {
            $type = 'stazioneCC';
        }

        $res = $this->find('all')
            ->contain(['PoliceStationTypes'])
            ->where(['PoliceStationTypes.type LIKE' => $type])
            ->where(['PoliceStations.name LIKE' =>'%'.$search.'%'])
            ->order(['PoliceStations.ordering' => 'ASC'])
            ->toArray();
    
        return $res;
    }
}
