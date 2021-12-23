<?php

namespace Calendar\Controller\Component;

use Cake\Controller\Component;
use Cake\I18n\Time;
use Cake\Log\Log;
use Cake\Core\Configure;

class GoogleCalendarComponent extends Component
{
    /**
    * List of components to include.
    *
    * @var array
    */
   public $components = array('Google', 'Calendar');

   /**
    * Google calendar service object.
    *
    * @var Google_Service_Calendar
    */
   public $service;

   /**
    * Array of google colors, and the default calendar color
    * @var array
    */
   public $colors;

   /**
    * [initialize description].
    *
    * @param  array  $config [description]
    *
    * @return [type]         [description]
    */
   public function initialize(array $config)
   {

   }

   /**
    * [accessUser description]
    * @param  string $user [description]
    * @return bool
    */
   public function accessUser($user = array())
   {
       try {
           $this->Google->accessUser($user);
           $this->service = new \Google_Service_Calendar($this->Google->client());
           return true;
       } catch (\Exception $e) {
           Log::error('Errore connessione utente: '.$e->getMessage(),'google');
           $this->service = false;
           return false;
       }

   }

  /**
   * getColors
   * @return array [description]
   */
   public function getColors()
   {
       try {
           $this->colors = Configure::read('calendarConfig.GoogleColors');
           $this->colors[null] = $this->service->calendarList->get('primary')->backgroundColor;
       } catch (\Exception $e) {
           Log::error('Errore lettura colori eventi e calednario: '.$e->getMessage(),'google');
           $this->colors = array();
       }

       return $this->colors;
   }

    /**
     * [calendarService description].
     *
     * @return [type] [description]
     */
    public function service()
    {
        return $this->service;
    }

    /**
     * Covnert the data to a google calendar event and try to save it through
     * the google api. If fail or the user doesn't have a google token return
     * void string.
     *
     * @param array  $dati       array of the event data
     * @param string $calendarId [description]
     *
     * @return string id dell'evento google o vuoto
     */
    public function saveEvent($dati, $calendarId = 'primary')
    {
        if ($this->service != false) {
            try {
                $startDateTime = new \DateTime($dati['start']);
                $endDateTime = new \DateTime($dati['end']);
                $recurrence = array();

                if ($dati['allDay']) {
                    $start = ['date' => $startDateTime->format('Y-m-d')];
                    $end = ['date' => $endDateTime->format('Y-m-d')];
                } else {
                    $start = ['dateTime' => $startDateTime->format(\DateTime::RFC3339)];
                    $end = ['dateTime' => $endDateTime->format(\DateTime::RFC3339)];
                    if($dati['repeated']){
                      $start['timeZone'] = $startDateTime->format('e');
                      $end['timeZone'] = $endDateTime->format('e');
                    }
                }

                if ($dati['repeated']) {
                    $recurrence[] = 'RRULE:'.$this->Calendar->buildReapetingRule($dati);
                }
                if (!empty($dati['EXDATE'])) {
                    $exDate = 'EXDATE;VALUE=DATE:';
                    $first = true;
                    foreach ($dati['EXDATE'] as $data) {
                        if (!$first) {
                            $exDate .= ',';
                        }
                        $exDate .= str_replace('-', '', $data);
                        $first = false;
                    }
                    $recurrence[] = $exDate;
                }
                $colorKey = array_search($dati['backgroundColor'], Configure::read('calendarConfig.GoogleColors'));
                $event = new \Google_Service_Calendar_Event(array(
                    'summary' => $dati['title'],
                    'description' => $dati['note'],
                    'start' => $start,
                    'end' => $end,
                    'recurrence' => $recurrence,
                    'colorId' => ( $colorKey !== false ? $colorKey : null )
                  ));

                if (!empty($dati['id_google'])) {
                    $event->id = $dati['id_google'];
                    $event = $this->service->events->update($calendarId, $event->id, $event);
                } else {
                    $event = $this->service->events->insert($calendarId, $event);
                }

                return $event->id;
            } catch (\Exception $e) {
                Log::error('Errore inserimento evento: '.$e->getMessage(),'google');
                Log::error($dati,'google');
                return '';
            }
        } else {
            return '';
        }
    }

    public function deleteEvent($event,$calendarId = 'primary')
    {
        try {
          if(!empty($event['id_google'])){
              $this->service->events->delete($calendarId, $event['id_google']);
          }else{
              $this->service->events->delete($calendarId, $event);
          }

        } catch (\Exception $e) {
            Log::error('Errore cancellazione evento: '.$e->getMessage(),'google');
            Log::error($event,'google');
            return false;
        }
        return true;
    }

    public function readEvents(array $query = array(),$calendarId = 'primary')
    {

        if($this->service !== false){
            return $this->service->events->listEvents($calendarId,$query)->getItems();
        }else{
            return false;
        }

    }

    public function sync($query,$userId)
    {
        try {
            $events = $this->readEvents($query);
        } catch (\Exception $e) {
            Log::error("Errore durante la lettura dei eventi utente $userId: ".$e->getMessage(),'google');
            Log::error($query,'google');
            throw new \Exception("Errore lettura eventi" );
            return false;
        }


        if($events === false){
            throw new \Exception("Errore nella comunicazione" );
            return false;
        }

        $googleColors = $this->getColors();
        $toDelete = array();
        foreach($events as $event){

            if($event->status != 'cancelled'){
                $toSave = true;
                $rrule = '';
                $start = $event->getStart();
                $end = $event->getEnd();
                $localEvent = $this->Calendar->_getByGoogleId($event->id);

                $dati = [
                  'start' => (!empty($start['date']) ? $start['date'] : $start['dateTime'] ),
                  'end' => (!empty($end['date']) ? $end['date'] : $end['dateTime'] ),
                  'id_user' => $userId,
                  'title' => (!empty($event->summary) ? $event->summary : ""),
                  'note' => (!empty($event->description) ? $event->description : ""),
                  'allDay' => (!empty($start['date']) ?  1 : 0 ),
                  'repeated' => 0,
                  'id_google' => $event->id
                ];
                if(!empty($event->recurrence)){
                  $rrule = array_shift($event->recurrence);
                  $rrule = $this->Calendar->parseRRule($rrule);
                  $dati = array_merge($dati,$rrule);
                  $dati['repeated'] = 1;
                }

                if(!empty($localEvent)){
                  if($dati['repeated']){
                    $toSave = false;
                  }
                  $dati = array_merge($localEvent->toArray(),$dati);
                  if(!empty($googleColors[$event->colorId])){
                      $color = $googleColors[$event->colorId];
                      $dati['backgroundColor'] = $color;
                      $dati['borderColor'] = $color;
                  }
                }else{

                  if(!empty($googleColors[$event->colorId])){
                      $color = $googleColors[$event->colorId];
                  }else{
                      $color = $this->Calendar->rand_color();
                  }
                  $dati['backgroundColor'] = $color;
                  $dati['borderColor'] = $color;
                }

                $dati['vobject'] = $this->Calendar->buildICalendar($dati);
                $dati['start'] = new Time($dati['start']);
                $dati['end'] = new Time($dati['end']);
                if($toSave){
                    $event = $this->Calendar->_newEntity();

                    $event = $this->Calendar->_patchEntity($event, $dati);

                    $save = $this->Calendar->_save($event);
                    if(!empty($dati['repeated']) && $save !== false){

                        $dati['id'] = $save->id;
                        $this->Calendar->buildRepeatingEvents($dati);
                    }
                }

            }else if($event->status == 'cancelled'){
                $toDelete[] = $event->id;
            }

        }
        return $toDelete;
    }
}
