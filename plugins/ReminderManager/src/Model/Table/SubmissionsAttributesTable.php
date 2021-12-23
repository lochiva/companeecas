<?php
namespace ReminderManager\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;

class SubmissionsAttributesTable extends Table
{

    public function initialize(array $config)
    {
      $this->table('submissions_attributes');
      $this->primaryKey('id');
      $this->addBehavior('Timestamp');
    }


    public function getAttributeId($attribute = ""){

      if(!empty($attribute)){

        $res = $this->find()->select('id')->where(['attribute' => $attribute])->toArray();
        //debug($res);

        return isset($res[0]['id']) ? $res[0]['id'] : false ;

      }else{
        return false;
      }

    }


}
