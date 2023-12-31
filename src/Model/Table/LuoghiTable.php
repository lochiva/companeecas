<?php
/** 
* Companee :    Luoghi (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
namespace App\Model\Table;


use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\Core\Configure;


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

    public function getEnabledProvinces()
    {
        $where = ['in_luo' => '3', 'enabled' => 1];
        
        $enabledProvinces = array_filter(array_map('trim', explode(',', Configure::read('dbconfig.generico.ENABLED_PROVINCES'))));
        if (!empty($enabledProvinces)) {
            $where['s_prv IN'] = $enabledProvinces;
        }
     
        $res = $this->find()
            ->select(['id' => 'c_luo','text' => 'des_luo'])
            ->where($where)
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
