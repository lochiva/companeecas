<?php
namespace Progest\Controller;

use Progest\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * Aziende Controller
 *
 * @property \Aziende\Model\Table\AziendeTable $Aziende
 */
class HomeController extends AppController
{

    public function initialize()
    {
        parent::initialize();
    }

    public function index()
    {
        $this->loadComponent('Aziende.Azienda');
        $this->loadComponent('Aziende.Order');
        $this->loadComponent('Aziende.Contatti');
        $this->loadComponent('Aziende.Fornitori');
        $this->loadComponent('Document.Document');
        $this->loadComponent('Scadenzario.Scadenzario');
        $tot = array(
          'aziende' => $this->Azienda->getTotAziende(),
          'contatti' => $this->Contatti->getTotContatti(),
          'ordini' => $this->Order->getTotOrders(),
          'documenti' => $this->Document->getTotDocuments(),
        );
        $odersChart = $this->Order->getOrdersChart();
        $ruoliChart = $this->Contatti->getContattiChart();
        $scadenze = $this->Scadenzario->getScadenzarioHome();
        $accessi  = TableRegistry::get('AccessLog')->getAccessHistory('login');
        $movimenti = TableRegistry::get('ActionLog')->getHistoryGeneral();
        $fatturePassive = $this->Fornitori->getFattureFornitoriChart();

        $this->set('tot',$tot);
        $this->set('ordersChart',$odersChart);
        $this->set('ruoliChart',$ruoliChart);
        $this->set('scadenze',$scadenze);
        $this->set('accessi',$accessi);
        $this->set('movimenti',$movimenti);
        $this->set('fatturePassive',json_encode($fatturePassive));

    }
}
