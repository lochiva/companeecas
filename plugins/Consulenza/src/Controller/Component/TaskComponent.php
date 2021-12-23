<?php
namespace Consulenza\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class TaskComponent extends Component
{

    public function createTasks($data = ""){

    	$out = false;

    	if($data != ""){

    		//echo "<pre>"; print_r($data); echo "</pre>"; //exit;

    		#########################################################################################################################################################
    		//recupero le fasi per cui devo generare i task
    		$phases = TableRegistry::get('Consulenza.Phases');

    		$listPhases = $phases->find('all')->where(['process_id' => $data['process_id'], 'plannable' => 1])->toArray();

    		//debug($listPhases);

    		$countPhase = count($listPhases);

    		//debug($countPhase);

    		#########################################################################################################################################################
    		//Recupero i dati del job orders

    		$jobsOrders = TableRegistry::get('Consulenza.JobsOrders');

    		//$dataJobOder = $jobsOrders->get($data['id']);
            $dataJobOder = $jobsOrders->find('all')->where(["JobsOrders.id" => $data['id']])->contain(['Processes'])->first();

    		//debug($dataJobOder); exit;

    		#########################################################################################################################################################
    		//Recupero i dati del job

    		$jobs = TableRegistry::get('Consulenza.Jobs');
    		$dataJob = $jobs->get($dataJobOder->job_id);

    		//debug($dataJob); exit;

    		#########################################################################################################################################################
    		//calcolo i secondi per ogni task che andrò a creare

    		$secondForTask = round($dataJobOder->totalTime / $countPhase);

    		//debug("Secondi per task: " . $secondForTask . " (" . $secondForTask / 60 /60 . ")");

    		#########################################################################################################################################################
    		//Per ogni fase a aquesto punto posso creare un task

            if($secondForTask > 0){

        		foreach ($listPhases as $key => $phase) {

        			$tasksToSave[$key]['job_id'] = $dataJobOder->job_id;
        			$tasksToSave[$key]['user_id'] = $dataJobOder->user_id;
        			$tasksToSave[$key]['order_id'] = $dataJobOder->order_id;
        			$tasksToSave[$key]['phase_id'] = $phase->id;
        			$tasksToSave[$key]['title'] = $dataJob->name;
        			$tasksToSave[$key]['start'] = '0000-00-00 00:00:00';
        			$tasksToSave[$key]['end'] = '0000-00-00 00:00:00';
        			$tasksToSave[$key]['allDay'] = '0';
                    if($dataJobOder->process->toConsume){
                        $tasksToSave[$key]['toConsume'] = '1';
                    }else{
                        $tasksToSave[$key]['toConsume'] = '0';
                    }
        			$tasksToSave[$key]['backgroundColor'] = '';
        			$tasksToSave[$key]['borderColor'] = $dataJob->borderColor;
        			$tasksToSave[$key]['byPlanning'] = 1;
        			$tasksToSave[$key]['plannedLenght'] = $secondForTask;
        			$tasksToSave[$key]['completed'] = 0;
        			$tasksToSave[$key]['note'] = '';

        		}

        		//debug($tasksToSave);

        		#########################################################################################################################################################
        		//Posso salvare

        		$tasks = TableRegistry::get('Consulenza.Tasks');

        		$tasksToSave = $tasks->newEntities($tasksToSave);

    	        foreach ($tasksToSave as $key => $task) {
    	            $tasks->save($task);
    	        }

        		#########################################################################################################################################################

    	        $out = true;

            }else{
                $out = false;
            }

    	}

    	return $out;

    }

    public function getTaskPlanned($jobId = 0, $orderId = 0){

        $count = 0;

        $opt['job_id'] = $jobId;
        $opt['order_id'] = $orderId;
        $opt['byPlanning'] = 1;
        $opt['start'] = "0000-00-00 00:00:00";
        $opt['end'] = "0000-00-00 00:00:00";

        $tasks = $this->getTasks($opt);

        $count = count($tasks);

        return $count;

    }

    public function getTaskProgrammed($jobId = 0, $orderId = 0){

        $count = 0;

        $opt['job_id'] = $jobId;
        $opt['order_id'] = $orderId;
        $opt['byPlanning'] = 1;
        $opt['start !='] = "0000-00-00 00:00:00";
        $opt['end !='] = "0000-00-00 00:00:00";

        $tasks = $this->getTasks($opt);

        $count = count($tasks);

        return $count;

    }

    public function getTaskManual($jobId = 0, $orderId = 0){

        $count = 0;

        $opt['job_id'] = $jobId;
        $opt['order_id'] = $orderId;
        $opt['byPlanning'] = 0;

        $tasks = $this->getTasks($opt);

        $count = count($tasks);

        return $count;

    }

    private function getTasks($opt = ""){

        $tasks = TableRegistry::get('Consulenza.Tasks');

        $res = $tasks->find('all')->where($opt)->toArray();

        //debug($res); exit;

        return $res;

    }

    public function deleteTasksPlanned($idJobOrder = 0){

        if($idJobOrder != 0){

            $jobsOrders = TableRegistry::get('Consulenza.JobsOrders');

            $jobOrder = $jobsOrders->get($idJobOrder);

            //debug($jobOrder);

            $tasks = TableRegistry::get('Consulenza.Tasks');

            $res = $tasks->deleteTasksPlanned($jobOrder->order_id, $jobOrder->job_id);

            //debug($ret);

            if($res['response'] == "OK"){

                $jobOrder->isLocked = 0;

                $jobsOrders->save($jobOrder);

            }

            $ret = $res;

        }else{
            $ret['result'] = "KO";
            $ret['data'] = "";
            $ret['msg'] = "Job order id mancante.";
        }

        return $ret;
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


    public function getAllTask($year = '', $office = -1, $xls = false){


      $tasks = TableRegistry::get('Consulenza.Tasks');
      $out = array();



      if (isset($year) && !empty($year)) {
              $this->year=$year;
      }else{
          $this->year=date('Y')-2;
      }

      $pass =  $this->request->query;
      if(!empty($pass['startDate']) && !empty($pass['endDate'] )){
        $this->request->session()->write('Report.TotaleTasks.startDate',$pass['startDate']);
        $this->request->session()->write('Report.TotaleTasks.endDate',$pass['endDate']);
      }else if(!$xls){
        $this->request->session()->delete('Report.TotaleTasks.startDate');
        $this->request->session()->delete('Report.TotaleTasks.endDate');
      }

      if($xls){
        $pass['startDate'] = $this->request->session()->read('Report.TotaleTasks.startDate');
        $pass['endDate'] = $this->request->session()->read('Report.TotaleTasks.endDate');

      }
      $res = $tasks->retrieveAllTasks($this->year,$office,$pass,false,$xls);
      $total = $tasks->retrieveAllTasks($this->year,$office,$pass,true);
      $rows = array();

      foreach($res as $task){

        if($xls){
          $task['durata'] = round($task['durata']/3600,2);
        }else{
          $task['durata'] = $this->oreMinuti($task['durata']);
        }

        $rows[] = array(
           $task['cliente'],
           $task['causale'],
           $task['operatore'],
           $task['note'],
           $task['start']->i18nFormat('dd-MM-yyy HH:mm:ss'),
           $task['end']->i18nFormat('dd-MM-yyy HH:mm:ss'),
           $task['durata'],
           ($task['completed'] == 1? 'Si' : 'No' )
        );
      }

      $out['total_rows'] = $total;
      $out['rows'] = $rows;
      // debug($out);die;


      return $out;


    }
    public function getReportScostamentiPerCausale($year='', $office = -1,$xls = false ){

        $tasks = TableRegistry::get('Consulenza.Tasks');

        $out = array();


        if (isset($year) and $year>0) {
          $this->year=$year;
        }else{
          $this->year=date('Y');
        }


        $pass =  $this->request->query;
        if(!empty($pass['startDate']) && !empty($pass['endDate'] )){
          $this->request->session()->write('Report.ScostamentiCausale.startDate',$pass['startDate']);
          $this->request->session()->write('Report.ScostamentiCausale.endDate',$pass['endDate']);
        }else if(!$xls){
          $this->request->session()->delete('Report.ScostamentiCausale.startDate');
          $this->request->session()->delete('Report.ScostamentiCausale.endDate');
        }

        if($xls){
          $pass['startDate'] = $this->request->session()->read('Report.ScostamentiCausale.startDate');
          $pass['endDate'] = $this->request->session()->read('Report.ScostamentiCausale.endDate');
        }

        $res = $tasks->retrieveReportScostamentiCausale($year,$office,$pass,false,$xls);
        $total = $tasks->retrieveReportScostamentiCausale($year,$office,$pass,true);
        $rows = array();
        //debug($res);die;
        foreach($res as $task){

          if($xls){
            $task['total_planned'] = round($task['total_planned']/3600,2);
            $task['programmed'] = round($task['programmed']/3600,2);
            $task['completed'] = round($task['completed']/3600,2);
          }else{
            $task['total_planned'] = $this->oreMinuti($task['total_planned']);
            $task['programmed'] = $this->oreMinuti($task['programmed']);
            $task['completed'] = $this->oreMinuti($task['completed']);
          }

          $rows[] = array(
            ($task['Aziende']['denominazione'] == null? '':$task['Aziende']['denominazione']),
             $task['job_name'],
             $task['total_planned'],
             $task['programmed'],
             $task['completed']
          );
        }

        $out['total_rows'] = $total;
        $out['rows'] = $rows;

        //debug($out);die;


  		return $out;

    }

}
