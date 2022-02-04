<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * SediSeditToTipologieOspiti Model
 *
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class SediSediToTipologieOspitiTable extends Table
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

        $this->setTable('sedi_sedi_to_tipologie_ospiti');
        $this->setDisplayField('id');
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
            ->integer('sede_id')
            ->requirePresence('sede_id', 'create')
            ->notEmpty('sede_id');

        $validator
            ->integer('tipologia_ospite_id')
            ->requirePresence('tipologia_ospite_id', 'create')
            ->notEmpty('tipologia_ospite_id');

        return $validator;
    }
}
