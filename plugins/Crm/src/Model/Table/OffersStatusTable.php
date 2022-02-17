<?php
namespace Crm\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

class OffersStatusTable extends Table
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

        $this->setTable('offers_status');
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->integer('ordering')
            ->requirePresence('ordering', 'create')
            ->notEmpty('ordering');

        $validator
            ->requirePresence('color', 'create')
            ->notEmpty('color');

        return $validator;
    }

    public function getAll(){

      return $this->find('all')->select(['id','name','color','ordering'])->order(['bar_ordering ASC'])->toArray();

    }

    public function getOrderingStatus($status){

      return $this->find('all')->select(['ordering'])->where(['OffersStatus.id' => $status])->toArray();

    }

}
