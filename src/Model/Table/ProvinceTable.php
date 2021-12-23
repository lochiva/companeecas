<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;


class ProvinceTable extends AppTable
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

        $this->setTable('province');
        $this->setDisplayField('s_prv');
        $this->setPrimaryKey('id');
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
            ->requirePresence('s_prv', 'create')
            ->notEmpty('s_prv');

        return $validator;
    }

    public function getList($all = false)
    {
      $query = $this->find()->select(['id' => 's_prv','text' => 's_prv'])->typeMap(['id' => 'string']);
      if(!$all){
          $query = $query->where(['enabled' => 1]);
      }
      return $query->toArray();
    }
}
