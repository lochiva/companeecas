<?php
namespace Calendar\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Sabre\VObject;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

class CalendarComponent extends Component
{

    public function getEventsByDate($start = "", $end = "", $type=1, $contactId = 0, $frozen = false){

        if($frozen){
            $events = TableRegistry::get('Calendar.Eventi',['className'=>'Calendar.EventiFrozen']);
        }else{
            $events = TableRegistry::get('Calendar.Eventi');
        }
        $opt['contain'] = array();
        $opt['fields'] = array();

        switch ($type) {
          case 1:
            $opt['conditions']['AND']['OR']['Eventi.id_contatto'] = $contactId;
            $opt['conditions']['AND']['OR']['UsersGrouping.id_user'] = $contactId;
            $opt['contain'] = ['Groups'=>['UsersGrouping','Operatori']];
            $opt['group'] = 'Eventi.id';
            break;
          case 2:
            $opt['conditions']['Orders.id_person'] = $contactId;
            $opt['contain'] = ['Groups'=>['UsersGrouping','Operatori']];
            $opt['group'] = 'Eventi.id';
            break;
        }
        $opt['conditions']['Eventi.start >= '] = $start;
        $opt['conditions']['Eventi.start <= '] = $end;
        $opt['conditions']['Eventi.repeated'] = 0;
        $opt['contain'] = array_merge(['Aziende' => function ($q) {
            return $q->select(['denominazione']);
        }, 'Tags' => function ($q) {
            return $q->select(['name','id']);
        }, 'Orders' => function($q){
            return $q->select(['name']);
        }, 'Contatti' => function($q){
            return $q->select(['operatore' => 'CONCAT(Contatti.cognome,SPACE(1),Contatti.nome)']);
        }],$opt['contain']);
        $opt['order'] = "Eventi.start ASC";

        $query = $events->getEvents($opt);

        return $query;

    }

    public function getRepeatedEventsByDate($start = "", $end = "", $type=1, $contactId = 0){

        $events = TableRegistry::get('Calendar.RepeatedEvents');

        switch ($type) {
          case 1:
            $opt['conditions']['Eventi.id_contatto'] = $contactId;
            break;
          case 2:
            $opt['conditions']['Orders.id_person'] = $contactId;
            break;
        }
        $opt['contain'] = ['Eventi' => ['Aziende','Orders','Contatti']];
        $opt['conditions']['RepeatedEvents.start >= '] = $start;
        $opt['conditions']['RepeatedEvents.start <= '] = $end;
        $opt['conditions']['Eventi.id_contatto'] = $contactId;
        $opt['order'] = "RepeatedEvents.start ASC";

        $query = $events->getEvents($opt);
        $max = count($query);
        for($i = 0; $i<$max; $i++){
          $query[$i]['tags'] = $this->queryForTags($query[$i]['id']);
        }


        return $query;

    }


    public function _newEntity(){
        $event = TableRegistry::get('Calendar.Eventi');
        return $event->newEntity();
    }

    public function _patchEntity($doc,$request){
        $event = TableRegistry::get('Calendar.Eventi');
        return $event->patchEntity($doc,$request);
    }

    /**
     * Nel caso di una compresenza creo un gruppo e salvo gli utenti associati al gruppo
     * secondo quelli che mi arrivano e dall'evento tolgo id_contatto e ci salvo il id_group.
     * Se si tratta di un update, quindi può esserci già un gruppo, lo cancello e continuo
     * il salvataggio come se fosse nuovo, cioè ricreo il gruppo.
     *
     * @param  object $event entity dell'evento
     * @return mixed         false o l'entity dell'evento salvato
     */
    public function _save($event){
        $eventTable = TableRegistry::get('Calendar.Eventi');
        $groupsTable = TableRegistry::get('Groups');
        if(!empty($event->id_group)){
            TableRegistry::get('UsersToGroups')->deleteAll(['id_group'=>$event->id_group]);
            TableRegistry::get('Groups')->deleteAll(['id'=>$event->id_group]);
            $event->id_group = 0;
        }

        if(!empty($event['operatore'])){
            $group = [
                'name' => 'compresenza',
                'note' => '',
                'UsersToGroups' => []
            ];

            $group['UsersToGroups'][0]['id_user'] = $event->id_contatto;
            $event->id_contatto = 0;
            foreach($event['operatore'] as $operatore){
                $group['UsersToGroups'][] = ['id_user' => $operatore];
            }

            $group = $groupsTable->newEntity($group);
            $group = $groupsTable->save($group);
            $event->id_group = $group->id;
        }
        return $eventTable->save($event);
    }

    public function _get($id){
        $event = TableRegistry::get('Calendar.Eventi');
        return $event->get($id);

    }

    public function _getByGoogleId($id){
        $event = TableRegistry::get('Calendar.Eventi');
        return $event->find()->where(['id_google' => $id])->first();

    }

    /**
     * Nel caso di un evento in compresenza, ovvero un evento che ha un gruppo, cancello
     * da esso l'operatore che sto visualizzando. Se il gruppo si svuota cancello anch'esso
     * e l'evento.
     *
     * @param  obeject  $event      entity dell'evento
     * @param  integer $userViewId utente di cui si sta visualizzando il calendario
     * @return boolean
     */
    public function _delete($event,$userViewId = 0){
        $eventTable = TableRegistry::get('Calendar.Eventi');
        if($event->cloned_from !== 0){
            TableRegistry::get('Calendar.EventiFrozen')->updateAll(['cloned'=>0],['id'=>$event->cloned_from ]);
        }
        if($event->id_group !== 0){

            if($userViewId == 0){
                TableRegistry::get('UsersToGroups')->deleteAll(['id_group'=>$event->id_group]);
                TableRegistry::get('Groups')->deleteAll(['id'=>$event->id_group]);
                return $eventTable->delete($event);
            }

            TableRegistry::get('UsersToGroups')->deleteAll(['id_user'=>$userViewId,'id_group'=>$event->id_group]);
            if(TableRegistry::get('UsersToGroups')->find()->where(['id_group'=>$event->id_group])->count() == 0){
                TableRegistry::get('Groups')->deleteAll(['id'=>$event->id_group]);
                return $eventTable->delete($event);
            }else{
                return true;
            }
        }
        return $eventTable->delete($event);
    }

    public function buildICalendar($dati)
    {
        $vcalendar = new VObject\Component\VCalendar([
            'VEVENT' => [
                'SUMMARY' => $dati['title'],
                'DTSTART' => new \DateTime($dati['start']),
                'DESCRIPTION' => (empty($dati['note']) ? '' : $dati['note'] ),
            ]
        ]);
        if($dati['allDay'] != 1){
          $vcalendar->VEVENT->DTEND = new \DateTime($dati['end']);
        }else{
          $vcalendar->VEVENT->DTSTART['VALUE'] = 'DATE';
        }
        if($dati['repeated'] == 1){
          $vcalendar->VEVENT->RRULE = $this->buildReapetingRule($dati);
          if(!empty($dati['EXDATE']) && is_array($dati['EXDATE'])){
            foreach($dati['EXDATE'] as $ex){
                $vcalendar->VEVENT->add('EXDATE',str_replace('-','',$ex) , ['VALUE' => 'DATE']);
            }

          }
        }

        return $vcalendar->serialize();
    }

    public function exportICalendar($events)
    {
        $vcalendar = new VObject\Component\VCalendar();

        foreach ($events as $key => $event) {

          $event = VObject\Reader::read($event['vobject'])->VEVENT;
          $vcalendar->add($event);

        }

        return $vcalendar->serialize();
    }

    public function buildRepeatingEvents($dati)
    {

      $repeatingEventsTable = TableRegistry::get('Calendar.RepeatedEvents');
      $repeatingEventsTable->deleteAll(['id_event' => $dati['id'] ]);
      $maxDate = Configure::read('dbconfig.calendar.MAX_REPEATED');
      $maxCount = Configure::read('dbconfig.calendar.MAX_COUNT');

      $dati['start'] = $dati['start']->i18nFormat('yyyy-MM-dd HH:mm:ss');
      $dati['end'] = $dati['end']->i18nFormat('yyyy-MM-dd HH:mm:ss');

      $repeatingEvents = array();

      switch($dati['repeatedEndType']) {
        case 'COUNT':
          if($dati['COUNT'] > $maxCount){
            $dati['COUNT'] = $maxCount;
          }
          $dati['UNTIL'] = '';
          break;
        case 'UNTIL':
          if($dati['UNTIL'] > $maxDate){
            $dati['UNTIL'] = $maxDate;
          }
          $dati['COUNT'] = '';
          break;
        default:
          $dati['COUNT'] = '';
          $dati['UNTIL'] = $maxDate;
          break;
      }

      $count = 0;
      $i = 0;
      $start = new \DateTime($dati['start']);
      $end = new \DateTime($dati['end']);

      while (($dati['start'] < $dati['UNTIL'].' 24' || $dati['COUNT'] != '' ) && ($count < $dati['COUNT'] || $dati['UNTIL'] != '' )   ) {

        if($i%$dati['INTERVAL'] == 0 ){

          if(!in_array(substr($dati['start'],0,10),$dati['EXDATE']) ){
              $repeatingEvents[] = array('id_event' => $dati['id'],'start'=>new Time($dati['start']),'end'=>new Time($dati['end']));
          }
          $count++;
          //$eventsPassed--;

        }
        $i++;

        switch ($dati['FREQ']) {
          case 'DAILY':
            $date_calculation = " +1 day";
            break;
          case 'WEEKLY':
            $date_calculation = " +1 week";
            break;

          case 'MONTHLY':
            $date_calculation = " +1 month";
            break;
          case 'YEARLY':
            $date_calculation = " +1 year";
            break;
          default:
            $date_calculation = " +1 week";
            break;
        }
        $start->modify($date_calculation);
        $end->modify($date_calculation);
        $dati['start'] = $start->format('Y-m-d H:i:s');
        $dati['end'] = $end->format('Y-m-d H:i:s');


      }

      $entities = $repeatingEventsTable->newEntities($repeatingEvents);
      return $repeatingEventsTable->saveMany($entities);


    }

    public function buildReapetingRule($dati)
    {
        $rule = 'FREQ='.$dati['FREQ'].';';
        switch ($dati['FREQ']) {
          case 'DAILY':

            break;
          case 'WEEKLY':
            $day = new \DateTime($dati['start']);
            $rule .= 'BYDAY='. strtoupper(substr($day->format('D'), 0, -1)).';';
            break;

          case 'MONTHLY':
            $day = new \DateTime($dati['start']);
            $rule .= 'BYMONTHDAY='. $day->format('j').';';
            break;
          case 'YEARLY':
            $day = new \DateTime($dati['start']);
            $rule .= 'BYMONTH='. $day->format('n').';';
            $rule .= 'BYMONTHDAY='. $day->format('j').';';
            break;
        }

        $rule .= 'INTERVAL='.$dati['INTERVAL'];

        switch($dati['repeatedEndType']) {
          case 'COUNT':
            $rule .= ';COUNT='.$dati['COUNT'];
            break;
          case 'UNTIL':
            $until = new \DateTime($dati['UNTIL']);
            $rule .= ';UNTIL='.$until->format('Ymd\THis').'Z';
            break;
        }


        return $rule;
    }

    public function updateTimeRepeated($dati)
    {
        $event = $this->_get($dati['id']);
        $start = new \DateTime($dati['start']);
        $end = new \DateTime($dati['end']);

        $event['start'] = $event['start']->i18nFormat('yyyy-MM-dd ').$start->format('H:i:s');
        $event['start'] = new \DateTime($event['start']);

        $diff = $start->diff($end);

        $dati['start'] = $event['start']->format('Y-m-d H:i:s');
        $dati['end'] = $event['start']->add($diff)->format('Y-m-d H:i:s');

        return $dati;
    }

    public function deleteRepeatedEvent($dati)
    {
      $dataToNotUnset = ['id','vobject'];
      $repeatingEventsTable = TableRegistry::get('Calendar.RepeatedEvents');

      $start = new \DateTime($dati['start']);
      $start = $start->format('Y-m-d');
      $repeatingEventsTable->deleteAll(['id_event' => $dati['id'], 'start LIKE' =>'%'.$start.'%' ]);
      $dati['EXDATE'][] = $start;

      if(!empty($dati['vobject'])){
          $vcalendar =  VObject\Reader::read($dati['vobject']);
          foreach($dati['EXDATE'] as $ex){
              $vcalendar->VEVENT->add('EXDATE',str_replace('-','',$ex) , ['VALUE' => 'DATE']);
          }
          $dati['vobject'] = $vcalendar->serialize();
      }else{
        $dati['vobject'] = $this->buildICalendar($dati);
      }

      foreach($dati as $key => $data){
          if(!in_array($key, $dataToNotUnset)){
            unset($dati[$key]);
          }
      }

      $event = $this->_newEntity();

      $event = $this->_patchEntity($event, $dati);

      $save = $this->_save($event);


      return $save;

    }

    public function deleteRepeatedEventsFuture($dati)
    {
        $dataToNotUnset = ['id','vobject'];
        $repeatingEventsTable = TableRegistry::get('Calendar.RepeatedEvents');

        $start = new \DateTime($dati['start']);
        $deletedCount = $repeatingEventsTable->deleteAll(['id_event' => $dati['id'], 'start >=' =>$start->format('Y-m-d').' 00:00:00' ]);

        $dati['UNTIL'] = $start->modify(' -1 day')->format('Y-m-d');
        $dati['repeatedEndType'] = 'UNTIL';
        $dati['vobject'] = $this->buildICalendar($dati);
        foreach($dati as $key => $data){
            if(!in_array($key, $dataToNotUnset)){
              unset($dati[$key]);
            }
        }

        $event = $this->_newEntity();

        $event = $this->_patchEntity($event, $dati);

        $save = $this->_save($event);


        return array('result' => $save,'deleted' => $deletedCount);


    }

    public function parseICalendar($calendar,$id_user)
    {
        $vcalendar = VObject\Reader::read($calendar);

        $toRet = array();
        foreach($vcalendar->VEVENT as $vevent) {

            $rrule = (empty($vevent->RRULE) ? '' :$vevent->RRULE->serialize() );
            $rrule = $this->parseRRule($rrule);
            $allDay = 0;
            $start = $vevent->DTSTART->getDateTime()->format('Y-m-d H:i:s');

            if(!empty($vevent->DTEND)){
              $end = $vevent->DTEND->getDateTime();
              if($start == $end->format('Y-m-d H:i:s') || $end->modify(' -1 day')->format('Y-m-d H:i:s') == $start ){
                $allDay = 1;
              }
              $end = $end->format('Y-m-d H:i:s');

            }else{
              $allDay = 1;
              $end = $vevent->DTSTART->getDateTime()->format('Y-m-d H:i:s');
            }

            if(!empty($vevent->EXDATE)){
              foreach($vevent->EXDATE as $date){
                  $rrule['EXDATE'][] = $date->getDateTime()->format('Y-m-d');
              }
            }
            $color = $this->rand_color();

            $toRet[] = array_merge(array(
              'start' =>  $start,
              'title' => (empty($vevent->SUMMARY) ? '' :$vevent->SUMMARY->getValue() ) ,
              'note' => (empty($vevent->DESCRIPTION) ? '' :$vevent->DESCRIPTION->getValue() ),
              'end' =>  $end,
              'repeated' => (empty($vevent->RRULE) ? 0 : 1),
              'id_user' => $id_user,
              'allDay' => $allDay,
              'backgroundColor' => $color,
              'borderColor' => $color
            ), $rrule);
        }
        return $toRet;

    }

    public function parseRRule($rrule)
    {
        $toRet = ['FREQ' => 'WEEKLY', 'INTERVAL' => 1, 'COUNT' => '', 'UNTIL' => '','EXDATE' => array()];

        preg_match('/FREQ=([^;]{4,7})/m',$rrule,$out);
        if(!empty($out[1])){
          $toRet['FREQ'] = trim($out[1]);
        }
        preg_match('/INTERVAL=([^;\n]{1,10})/m',$rrule,$out);
        if(!empty($out[1])){
          $toRet['INTERVAL'] = trim($out[1]);
        }
        preg_match('/COUNT=([^;\n]{1,10})/m',$rrule,$out);
        if(!empty($out[1])){
          $toRet['COUNT'] = trim($out[1]);
        }
        preg_match('/UNTIL=([^;\n]{1,20})/m',$rrule,$out);
        if(!empty($out[1])){
          $toRet['UNTIL'] = new \DateTime(trim($out[1]));
          $toRet['UNTIL'] = $toRet['UNTIL']->format('Y-m-d');
        }


        if($toRet['COUNT'] !== ''){
          $toRet['repeatedEndType'] = 'COUNT';
        }else if ($toRet['UNTIL'] !== '') {
          $toRet['repeatedEndType'] = 'UNTIL';
        }else{
          $toRet['repeatedEndType']  = 'NEVER';
        }
        return $toRet;

    }

    public function rand_color()
    {
      $randColors = array_merge(Configure::read('calendarConfig.GoogleColors'),Configure::read('calendarConfig.Colors'));

      $val = mt_rand(0, (count($randColors)-1) );

      return $randColors[$val];
    }

    public function queryForTags($id)
    {
      $conn = ConnectionManager::get('default');
      return $conn->execute("SELECT `tags`.`id` as id, `tags`.`name` as name
        FROM `calendar_events_to_tags` JOIN `tags` ON `calendar_events_to_tags`.`id_tag` = `tags`.`id`
        WHERE `calendar_events_to_tags`.`id_event` = :id ",['id'=>$id])->fetchAll('assoc');

    }

	public function getEventDetailForCalendarModal($id){

		$eventsDetails = TableRegistry::get('Calendar.EventiDettaglio');
		$details = $eventsDetails->getEventDetailsById($id)->toArray();

		if(isset($details[0]['user_start']) && $details[0]['user_start'] != ''){
				$details[0]['user_start_date'] = date_format($details[0]['user_start'], 'Y-m-d');
				$details[0]['user_start_time'] = date_format($details[0]['user_start'], 'H:i');
		}

		if(isset($details[0]['real_start']) && $details[0]['real_start'] != ''){
				$details[0]['real_start'] = date_format($details[0]['real_start'], 'Y-m-d H:i:s');;
		}else{
				$details[0]['real_start'] = 'Non disponibile';
		}

		if(isset($details[0]['user_end']) && $details[0]['user_end'] != ''){
				$details[0]['user_end_date'] = date_format($details[0]['user_end'], 'Y-m-d');
				$details[0]['user_end_time'] = date_format($details[0]['user_end'], 'H:i');
		}

		if(isset($details[0]['real_end']) && $details[0]['real_end'] != ''){
				$details[0]['real_end'] = date_format($details[0]['real_end'], 'Y-m-d H:i:s');
		}else{
				$details[0]['real_end'] = 'Non disponibile';
		}

		if(!isset($details[0]['start_lat']) || $details[0]['start_lat'] == ''){
				$details[0]['start_lat'] = 'Non disponibile';
		}

		if(!isset($details[0]['start_long']) || $details[0]['start_long'] == ''){
				$details[0]['start_long'] = 'Non disponibile';
		}

		if(!isset($details[0]['stop_lat']) || $details[0]['stop_lat'] == ''){
				$details[0]['stop_lat'] = 'Non disponibile';
		}

		if(!isset($details[0]['stop_long']) || $details[0]['stop_long'] == ''){
				$details[0]['stop_long'] = 'Non disponibile';
		}

		if(!isset($details[0]['note']) || $details[0]['note'] == null){
				$details[0]['note'] = '';
		}

		if(!isset($details[0]['signature']) || $details[0]['signature'] == null || $details[0]['signature'] == ''){
				$details[0]['signature'] = 'Non disponibile';
		}


		return $details[0];
	}


}
