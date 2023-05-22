<?php
/**
* Cespiti is a plugin for manage attachment
*
* Companee :    Ws (https://www.companee.it)
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
namespace Cespiti\Controller;

use Cake\Routing\Router;
use Scadenzario\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * Cespiti Controller
 *
 * @property \Cespiti\Model\Table\CespitiTable $Cespiti
 */
class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Cespiti.Cespiti');
		$this->loadComponent('Aziende.Azienda');
		$this->loadComponent('Aziende.Fornitori');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

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
    public function getCespiti()
    {
        $pass['query'] = $this->request->query;

		if(isset($pass['query']['filter'][4])){
			if($pass['query']['filter'][4] == 'Attivo'){
				$pass['query']['filter'][4] = 0;
			}elseif($pass['query']['filter'][4] == 'Dismesso'){
				$pass['query']['filter'][4] = 1;
			}
		}

        $cespiti = $this->Cespiti->getCespiti($pass);

        $totCespiti = $this->Cespiti->getTotCespiti($pass);

        $out['total_rows'] = $totCespiti;

        if(!empty($cespiti)){

            foreach ($cespiti as $key => $cespite) {

                $button = "";
                $button .= '<div class="btn-group-cespiti">';
				$button .= '<a class="btn btn-xs btn-primary edit" href="#" data-id="' . $cespite->id . '" title="Modifica cespite" data-toggle="modal" data-target="#myModalCespite"><i class="fa fa-pencil"></i></a>';
                $button .= '<a class="btn btn-xs btn-danger delete" href="#" data-id="' . $cespite->id . '" title="Elimina cespite"><i class="fa fa-trash-o"></i></a>';
                $button .= '</div>';

				if($cespite->stato == 1){
					$stato = '<td class="centrato"><i class="glyphicon glyphicon-remove-sign" style="color:red; font-size:24px;" title="Dismesso"></i></td>';
				}else{
					$stato = '<td class="centrato"><i class="glyphicon glyphicon-ok-sign" style="color:green; font-size:24px;" title="Attivo"></i></td>';
				}
				//$date = explode('-', $cespite->i['emission_date']);
				$rows[] = array(
					$cespite->a['denominazione'],
					'<td class="centrato">'./*$date[0].' n. '.*/$cespite->i['num'].'</td>',
					'<td class="centrato">'.$cespite->num.'</td>',
                    htmlspecialchars($cespite->descrizione),
                    $stato,
                    htmlspecialchars($cespite->note),
                    $button
                );
            }

            $out['rows'] = $rows;

            $this->_result = $out;

        }else{

            $this->_result = array();
        }


    }

    public function saveCespite($id = 0){

		$cespite = $this->Cespiti->_newEntity();

        if($id == 0){
            unset($this->request->data['id']);
        }else{
			$cespite->id = $this->request->data['id'];
		}

        $cespite = $this->Cespiti->_patchEntity($cespite, $this->request->data);

        if ($this->Cespiti->_save($cespite)) {
            $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Salvato");
        }else{
			debug($cespite->errors());
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
        }

    }

    public function deleteCespite($id = 0){

        if($id != 0){

            $cespite = $this->Cespiti->_get($id);
			$cespite->cancellato = 1;
            if($this->Cespiti->_save($cespite)){
                $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
            }else{
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
            }

        }else{
             $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
        }

    }

    public function loadCespiti($id){

        if($id != 0){

            $cespite = $this->Cespiti->_get($id);

            $this->_result = array('response' => 'OK', 'data' => $cespite, 'msg' => "Cespite trovata");

        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");
        }

    }

	public function getFornitori(){

		$fornitori = $this->Azienda->getFornitori();

		if($fornitori){
			$this->_result = array('response' => 'OK', 'data' => $fornitori, 'msg' => "Fornitori trovati");
		}else{
			$this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati.");
		}
	}

	public function getFatture($id){

		$fatture = $this->Fornitori->getFattureFornitore($id);

		if($fatture){
			$this->_result = array('response' => 'OK', 'data' => $fatture, 'msg' => "Fatture trovate");
		}else{
			$this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Nessuna fattura trovata.");
		}
	}

}
