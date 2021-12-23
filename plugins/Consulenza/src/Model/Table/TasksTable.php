<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;

class TasksTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->table('tasks');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        //$this->entityClass('Consulenza.Frozentask');
        $this->belongsTo('Consulenza.Jobs',[
        	'foreignKey' => 'job_id',
        	'propertyName' => 'job'
        	]);
        $this->belongsTo('Consulenza.Users',[
        	'foreignKey' => 'user_id',
        	'propertyName' => 'user'
        	]);
        $this->belongsTo('Consulenza.Orders',[
            'foreignKey' => 'order_id',
            'propertyName' => 'order'
            ]);
        $this->belongsTo('Consulenza.Phases',[
            'foreignKey' => 'phase_id',
            'propertyName' => 'phase'
            ]);
        /*
        $this->belongsToMany('Consulenza.Jobs', [
            'joinTable' => 'jobs_orders',
        ]);
        */
    }


    public function deleteTasksPlanned($idOrder , $idJob){

        $countProgrammed = $this->countTaskProgrammedByPlanned($idOrder , $idJob);

        //echo $countProgrammed; exit;

        if($countProgrammed == 0){

            $data = $this->deleteAll(['order_id' => $idOrder, 'job_id' => $idJob]);

            $ret['response'] = "OK";
            $ret['data'] = $data;
            $ret['msg'] ="Task cancellati";

        }else{

            $ret['response'] = "KO";
            $ret['data'] = -1;
            $ret['msg'] ="Impossibile cancellare i task pianificati, alcuni di essi sono già stati inseriti in calendario";

        }

        //debug($ret);

        return $ret;

    }

    public function countTaskProgrammedByPlanned($idOrder , $idJob){

        $opt['order_id'] = $idOrder;
        $opt['job_id'] = $idJob;
        $opt['byPlanning'] = 1;
        $opt['start !='] = "0000-00-00";
        $opt['end !='] = "0000-00-00";

        return $this->find('all')->where($opt)->count();

    }

    public function retrieveReportScostamentiAzienda($year,$aziendaId,$office,$pass=array(), $count=false, $xls=false){


      $opz = array();
      $opt = array();
      $opz2 = array();


      $col[0] = "Jobs.name";
      $col[1] = "milestone";
      $col[2] = "total_planned";
      $col[3] = "programmed";
      $col[4] = "completed";


      ######################################################################################################
      //Gestione paginazione

      if(isset($pass['size']) && isset($pass['page'])){
          $size = $pass['size'];
          $page = $pass['page'] + 1;
      }else{
          $size = 50;
          $page = 1;
      }

      if($size != "all"){
         $opt['limit'] = $size;
         $opt['page'] = $page;
      }

      ######################################################################################################
      //Gestione ordinamento


      $order = "";
      $separatore = "";

      if($size != "all"){

          $opt['order'] = "job_name ASC";

          if(isset($pass['column']) && !empty($pass['column']) && is_array($pass['column'])){

              foreach ($pass['column'] as $key => $value) {

                 // if($key==0) continue; // gestione particolare per denominazione

                  if(isset($col[$key])){

                      $order .= $separatore . $col[$key];
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

      }



      //controllo se $office è una lista di uffici e lo trasformo in un array
      if(preg_match("/,/",$office) || $office != -1)
      {
          $office = explode(",",$office);
          $opz['Orders.office_id IN'] = $office;
      }

      ######################################################################################################
      // gestione filtri

      // Filtro anno
      // Controllo se sono settati il startDate e l'endData
      if(!empty($pass['startDate']) && !empty($pass['endDate'] )){

        $start = date('Y-m-d',strtotime(str_replace("/","-",$pass['startDate'])));
        $end = date('Y-m-d',strtotime(str_replace("/","-",$pass['endDate'])));

        $opz['Tasks.start >= ']=$start." 00:00:00";
        $opz['Tasks.start <= ']=$end." 23:59:59";


      }
        $opz['Orders.year']=$year;


      // Filtri per campi
      if(isset($pass['filter']) && !empty($pass['filter']) && is_array($pass['filter'])){

          foreach ($pass['filter'] as $key => $value){

              switch ($key) {
                case '0':
                  $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                break;
                case '1':
                  $opz2[$col[$key] . ' LIKE'] = "%" . $value . "%";
                break;
              }
          }
        }


      $opz['Orders.azienda_id']=$aziendaId;

      $jobsorders = TableRegistry::get('Consulenza.JobsOrders');
      $subquery = $jobsorders->find('all')->select(['milestone'=>'Phases.milestone'])->contain('Phases')->
      where(['JobsOrders.order_id = orderid','JobsOrders.job_id = jobid'])->limit(1);

      if($count){
        return $this->find()->select(['Tasks.id','jobid'=>'Tasks.job_id','Tasks.start','Orders.year','orderid'=>'Orders.id','job_name' => 'Jobs.name','Orders.office_id',
          'total_planned' => 'SUM(Tasks.plannedLenght * IF(Tasks.start="0000-00-00 00:00:00",1,0) )',
          'programmed'=> 'SUM(TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * !completed)',
          'completed' => 'SUM(TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * completed)',
          'milestone' => $subquery ])
        ->matching('Orders')->matching('Jobs')->where($opz)->having($opz2)
        ->group('Tasks.job_id')->order($opt['order'])->count();

      }else{

        if($xls){

          return $this->find()->select(['Tasks.id','jobid'=>'Tasks.job_id','Tasks.start','Orders.year','orderid'=>'Orders.id','job_name' => 'Jobs.name','Orders.office_id',
            'total_planned' => 'SUM(Tasks.plannedLenght * IF(Tasks.start="0000-00-00 00:00:00",1,0) )',
            'programmed'=> 'SUM(TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * !completed)',
            'completed' => 'SUM(TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * completed)',
            'milestone' => $subquery ])
          ->matching('Orders')->matching('Jobs')->where($opz)->having($opz2)
          ->group('Tasks.job_id')->order($opt['order'])->toArray();


        }else{

          return $this->find()->select(['Tasks.id','jobid'=>'Tasks.job_id','Tasks.start','Orders.year','orderid'=>'Orders.id','job_name' => 'Jobs.name','Orders.office_id',
            'total_planned' => 'SUM(Tasks.plannedLenght * IF(Tasks.start="0000-00-00 00:00:00",1,0) )',
            'programmed'=> 'SUM(TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * !completed)',
            'completed' => 'SUM(TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * completed)',
            'milestone' => $subquery ])
          ->matching('Orders')->matching('Jobs')->where($opz)->having($opz2)
          ->group('Tasks.job_id')->order($opt['order'])->limit($opt['limit'])->page($opt['page'])->toArray();
        }
      }

    }

    public function retrieveAllTasks($year,$office,$pass=array(), $count=false, $xls=false){

            $opz = array();
            $opt = array();


            $col[0] = "cliente";
            $col[1] = "causale";
            $col[2] = "operatore";
            $col[3] = "note";
            $col[4] = "Tasks.start";
            $col[5] = "Tasks.end";
            $col[6] = "durata";
            $col[7] = "Tasks.completed";

            ######################################################################################################
            //Gestione paginazione

            if(isset($pass['size']) && isset($pass['page'])){
                $size = $pass['size'];
                $page = $pass['page'] + 1;
            }else{
                $size = 50;
                $page = 1;
            }

            if($size != "all"){
               $opt['limit'] = $size;
               $opt['page'] = $page;
            }


            ######################################################################################################
            //Gestione ordinamento


            $order = "";
            $separatore = "";

            if($size != "all"){

                $opt['order'] = "Tasks.start DESC";

                if(isset($pass['column']) && !empty($pass['column']) && is_array($pass['column'])){

                    foreach ($pass['column'] as $key => $value) {

                       // if($key==0) continue; // gestione particolare per denominazione

                        if(isset($col[$key])){

                            $order .= $separatore . $col[$key];
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

            }



            //controllo se $office è una lista di uffici e lo trasformo in un array
            if(preg_match("/,/",$office) || $office != -1)
            {
                $office = explode(",",$office);
                $opz['Orders.office_id IN'] = $office;
            }

            ######################################################################################################
            // gestione filtri

            // Filtro anno
            // Controllo se sono settati il startDate e l'endData
            if(!empty($pass['startDate']) && !empty($pass['endDate'] )){

              $start = date('Y-m-d',strtotime(str_replace("/","-",$pass['startDate'])));
              $end = date('Y-m-d',strtotime(str_replace("/","-",$pass['endDate'])));

              $opz['Tasks.start >= ']=$start." 00:00:00";
              $opz['Tasks.start <= ']=$end." 23:59:59";


            }
            if($year != -1){
              $opz['Orders.year']=$year;
            }
            // Filtro per non ritornare campi con start a 0
            $opz['TIME_TO_SEC(Tasks.start) > ']=0;


            // Filtri per campi
            if(isset($pass['filter']) && !empty($pass['filter']) && is_array($pass['filter'])){

                foreach ($pass['filter'] as $key => $value){

                    switch ($key) {
                      case '0':
                        $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                      break;
                      case '1':
                        $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                      break;
                      case '2':
                        $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                      break;
                      case '3':
                        $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                      break;
                    }
                }
              }

            if($count){

              return $this->find()->select(['Tasks.id','anno'=>'Orders.year','office'=>'Orders.office_id',
                  'cliente'=>'Aziende.denominazione','causale'=>'Jobs.name','Tasks.start', 'Tasks.end',
                  'operatore' => 'CONCAT(Users.nome," ",Users.cognome)','note'=>'Tasks.note',
                  'durata' => 'TIMESTAMPDIFF(second,Tasks.start,Tasks.end)', 'Tasks.completed'])->matching('Orders' ,function ($q){
                  return $q->matching('Aziende'); })->matching('Jobs')->matching('Users')->having($opz)
                  ->group('Tasks.id')->order($opt['order'])->count();

            }else{

              if($xls){

                return $this->find()->select(['Tasks.id','anno'=>'Orders.year','office'=>'Orders.office_id',
                    'cliente'=>'Aziende.denominazione','causale'=>'Jobs.name','Tasks.start', 'Tasks.end',
                    'operatore' => 'CONCAT(Users.nome," ",Users.cognome)','note'=>'Tasks.note',
                    'durata' => 'TIMESTAMPDIFF(second,Tasks.start,Tasks.end)', 'Tasks.completed'])->matching('Orders' ,function ($q){
                    return $q->matching('Aziende'); })->matching('Jobs')->matching('Users')->having($opz)
                    ->group('Tasks.id')->order($opt['order'])->toArray();

              }else{

                return $this->find()->select(['Tasks.id','anno'=>'Orders.year','office'=>'Orders.office_id',
                    'cliente'=>'Aziende.denominazione','causale'=>'Jobs.name','Tasks.start', 'Tasks.end',
                    'operatore' => 'CONCAT(Users.nome," ",Users.cognome)','note'=>'Tasks.note',
                    'durata' => 'TIMESTAMPDIFF(second,Tasks.start,Tasks.end)', 'Tasks.completed'])->matching('Orders' ,function ($q){
                    return $q->matching('Aziende'); })->matching('Jobs')->matching('Users')->having($opz)
                    ->group('Tasks.id')->order($opt['order'])->limit($opt['limit'])->page($opt['page'])->toArray();
              }
          }


    }

    public function retrieveReportScostamentiCausale($year,$office,$pass=array(), $count=false, $xls=false){


      $opz = array();
      $opt = array();


      $col[0] = "Aziende.denominazione";
      $col[1] = "Jobs.name";
      $col[2] = "total_planned";
      $col[3] = "programmed";
      $col[4] = "completed";


      ######################################################################################################
      //Gestione paginazione

      if(isset($pass['size']) && isset($pass['page'])){
          $size = $pass['size'];
          $page = $pass['page'] + 1;
      }else{
          $size = 50;
          $page = 1;
      }

      if($size != "all"){
         $opt['limit'] = $size;
         $opt['page'] = $page;
      }

      ######################################################################################################
      //Gestione ordinamento


      $order = "";
      $separatore = "";

      if($size != "all"){

          $opt['order'] = "job_name ASC";

          if(isset($pass['column']) && !empty($pass['column']) && is_array($pass['column'])){

              foreach ($pass['column'] as $key => $value) {

                 // if($key==0) continue; // gestione particolare per denominazione

                  if(isset($col[$key])){

                      $order .= $separatore . $col[$key];
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

      }



      //controllo se $office è una lista di uffici e lo trasformo in un array
      if(preg_match("/,/",$office) || $office != -1)
      {
          $office = explode(",",$office);
          $opz['Orders.office_id IN'] = $office;
      }

      ######################################################################################################
      // gestione filtri

      // Filtro anno
      // Controllo se sono settati il startDate e l'endData
      if(!empty($pass['startDate']) && !empty($pass['endDate'] )){

        $start = date('Y-m-d',strtotime(str_replace("/","-",$pass['startDate'])));
        $end = date('Y-m-d',strtotime(str_replace("/","-",$pass['endDate'])));

        $opz['Tasks.start >= ']=$start." 00:00:00";
        $opz['Tasks.start <= ']=$end." 23:59:59";


      }
        $opz['Orders.year']=$year;


      // Filtri per campi
      if(isset($pass['filter']) && !empty($pass['filter']) && is_array($pass['filter'])){

          foreach ($pass['filter'] as $key => $value){

              switch ($key) {
                case '0':
                  $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                break;
                case '1':
                  $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                break;
              }
          }
        }


      //$opz['Orders.azienda_id']=$aziendaId;

      /*$jobsorders = TableRegistry::get('Consulenza.JobsOrders');
      $subquery = $jobsorders->find('all')->select(['milestone'=>'Phases.milestone'])->contain('Phases')->
      where(['JobsOrders.order_id = orderid','JobsOrders.job_id = jobid'])->limit(1);*/

      if($count){
        return $this->find()->select(['Tasks.id','jobid'=>'Tasks.job_id','Tasks.start','Orders.year','orderid'=>'Orders.id',
          'job_name' => 'Jobs.name','Orders.office_id','Orders.azienda_id','Aziende.denominazione',
          'total_planned' => 'SUM(Tasks.plannedLenght * IF(Tasks.start="0000-00-00 00:00:00",1,0) )',
          'programmed'=> 'SUM(TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * !completed)',
          'completed' => 'SUM(TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * completed)'])
        ->matching('Orders',function ($q){return $q->contain('Aziende'); })->matching('Jobs')->where($opz)
        ->group('Orders.azienda_id')->group('Tasks.job_id')->order($opt['order'])->count();

      }else{

        if($xls){

          return $this->find()->select(['Tasks.id','jobid'=>'Tasks.job_id','Tasks.start','Orders.year','orderid'=>'Orders.id',
            'job_name' => 'Jobs.name','Orders.office_id','Orders.azienda_id','Aziende.denominazione',
            'total_planned' => 'SUM(Tasks.plannedLenght * IF(Tasks.start="0000-00-00 00:00:00",1,0) )',
            'programmed'=> 'SUM(TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * !completed)',
            'completed' => 'SUM(TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * completed)'])
          ->matching('Orders',function ($q){return $q->contain('Aziende'); })->matching('Jobs')->where($opz)
          ->group('Orders.azienda_id')->group('Tasks.job_id')->order($opt['order'])->toArray();


        }else{

          return $this->find()->select(['Tasks.id','jobid'=>'Tasks.job_id','Tasks.start','Orders.year','orderid'=>'Orders.id',
            'job_name' => 'Jobs.name','Orders.office_id','Orders.azienda_id','Aziende.denominazione',
            'total_planned' => 'SUM(Tasks.plannedLenght * IF(Tasks.start="0000-00-00 00:00:00",1,0) )',
            'programmed'=> 'SUM(TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * !completed)',
            'completed' => 'SUM(TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * completed)' ])
          ->matching('Orders',function ($q){return $q->contain('Aziende'); })->matching('Jobs')->where($opz)
          ->group('Orders.azienda_id')->group('Tasks.job_id')->order($opt['order'])->limit($opt['limit'])->page($opt['page'])->toArray();
        }
      }

    }
}
