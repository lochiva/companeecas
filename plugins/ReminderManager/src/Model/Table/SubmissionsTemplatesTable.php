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
      $this->table('submissions_templates');
      $this->primaryKey('id');
      $this->addBehavior('Timestamp');
    }


}
