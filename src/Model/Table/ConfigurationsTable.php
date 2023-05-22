<?php
/** 
* Companee :    Configurations (https://www.companee.it)
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


use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;

class ConfigurationsTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('key_conf', 'La Chiave è obbligatorio')
            ->notEmpty('label', 'La Label è obbligatoria')
            ->notEmpty('tooltip', 'Il Tooltip è obbligatorio')
            ->allowEmpty('value', 'Il valore è obbligatorio');
    }

    public function buildRules(RulesChecker $rules)
    {
        // Add a rule that is applied for create and update operations
        $rules->add($rules->isUnique(['key_conf']));

        return $rules;
    }

    public function getConfigTypes($level = 0)
    {
      $toRet = array();
      $configs = $this->find('all')->select('plugin')->distinct('plugin')
          ->where(['level <=' => $level])->toArray();
      foreach($configs as $config){
        $toRet[] = $config->plugin;
      }
      return $toRet;
    }

    public function getConfigPerType($types,$level = 0)
    {
      $toRet = array();
      foreach($types as $type){

        $toRet[$type] = $this->find('all')->where(['level <=' => $level,'plugin'=>$type])->toArray();
      }

      return $toRet;
    }

}
