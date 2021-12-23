<?php
namespace Calendar\Controller;

use Calendar\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Core\Configure;
/**
 * Stamp Controller
 *
 */
class StampeController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        /*$this->loadComponent('Aziende.Azienda');
        $this->loadComponent('Aziende.Sedi');
        $this->loadComponent('Aziende.Contatti');*/
        $this->loadComponent('Csrf');
        $this->loadComponent('Calendar.Calendar');
        $this->loadComponent('Calendar.Stampe');

        $this->set('title', 'Calendario');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->viewBuilder()->layout('Calendar.stampe');
    }

    public function operatori()
    {
        $data = $this->request->data;
        $datiStampa = array();

        if(!empty($data['operatore']) && is_array($data['operatore'])){
          $operatori = $this->Stampe->getListOperatori($data['operatore']);
          foreach ($operatori as $id => $operatore) {
              $datiStampa[$operatore] = $this->Stampe->getEventsOperatore($id, $data['start'], $data['end']);
          }
        }
        if(!empty($data['select_all'])){
          $datiStampa = array_filter($datiStampa);
        }
        // l'end mi arriva considerando un intervallo aperto, quindi ne sottraggo un giorno
        $dal = date('d/m/Y',strtotime($data['start']));
        $al = date('d/m/Y',(strtotime($data['end'])-60*60*24));//debug($datiStampa);die;

        $this->set('datiStampa', $datiStampa);
        $this->set('dal',$dal);
        $this->set('al', $al);
    }

    public function persone()
    {
        $data = $this->request->data;
        $datiStampa = array();

        if(!empty($data['persona']) && is_array($data['persona'])){
          $persone = $this->Stampe->getListPersone($data['persona']);

          foreach ($persone as $id => $persona) {
              $datiStampa[$persona] = $this->Stampe->getEventsPersona($id, $data['start'], $data['end']);
          }
        }
        if(!empty($data['select_all'])){
          $datiStampa = array_filter($datiStampa);
        }
        // l'end mi arriva considerando un intervallo aperto, quindi ne sottraggo un giorno
        $dal = date('d/m/Y',strtotime($data['start']));
        $al = date('d/m/Y',(strtotime($data['end'])-60*60*24));

        $this->set('datiStampa', $datiStampa);
        $this->set('dal',$dal);
        $this->set('al', $al);
    }

    public function monteOre()
    {
        $data = $this->request->data;
        $datiStampa = array();

        $operatori = $this->Stampe->getListOperatoriMonteOre($data['skills']);
        foreach ($operatori as $id => $operatore) {
            $datiStampa[$operatore] = $this->Stampe->getMonteOreOperatore($id, $data['start'], $data['end']);
        }
        $totale = $this->Stampe->formatTotalMonteOre($datiStampa);
        // l'end mi arriva considerando un intervallo aperto , quindi ne sottraggo un giorno
        $dal = date('d/m/Y',strtotime($data['start']));
        $al = date('d/m/Y',(strtotime($data['end'])-60*60*24));

        $this->set('datiStampa', $datiStampa);
        $this->set('dal',$dal);
        $this->set('al', $al);
        $this->set('counter', 1);
        $this->set('totale', $totale);
    }

}
