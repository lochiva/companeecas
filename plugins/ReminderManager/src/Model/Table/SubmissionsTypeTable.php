<?php
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
