<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;


class HomeController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $user = $this->Auth->user();

        if(isset($user['role']) && ($user['role'] == 'centro' || $user['role'] == 'nodo')){
            $this->Auth->allow(['index', 'checkPathFiles']);
        }
    }

    public function index()
    {
      $user = $this->Auth->user();
      if($user['role'] == 'nodo'){
        $provincia = TableRegistry::get('Aziende.Contatti')->getProvinciaContattoByUser($user['id']);

        $provincia = ucfirst(strtolower($provincia));

        $this->set('provincia', $provincia);
      }
      /*
        $this->loadComponent('Aziende.Azienda');
        $this->loadComponent('Aziende.Order');
        $this->loadComponent('Aziende.Contatti');
        $this->loadComponent('Aziende.Fornitori');
        $this->loadComponent('Document.Document');
        $this->loadComponent('Scadenzario.Scadenzario');
        $this->loadComponent('Crm.Offers');

        $tot = array(
          'aziende' => $this->Azienda->getTotAziende(),
          'contatti' => $this->Contatti->getTotContatti(),
          'ordini' => $this->Order->getTotOrders(),
          'documenti' => $this->Document->getTotDocuments(),
        );
        $odersChart = $this->Order->getOrdersChart(15);
        $ruoliChart = $this->Contatti->getContattiChart();
        $scadenze = $this->Scadenzario->getScadenzarioHome();
        $accessi  = TableRegistry::get('AccessLog')->getAccessHistory('login');
        $movimenti = TableRegistry::get('ActionLog')->getHistoryGeneral();
        $fatturePassive = $this->Fornitori->getFattureFornitoriChart(15);
        $valoreOfferte = $this->Offers->getValoreOfferteChart(15);
        $valoreOfferte2 = $this->Offers->getValoreOfferteChart2(15);
        $causaliFatture = $this->Fornitori->getFattureChartPerCausale();

        //echo "<pre>"; print_r($causaliFatture); echo "</pre>";
        //echo "<pre>"; print_r($fatturePassive); echo "</pre>";
        //echo "<pre>"; print_r($ruoliChart); echo "</pre>";
        //echo "<pre>"; print_r($odersChart); echo "</pre>"; //die();
        //echo "<pre>"; print_r($valoreOfferte2); echo "</pre>"; die();
        //debug($accessi);die;
        
        //$kpiTable = TableRegistry::get('Crediti.Kpi');
        //$crediti = $kpiTable->getIndicatoreCrediti();
        //foreach($crediti as &$val){
          //$val['giorno'] = $val['giorno']->i18nFormat('dd/MM/yyyy');
          //$val['valore'] = round($val['valore']);
        //}

        //$this->set('crediti',$crediti);
    

        //debug($scadenze);die;
        $this->set('tot',$tot);
        $this->set('ordersChart',json_encode($odersChart));
        $this->set('ruoliChart',json_encode($ruoliChart));
        $this->set('scadenze',$scadenze);
        $this->set('accessi',$accessi);
        $this->set('movimenti',$movimenti);
        $this->set('fatturePassive',json_encode($fatturePassive));
        $this->set('valoreOfferte',json_encode($valoreOfferte));
        $this->set('valoreOfferte2',json_encode($valoreOfferte2));
        $this->set('causaliFatture',json_encode($causaliFatture));
        */
    }

    /*public function index2()
    {

    }*/

    public function checkPathFiles()
    {
      $this->layout='login';
      $path = Configure::read('dbconfig.PATH_FILES');
      if(is_readable($path)){
          if(is_writable($path)){
            $this->Flash->success(__('Il path: "'.$path.'" ha permessi di SCRITTURA e LETTURA.'));
          }else{
            $this->Flash->success(__('Il path: "'.$path.'" ha permessi di LETTURA.'));
            $this->Flash->error(__('Il path: "'.$path.'" non ha permessi di SCRITTURA.'));
          }
      }else{
        $this->Flash->error(__('Il path: "'.$path.'" non ha permessi di LETTURA.'));
      }

    }

}
