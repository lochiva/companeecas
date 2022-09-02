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
        $exitRequestStatuses = TableRegistry::get('Aziende.GuestsExitRequestStatuses')->find()->toArray();

        $this->set('sede', $sede);
        $this->set('azienda', $azienda);
        $this->set('statuses', $statuses);
        $this->set('exitRequestStatuses', $exitRequestStatuses);
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

        //Se la sede è chiusa non posso aggiungere un ospite
        $guestId = $this->request->query('guest');
        if (empty($guestId) && $sede['operativita'] == 0) {
            $this->Flash->error('La struttura è chiusa pertanto non è possibile aggiungere ospiti.');
            $this->redirect('/aziende/guests/index/'.$sedeId);
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
