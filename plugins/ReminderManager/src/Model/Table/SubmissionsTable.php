<?php
namespace ReminderManager\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;

class SubmissionsTable extends Table
{

    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');

        $this->hasMany('ReminderManager.SubmissionsEmails',[
        	'foreignKey' => 'id_submission',
        	'propertyName' => 'SubmissionsEmails',
          'sort' => ['SubmissionsEmails.sended' => 'DESC' , 'SubmissionsEmails.name' => 'ASC']
        	]);

        $this->hasMany('ReminderManager.SubmissionsAttachements',[
        	'foreignKey' => 'id_submission',
        	'propertyName' => 'SubmissionsAttachements'
        	]);

    }

    public function getSubmissions($filters,$all = false){

      //debug($filters);

      $opt = array();
      $opz = array();

      ######################################################################################################
      //Gestione paginazione

      if(isset($filters['size']) && isset($filters['page'])){
          $size = $filters['size'];
          $page = $filters['page'] + 1;
      }else{
          $size = 50;
          $page = 1;
      }

      if($all === false){
         $opt['limit'] = $size;
         $opt['page'] = $page;
      }

      ######################################################################################################
      //Gestione ordinamento

      $col[0]['order'] = "data";
      $col[1]['order'] = "tipo";
      $col[1]['where'] = "ST.name";
      $col[2]['order'] = "destinatari";
      $col[3]['order'] = "completamento";
      //$col[4] = "";
      //$col[5] = "";


      $order = "";
      $separatore = "";

      if($all === false){

          $opt['order'] = "data DESC";

          if(isset($filters['column']) && !empty($filters['column']) && is_array($filters['column'])){

              foreach ($filters['column'] as $key => $value) {

                 // if($key==0) continue; // gestione particolare per denominazione

                  if(isset($col[$key]['order'])){

                      $order .= $separatore . $col[$key]['order'];
                      $separatore = ", ";

                      if($value == 1){
                          $order .= " DESC";
                      }else{
                          $order .= " ASC";
                      }

                  }
              }

              $opt['order'] = $order;


          }

      }else{
        $opt['order']  = "";
      }

      ######################################################################################################
      // Gestione filtri per campi

      if(isset($filters['filter']) && !empty($filters['filter']) && is_array($filters['filter'])){

          foreach ($filters['filter'] as $key => $value){

              switch ($key) {
                case '1':
                  $a[$col[$key]['where'] . ' LIKE'] = "%" . $value . "%";
                  $b['SA.attribute LIKE'] = "%" . $value . "%";
                  $opz[]['OR'] = [$a ,$b];
                break;

                case '3':

                  switch ($value) {

                    case 'Salvato':
                      $opz['Submissions.status'] = 0;
                    break;

                    case 'Da Inviare':
                      $opz['Submissions.status'] = 1;
                    break;

                    case 'In Corso':
                      $opz['Submissions.status'] = 2;
                    break;

                    case 'Terminato':
                      $opz['Submissions.status'] = 3;
                    break;

                    case 'Sospeso':
                      $opz['Submissions.status'] = 4;
                    break;

                    case 'Errore':
                      $opz['Submissions.status'] = 5;
                    break;
                  }

                break;

              }
          }
      }

      // Filtro anno
      // Controllo se sono settati il startDate e l'endData
      if(!empty($filters['startDate'])){

        $start = date('Y-m-d',strtotime(str_replace("/","-",$filters['startDate'])));
        $opz['Submissions.created >= ']=$start." 00:00:00";

      }
      if(!empty($filters['endDate'])){

        $end = date('Y-m-d',strtotime(str_replace("/","-",$filters['endDate'])));
        $opz['Submissions.created <= ']=$end." 23:59:59";

      }

      //debug($opt);
      //debug($opz);

      $res = $this->find()
        ->select([
          'id' => 'Submissions.id',
          'data' => 'Submissions.created',
          'tipo' => 'ST.name',
          'name' => 'Submissions.name',
          'destinatari' => '(SELECT COUNT(SE.id) FROM submissions_emails SE WHERE SE.id_submission = Submissions.id)',
          'completamento' => '(ROUND(((SELECT COUNT(SE.id) FROM submissions_emails SE WHERE SE.id_submission = Submissions.id AND SE.sended = 1) * 100) / (SELECT COUNT(SE.id) FROM submissions_emails SE WHERE SE.id_submission = Submissions.id)))',
          'stato' => 'Submissions.status',
          'stato_text' => 'Submissions.status_text',
          'attributo' => 'Submissions.attribute'
        ])
        ->join([
          'ST' => [
              'table' => 'submissions_type',
              'type' => 'LEFT',
              'conditions' => 'Submissions.id_submission_type = ST.id'
          ],
          'STSA' => [
            'table' => 'submissions_type_submissions_attributes',
            'type' => 'LEFT',
            'conditions' => 'STSA.id_submission_type = ST.id'
          ],
          'SA' => [
            'table' => 'submissions_attributes',
            'type' => 'LEFT',
            'conditions' => 'STSA.id_submission_attribute = SA.id'
          ]
        ])
        ->where($opz)
        ->order($opt['order']);

      //debug($res);
      if($all === false){
        $out = $res->limit($opt['limit'])->page($opt['page'])->toArray();
      }else{
        $out = $res->toArray();
      }

      return $out;

    }

    public function getSubmissionDetail($data){

      $res = $this->find('all')
      ->contain([
        'SubmissionsEmails' => ['SubmissionsEmailsAttachements','SubmissionsEmailsCustoms'],
        'SubmissionsAttachements'
      ])
      ->join([
        'ST' => [
            'table' => 'submissions_type',
            'type' => 'LEFT',
            'conditions' => 'Submissions.id_submission_type = ST.id'
        ],
        'STSA' => [
          'table' => 'submissions_type_submissions_attributes',
          'type' => 'LEFT',
          'conditions' => 'STSA.id_submission_type = ST.id'
        ],
        'SA' => [
          'table' => 'submissions_attributes',
          'type' => 'LEFT',
          'conditions' => 'STSA.id_submission_attribute = SA.id'
        ]
      ])
      ->autoFields(true)
      ->select([
        'type_name' => 'ST.name',
        'attribute' => 'SA.attribute',
        'titolo' => 'Submissions.name'
      ])
      ->where(['Submissions.id' => $data['id']])
      ;

      //debug($res);

      return $res->toArray();

    }

    public function getNextMail($idMailer = null){

      if($idMailer){
        $where = ['Submissions.id' => $idMailer];
      }else{
        $where = ['OR' => [['Submissions.status' => 1] , ['Submissions.status' => 2]]];
      }

      $res = $this->find('all')
      ->contain([
        'SubmissionsEmails' => function($q){
          return $q->contain(['SubmissionsEmailsAttachements','SubmissionsEmailsCustoms'])->where(['SubmissionsEmails.sended' => 0])->limit(1);
        },
        'SubmissionsAttachements'
      ])
      ->autoFields(true)
      ->where($where)
      ->order('Submissions.status DESC , Submissions.created ASC')
      ->first()
      ;

      //debug($res);
      //debug($res->toArray());

      return !empty($res)?$res->toArray():[];

    }

}
