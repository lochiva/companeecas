<?php
namespace Aziende\Controller;

use Aziende\Controller\AppController;
use Cake\ORM\TableRegistry;

/**
 * Guests Controller
 */
class GuestsController extends AppController
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
    public function index($sedeId)
    {
        $user = $this->request->session()->read('Auth.User');
        $sede = TableRegistry::get('Aziende.Sedi')->get($sedeId, ['contain' => ['Comuni', 'Province']]);

        if(!$this->Azienda->verifyUser($user, $sede['id_azienda'])){
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return null;
        }

        $azienda = TableRegistry::get('Aziende.Aziende')->get($sede['id_azienda']);
        $statuses = TableRegistry::get('Aziende.GuestsStatuses')->find()->toArray();

        $this->set('sede', $sede);
        $this->set('azienda', $azienda);
        $this->set('statuses', $statuses);
    }

    public function guest()
    {
        $sedeId = $this->request->query('sede');
        $user = $this->request->session()->read('Auth.User');
        $sede = TableRegistry::get('Aziende.Sedi')->get($sedeId, ['contain' => ['Comuni', 'Province']]);

        if(!$this->Azienda->verifyUser($user, $sede['id_azienda'])){
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return null;
        }

        $azienda = TableRegistry::get('Aziende.Aziende')->get($sede['id_azienda']);

        $this->set('sede', $sede);
        $this->set('azienda', $azienda);
    }

    public function notifications($enteType = 1)
    {
        $this->set('enteType', $enteType);
    }

}
