<?php
namespace Aziende\Controller;

use Aziende\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * Aziende Controller
 *
 * @property \Aziende\Model\Table\AziendeTable $Aziende
 */
class SediController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        //$this->loadComponent('Document.Document');
        $this->loadComponent('Aziende.Azienda');
        $this->loadComponent('Aziende.Sedi');
    }

    public function isAuthorized($user)
    {
        if($user['role'] == 'admin' || $user['role'] == 'ente'){
            return true;
        }else{
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return true;
        }
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
    public function index($idAzienda = 0)
    {
        $user = $this->request->session()->read('Auth.User');
        
        if(!$this->Azienda->verifyUser($user, $idAzienda)){
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return null;
        }

        //Carico i dati dell'azienda se mi è stata passata
        $azienda = array();
        if($idAzienda != 0){
            $azienda = $this->Azienda->_get($idAzienda);
        }

        //Recupero i tipi di sedi
        $sediTipi = $this->Sedi->getSediTipi();
        
        $province = TableRegistry::get('Luoghi')->getProvince();

        $tipologieCentro = TableRegistry::get('Aziende.SediTipologieCentro')->getList();
        $tipologieOspiti = TableRegistry::get('Aziende.SediTipologieOspiti')->getList();
        $procedureAffidamento = TableRegistry::get('Aziende.SediProcedureAffidamento')->getList();

        $this->set('idAzienda',$idAzienda);
        $this->set('azienda',$azienda);
        $this->set('sediTipi',$sediTipi);
        $this->set('province',$province);
        $this->set('tipologieCentro',$tipologieCentro);
        $this->set('tipologieOspiti',$tipologieOspiti);
        $this->set('procedureAffidamento',$procedureAffidamento);
    }

}
