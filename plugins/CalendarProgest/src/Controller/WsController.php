<?php
namespace Calendar\Controller;

use Calendar\Controller\AppController;
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
        $this->loadComponent('Calendar.Calendar');
        $this->loadComponent('Calendar.GoogleCalendar');
        $this->loadComponent('Calendar.Pianificazione');
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
     * In progest viene usato il id_contatto al posto dell'id_user per il caricamento degli eventi.
     * @return json
     */
    public function getCalendarEvents($type = 1,$userId = '')
    {

        /*
        Questo metodo restituisce gli eventi da mostrare nel calendario in base alle date ricevute
        Non utilizza il formato standard di output dei dati poichè il plugin del calendario prevede un suo flusso dati in ingresso
        */

        //$this->autoRender = false;
        $events = array();

        if(empty($userId) || $this->Auth->user('role') !== 'admin'){
            $contact = $this->request->session()->read('User.Contact');
            if(!empty($contact)){
              $userId = $contact['id'];
            }else{
              $userId = '';
            }

        }
        //echo "<pre>"; print_r($this->request->query); echo "</pre>";

        if(isset($this->request->query['start']) && isset($this->request->query['end'])){

            $star = $this->request->query['start'];
            $end = $this->request->query['end'];/*$timeMin = new \DateTime($star);$timeMax = new \DateTime($end);
            $gog = $this->GoogleCalendar->readEvents(['showDeleted' => false ,'timeMin' => $timeMin->format('c'),'timeMax' =>$timeMax->format('c') ])->getItems();
            debug($gog[2]->id);*/
            $resEvents = $this->Calendar->getEventsByDate($star,$end,$type,$userId);
            $repEvents = $this->Calendar->getRepeatedEventsByDate($star,$end,$type,$userId);

            //echo "<pre>"; print_r($resEvents); echo "</pre>";
            //debug($resEvents);debug($repEvents);die;

            $events = array_merge($resEvents,$repEvents);
        }

        //debug($events);die;

        $this->_result = $events;

    }

    public function getCalendarEventsFrozen($type = 1,$userId = '')
    {
        /*
        Questo metodo restituisce gli eventi da mostrare nel calendario in base alle date ricevute
        Non utilizza il formato standard di output dei dati poichè il plugin del calendario prevede un suo flusso dati in ingresso
        */
        $events = array();

        if(empty($userId) || $this->Auth->user('role') !== 'admin'){
            $contact = $this->request->session()->read('User.Contact');
            if(!empty($contact)){
              $userId = $contact['id'];
            }else{
              $userId = '';
            }
        }

        if(isset($this->request->query['start']) && isset($this->request->query['end'])){

            $star = $this->request->query['start'];
            $end = $this->request->query['end'];
            $events = $this->Calendar->getEventsByDate($star,$end,$type,$userId,true);
        }

        $this->_result = $events;

    }

    public function saveEvent(){
        array_walk_recursive($this->request->data, array($this,'trimByReference') );
        $dati = $this->request->data;
        //echo "<pre>"; print_r($dati); echo "</pre>"; exit;
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
        //$dati['id_google'] = $this->_googleSaveEvent($dati);
        $dati['vobject'] = $this->Calendar->buildICalendar($dati);
        $dati['start'] = new Time($dati['start']);
        $dati['end'] = new Time($dati['end']);
        $check = $this->Pianificazione->checkPeriodOperatore($dati);
        if($check !== true){
            $this->_result = array('response' => 'KO', 'data' => -1,
              'msg' => "ERRORE SALVATAGGIO\nI seguenti operatori non sono liberi durante il periodo selezinato:\n".$check);
            return;
        }
        $event = $this->Calendar->_newEntity();

        $event = $this->Calendar->_patchEntity($event, $dati);

        $save = $this->Calendar->_save($event);
        if($dati['repeated'] == 1 && $save !== false){
            //$dati = $this->request->data;
            $dati['id'] = $save->id;
            $this->Calendar->buildRepeatingEvents($dati);
        }

        if ($save) {
            if($dati['id_user'] != $userId){
              TableRegistry::get('notifications')->notifyCalendarEvent($dati,$userId);
            }
            $this->_result = array('response' => 'OK', 'data' => array('id'=>$save->id), 'msg' => "Salvato");
        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
        }

    }

	public function saveEventDetails(){

        $dati = $this->request->data;
        $userId = $this->Auth->user('id');

        if(empty($dati['id_user'])){

            $dati['id_user'] = $userId;

        }elseif($dati['id_user'] != $userId && $this->Auth->user('role') !== 'admin'){

            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Permesso negato!");
            return;
        }

		$eventsDetail = TableRegistry::get('Calendar.EventiDettaglio');
		$eventDetailActivities = TableRegistry::get('Calendar.EventiDettaglioAttivita');
		if($dati['id'] != 'undefined' && $dati['id'] != '' && $dati['id'] != null){
			$eventModify = $eventsDetail->get($dati['id']);

			$eventModify->user_start = $dati['userStart'];
			$eventModify->user_end = $dati['userStop'];
			$eventModify->note = $dati['eventNote'];
			$eventModify->note_importanza = $dati['eventNoteImportanza'];

			if(isset($dati['arrayCheckboxes'])){
				foreach(json_decode($dati['arrayCheckboxes']) as $id_activity => $array){

					$activity = $eventDetailActivities->find()->where(['id_activity' => $id_activity, 'id_event_detail' => $dati['id']])->toArray();

					if($activity){
						$id_record = $activity[0]['id'];
						$activityEntity = $eventDetailActivities->get($id_record);
						$eventDetailActivities->delete($activityEntity);
					}

					if($array[0] === true){
						$activity = $eventDetailActivities->newEntity();
						$activity->id_event_detail = $dati['id'];
						$activity->id_activity = $id_activity;
						$activity->note = $array[1];
						$eventDetailActivities->save($activity);
					}
				}
			}

			$save = $eventsDetail->save($eventModify);

			if($save){
	            $this->_result = array('response' => 'OK', 'data' => '', 'msg' => "Salvato");
	        }else{
	            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
	        }

		}else{
			$eventNew = $eventsDetail->newEntity();

			$eventNew->event_id = $dati['id_event'];
			$eventNew->operator_id = $dati['id_operatore'];
			$eventNew->user_start = $dati['userStart'];
			$eventNew->user_end = $dati['userStop'];
			$eventNew->note = $dati['eventNote'];
			$eventNew->note_importanza = $dati['eventNoteImportanza'];

			$save = $eventsDetail->save($eventNew);

			$eventN = $eventsDetail->find()->where(['event_id' => $dati['id_event']])->toArray();

			if(isset($dati['arrayCheckboxes'])){
				foreach(json_decode($dati['arrayCheckboxes']) as $id_activity => $array){

					$activity = $eventDetailActivities->find()->where(['id_activity' => $id_activity, 'id_event_detail' => $dati['id']])->toArray();

					if($activity){
						$id_record = $activity[0]['id'];
						$activityEntity = $eventDetailActivities->get($id_record);
						$eventDetailActivities->delete($activityEntity);
					}

					if($array[0] === true){
						$activity = $eventDetailActivities->newEntity();
						$activity->id_event_detail = $eventN[0]['id'];
						$activity->id_activity = $id_activity;
						$activity->note = $array[1];
						$eventDetailActivities->save($activity);
					}
				}
			}

			if($save){
				$this->_result = array('response' => 'OK', 'data' => '', 'msg' => "Salvato");
			}else{
				$this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
			}

		}
    }

    public function deleteEvent($id = "",$userViewId = 0){

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

            if($this->Calendar->_delete($event,$userViewId)){

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

    public function export($type = 1,$userId='')
    {
      /*
      IN PROGEST MI ARRIVA l'id_contatto

      Questo metodo restituisce gli eventi da mostrare nel calendario in base alle date ricevute
      Non utilizza il formato standard di output dei dati poichè il plugin del calendario prevede un suo flusso dati in ingresso
      */
      $events = array();

      if(empty($userId) || $this->Auth->user('role') != 'admin'){
          $contact = $this->request->session()->read('User.Contact');
          if(!empty($contact)){
            $userId = $contact['id'];
          }else{
            $userId = '';
          }
          $type = 1;
      }
      //echo "<pre>"; print_r($this->request->query); echo "</pre>";
      $start = $this->request->query['start'];
      $end = $this->request->query['end'];
      /*$events = TableRegistry::get('Calendar.Eventi');
      $opt['conditions']['Eventi.id_contatto'] = $userId;*/
      $events = $this->Calendar->getEventsByDate($start,$end,$type,$userId);

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

    public function eventDetail($id,$frozen = false)
    {
        $this->loadComponent('Progest.Service');
        if($frozen == 'true'){
            $className = 'Calendar.EventiFrozen';
        }else{
            $className = 'Calendar.Eventi';
        }
        $evento = TableRegistry::get('Calendar.Eventi',['className'=>$className])->getDetails($id);
        $pass['start'] = $evento['start']->i18nFormat('yyyy-MM-dd HH:mm:ss');
        $pass['end'] = $evento['end']->i18nFormat('yyyy-MM-dd HH:mm:ss');
        $pass['users'] = $evento['operatori'];
        $contacts = $this->Service->contactsForService($evento->id_service,$pass);
        //$found = false;
        /*foreach ($contacts as $contatto) {
            if($contatto->id == $evento->id_contatto){
              $found = true;
              break;
            }
        }
        if(!$found){
          $contacts[] = ['id' => $evento->contatto->id,
            'text' => $evento->contatto->cognome.' '.$evento->contatto->nome];
        }*/

		$details = $this->Calendar->getEventDetailForCalendarModal($id);

		$activitiesTable = TableRegistry::get('Progest.Activities');
		$activities = $activitiesTable->find()->where(['id_service' => $evento->id_service])->toArray();

		if(isset($details['id'])){

			$eventDetailsActivities = TableRegistry::get('Calendar.EventiDettaglioAttivita');
			$peopleExtension = TableRegistry::get('Progest.PeopleExtension');
			$personExtension = $peopleExtension->find()->where(['id_person' => $evento->ordine->persona->id])->toArray();

			$details['address_lat'] = $personExtension[0]['address_lat'];
			$details['address_long'] = $personExtension[0]['address_long'];

			$detailActivities = $eventDetailsActivities->find()->where(['id_event_detail' => $details['id']])->toArray();

			foreach($activities as $activity){
				$activity['checked_activity'] = false;
				$activity['note'] = '';
				foreach($detailActivities as $detailActivity){
					if($activity['id'] == $detailActivity['id_activity']){
						$activity['checked_activity'] = true;
						$activity['note'] = $detailActivity['note'];
					}
				}
			}
		}else{
			foreach($activities as $activity){
				$activity['checked_activity'] = false;
				$activity['note'] = '';
			}
		}

        $this->_result = array('response' => 'OK', 'data' => ['evento' => $evento,'contatti'=>$contacts, 'dettagli' => $details, 'attivita' => $activities], 'msg' => "Evento Trovato.");
    }

    public function frozeCaldendar()
    {
        if($this->Auth->user('role') != 'admin'){
            $this->_result = array('response' => 'KO', 'msg' => "Non possiedi le autorizzazioni necessarie!");
            return;
        }
        if(isset($this->request->query['start']) && isset($this->request->query['end'])){

            $start = $this->request->query['start'];
            $end = $this->request->query['end'];

            if($this->Pianificazione->checkFrozeWeek($start,$end)){
                $this->_result = array('response' => 'KO', 'msg' => "La settimana è già stata congelata!");
                return;
            }

            if(!$this->Pianificazione->frozeCalendar($start,$end)){
                $this->_result = array('response' => 'KO', 'msg' => "Errore Server!");
                return;
            }
        }

        $this->_result = array('response' => 'OK', 'msg' => "Congelamento avvenuto con successo!");
    }

    public function cloneEvents($giorni, $opId = 0)
    {
        if($this->Auth->user('role') != 'admin'){
            $this->_result = array('response' => 'KO', 'msg' => "Non possiedi le autorizzazioni necessarie!");
            return;
        }
        $data = $this->request->query;

		$addTime= '';
		switch($giorni){
			case '7':
				$addTime = '+1 week';
        $oneclone=1;
				break;
			case '14':
				$addTime = '+2 weeks';
        $oneclone=0;
				break;
			default:
				$addTime = '+1 week';
        $oneclone=1;
		}

        $operatori = $this->Pianificazione->getOperatori($opId);
        foreach ($operatori as $id => $operatore) {
            $events = $this->Pianificazione->getEventsOperatoreForClone($id,$data['start'],$data['end'],$oneclone);
            $this->Pianificazione->cloneEvents($events, $addTime);
        }

        $this->_result = array('response' => 'OK', 'data' => '', 'msg' => "Clonazione avvenuta con successo");
    }

    public function check()
    {
        if($this->Auth->user('role') != 'admin'){
            $this->_result = array('response' => 'KO', 'msg' => "Non possiedi le autorizzazioni necessarie!");
            return;
        }
        $start = $this->request->query['start'];
        $end = $this->request->query['end'];
        $toRet = $this->Pianificazione->checkEvents($start,$end);

        $this->_result = array('response' => 'OK', 'data' => $toRet, 'msg' => "Check eseguito");

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

	public function setIgnora(){

		$id_order = $this->request['data']['id_order'];
		$ignora = $this->request['data']['ignora'];
		$note = $this->request['data']['note'];

		if($this->Auth->user('role') == 'admin'){
			if(isset($id_order) && $id_order !== '' && isset($ignora) && $ignora !== ''){
				$ordersTable = TableRegistry::get('Orders');
				$order = $ordersTable->get($id_order);
				$order->ignora_controllo = $ignora;
				$order->ignora_note = $note;

				if($ordersTable->save($order)){
					$this->_result = array('response' => 'OK', 'message' => "Ignora controllo impostato.");
				}else{
					$this->_result = array('response' => 'KO', 'message' => "ERRORE. Ignora controllo non impostato.");
				}
			}else{
				$this->_result = array('response' => 'KO', 'message' => "ERRORE. id_order o parametro ignora non trovati.");
			}
		}else{
			$this->_result = array('response' => 'KO', 'message' => "ERRORE. L\'utente deve essere admin");
		}

	}



}
