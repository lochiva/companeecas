<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * PoliceStationTypes Model
 *
 * @property \Aziende\Model\Table\PoliceStationsTable&\Cake\ORM\Association\HasMany $PoliceStations
 *
 * @method \Aziende\Model\Entity\PoliceStationType get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\PoliceStationType newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\PoliceStationType[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\PoliceStationType|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\PoliceStationType saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\PoliceStationType patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\PoliceStationType[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\PoliceStationType findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PoliceStationTypesTable extends Table
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

        $this->setTable('police_station_types');
        $this->setDisplayField('type');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('PoliceStations', [
            'foreignKey' => 'police_station_type_id',
            'className' => 'Aziende.PoliceStations'
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
            ->scalar('type')
            ->maxLength('type', 255)
            ->requirePresence('type', 'create')
            ->notEmptyString('type');

        $validator
            ->scalar('label_in_letter')
            ->maxLength('label_in_letter', 255)
            ->requirePresence('label_in_letter', 'create')
            ->notEmptyString('label_in_letter');

        return $validator;
    }
}
