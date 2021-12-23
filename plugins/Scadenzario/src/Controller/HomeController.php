<?php
namespace Scadenzario\Controller;

use Scadenzario\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * Scadenzario Controller
 *
 * @property \Scadenzario\Model\Table\ScadenzarioTable $Scadenzario
 */
class HomeController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Scadenzario.Scadenzario');
        /*$this->loadComponent('Scadenzario.Sedi');
        $this->loadComponent('Scadenzario.Contatti');*/
        $this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['index','info']);


        $user = $this->request->session()->read('Auth.User');

        /*if($user['role']!='admin'){
            $this->redirect('/calendar');
        }*/

    }

    /**
     * Index method
     *
     * @return void
     */
    public function index()
    {

    }

    public function info($idScadenzario = 0){

        if($idScadenzario != 0){

            ################################################################################
            //Recupero i dati dell'azienda
            $azienda = $this->Azienda->_get($idScadenzario);

            ################################################################################
            //recupero le sedi
            $pass['idScadenzario'] = $idScadenzario;
            $sedi = $this->Sedi->getSedi($pass);

            //echo "<pre>"; print_r($sedi); echo "</pre>";

            ################################################################################
            //recupero i contatti
            $pass['id'] = $idScadenzario;
            $pass['tipo'] = 'azienda';

            //$contatti = $this->Contatti->getContatti($pass);

            $this->set('azienda',$azienda);
            $this->set('sedi',$sedi);
            //$this->set('contatti',$contatti);
            $this->set('idScadenzario',$idScadenzario);

        }else{
            $this->redirect('/scadenzario');
        }

    }

}
