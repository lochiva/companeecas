<?php

namespace Pmm\Controller;

use Pmm\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent("Pmm.Adesioni");
        $this->loadComponent("Pmm.Schede");
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');
        $this->_result = array('response' => 'KO', 'data' => null, 'msg' => null);

    }

    public function beforeRender(Event $event) {
        parent::beforeFilter($event);
        $this->set('result', json_encode($this->_result));
    }

    public function getXlsAdesioni()
    {
        $this->Adesioni->getXlsAdesioni();
        die;
    }

    public function getSecondXlsAdesioni()
    {
        $this->Adesioni->getSecondXlsAdesioni();
        die;
    }

    public function getAdesione()
    {
        try
        {
            if(isset($this->request->data['id']) && $this->request->data['id'])
            {
                $adesione = $this->Adesioni->getAdesione($this->request->data['id'],true);

                $this->_result['response'] = 'OK';
                $this->_result['data'] = $adesione;

            }else
            {
                throw new Exception("Id contratto mancante",0);
            }
        }catch(\Exception $e)
        {
            $this->_exceptionHandler($e);
        }
    }

    public function saveAdesione()
    {
        try
        {
            if(count($this->request->data) > 0)
            {
                if($this->Adesioni->saveAdesione($this->request->data))
                {
                    $this->_result['response'] = 'OK';
                }else
                {
                    throw new Exception("Si è verificato un errore durante il salvataggio dell'adesione",0);
                }
            }else
            {
                throw new Exception("Impossibile salvare l'adesione, dati mancanti.",0);
            }
        }catch(\Exception $e)
        {
            $this->_exceptionHandler($e);
        }
    }

    public function saveAdesioniMultiple()
    {
        try
        {
            if(count($this->request->data) > 0)
            {
                //echo "<pre>";print_r($this->request->data);die;

                $result = $this->Adesioni->saveAdesioniMultiple($this->request->data);

                $this->_result['response'] = $result['response'];
                $this->_result['data'] = $result['data'];

            }else
            {
                throw new Exception("Impossibile salvare le adesioni, dati mancanti.",0);
            }
        }catch(\Exception $e)
        {
            $this->_exceptionHandler($e);
        }
    }

    public function getXlsLibroSoci()
    {
      $this->Schede->getLibroSociForXls();
      die;

    }

    public function getXlsProvvigioni($anno = 0,$mese = 0)
    {
      if($anno != 0){
        $this->Adesioni->getProvvigioniForXls($anno,$mese);
      }else{
        $this->redirect('/pmm/home/provvigioni');
      }

      die;
    }

    /********************************************************* FUNZIONI PRIVATE **********************************************************************************/

    private function _exceptionHandler($e = "")
    {

        $response = 'KO';
        $data = array();

        if($e != "")
        {
            //se è un Exception generata da php restituisco un errore generico
            if(intval($e->getCode()) == 0)
            {
                $msg = $e->getMessage();
            }else
            {
                $msg = "Si è verificato un errore, il processo è stato interrotto";
            }

        }else
        {
            $msg = "Si è verificato un errore, il processo è stato interrotto";
        }

        $this->_result = ['response' => $response,'data' => $data,'msg' => $msg];
    }

}
