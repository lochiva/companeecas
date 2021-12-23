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
                $sedi = $this->Sedi->getSedi($pass);

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
