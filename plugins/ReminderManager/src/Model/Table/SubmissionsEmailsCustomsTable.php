<?php
namespace ReminderManager\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;

class SubmissionsEmailsCustomsTable extends Table
{

    public function initialize(array $config)
    {
      $this->table('submissions_emails_customs');
      $this->primaryKey('id');
      $this->addBehavior('Timestamp');

      $this->belongsTo('ReminderManager.SubmissionsEmails',[
        'foreignKey' => 'id_submission_email',
        'propertyName' => 'SubmissionsEmails'
        ]);

    }


    public function getSubmissions($cKey,$cValue){

      /*
      ->select([
        'name' => 'Submissions.name',
        'submissionStatus' => 'Submissions.status',
        'submissionEmailSended' => 'SubmissionsEmails.sended',
        'submissionEmailMail' =>'SubmissionsEmails.email',
        'submissionId' => 'Submissions.id',
        'submissionEmailId' => 'SubmissionsEmails.id',
        'submissionEmailAttachmentId' => 'SubmissionsEmailsAttachements.id'
      ])


      */
      $subs = [];

      $res = $this->find('all')
        ->contain(['SubmissionsEmails' => ['Submissions' => ['SubmissionsAttachements'],'SubmissionsEmailsAttachements']])
        ->where(['SubmissionsEmailsCustoms.custom_key' => $cKey, 'SubmissionsEmailsCustoms.custom_value' => $cValue])
        ->order('Submissions.created DESC')
        ->toArray();

      //debug($res);

      return $res;

    }

}
