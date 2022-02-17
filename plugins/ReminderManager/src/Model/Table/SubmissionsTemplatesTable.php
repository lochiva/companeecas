<?php
namespace ReminderManager\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;

class SubmissionsTemplatesTable extends Table
{

    public function initialize(array $config)
    {
      $this->setTable('submissions_templates');
      $this->setPrimaryKey('id');
      $this->addBehavior('Timestamp');
    }


}
