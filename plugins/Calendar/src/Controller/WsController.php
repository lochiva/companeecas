<?php
namespace Calendar\Controller;

use Calendar\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\I18n\Date;
use Cake\Http\Client;
use Cake\Core\Configure;
use Cake\Routing\Router;

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
        $this->loadComponent('Calendar.Calendar');
        $this->loadComponent('Calendar.GoogleCalendar');
        //$this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['getCalendarEvents', 'saveEvent','deleteEvent']);

        $this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');
        $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");

    }

    public function beforeRender(Event $event) {
        parent::beforeFilter($event);
        $this->set('result', json_encode($this -> _result));
    }

    /**
     * Index method
     *
     * @return json
     */
    public function getCalendarEvents($userId = '')
    {

        /*
        Questo metodo restituisce gli eventi da mostrare nel calendario in base alle date ricevute
        Non utilizza il formato standard di output dei dati poichè il plugin del calendario prevede un suo flusso dati in ingresso
        */

        //$this->autoRender = false;
        $events = array();

        if(empty($userId) || $this->Auth->user('role') !== 'admin'){
            $userId = $this->Auth->user('id');
        }
        //echo "<pre>"; print_r($this->request->query); echo "</pre>";

        if(isset($this->request->query['start']) && isset($this->request->query['end'])){

            $start = $this->request->query['start'];
            $end = $this->request->query['end'];/*$timeMin = new \DateTime($star);$timeMax = new \DateTime($end);
            $gog = $this->GoogleCalendar->readEvents(['showDeleted' => false ,'timeMin' => $timeMin->format('c'),'timeMax' =>$timeMax->format('c') ])->getItems();
            debug($gog[2]->id);*/
            $resEvents = $this->Calendar->getEventsByDate($start,$end,$userId);
            $repEvents = $this->Calendar->getRepeatedEventsByDate($start,$end,$userId);

            //echo "<pre>"; print_r($resEvents); echo "</pre>";
            //debug($resEvents);debug($repEvents);die;

            $events = array_merge($resEvents,$repEvents);
        }

        //debug($events);die;

        $this->_result = $events;

    }

    public function saveEvent(){ 
        array_walk_recursive($this->request->data, array($this,'trimByReference') );
        $dati = $this->request->data;
    //debug($dati);die;
        $userId = $this->Auth->user('id');

        if(empty($dati['id_user'])  ){

            $dati['id_user'] = $userId;

        }elseif($dati['id_user'] != $userId && $this->Auth->user('role') !== 'admin'){

            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Permesso negato!");
            return;
        }
        //debug($dati);
        if(!empty($dati['tags'])){
          $dati['tags'] = explode(',', $dati['tags']);
          foreach($dati['tags'] as $key => $val){
             if(is_numeric($val)){
               $dati['tags'][$key] = ['id' => intval($val)];
             }else{
               $dati['tags'][$key] = ['name' => trim($val), 'level' => 0];
             }
          }
        }

        if(!empty($dati['EXDATE'])){
          $dati['EXDATE'] = explode(',', $dati['EXDATE']);
          foreach($dati['EXDATE'] as $key => $date){
            $dati['EXDATE'][$key] = date_format(date_create($date),'Y-m-d');
          }
        }else{
          $dati['EXDATE'] = array();
        }

        if($dati['id'] == ""){
            unset($dati['id']);
        }else{
          if($dati['repeated'] == 1){
            $this->_updateRepeatedEvent($dati);
            return;
          }
        }
        $dati['id_google'] = $this->_googleSaveEvent($dati);
        $dati['vobject'] = $this->Calendar->buildICalendar($dati);
        $dati['start'] = new Time($dati['start']);
        $dati['end'] = new Time($dati['end']);

        $event = $this->Calendar->_newEntity();

        $event = $this->Calendar->_patchEntity($event, $dati);
        //debug($event);die;
        //echo "<pre>"; print_r($event); echo "</pre>";
        $save = $this->Calendar->_save($event);
        if($dati['repeated'] == 1 && $save !== false){
            //$dati = $this->request->data;
            $dati['id'] = $save->id;
            $this->Calendar->buildRepeatingEvents($dati);
        }
        //echo "<pre>"; print_r($save); echo "</pre>";

        if ($save) {
            if($dati['id_user'] != $userId){
              TableRegistry::get('notifications')->notifyCalendarEvent($dati,$userId);
            }

            if(!empty($dati['id_timetask'])){
                $this->Calendar->updateTaskToTimetask($dati);
            }

            $this->_result = array('response' => 'OK', 'data' => array('id'=>$save->id), 'msg' => "Salvato");
        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
        }

    }

    public function deleteEvent($id = ""){

        if($id != ""){

            $event = $this->Calendar->_get($id);
            if($event->repeated == 1){

                if(!empty($this->request->data['data'])){
                  $event = $event->toArray();
                  $event['start'] = $this->request->data['data'];
                  $this->Calendar->deleteRepeatedEvent($event);
                  $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
                  return;
                }

              $repeatingEventsTable = TableRegistry::get('Calendar.RepeatedEvents');
              $repeatingEventsTable->deleteAll(['id_event' => $event->id ]);
            }

            if($this->Calendar->_delete($event)){

                if(!empty($event['id_google'])){
                    $userId = $this->Auth->user('id');
                    $user = array();
                    if($userId !== $event['id_user']){
                        $user = TableRegistry::get('Registration.Users')->get($dati['id_user'])->toArray();
                    }
                    $this->GoogleCalendar->accessUser($user);
                    $this->GoogleCalendar->deleteEvent($event);
                }

                $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
            }else{
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
            }

        }else{
            $this->_result = array('response' => 'KO', 'data' => 1, 'msg' => "Id evento mancante");
        }



    }

    public function export($userId='')
    {
      /*
      Questo metodo restituisce gli eventi da mostrare nel calendario in base alle date ricevute
      Non utilizza il formato standard di output dei dati poichè il plugin del calendario prevede un suo flusso dati in ingresso
      */

      //$this->autoRender = false;
      $events = array();

      if(empty($userId) || $this->Auth->user('role') != 'admin'){
          $userId = $this->Auth->user('id');
      }
      //echo "<pre>"; print_r($this->request->query); echo "</pre>";

      $events = TableRegistry::get('Calendar.Eventi');
      $opt['conditions']['Eventi.id_user'] = $userId;
      $events = $events->find('all')->where($opt['conditions'])->toArray();

      $this->response->body($this->Calendar->exportICalendar($events));
      $this->response->type('ical');

      // Optionally force file download
      $this->response->download('export.ical');

      // Return response object to prevent controller from trying to render
      // a view.
      return $this->response;


    }

    public function syncGoogle()
    {
        $query =  $this->request->query;
        //debug($query);die;
        try {
            if(isset($query['start']) && isset($query['end'])){

                $param = array();
                $param['timeMin'] = new \DateTime($query['start']);
                $param['timeMax'] = new \DateTime($query['end']);
                $param['timeMin'] = $param['timeMin']->format(\DateTime::RFC3339);
                $param['timeMax'] = $param['timeMax']->format(\DateTime::RFC3339);
                //$param['showDeleted'] = true;

                $userId = $this->Auth->user('id');
                $this->GoogleCalendar->accessUser();
                $toDelete = $this->GoogleCalendar->sync($param,$userId);
            }
            $this->_result = array('response' => 'OK', 'msg' => "Sincronizzazione avvenuta con successo!");

        } catch (\Exception $e) {
            $this->_result = array('response' => 'KO', 'msg' => "Errore durante la sincronizzazione, assicurati di aver collegato l'account google!");
        }

    }

    public function getTaskFromTimetask($taskNumber)
    {   
        $user = TableRegistry::get('Users')->get($this->request->session()->read('Auth.User.id'));
        $personid = json_decode($user->anagrafica_timetask)->id;

        $timetaskToken = $user->timetask_token;

        $http = new Client();

        $url = 'https://api.myintervals.com/task/';

        $response = $http->get(
            $url, 
            ['localid' => $taskNumber], 
            [
                'headers' => ['Authorization' => 'Basic '.base64_encode($timetaskToken.':X')],
                'type' => 'json'
            ]
        );

        $res = json_decode($response->body);

        if($res){
            if($res->code == 200){
                if($res->listcount > 0){
                    $timestamp = strtotime($res->task[0]->dateopen);
                    $res->task[0]->dateopen = date('d/m/Y', $timestamp);
    
                    $timestamp = strtotime($res->task[0]->datedue);
                    $res->task[0]->datedue = date('d/m/Y', $timestamp);

                    $res->task[0]->title = htmlspecialchars_decode($res->task[0]->title);
    
                    $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Dati del task recuperati con successo.");
                    
                }else{
                    $this->_result = array('response' => 'KO', 'msg' => "Nessun task trovato per questo numero.");
                }             
            }else{
                $this->_result = array('response' => 'KO', 'msg' => "Errore. Il numero di task inserito non è del formato corretto.");
            }
        }else{
            $this->_result = array('response' => 'KO', 'msg' => "Errore nella chiamata a timetask.");
        }
    }

    public function uploadFile()
    {
        $this->_result['response'] = "KO";
        $this->_result['data'] = -1;
        if(empty($this->request->data['uploadedfile'])){
          $this->_result['msg'] = "Non hai caricato nessun file.";
          return;
        }

        $file = $this->request->data['uploadedfile'];
        $type = finfo_file(finfo_open(FILEINFO_MIME_TYPE),$file['tmp_name']);
        $type = substr($type, 0, strpos($type, '/'));
        $arr_type = ['image','audio','video'];

        if(!in_array($type, $arr_type)){
          $this->_result['msg'] = "Formato del file non valido.";
          return;
	  	}

        $folderPath = DS.date('Y').DS.date('m');
        $uploadPath = ROOT.DS.'webroot'.DS.'files'.$folderPath;
        $fileName = uniqid().$file['name'];

        if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)){
          $this->_result['msg'] = "Errore durante salvataggio del file.";
          return;
        }

        if(!move_uploaded_file($file['tmp_name'],$uploadPath.DS.$fileName) ){
          $this->_result['msg'] = "Errore durante salvataggio del file.";
          return;
        }

        $this->_result['response'] = "OK";
        $this->_result['data'] = Router::url('/files'.$folderPath.DS.$fileName, true);
        $this->_result['msg'] = "Salvataggio avvenuto.";

    }

    public function eventDetail($id)
    {
        $evento = TableRegistry::get('Calendar.Eventi')->getDetails($id);
        $pass['start'] = $evento['start']->i18nFormat('yyyy-MM-dd HH:mm:ss');
        $pass['end'] = $evento['end']->i18nFormat('yyyy-MM-dd HH:mm:ss');
        $pass['users'] = $evento['operatori'];

        $contacts = TableRegistry::get('users')->find()->toArray();

		$details = $this->Calendar->getEventDetailForCalendarModal($id);

        $this->_result = array('response' => 'OK', 'data' => ['evento' => $evento,'contatti'=>$contacts, 'dettagli' => $details], 'msg' => "Evento Trovato.");
    }

    public function saveEventDetails(){

        $dati = $this->request->data;
        $userId = $this->Auth->user('id');

        //echo "<pre>"; print_r($dati); echo "</pre>";
  
        $eventsDetail = TableRegistry::get('Calendar.EventiDettaglio');  

        $error = false;

        if(!empty($dati['detail'])){
  
            foreach ($dati['detail'] as $detail) {
    
            if($dettaglio = $eventsDetail->get($detail['idEventDetail'])){
    
                $dettaglio->event_id = $detail['idEvento'];
                $dettaglio->operator_id = $detail['idOperatore'];
                $dettaglio->user_start = $detail['user_start_date'] . ' ' . $detail['user_start_time'];
                $dettaglio->user_end = $detail['user_stop_date'] . ' ' . $detail['user_stop_time'];
                $dettaglio->note_importanza = $detail['note_importanza'];
                $dettaglio->note = $detail['event_details_note'];
    
                if(!$eventsDetail->save($dettaglio)){
                    $error = true;
                }
    
            }else{
    
                $newDetail = $eventsDetail->newEntity();
    
                $newDetail->event_id = $detail['idEvento'];
                $newDetail->operator_id = $detail['idOperatore'];
                $newDetail->user_start = $detail['user_start_date'] . ' ' . $detail['user_start_time'];
                $newDetail->user_end = $detail['user_stop_date'] . ' ' . $detail['user_stop_time'];
                $newDetail->note_importanza = $detail['note_importanza'];
                $newDetail->note = $detail['event_details_note'];
    
                if(!$eventsDetail->save($newDetail)){
                    $error = true;
                }
            }
    
            }
        }
  
        if(!$error){
          $this->_result = array('response' => 'OK', 'data' => '', 'msg' => "Salvato");
        }else{
          $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
        }
  
    }

    public function sendTimeTimetask()
    {
        $data = $this->request->data;

        //calcolo durata evento in ore (con decimali)
        $start = new Date($data['start_date'].' '.$data['start_time']);
        $stop = new Date($data['stop_date'].' '.$data['stop_time']);
        $diff = $stop->diff($start);
        $time = round(round($diff->s / 3600 + $diff->i / 60 + $diff->h + $diff->days * 24, 3) * 8) / 8;

        $user = TableRegistry::get('Users')->get($data['id_user']);
        $personid = json_decode($user->anagrafica_timetask)->id;

        $timetaskToken = $user->timetask_token;

        $http = new Client();

        if(!$data['id_time_timetask']){

            $url = 'https://api.myintervals.com/time/';

            $dataPost = [
                'taskid' => $data['id_task'],
                'worktypeid' => '286938', //sviluppo
                'personid' => $personid,
                'date' => $data['start_date'],
                'time' => $time,
                'billable' => true
            ];

            $response = $http->post(
                $url, 
                json_encode($dataPost), 
                [
                    'headers' => ['Authorization' => 'Basic '.base64_encode($timetaskToken.':X')],
                    'type' => 'json'
                ]
            );

            $res = json_decode($response->body);

            if($res){ 
                if($res->code == 201){
                    $events = TableRegistry::get('Calendar.Eventi');

                    $entity = $events->get($data['id_event']);
                    $entity->id_time_timetask = $res->time->id;

                    $events->save($entity);

                    if(!empty($data['note'])){

                        $url = 'https://api.myintervals.com/tasknote/';

                        $dataPost = [
                            'taskid' => $data['id_task'],
                            'note' => $data['note'],
                            'public' => 'f'
                        ];

                        $response = $http->post(
                            $url, 
                            json_encode($dataPost), 
                            [
                                'headers' => ['Authorization' => 'Basic '.base64_encode($timetaskToken.':X')],
                                'type' => 'json'
                            ]
                        );

                        $resComment = json_decode($response->body);

                        if($resComment){ 
                            if($resComment->code == 201){
                                $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Tempo inviato correttamente.");    
                            }else{
                                $this->_result = array('response' => 'KO', 'msg' => "Errore. Dati per l'inserimento del commento non corretti o mancanti.");
                            }
                        }else{
                            $this->_result = array('response' => 'KO', 'msg' => "Errore nella chiamata a timetask per l'inserimento del commento.");
                        }        
                    }else{
                        $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Tempo inviato correttamente.");
                    }
                }else{
                    $this->_result = array('response' => 'KO', 'msg' => "Errore. Dati per l'inserimento del tempo non corretti o mancanti.");
                }
            }else{
                $this->_result = array('response' => 'KO', 'msg' => "Errore nella chiamata a timetask per l'inserimento del tempo.");
            }

        }else{ 
            $url = 'https://api.myintervals.com/time/'.$data['id_time_timetask'];

            $dataPost = [
                'personid' => $personid,
                'date' => $data['start_date'],
                'time' => $time
            ];

            $response = $http->put(
                $url, 
                json_encode($dataPost),
                [
                    'headers' => ['Authorization' => 'Basic '.base64_encode($timetaskToken.':X')],
                    'type' => 'json'
                ]
            );

            $res = json_decode($response->body);

            if($res){ 
                if($res->code == 202){
                    $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => "Tempo inviato correttamente.");            
                }else{
                    $this->_result = array('response' => 'KO', 'msg' => "Errore. Dati per l'inserimento del tempo non corretti o mancanti.");
                }
            }else{
                $this->_result = array('response' => 'KO', 'msg' => "Errore nella chiamata a timetask per l'inserimento del tempo.");
            }
        }
    }


    private function _updateRepeatedEvent($dati)
    {

      $save = '';

      switch ($dati['repeatedToModify']) {

        case 'allEvents':
          $dati = $this->Calendar->updateTimeRepeated($dati);
          $dati['vobject'] = $this->Calendar->buildICalendar($dati);
          $dati['id_google'] = $this->_googleSaveEvent($dati);
          $dati['start'] = new Time($dati['start']);
          $dati['end'] = new Time($dati['end']);

          $event = $this->Calendar->_newEntity();

          $event = $this->Calendar->_patchEntity($event, $dati);

          $save = $this->Calendar->_save($event);
          if($save !== false){
              $dati['id'] = $save->id;
              $this->Calendar->buildRepeatingEvents($dati);
          }
          break;

        case 'futureEvents':
          $res = $this->Calendar->deleteRepeatedEventsFuture($dati);
          if($res['result']){
            $dati['id_parentEvent'] = $dati['id'];
            unset($dati['id'],$dati['id_google']);
            $dati['COUNT'] = $dati['COUNT'] - ($dati['COUNT']-$res['deleted']);
            $dati['vobject'] = $this->Calendar->buildICalendar($dati);
            $dati['id_google'] = $this->_googleSaveEvent($dati);
            $dati['start'] = new Time($dati['start']);
            $dati['end'] = new Time($dati['end']);

            $event = $this->Calendar->_newEntity();

            $event = $this->Calendar->_patchEntity($event, $dati);
            //debug($event);die;
            //echo "<pre>"; print_r($event); echo "</pre>";
            $save = $this->Calendar->_save($event);
            if($save !== false){
                //$dati = $this->request->data;
                $dati['id'] = $save->id;
                $this->Calendar->buildRepeatingEvents($dati);
            }
          }
          break;

        case 'thisEvent':
          if($this->Calendar->deleteRepeatedEvent($dati)){
            $dati['id_parentEvent'] = $dati['id'];
            unset($dati['id']);
            $dati['repeated'] = 0;
            $dati['vobject'] = $this->Calendar->buildICalendar($dati);
            $dati['id_google'] = $this->_googleSaveEvent($dati);
            $dati['start'] = new Time($dati['start']);
            $dati['end'] = new Time($dati['end']);

            $event = $this->Calendar->_newEntity();

            $event = $this->Calendar->_patchEntity($event, $dati);
            //debug($event);die;
            //echo "<pre>"; print_r($event); echo "</pre>";
            $save = $this->Calendar->_save($event);
          }else{
            $save = false;
          }
          break;
      }

      if ($save) {
          $this->_result = array('response' => 'OK', 'data' => array('id'=>$save->id), 'msg' => "Salvato");
      }else{
          $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
      }

    }

    private function _googleSaveEvent($dati)
    {
        $userId = $this->Auth->user('id');
        $id_google = '';
        // In caso di cambio id_user dell'evento elimino il precedente su google calendar
        if(!empty($dati['id'])){
            $oldEvent = $this->Calendar->_get($dati['id']);
            if(!empty($oldEvent) && $oldEvent['id_user'] != $dati['id_user']){
                $user = TableRegistry::get('Registration.Users')->get($oldEvent['id_user'])->toArray();
                if($this->GoogleCalendar->accessUser($user)){
                    $this->GoogleCalendar->deleteEvent($oldEvent['id_google']);
                }
                unset($dati['id_google']);
            }
        }
        // Salvo o aggiorno l'evento su google calendar se possibile e se non è un evento ripetuto
        if ( $dati['id_user'] == $userId ) {
            if($this->GoogleCalendar->accessUser()){
                $id_google = $this->GoogleCalendar->saveEvent($dati);
            }
        }else{
            $user = TableRegistry::get('Registration.Users')->get($dati['id_user'])->toArray();
            if(!empty($user)){
                if($this->GoogleCalendar->accessUser($user)){
                    $id_google = $this->GoogleCalendar->saveEvent($dati);
                }
            }
        }

        return $id_google;

    }


}
