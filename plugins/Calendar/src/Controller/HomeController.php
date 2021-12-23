<?php
namespace Calendar\Controller;

use Calendar\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Core\Configure;

/**
 * Home Controller
 *
 * @property \Calendar\Model\Table\HomeTable $Home
 */
class HomeController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        /*$this->loadComponent('Aziende.Azienda');
        $this->loadComponent('Aziende.Sedi');
        $this->loadComponent('Aziende.Contatti');*/
        $this->loadComponent('Csrf');
        $this->loadComponent('Calendar.Calendar');

        $this->set('title', 'Calendario');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['index']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
      $users = TableRegistry::get('Users')->find('all')->toArray();
      $eventColors = array_merge(Configure::read('calendarConfig.GoogleColors'),Configure::read('calendarConfig.Colors'));

      $this->set('authUser',$this->Auth->user());
      $this->set('users',$users);
      $this->set('eventColors',$eventColors);

    }

    public function importCalendar($user_id=0)
    {


        if(empty($this->request->data['uploadedfile'])){
          $this->Flash->error(__('Non hai caricato nessun file.'));
          return $this->redirect($this->referer());
        }

        $file = $this->request->data['uploadedfile'];
        $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
        $arr_ext = array('ical', 'ics', 'ifb','icalendar');

        if(!in_array($ext, $arr_ext)){
          $this->Flash->error(__('Formato del file errato.'));
          return $this->redirect($this->referer());
        }

        if(empty($user_id)  ){

            $user_id =  $this->Auth->user('id');

        }elseif($this->Auth->user('id') != $user_id && $this->Auth->user('role') !== 'admin'){

            $this->Flash->error(__('Permesso negato.'));
            return $this->redirect($this->referer());
        }

        try {
          $calendar = file_get_contents($_FILES['uploadedfile']['tmp_name']);
          $toSave = $this->Calendar->parseICalendar($calendar,$user_id);
          if(!empty($toSave) && is_array($toSave)){
            foreach($toSave as $dati){

              $dati['vobject'] = $this->Calendar->buildICalendar($dati);
              $dati['start'] = new Time($dati['start']);
              $dati['end'] = new Time($dati['end']);
              $event = $this->Calendar->_newEntity();

              $event = $this->Calendar->_patchEntity($event, $dati);

              $save = $this->Calendar->_save($event);
              if($dati['repeated'] == 1 && $save !== false){

                  $dati['id'] = $save->id;
                  $this->Calendar->buildRepeatingEvents($dati);
              }

            }
          }

          $this->Flash->success(__('Calendario importato correttamente.'));
          return $this->redirect($this->referer());

        } catch (\Exception $e) {

          $this->Flash->error(__('Errore durante il parsing del file, assicurati che non abbia errori.'));
          return $this->redirect($this->referer());

        }


    }

}
