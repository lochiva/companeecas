<?php
namespace Crm\Controller;

use Crm\Controller\AppController;
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
        $this->loadComponent('Crm.Offers');
        /*$this->loadComponent('Scadenzario.Sedi');
        $this->loadComponent('Scadenzario.Contatti');*/
        $this->loadComponent('Csrf');

        $this->set('title', 'Crm');
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

    public function index()
    {
       $offersPieChart = $this->Offers->getOffersPieChart();
       $offersLineChart = $this->Offers->getOffersLineChart();

       $this->set('offersPieChart',$offersPieChart);
       $this->set('offersLineChart',$offersLineChart);
    }

}
