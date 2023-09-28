<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Contatti  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
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
class ContattiController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        //$this->loadComponent('Document.Document');
        $this->loadComponent('Aziende.Azienda');
        $this->loadComponent('Aziende.Sedi');
        $this->loadComponent('Aziende.Contatti');
    }

    public function isAuthorized($user)
    {
        if(
            $user['role'] == 'admin' || 
            $user['role'] == 'area_iv' || 
            $user['role'] == 'ro_area_iv'||
            $user['role'] == 'ragioneria' || 
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
    public function index($tipo = "" , $id = 0)
    {

        if($tipo != ""){

            $sede = array();
            $sedi = array();
            $azienda = array();
            $aziende = array();

            if($tipo == "sede" && $id != 0){
                //Carico i dati della sede se mi è stata passata
                $sede = $this->Sedi->getById($id);
                $idAzienda = $sede->id_azienda;

            }

            if($tipo == "azienda" && $id != 0){
                $azienda = $this->Azienda->_get($id);
                $idAzienda = $id;

                ################################################################################
                //recupero le sedi
                $pass['idAzienda'] = $idAzienda;
                $sedi = $this->Sedi->getSedi($pass, $azienda->id_tipo);

            }

            if($tipo == "all"){
                $idAzienda = $id;
                //$aziende = $aziende = $this->Azienda->getAziende(array());
            }

            //Carico i ruoli
            $ruoli = $this->Contatti->getRuoli();
            $skills = TableRegistry::get('Aziende.Skills')->getList();

            //Province
            $province = TableRegistry::get('Luoghi')->getProvince();

            $this->set('id',$id);
            $this->set('tipo',$tipo);
            $this->set('idAzienda',$idAzienda);
            $this->set('sede',$sede);
            $this->set('ruoli',$ruoli);
            $this->set('azienda',$azienda);
            $this->set('sedi',$sedi);
            $this->set('skills',$skills);
            $this->set('province',$province);
            //$this->set('aziende',$aziende);

        }else{
            $this->redirect('/aziende/');
        }


    }

}
