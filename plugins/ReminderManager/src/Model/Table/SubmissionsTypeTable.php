<?php
/**
* Reminder Manager is a plugin for manage attachment
*
* Companee :    Submissions Type  (https://www.companee.it)
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
namespace ReminderManager\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;

class SubmissionsTypeTable extends Table
{

    public function initialize(array $config)
    {
      $this->setTable('submissions_type');
      $this->setPrimaryKey('id');
      $this->addBehavior('Timestamp');
    }


    public function getTypeByAttributes($attributes){

      $res = $this->find('all')->join([
            'TA' => [
                'table' => 'submissions_type_submissions_attributes',
                'type' => 'INNER',
                'conditions' => 'SubmissionsType.id = TA.id_submission_type'
            ],
            'SubmissionsAttribute' => [
                'table' => 'submissions_attributes',
                'type' => 'INNER',
                'conditions' => 'SubmissionsAttribute.id = TA.id_submission_attribute'
            ]
        ])->where([
          'SubmissionsAttribute.attribute' => $attributes
        ])->order('SubmissionsType.name ASC')->toArray();

        //debug($res);

        return $res;

    }

    public function setNewType($typeData){

      //debug($typeData);
      $type = $this->newEntity();

      $type->name = $typeData['name'];

      if($this->save($type)){
        return $type->id;
      }else{
        return false;
      }

    }

}
