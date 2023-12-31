<?php
/** 
* Companee :    Cap (https://www.companee.it)
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

class CapTable extends Table
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

        $this->setTable('cap');
        $this->setDisplayField('cap');
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
            ->requirePresence('cap', 'create')
            ->notEmpty('cap');

        $validator
            ->requirePresence('localita', 'create')
            ->notEmpty('localita');

        $validator
            ->boolean('disagiato')
            ->requirePresence('disagiato', 'create')
            ->notEmpty('disagiato');

        return $validator;
    }

    public function getList($luogo = '', $all = false)
    {
      $query = $this->find()->select(['id' => 'cap','text'=>'cap'])->typeMap(['id' => 'string'])
        ->where(['localita LIKE' => $luogo]);
      if(!$all){
          $query = $query->where(['enabled' => 1]);
      }
      return $query->toArray();
    }
}
