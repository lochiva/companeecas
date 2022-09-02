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
        if(
            $user['role'] == 'admin' || 
            $user['role'] == 'area_iv' || 
            $user['role'] == 'ragioneria' || 
            $user['role'] == 'ente_ospiti' ||
            $user['role'] == 'ente_contabile'
        ){
            return true;
        }
        
        return false;
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
