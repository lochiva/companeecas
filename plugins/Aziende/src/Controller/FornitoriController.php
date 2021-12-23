<?php
namespace Aziende\Controller;

use Aziende\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

/**
 * Fornitori Controller
 *
 * @property \Aziende\Model\Table\AziendeTable $Aziende
 */
class FornitoriController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        //$this->loadComponent('Document.Document');
        $this->loadComponent('Aziende.Fornitori');
        $this->loadComponent('Aziende.Sedi');
        $this->loadComponent('Aziende.Azienda');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['index']);
    }

    public function fatture($id = 0)
    {

        $purposes = $this->Fornitori->getInvoicePurposesTree();
        $paymentConditions = $this->Fornitori->getPaymentConditions();
        $payers = $this->Azienda->getAziendeInterne();
        $metodi = json_decode(Configure::read('dbconfig.aziende.FATTUREINCLOUD_METHODS'), true);

        if(!empty($id) && $id != 'all'){
            $fornitore = TableRegistry::get('Aziende.Aziende')->get($id);
            $this->set('fornitore',$fornitore);
        }


        $this->set('purposesPassive',$purposes);
        $this->set('paymentConditions',$paymentConditions);
        $this->set('idFornitore',$id);
        $this->set('payers',$payers);
        $this->set('lista_metodi', $metodi['lista_conti']);
        //debug($purposes);die;
    }

    public function getAttachment($year,$month,$fileName)
    {
      if(!empty($fileName)){
          $this->response->file('files'.DS.$year.DS.$month.DS.$fileName);
      }

      return $this->response;

    }
}
