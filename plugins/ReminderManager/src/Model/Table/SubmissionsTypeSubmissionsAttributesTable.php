<?php
namespace ReminderManager\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;

class SubmissionsTypeSubmissionsAttributesTable extends Table
{

    public function initialize(array $config)
    {
      $this->table('submissions_type_submissions_attributes');
      $this->primaryKey('id');
      $this->addBehavior('Timestamp');
    }

    


}
