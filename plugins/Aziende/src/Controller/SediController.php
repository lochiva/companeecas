<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Sedi (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
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
        if(
            $user['role'] == 'admin' || 
            $user['role'] == 'area_iv' || 
            $user['role'] == 'ragioneria' || 
            $user['role'] == 'ragioneria_adm'||
            $user['role'] == 'questura' || 
            $user['role'] == 'ente_ospiti' ||
            $user['role'] == 'ente_contabile'
        ){
            return true;
        }
        
        return false;
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
        $sediTipiMinistero = $this->Sedi->getSediTipiMinistero();
        $sediTipiCapitolato = $this->Sedi->getSediTipiCapitolato();
        
        $province = TableRegistry::get('Luoghi')->getEnabledProvinces();

        $tipologieCentro = TableRegistry::get('Aziende.SediTipologieCentro')->getList();
        $tipologieOspiti = TableRegistry::get('Aziende.SediTipologieOspiti')->getList();
        $procedureAffidamento = TableRegistry::get('Aziende.SediProcedureAffidamento')->getList();

        $this->set('idAzienda',$idAzienda);
        $this->set('azienda',$azienda);
        $this->set('sediTipiMinistero',$sediTipiMinistero);
        $this->set('sediTipiCapitolato',$sediTipiCapitolato);
        $this->set('province',$province);
        $this->set('tipologieCentro',$tipologieCentro);
        $this->set('tipologieOspiti',$tipologieOspiti);
        $this->set('procedureAffidamento',$procedureAffidamento);
    }

    public function presenze()
    {
        $user = $this->request->session()->read('Auth.User');
        $sede = TableRegistry::get('Aziende.Sedi')->get($this->request->query['sede'], ['contain' => ['Comuni', 'Province']]);

        if(!$this->Azienda->verifyUser($user, $sede['id_azienda'] && $user['role'] != 'questura')){
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return null;
        }

        $azienda = TableRegistry::get('Aziende.Aziende')->get($sede['id_azienda']);

        $nextSede = TableRegistry::get('Aziende.Sedi')->getNextAziendaSede($this->request->query['sede']);

        $this->set('sede', $sede);
        $this->set('azienda', $azienda);
        $this->set('nextSede', $nextSede ? $nextSede['id'] : '');
    }

}
