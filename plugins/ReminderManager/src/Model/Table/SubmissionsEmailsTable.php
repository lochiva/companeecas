<?php
namespace ReminderManager\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;

class SubmissionsEmailsTable extends Table
{

    public function initialize(array $config)
    {
      $this->setTable('submissions_emails');
      $this->setPrimaryKey('id');
      $this->addBehavior('Timestamp');

      $this->hasMany('ReminderManager.SubmissionsEmailsAttachements',[
        'foreignKey' => 'id_submission_email',
        'propertyName' => 'SubmissionsEmailsAttachements'
        ]);

      $this->hasMany('ReminderManager.SubmissionsEmailsCustoms',[
        'foreignKey' => 'id_submission_email',
        'propertyName' => 'SubmissionsEmailsCustoms'
        ]);

      $this->belongsTo('ReminderManager.Submissions',[
        'foreignKey' => 'id_submission',
        'propertyName' => 'Submissions'
        ]);



    }


}
