<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class LuoghiTable extends Table
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

        $this->setTable('luoghi');
        $this->setPrimaryKey('c_luo');
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
            ->requirePresence('c_luo', 'create')
            ->notEmpty('c_luo');

        $validator
            ->requirePresence('in_luo', 'create')
            ->notEmpty('in_luo');

        $validator
            ->requirePresence('in_luo_orig', 'create')
            ->notEmpty('in_luo_orig');

        $validator
            ->requirePresence('des_luo', 'create')
            ->notEmpty('des_luo');

        $validator
            ->requirePresence('c_rgn', 'create')
            ->notEmpty('c_rgn');

        $validator
            ->requirePresence('c_prv', 'create')
            ->notEmpty('c_prv');

        $validator
            ->requirePresence('c_cat', 'create')
            ->notEmpty('c_cat');

        $validator
            ->requirePresence('s_prv', 'create')
            ->notEmpty('s_prv');

        return $validator;
    }

    public function getAutocomplete($nome, $s_prv = '')
    {
      $query = $this->find('all')->select(['id' => 'c_luo','text' => 'des_luo'])
                ->where(['enabled' => 1,'des_luo LIKE' =>'%'.$nome.'%', 'c_cat !=' => '' ]);
      if(!empty($s_prv)){
          $query =  $query->where(['s_prv' => $s_prv]);
      }
      return $query->toArray();
    }

    public function getList($provincia='', $all = false)
    {
      $query = $this->find('all')->select(['id' => 'des_luo','text' => 'des_luo'])->typeMap(['id' => 'string'])
                ->where(['s_prv' => $provincia, 'c_cat !=' => '' ]);
                
      if(!$all){
          $query = $query->where(['enabled' => 1]);
      }
      return $query->toArray();
    }

    public function getAutocompleteProvincia($search)
    {
        $res = $this->find()
            ->select(['id' => 'c_luo','text' => 'des_luo'])
            ->where(['in_luo' => '3', 'des_luo LIKE' =>'%'.$search.'%', 'enabled' => 1])
            ->toArray();

        return $res;
    }

    public function getProvince()
    {
        $res = $this->find()
            ->select(['id' => 'c_luo','text' => 'des_luo'])
            ->where(['in_luo' => '3', 'enabled' => 1])
            ->toArray();

        return $res;
    }

    public function getAutocompleteComune($search, $prv = '')
    {
        $res = $this->find()
            ->select(['id' => 'Luoghi.c_luo','text' => 'Luoghi.des_luo'])
            ->where(['Luoghi.in_luo' => '4', 'Luoghi.des_luo LIKE' =>'%'.$search.'%', 'Luoghi.c_cat !=' => '', 'Luoghi.c_prv = l.c_prv', 'Luoghi.enabled' => 1])
            ->join([
                [
                    'table' => 'luoghi',
                    'alias' => 'l',
                    'type' => 'LEFT',
                    'conditions' => ['l.c_luo' => $prv, 'l.enabled' => 1]
                ]
            ])
            ->toArray();
    
        return $res;
    }
}
