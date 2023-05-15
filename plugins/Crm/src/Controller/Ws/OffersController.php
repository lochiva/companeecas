<?php
/**
* Crm is a plugin for manage attachment
*
* Companee :    Offers  (https://www.companee.it)
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
namespace Crm\Controller\Ws;

use Crm\Controller\Ws\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
/**
 * Ws Offers Controller
 *
 * @property \Scadenzario\Model\Table\ScadenzarioTable $Scadenzario
 */
class OffersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Crm.Offers');
    }

    public function table($idAzienda = 0)
    {
      $pass['query'] = $this->request->query; 
      $offers = $this->Offers->getOffersTable($pass, $idAzienda);
      $out = array('rows'=>[], 'total_rows'=>$offers['tot'] );

      foreach ($offers['res'] as $key => $offer) {

          $button = "";
          $button.= '<div class="btn-group">';
          $button.= '<a class="btn btn-xs btn-default edit-offer" href="#" data-id="' . $offer['id'] . '" data-toggle="modal" data-target="#myModalOffer"><i data-toggle="tooltip" title="Modifica" href="#" class="fa  fa-pencil"></i></a>';
          $button.= '<div class="btn-group navbar-right" data-toggle="tooltip" title="Vedi tutte le opzioni">';
          $button.= '<a class="btn btn-xs btn-default dropdown-toggle dropdown-tableSorter" data-toggle="dropdown">Altro <span class="caret"></span></a>';
          $button.= '<ul style="width:100px !important;" class="dropdown-menu">';
          $button.= '<li><a class="delete-offer" href="#" data-id="' . $offer['id'] . '"><i style="margin-right: 7px;" class="fa fa-trash"></i> Elimina</a></li>';
          $button.= '</ul>';
          $button.= '</div>';
          $button.= '</div>';
          foreach ($offer->toArray() as $column => $value) {
            switch ($column) {
              case 'attachment':
                $out['rows'][$key][] = (!empty($value) ? '<a href="'.Router::url('/crm/offers/getAttachment/'.htmlspecialchars($value)).'" target="_blank">Allegato</a>'  : '');
                break;
              case 'status':
                $out['rows'][$key][] = '<span class="badge offerStatus-'.$value.'">'.htmlspecialchars($value)."</span>";
                break;
              case 'date':
                $out['rows'][$key][] = (!empty($value)? $value->i18nFormat('dd/MM/yyyy'): '');
                break;
              default:
                $out['rows'][$key][] = (!empty($value) ? htmlspecialchars($value) : '');
                break;
            }

          }
          $out['rows'][$key][] = $button;
      }

      $this->_result = $out;
    }

    public function get($id = 0)
    {
        if($id > 0){

            $offer = $this->Offers->_get($id);
            if(!empty($offer)){
              $this->_result = array('response' => 'OK', 'data' => $offer, 'msg' => "Offer trovato");
              return;
            }

        }
        $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");

    }

    public function save()
    {

        $msg = '';
        if(empty($this->request->data['id'])){
            unset($this->request->data['id']);
        }
        if(empty($this->request->data)){
            return;
        }

        array_walk_recursive($this->request->data, array($this,'trimByReference') );
        if(!empty($this->request->data['attachment_file']['tmp_name'])){
            $attachment = $this->Offers->uploadAttachment($this->request->data['attachment_file']);
            if($attachment){
              $this->request->data['attachment']= $attachment;
            }else{
              $msg = "Errore durante il salvataggio dell'allegato";
            }
        }
        $offer = $this->Offers->saveOffer($this->request->data);

        if ($offer) {
            $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => $msg);
        }else{
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel salvataggio");
        }

    }

    public function delete($id = 0)
    {
        if($id > 0){

            if($this->Offers->_delete($id)){
                $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
            }else{
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
            }

        }else{
             $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
        }
    }

    public function getStoricoStati($idOffer = 0)
    {
        if($idOffer > 0){

            $storico = $this->Offers->getStoricoStati($idOffer);
            
            $this->_result = array('response' => 'OK', 'data' => $storico, 'msg' => "Stati trovati.");

        }else{

            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nel caricamento dei dati: id mancante.");

        }
        
    }

    public function deleteStatus($idStatus = 0)
    {
        if($idStatus > 0){

            if($this->Offers->deleteStatus($idStatus)){
                $this->_result = array('response' => 'OK', 'data' => 1, 'msg' => "Cancellazione avvenuta con successo.");
            }else{
                $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
            }

        }else{
             $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore nella cancellazione: id mancante.");
        }
    }



}
