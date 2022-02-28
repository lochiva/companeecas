<?php
namespace Aziende\Controller;

use Aziende\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Agreements Controller
 */
class AgreementsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Aziende.Azienda');
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

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($aziendaId)
    {
        $azienda = TableRegistry::get('Aziende.Aziende')->get($aziendaId);
        $sedi = TableRegistry::get('Aziende.Sedi')->find()->where(['id_azienda' => $aziendaId])->contain('Comuni')->toArray();
        $procedureAffidamento = TableRegistry::get('Aziende.SediProcedureAffidamento')->getList();

        $this->set('azienda', $azienda);
        $this->set('sedi', $sedi);
        $this->set('procedureAffidamento',$procedureAffidamento);
    }

}
