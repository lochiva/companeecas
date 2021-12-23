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
class StampeController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        //$this->loadComponent('Aziende.Azienda');

        $this->set('title', 'Stampe');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['index','info']);

        $this->viewBuilder()->layout('Aziende.stampa');
    }

    
    public function printCover($idAzienda)
    {
        $azienda = TableRegistry::get('Aziende.Aziende')->get($idAzienda);

        $aziendaSede = TableRegistry::get('Aziende.Sedi')
            ->find()
            ->where(['id_azienda' => $idAzienda, 'id_tipo' => 1])
            ->first();

        $this->set('azienda', $azienda);
        $this->set('aziendaSede', $aziendaSede);
    }

}
