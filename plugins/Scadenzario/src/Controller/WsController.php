<?php
namespace Scadenzario\Controller;

use Cake\Routing\Router;
use Scadenzario\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * Scadenzario Controller
 *
 * @property \Scadenzario\Model\Table\ScadenzarioTable $Scadenzario
 */
class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Scadenzario.Scadenzario');
        //$this->loadComponent('Scadenzario.Sedi');
        //$this->loadComponent('Scadenzario.Contatti');
        //$this->loadComponent('Csrf');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['getScadenzario','saveScadenzario','deleteScadenzario','loadScadenzario','getSedi','saveSede','deleteSede','loadSede','getContatti','saveContatto','deleteContatto','loadContatto']);

        $this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');
        $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");


    }

    public function beforeRender(Event $event) {
        parent::beforeFilter($event);
        $this->set('result', json_encode($this -> _result));
    }

    /**
     * Index method
     *
     * @return void
     */
    public function getScadenzario($future = 0)
    {

        //echo "<pre>"; print_r($this->request->query); echo "</pre>";

        $pass['query'] = $this->request->query;

        $scadenzario = $this->Scadenzario->getScadenzario($pass,$future);

        $totScadenzario = $this->Scadenzario->getTotScadenzario($pass,$future);

        $out['total_rows'] = $totScadenzario;

        if(!empty($scadenzario)){

			//echo "<pre>"; print_r($scadenzario); echo "</pre>";

            foreach ($scadenzario as $key => $scad) {

				if (is_object($scad->data))
				{
					$data_temp = $scad->data->i18nFormat('dd/MM/yyyy');
				}
				else
				{
					$data_temp = "-";
				}

				if (is_object($scad->data_eseguito))
				{
					$data_eseguito_temp = $scad->data_eseguito->i18nFormat('dd/MM/yyyy');
				}
				else
				{
					$data_eseguito_temp = "-";
				}

				//echo "<br/>$key";
				//echo "<br/>scad->data vale " . $scad->data->date;
				//echo "<br/>scad->data_eseguito vale " . $scad->data_eseguito->date;

				/*if (!empty($scad->data->date))
					$data_temp = date("d/m/Y",strtotime($scad->data->date));
				else
					$data_temp = "-";

				if (!empty($scad->data_eseguito->date))
					$data_eseguito_temp = date("d/m/Y",strtotime($scad->data_eseguito->date));
				else
					$data_eseguito_temp = "-";*/


                $button = "";
                $button.= '<div class="btn-group">';
                //$button.= '<a class="btn btn-sm btn-warning view" href="' . Router::url('/scadenzario/home/info/' . $scad->id) . '" data-id="' . $scad->id . '" data-descrizione="' . $scad->descrizione . '" ><i class="fa  fa-eye"></i></a>';
                $button.= '<a class="btn btn-sm btn-warning edit" href="#" data-id="' . $scad->id . '" data-descrizione="' . htmlspecialchars($scad->descrizione) . '" data-toggle="modal" data-target="#myModalScadenzario"><i class="fa  fa-pencil"></i></a>';
                //$button.= '<a class="btn btn-sm btn-primary sedi" href="' . Router::url('/aziende/sedi/index/' . $scad->id) . '" data-id="' . $azienda->id . '" data-descrizione="' . $azienda->descrizione . '"><i class="fa fa-industry"></i></a>';
                //$button.= '<a class="btn btn-sm btn-primary contatti" href="' . Router::url('/aziende/contatti/index/azienda/' . $azienda->id) . '" data-id="' . $azienda->id . '" ><i class="fa fa-users"></i></a>';
                $button.= '<a class="btn btn-sm btn-danger delete" href="#" data-id="' . $scad->id . '" data-descrizione="' . htmlspecialchars($scad->descrizione) . '"><i class="fa  fa-trash-o"></i></a>';
                $button.= '</div>';

                $rows[] = array(
                    htmlspecialchars($scad->descrizione),
                    $data_temp,
                    $data_eseguito_temp,
                    htmlspecialchars($scad->note),
                    $button
                );
            }

			//echo "<pre>"; print_r($rows); echo "</pre>";

            $out['rows'] = $rows;

            $this->_result = $out;

        }else{

            $this->_result = array();
        }


    }

    public function saveScadenzario($id = 0){

        if($id == 0){
            unset($this->request->data['id']);
        }

		if (!empty($this->request->data['data']))
			$this->request->data['data'] = $this->Scadenzario->convertDate($this->request->data['data']);

		if (!empty($this->request->data['data_eseguito']))
			$this->request->data['data_eseguito'] = $this->Scadenzario->convertDate($this->request->data['data_eseguito']);

        $scadenzario = $this->Scadenzario->_newEntity();

        $scadenzario = $this->Scadenzario->_patchEntity($scadenzario, $this->request->data);

		//echo "<pre>"; print_r($scadenzario); echo "</pre>";

        if ($this->Scadenzario->_save($scadenzario)) {
            $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Salvato");
        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
        }

    }

    public function deleteScadenzario($id = 0){

        if($id != 0){

            $scadenzario = $this->Scadenzario->_get($id);

            if($this->Scadenzario->_delete($scadenzario)){
                $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
            }else{
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
            }

        }else{
             $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
        }

    }

    public function loadScadenzario($id = 0){

        if($id != 0){

            $scadenzario = $this->Scadenzario->_get($id);

			if (!empty($scadenzario->data))
			{
				$data_temp = explode(" ",$scadenzario->data);
				$scadenzario->data = $scadenzario->data->i18nFormat('dd/MM/yyyy');
			}

			if (!empty($scadenzario->data_eseguito))
			{
				$data_temp = explode(" ",$scadenzario->data_eseguito);
				$scadenzario->data_eseguito = $scadenzario->data_eseguito->i18nFormat('dd/MM/yyyy');
			}

            $this->_result = array('response' => 'OK', 'data' => $scadenzario, 'msg' => "Azienda trovata");

        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
        }

    }

}
