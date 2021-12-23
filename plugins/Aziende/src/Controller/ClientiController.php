<?php
namespace Aziende\Controller;

use Aziende\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

/**
 * Clienti Controller
 *
 * @property \Aziende\Model\Table\AziendeTable $Aziende
 */
class ClientiController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Aziende.Clienti');
        $this->loadComponent('Aziende.Sedi');
        $this->loadComponent('Aziende.Azienda');
        $this->loadComponent('Aziende.Contatti');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $user = $this->Auth->user();

        if(isset($user['role']) && $user['role'] == 'companee_admin'){
            $this->Auth->allow(['datiAziendali']);
        }
    }

    public function fatture($id = 0)
    {
        $purposes = $this->Clienti->getInvoicePurposesTree();
        $paymentConditions = $this->Clienti->getPaymentConditions();
        $issuers = $this->Azienda->getAziendeinterne();
        $metodi = json_decode(Configure::read('dbconfig.aziende.FATTUREINCLOUD_METHODS'), true);
        $listaIva = json_decode(Configure::read('dbconfig.aziende.FATTUREINCLOUD_IVA'), true);

        if(!empty($id) && $id != 'all'){
            $cliente = TableRegistry::get('Aziende.Aziende')->get($id);
            $this->set('cliente',$cliente);
        }

        $this->set('purposesActive',$purposes);
        $this->set('paymentConditions',$paymentConditions);
        $this->set('idCliente',$id);
        $this->set('issuers',$issuers);
        $this->set('lista_metodi', $metodi['lista_conti']);
        $this->set('lista_iva', $listaIva['lista_iva']);
    }

    public function getAttachment($year,$month,$fileName)
    {
      if(!empty($fileName)){
          $this->response->file('files'.DS.$year.DS.$month.DS.$fileName);
      }

      return $this->response;
    }


    public function datiAziendali()
    {
        $userId = $this->request->session()->read('Auth.User.id');
        $contact = TableRegistry::get('Aziende.Contatti')->find()->where(['id_user' => $userId])->first();

        if($contact){

            $sediTipi = $this->Sedi->getSediTipi();
            $ruoli = $this->Contatti->getRuoli();
            $skills = TableRegistry::get('Aziende.Skills')->getList();

            $this->set('sediTipi',$sediTipi);
            $this->set('aziendaId', $contact->id_azienda);

        }else{
            $this->Flash->error('Pagina non trovata. Contattare '.Configure::read('dbconfig.registration.SENDER_EMAIL').'.');
            $this->redirect('/');
        }
    }
}
