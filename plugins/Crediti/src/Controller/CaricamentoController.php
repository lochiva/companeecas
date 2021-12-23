<?php
namespace Crediti\Controller;

use Crediti\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

/**
 * Caricamento Controller
 *
 * @author Rafael Esposito
 * @property \Crediti\Model\Table\CreditsTable $Credits
 */
class CaricamentoController extends AppController
{
 /**
  * Metodo initialize
  *
  * Richiama il metodo padre e carica il CsvHandlerComponent.
  *
  * @return void
  */
  public function initialize()
   {
       parent::initialize();
       $this->loadComponent('Crediti.CsvHandler');
   }

  /**
   * Metodo beforeFilter
   *
   * Controllo se l'utente è admin, in caso negativo lo riporto alla home.
   *
   * @param Event $event Object Event
   * @return void
   */
   public function beforeFilter(Event $event)
   {
       parent::beforeFilter($event);
       //$this->Auth->allow(['index','info']);

       $user = $this->request->session()->read('Auth.User');

       if($user['role']!='admin'){
           $this->redirect('/');
       }
   }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {

    }
   /**
    * Metodo add
    *
    * In caso la richiesta sia in post invoco il metodo checkCsv del CsvHandlerComponent
    * per il controllo del file csv. Se è stato selezionato save-file invoco saveCsv,
    * metodo che salva i dati nella tabella credits. Infine se è selezionato
    * save-credits_totals consolido i crediti con il metodo saveCreditsTotals nella
    * tabella creadits_totals. Infine setto le variabili del view.
    *
    * @return void
    */
    public function add()
    {

      $action = 'verify';

      $result = '';
      if ($this->request->is('post')) {

          $res = $this->CsvHandler->checkCsv($this->request->data);
          
          if($this->request->data['save-file'] && $res != false){

            $result = $this->CsvHandler->saveCsv($res['data'],$this->request->data['prefix']);
            $action = 'save';
            if($this->request->data['save-credits_totals']){

              $date = date( 'Y-m-d',strtotime(str_replace("/","-",$this->request->data['date'])) );
              $this->CsvHandler->saveCreditsTotals($date,$this->request->data['prefix'] );
              $action = 'totals';

            }
          }
      }

      $this->set('action',$action);
      $this->set('result',$result);

      if($res != false){
        $this->set('errors',$res['errors']);
        $this->set('data',$res['data']);
      }else{
        $this->Flash->error(__('Errore, lettura del file non riuscito!'));
      }

    }
    /**
     * Report method
     *
     * @return void
     */
    public function report()
    {

    }


}
