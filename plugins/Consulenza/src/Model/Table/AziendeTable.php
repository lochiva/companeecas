<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class AziendeTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->setTable('aziende');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Consulenza.Azienda');
        //$this->belongsTo('Document.Contacts',['foreignKey' => 'id_client', 'conditions' => ['Contacts.client' => 1], 'propertyName' => 'client']);
        //$this->belongsTo('Document.Projects',['foreignKey' => 'id_project']);
        $this->hasMany('Consulenza.Orders', [
            'foreignKey' => 'azienda_id'
        ]);
    }

    public function retrieveReportScostamenti($year,$office,$pass=array(), $count=false, $xls=false){


      $opz = array();
      $opt = array();

      $col[0] = "denominazione";
      $col[1] = "total_planned";
      $col[2] = "programmed";
      $col[3] = "completed";

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

          $opt['order'] = "Aziende.denominazione ASC";

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
              }
          }
        }





      if($count){

        return $this->find()->select(['id','denominazione','Orders.year','Tasks.start','Tasks.end','Tasks.plannedLenght'])->matching('Orders',function ($q) {
                return $q->matching('Tasks' , function ($q) {
                  return $q->select(['total_planned' => $q->func()->sum('Tasks.plannedLenght * IF(Tasks.start="0000-00-00 00:00:00",1,0)'),
                  'programmed'=> $q->func()->sum('TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * !completed'),
                  'completed' => $q->func()->sum('TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * completed')]);
                });
            }
        )->where($opz)->group('Aziende.id')->order($opt['order'])->count();


      }else{

        if($xls){

          return $this->find()->select(['id','denominazione','Orders.year','Tasks.start','Tasks.end','Tasks.plannedLenght'])->matching('Orders',function ($q) {
                  return $q->matching('Tasks' , function ($q) {
                    return $q->select(['total_planned' => $q->func()->sum('Tasks.plannedLenght * IF(Tasks.start="0000-00-00 00:00:00",1,0)'),
                    'programmed'=> $q->func()->sum('TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * !completed'),
                    'completed' => $q->func()->sum('TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * completed')]);
                  });
              }
          )->where($opz)->group('Aziende.id')->order($opt['order'])->toArray();



        }else{

          return $this->find()->select(['id','denominazione','Orders.year','Tasks.start','Tasks.end','Tasks.plannedLenght'])->matching('Orders',function ($q) {
                  return $q->matching('Tasks' , function ($q) {
                    return $q->select(['total_planned' => $q->func()->sum('Tasks.plannedLenght * IF(Tasks.start="0000-00-00 00:00:00",1,0)'),
                    'programmed'=> $q->func()->sum('TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * !completed'),
                    'completed' => $q->func()->sum('TIMESTAMPDIFF(second,Tasks.start,Tasks.end) * completed')]);
                  });
              }
          )->where($opz)->group('Aziende.id')->order($opt['order'])->limit($opt['limit'])->page($opt['page'])->toArray();
        }

      }



    }





}
