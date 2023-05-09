<?php
/**
* Controller is a plugin for manage attachment
*
* Companee :    Home  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
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
use Cake\Core\Configure;

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
        $this->loadComponent('Aziende.Azienda');
        $this->loadComponent('Aziende.Sedi');
        $this->loadComponent('Aziende.Contatti');
        $this->loadComponent('Aziende.Order');
        $this->loadComponent('Aziende.Fornitori');
        $this->loadComponent('Aziende.Clienti');
        $this->loadComponent('Csrf');
        //$this->loadComponent('Crm.Offers');
        //$this->loadComponent('Leads.Interview');

        $this->set('title', 'Aziende');
    }

    public function isAuthorized($user)
    {
        if(
            $user['role'] == 'admin' || $user['role'] == 'area_iv' || $user['role'] == 'ragioneria') {
            return true;

        } else if ($user['role'] == 'ente_ospiti') {
            if (!empty($this->request->getParam('action')) && $this->request->getParam('action') == 'info') {
                if (!empty($this->request->getParam('pass')) && array_key_exists(0, $this->request->getParam('pass'))) {
                    $pass = $this->request->getParam('pass');

                    $userId = $this->request->session()->read('Auth.User.id');
                    $contatto = TableRegistry::get('Aziende.Contatti')->getContattoByUser($userId);

                    if ($pass[0] == $contatto['id_azienda']) {
                        return true;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        } 
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['index','info']);
    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {
        $sediTipiMinistero = $this->Sedi->getSediTipiMinistero();
        $sediTipiCapitolato = $this->Sedi->getSediTipiCapitolato();
        $ruoli = $this->Contatti->getRuoli();
        $skills = TableRegistry::get('Aziende.Skills')->getList();
        $gruppi = TableRegistry::get('Aziende.AziendeGruppi')->getList();
        $tipi = TableRegistry::get('Aziende.AziendeTipi')->getList();
        $tipologieCentro = TableRegistry::get('Aziende.SediTipologieCentro')->getList();
        $tipologieOspiti = TableRegistry::get('Aziende.SediTipologieOspiti')->getList();
        $procedureAffidamento = TableRegistry::get('Aziende.SediProcedureAffidamento')->getList();

        $province = TableRegistry::get('Luoghi')->getProvince();

        $this->set('skills',$skills);
        $this->set('gruppi',$gruppi);
        $this->set('sediTipiMinistero',$sediTipiMinistero);
        $this->set('sediTipiCapitolato',$sediTipiCapitolato);
        $this->set('ruoli',$ruoli);
        $this->set('province',$province);
        $this->set('tipologieCentro',$tipologieCentro);
        $this->set('tipologieOspiti',$tipologieOspiti);
        $this->set('procedureAffidamento',$procedureAffidamento);
        $this->set('tipi',$tipi);
    }

    public function info($idAzienda = 0){

        if($idAzienda != 0){

            $sediTipiMinistero = $this->Sedi->getSediTipiMinistero();
            $sediTipiCapitolato = $this->Sedi->getSediTipiCapitolato();
            $ruoli = $this->Contatti->getRuoli();

            ################################################################################
            //Recupero i dati dell'azienda
            $azienda = $this->Azienda->_get($idAzienda);

            ################################################################################
            //recupero le sedi
            $pass['idAzienda'] = $idAzienda;
            $sedi = $this->Sedi->getSedi($pass, $azienda->id_tipo);

            //echo "<pre>"; print_r($sedi); echo "</pre>";

            ################################################################################
            //recupero i contatti
            $pass['id'] = $idAzienda;
            $pass['tipo'] = 'azienda';

            $contatti = $this->Contatti->getContatti($pass);

            ################################################################################
            //recupero gli orders
            $orders = $this->Order->getOrdersAzienda($idAzienda);

            ################################################################################
            //recupero la history
            $history = $this->Azienda->getAzeindaHistory($idAzienda);

            ################################################################################
            //recupero le offerte
            $offers =[]; // $this->Offers->getOffersAzienda($idAzienda, 4);

            ################################################################################
            //recupero le fatture passive
            $passiveInvoices = $this->Fornitori->getFatturePassiveAzienda($idAzienda, 4);

            ################################################################################
            //recupero le fatture attive
            $activeInvoices = $this->Clienti->getFattureAttiveAzienda($idAzienda, 4);

            $skills = TableRegistry::get('Aziende.Skills')->getList();
            $gruppi = TableRegistry::get('Aziende.AziendeGruppi')->getList();

            $issuers = $payers = $this->Azienda->getAziendeInterne();

            $purposesActive = $this->Clienti->getInvoicePurposesTree();
            $purposesPassive = $this->Fornitori->getInvoicePurposesTree();
            $paymentConditions = $this->Clienti->getPaymentConditions();
            $issuers = $this->Azienda->getAziendeinterne();
            $metodi = json_decode(Configure::read('dbconfig.aziende.FATTUREINCLOUD_METHODS'), true);
            $listaIva = json_decode(Configure::read('dbconfig.aziende.FATTUREINCLOUD_IVA'), true);

            $statusList =[]; // $this->Offers->getStatusList();
            $aziendeList = $this->Azienda->getAziendeInterne();

            #################################################################################
            //recupero le interviste
            $interviews = []; // $this->Interview->getInterviewsByAzienda($idAzienda);

            //recupero le province
            $province = TableRegistry::get('Luoghi')->getProvince();

            $tipologieCentro = TableRegistry::get('Aziende.SediTipologieCentro')->getList();
            $tipologieOspiti = TableRegistry::get('Aziende.SediTipologieOspiti')->getList();
            $procedureAffidamento = TableRegistry::get('Aziende.SediProcedureAffidamento')->getList();
            $tipi = TableRegistry::get('Aziende.AziendeTipi')->getList();

            $this->set('history',$history);
            $this->set('azienda',$azienda);
            $this->set('orders',$orders);
            $this->set('sedi',$sedi);
            $this->set('contatti',$contatti);
            $this->set('idAzienda',$idAzienda);
            $this->set('sediTipiMinistero',$sediTipiMinistero);
            $this->set('sediTipiCapitolato',$sediTipiCapitolato);
            $this->set('ruoli',$ruoli);
            $this->set('skills',$skills);
            $this->set('gruppi',$gruppi);
            $this->set('offers', $offers);
            $this->set('passiveInvoices', $passiveInvoices);
            $this->set('activeInvoices', $activeInvoices);
            $this->set('issuers', $issuers);
            $this->set('payers', $payers);
            $this->set('purposesActive',$purposesActive);
            $this->set('purposesPassive',$purposesPassive);
            $this->set('paymentConditions',$paymentConditions);
            $this->set('issuers',$issuers);
            $this->set('statusList',$statusList);
            $this->set('aziendeList',$aziendeList);
            $this->set('interviews', $interviews);
            $this->set('province',$province);
            $this->set('tipologieCentro',$tipologieCentro);
            $this->set('tipologieOspiti',$tipologieOspiti);
            $this->set('procedureAffidamento',$procedureAffidamento);
            $this->set('tipi',$tipi);

        }else{
            $this->redirect('/aziende');
        }

    }

}
