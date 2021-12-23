<?php
namespace Consulenza\Controller;

use Cake\Routing\Router;
use Consulenza\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

/**
 * Home Controller
 *
 * @property \Calendar\Model\Table\HomeTable $Home
 */
class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Consulenza.Order');
        $this->loadComponent('Consulenza.Job');
        $this->loadComponent('Consulenza.Phase');
        $this->loadComponent('Consulenza.Notice');
        $this->loadComponent('Consulenza.Task');
        $this->loadComponent('Consulenza.JobsOrders');
        $this->loadComponent('Consulenza.Azienda');
        //$this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['getCalendarEvents', 'saveEvent','deleteEvent']);

        $this->layout = 'ajax';
        $this->viewPath = 'Async';
        $this->view = 'default';
        $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");

    }

    public function beforeRender(Event $event) {
        parent::beforeFilter($event);
        $this->set('result', json_encode($this -> _result));
    }



    public function getOrderAzienda($id = ""){

        $out = array();

        if($id != ""){

            $res = $this->Order->getOrdersByIdAzienda($id);

            $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "");

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Id Azienda mancante");
        }

    }

    public function autocomplete($type = ""){

        switch ($type) {
            case 'causali':

                $term = $this->request->query['term'];

                $res = $this->Job->autocomplete($term);
                $this->_result = $res;
                break;

            default:
                $this->_result = array();
                break;
        }

    }

    public function getProcessByIdJob($id = "", $order = ""){

        $out = array();

        if($id != ""){

            $res = $this->Job->getProcessByIdJob($id,$order);

            $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "");

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Id Job mancante");
        }

    }

    public function getFasiByProcesso($id = ""){

        $out = array();

        if($id != ""){

            $res = $this->Phase->getPhaseByProcess($id);

            $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "");

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Id Processo mancante");
        }

    }

    public function getAziendaFromOrder($orderId = ""){

        $out = array();

        if($orderId != ""){

            $res = $this->Order->getAziendaFromOrder($orderId);

            $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "");

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Id Ordine mancante");
        }

    }

    public function getJobById($jobId = ""){

        $out = array();

        if($jobId != ""){

            $res = $this->Job->_get($jobId);

            $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "");

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Id Job mancante");
        }

    }

    public function selectProcessByPhase($phaseId = ""){

        $out = array();

        if($phaseId != "" && $phaseId != 0 && $phaseId != null){

            $res = $this->Phase->_get($phaseId);

            $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "");

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Id Phase mancante");
        }

    }

    public function updateJobsOrderData(){

        $out = array();

        $data = $this->request->data;

        //echo "<pre>"; print_r($data); echo "</pre>"; exit;

        if(!empty($data) && isset($data['id'])){

            $res = $this->Job->updateJobsOrderData($data);

            if($res){

                //debug($res); exit;
                $data['id'] = $res->id;

                $timeToBeAssigned = $this->Job->getStringTime($this->Job->checkTimetoBeAssigned($data['id']));

                $order = $this->Job->getDataOrderByJobsOrderId($data['id']);

                $this->Order->lockOrderById($order->id);

                $this->_result = array('response' => 'OK', 'data' => array('timeToBeAssigned' => $timeToBeAssigned, 'id' => $data['id']), 'msg' => "");

            }else{

                $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Erorre nel salvataggio dei dati");

            }

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Erorre nella ricezione dei dati");
        }

    }

    public function createdTasks(){

        $out = array();

        $data = $this->request->data;

        //echo "<pre>"; print_r($data); echo "</pre>"; exit;

        if(!empty($data) && isset($data['id'])){

            $res = $this->Task->createTasks($data);

            if($res){
                $timeToBeAssigned = $this->Job->getStringTime($this->Job->checkTimetoBeAssigned($data['id']));

                $order = $this->Job->getDataOrderByJobsOrderId($data['id']);

                $this->Order->lockOrderById($order->id);

                $this->Job->lockJobOrderById($data['id']);

                $tasksPlanned = $this->Task->getTaskPlanned($data['job_id'], $order->id);

                $this->_result = array('response' => 'OK', 'data' => array('timeToBeAssigned' => $timeToBeAssigned , 'taskPlanned' => $tasksPlanned), 'msg' => "");
            }else{
                $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Erorre nella generazione dei task, si prega di riprovare.");
            }
        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Erorre nella ricezione dei dati");
        }

    }

    public function deleteTasksPlanned(){

        $data = $this->request->data;

        if(!empty($data) && isset($data['id'])){

            $res = $this->Task->deleteTasksPlanned($data['id']);

            if($res['response'] == "OK"){

                $timeToBeAssigned = $this->Job->getStringTime($this->Job->checkTimetoBeAssigned($data['id']));

                $this->_result = array('response' => 'OK', 'data' => array('timeToBeAssigned' => $timeToBeAssigned , 'taskPlanned' => 0), 'msg' => "");

            }else{
                $this->_result = $res;
            }

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Erorre nella ricezione dei dati");
        }

    }

    public function getCountMyNewNotice(){ // ottiene un totale di tue notifiche per mostrarne il numero

        // leggo id da sessione e recupero sue notifiche
        $uid =  $this->request->session()->read('Auth.User.id');

        if($uid != ""){

            $res = $this->Notice->GetMyNewNotice();

            $this->_result = array('response' => 'OK', 'data' => count($res), 'msg' => "");

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Non autorizzato");
        }

    }



    public function getMyNewNotice(){ // ottiene le notifiche da leggere dell'utente loggato

        $uid =  $this->request->session()->read('Auth.User.id');

        if($uid != ""){

            $res = $this->Notice->GetMyNewNotice();

            foreach($res as $resource){
                $resource->dateWrited = $resource->dateWrited->i18nFormat('dd/MM/yyyy');
            }

            $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "");

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Non autorizzato");
        }

    }

    public function noticeRead(){ // segna quella notifica come letta

        $uid =  $this->request->session()->read('Auth.User.id');

        $nid = $this->request->data('nid');

        if($uid != "" && $nid != ""){

            $res = $this->Notice->setNoticeRead($nid);

            $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "");

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Non autorizzato");
        }

    }

    public function getInviiCausali($causaleId = -1, $year = -1,$office = -1) {

        if($causaleId != 0 && $year != null){

            // setto in sessione i dati per evitare facendo F5 di perdere i filtri

            $this->request->session()->write('Report.InviiCausali.causaleId',$causaleId);
            $this->request->session()->write('Report.InviiCausali.year',$year);
			$this->request->session()->write('Report.InviiCausali.office',$office);

            $out = $this->Job->jobInviiCausali($causaleId,$year,$office);

            $this->_result = $out;

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
        }

    }

    public function getInviiCausaliUNICO($causaleId = -1, $year = -1,$office = -1) {

        if($causaleId != 0 && $year != null){

            // setto in sessione i dati per evitare facendo F5 di perdere i filtri

            $this->request->session()->write('Report.InviiCausaliUnico.causaleId',$causaleId);
            $this->request->session()->write('Report.InviiCausaliUnico.year',$year);
            $this->request->session()->write('Report.InviiCausaliUnico.office',$office);

            $out = $this->Job->jobInviiCausaliUNICO($causaleId,$year,$office);

            $this->_result = $out;

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
        }

    }

    public function getInviiCausaliUNICOSC($causaleId = -1, $year = -1,$office = -1) {

        if($causaleId != 0 && $year != null){

            // setto in sessione i dati per evitare facendo F5 di perdere i filtri

            $this->request->session()->write('Report.InviiCausaliUnicoSC.causaleId',$causaleId);
            $this->request->session()->write('Report.InviiCausaliUnicoSC.year',$year);
            $this->request->session()->write('Report.InviiCausaliUnicoSC.office',$office);

            $out = $this->Job->jobInviiCausaliUNICOSC($causaleId,$year,$office);

            $this->_result = $out;

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
        }

    }

    public function getInviiCausaliUNICOENC($causaleId = -1, $year = -1,$office = -1) {

        if($causaleId != 0 && $year != null){

            // setto in sessione i dati per evitare facendo F5 di perdere i filtri

            $this->request->session()->write('Report.InviiCausaliUnicoENC.causaleId',$causaleId);
            $this->request->session()->write('Report.InviiCausaliUnicoENC.year',$year);
            $this->request->session()->write('Report.InviiCausaliUnicoENC.office',$office);

            $out = $this->Job->jobInviiCausaliUNICOENC($causaleId,$year,$office);

            $this->_result = $out;

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
        }

    }

    public function inviaReport($order=0) {

        $user = $this->request->session()->read('Auth.User');

        if(isset($order) && $order>0){

            // Sergio task 5531, controllo se l'utente è autorizzato all'invio
            if($this->Job->checkAuthInvioCausale()){

                $res = $this->JobsOrders->setInviato($order);

                if($res){
                    $out = array('response' => 'OK', 'data' => $res, 'msg' => "");
                } else {
                    $out = array('response' => 'KO', 'data' => $res, 'msg' => "");
                }

                $this->_result = $out;

            }else{

                $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Non autorizzato");
            }

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
        }


    }

    public function inviaReportIrap($order=0) {

        $user = $this->request->session()->read('Auth.User');

        if($user['role']=='admin' && $user['level']>='100' ){ // check if admin


            if(isset($order) && $order>0){

                $res = $this->JobsOrders->setIrapInviato($order);

                if($res){
                    $out = array('response' => 'OK', 'data' => $res, 'msg' => "");
                } else {
                    $out = array('response' => 'KO', 'data' => $res, 'msg' => "");
                }

                $this->_result = $out;

            }else{
                $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
            }

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Non autorizzato");
        }


    }

    public function getStep(){

        $data = $this->request->data;

        if(isset($data['idProcess']) && isset($data['idJob']) && isset($data['idOrder'])){

            $phases = TableRegistry::get('Consulenza.Phases');

            $res = $phases->getStep($data);

            //debug($res);

            $out = array();
             //$i=0;

            foreach ($res as $key => $value) {
/*
                $out[$value['id']]['id'] = $value['id'];
                $out[$value['id']]['milestone'] = $value['milestone'];
                $out[$value['id']]['selected'] = $value['selected'];
                $out[$value['id']]['ordering'] = $value['ordering'];
                $out[$value['id']]['status'] = $value['status'];*/

        /*       $i++;
                $out[$i]['id'] = $value['id'];
                $out[$i]['milestone'] = $value['milestone'];
                $out[$i]['selected'] = $value['selected'];
                $out[$i]['ordering'] = $value['ordering'];
                $out[$i]['status'] = $value['status'];*/
              $out[]=array(
                  'id' => $value['id'],
                  'milestone' => $value['milestone'],
                  'selected' => $value['selected'],
                  'ordering' => $value['ordering'],
                  'status' => $value['status']
                );
            }

            $this->_result = array('response' => 'OK', 'data' => $out, 'msg' => "");

        }else{

            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Id non valido");

        }

    }

    public function getReportScostamentiClienti($year='', $office = -1){

        if($year != null){

          $this->request->session()->write('Report.ScostamentiClienti.year',$year);
          $this->request->session()->write('Report.ScostamentiClienti.office',$office);


          $out = $this->Azienda->getReportScostamenti($year, $office);



          $this->_result = $out;

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
        }


    }

    public function getReportScostamentiAzienda($year='',$aziendaId = 1, $office = -1){

      if($year != null && $aziendaId != null ){

        $this->request->session()->write('Report.ScostamentiClienti.year',$year);
        $this->request->session()->write('Report.ScostamentiClienti.office',$office);


        $out = $this->Azienda->getReportScostamentiPerAzienda($year,$aziendaId, $office);



        $this->_result = $out;

      }else{
          $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
      }

    }

    public function getReportTotaleTasks($year = '', $office = -1, $xls = false){
        if($year != null  ){
          $this->request->session()->write('Report.TotaleTasks.year',$year);
          $this->request->session()->write('Report.TotaleTasks.office',$office);


          $out = $this->Task->getAllTask($year,$office);



          $this->_result = $out;
        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
        }
    }
    public function getReportScostamentiCausale($year='', $office = -1){

      if($year != null  ){

        $this->request->session()->write('Report.ScostamentiCausale.year',$year);
        $this->request->session()->write('Report.ScostamentiCausale.office',$office);


        $out = $this->Task->getReportScostamentiPerCausale($year,$office);



        $this->_result = $out;

      }else{
          $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
      }

    }

    public function editNotesJobsOrder()
    {
      $params = $this->request->data;
      if(!empty($params['id']) && isset($params['notes'])){

          $res = $this->Job->saveNotesJobsOrders($params);

          $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Modifica Avvenuta con successo");

      }else{

          $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
      }

    }

    public function sbloccaAzienda()
    {
      $params = $this->request->data;
      $user = $this->request->session()->read('Auth.User');

      if($user['role']!='admin'){

        $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel ripristino: utente non autorizzato.");

      }else{
        if(isset($params['order_id']) ){
            $locked = $this->Job->checkLockJobsOrders($params['order_id']);
            //debug($locked);die;
            if($locked !== null){
              if(!$locked){

                $res = $this->Order->unlockOrderById($params['order_id']);
                $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Modifica Avvenuta con successo");
              }else{

                $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore , ci sono ancora delle task generate per l'anno");

              }

            }else{
              $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri, non sono stati trovati jobs riferiti a quest'anno");
            }
        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Errore parametri");
        }
      }


    }

}
