<?php

namespace Aziende\Controller\Admin;

use Aziende\Controller\Admin\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;

class AziendeController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Aziende.Contatti');
    }

    public function index()
    {
        $ruoli = $this->Contatti->getRuoli();

        $this->set('ruoli', $ruoli);
    }

    public function editAddRuolo($id=0)
    {
        if(empty($id)){
            $entity = $this->Contatti->_newEntityRuolo();
            $entity = $this->Contatti->_patchEntityRuolo($entity,$this->request->data);
            $entity = $this->Contatti->_saveRuolo($entity);

        }else{
            $entity = $this->Contatti->_getRuolo($id);
            $entity = $this->Contatti->_patchEntityRuolo($entity,$this->request->data);
            $entity = $this->Contatti->_saveRuolo($entity);
        }

        if($entity){
            die(strval($entity['id']));
        }else{
            die('Errore durante il salvataggio. Controlla i dati.');
        }

    }

    public function deleteRuolo($id=0)
    {
        if(!empty($id)){
            if($this->Contatti->getTotContattiRuolo($id)){
              die('Errore cancellazione. Sono presenti dei contatti che hanno questo ruolo.');
            }
            $entity = $this->Contatti->_getRuolo($id);
            $entity = $this->Contatti->_deleteRuolo($entity);
            if($entity){
                die('1');
            }else{
                die('Errore cancellazione.');
            }
        }else{
            die('Errore cancellazione. Mancano i parametri.');
        }

    }
}
