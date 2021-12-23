<?php
namespace Consulenza\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class AziendaComponent extends Component
{


    public function _newEntity(){
        $aziende = TableRegistry::get('Consulenza.Aziende');
        return $aziende->newEntity();
    }

    public function _patchEntity($doc,$request){
        $aziende = TableRegistry::get('Consulenza.Aziende');
        return $aziende->patchEntity($doc,$request);
    }

    public function _save($doc){
        $aziende = TableRegistry::get('Consulenza.Aziende');
        return $aziende->save($doc);
    }

    public function _get($id){
        $aziende = TableRegistry::get('Consulenza.Aziende');
        return $aziende->get($id);

    }

    public function _delete($doc){
        $aziende = TableRegistry::get('Consulenza.Aziende');
        return $aziende->delete($doc);
    }

    public function oreMinuti($total){
      $hours = floor($total / 3600);
      $minutes = floor(($total/ 60) % 60);

      if($hours < 0){
        $hours = "0";
      }
      if(empty($minutes) || $minutes < 0){
        $minutes = "00";
      }else if(strlen((string)$minutes)==1){
        $minutes = "0".$minutes;
      }

      return $hours.":".$minutes;
    }

    public function getReportScostamenti($year='', $office = -1 , $xls=false){


      $aziende = TableRegistry::get('Consulenza.Aziende');
      $out = array();



    	if (isset($year) and $year>0) {
      				$this->year=$year;
    	}else{
      		$this->year=date('Y')-2;
    	}




      $pass =  $this->request->query;
      if(!empty($pass['startDate']) && !empty($pass['endDate'] )){
        $this->request->session()->write('Report.ScostamentiClienti.startDate',$pass['startDate']);
        $this->request->session()->write('Report.ScostamentiClienti.endDate',$pass['endDate']);
      }else if(!$xls){
        $this->request->session()->delete('Report.ScostamentiClienti.startDate');
        $this->request->session()->delete('Report.ScostamentiClienti.endDate');
      }

      if($xls){
        $pass['startDate'] = $this->request->session()->read('Report.ScostamentiClienti.startDate');
        $pass['endDate'] = $this->request->session()->read('Report.ScostamentiClienti.endDate');
      }

      $res = $aziende->retrieveReportScostamenti($year,$office,$pass,false,$xls);
      $total = $aziende->retrieveReportScostamenti($year,$office,$pass,true);
      $rows = array();

      foreach($res as $azienda){
        if(!$xls){
          $rows[] = array(
            '<a href="'.Router::url('/consulenza/report/cliente/'.$this->request->session()->read('Report.ScostamentiClienti.year').'/'.$azienda['id']).'">'.$azienda['denominazione'].'</a> ',
             $this->oreMinuti($azienda['total_planned']),
             $this->oreMinuti($azienda['programmed']),
             $this->oreMinuti($azienda['completed'])
          );
        }else{
          $rows[] = array(
             $azienda['denominazione'],
             round($azienda['total_planned']/3600,2),
             round($azienda['programmed']/3600,2),
             round($azienda['completed']/3600,2)
          );
        }
      }


      $out['total_rows'] = $total;
      $out['rows'] = $rows;
      //debug($out);

      return $out;

    }

    public function getReportScostamentiPerAzienda($year='',$aziendaId=1, $office = -1,$xls = false ){

        $tasks = TableRegistry::get('Consulenza.Tasks');

        $out = array();


        if (isset($year) and $year>0) {
          $this->year=$year;
        }else{
          $this->year=date('Y');
        }


        $pass =  $this->request->query;
        if(!empty($pass['startDate']) && !empty($pass['endDate'] )){
          $this->request->session()->write('Report.ScostamentiClienti.startDate',$pass['startDate']);
          $this->request->session()->write('Report.ScostamentiClienti.endDate',$pass['endDate']);
        }else if(!$xls){
          $this->request->session()->delete('Report.ScostamentiClienti.startDate');
          $this->request->session()->delete('Report.ScostamentiClienti.endDate');
        }

        if($xls){
          $pass['startDate'] = $this->request->session()->read('Report.ScostamentiClienti.startDate');
          $pass['endDate'] = $this->request->session()->read('Report.ScostamentiClienti.endDate');
        }

        $res = $tasks->retrieveReportScostamentiAzienda($year,$aziendaId,$office,$pass,false,$xls);
        $total = $tasks->retrieveReportScostamentiAzienda($year,$aziendaId,$office,$pass,true);
        $rows = array();

        foreach($res as $task){

          if(!$xls){
            $rows[] = array(
               $task['job_name'],
               ($task['milestone'] == null? '':$task['milestone']),
               $this->oreMinuti($task['total_planned']),
               $this->oreMinuti($task['programmed']),
               $this->oreMinuti($task['completed'])
            );
          }else{
            $rows[] = array(
               $task['job_name'],
               ($task['milestone'] == null? '':$task['milestone']),
               round($task['total_planned']/3600,2),
               round($task['programmed']/3600,2),
               round($task['completed']/3600,2)
            );
          }
        }

        $out['total_rows'] = $total;
        $out['rows'] = $rows;




  		return $out;

    }

}
