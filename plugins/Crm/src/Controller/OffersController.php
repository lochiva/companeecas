<?php
namespace Crm\Controller;

use Crm\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * Ws Offers Controller
 *
 * @property \Scadenzario\Model\Table\ScadenzarioTable $Scadenzario
 */
class OffersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('Crm.Offers');
        $this->loadComponent('Aziende.Azienda');

        $this->set('title', 'offerte');
    }

    public function index($idAzienda = 0)
    {
        $statusList = $this->Offers->getStatusList();
        $aziendeList = $this->Azienda->getAziendeInterne();

        if($idAzienda > 0){
            $azienda = $this->Azienda->_get($idAzienda);
        }else{
            $azienda['denominazione'] = '';
        }

        $this->set('statusList',$statusList);
        $this->set('aziendeList',$aziendeList);
        $this->set('idAzienda',$idAzienda);
        $this->set('nomeAzienda', $azienda['denominazione']);
    }

    public function getAttachment($year,$month,$fileName)
    {
      if(!empty($fileName)){
          $this->response->file('files'.DS.$year.DS.$month.DS.$fileName);
      }

      return $this->response;

    }


}
