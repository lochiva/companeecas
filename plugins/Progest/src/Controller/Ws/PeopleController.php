<?php
namespace Progest\Controller\Ws;

use Progest\Controller\Ws\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
/**
 * Ws Offers Controller
 *
 */
class PeopleController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Progest.People');
    }

    public function table()
    {
        $pass['query'] = $this->request->query;
        $people = $this->People->getForTable($pass);
        $out = array('rows'=>[], 'total_rows'=>$people['tot'] );

        foreach ($people['res'] as $key => $person) {

            $button = "";
            $button.= '<div class="btn-group">';
            $button.= '<a class="btn btn-xs btn-default edit" href="#" data-id="' . $person['id'] . '" data-toggle="modal" data-target="#myModalPerson"><i data-toggle="tooltip" title="Modifica" href="#" class="fa  fa-pencil"></i></a>';
            $button.= '<div class="btn-group navbar-right" data-toggle="tooltip" title="Vedi tutte le opzioni">';
            $button.= '<a class="btn btn-xs btn-default dropdown-toggle dropdown-tableSorter" data-toggle="dropdown">Altro <span class="caret"></span></a>';
            $button.= '<ul style="width:100px !important;" class="dropdown-menu">';
            $button.= '<li><a class="delete" href="#" data-id="' . $person['id'] . '" ><i style="margin-right: 7px;" class="fa fa-trash"></i> Elimina</a></li>';
            $button.= '</ul>';
            $button.= '</div>';
            $button.= '</div>';
            unset($person['id']);
            $person['button'] = $button;
            $person['birth_date'] = (!empty($person['birth_date'])?$person['birth_date']->i18nFormat('dd/MM/yyyy'): '');
            $out['rows'][$key] = $person;
        }

        $this->_result = $out;
    }

    public function autocomplete($active = false)
    {
        $q = $this->request->query['q'];
        $res = $this->People->autocomplete($q,$active);

        $this->_result = array('response' => 'OK', 'data' => $res, 'msg' => '');
    }

    public function get($id)
    {
        $person = $this->People->get($id);

        $this->_result = array('response' => 'OK', 'data' => $person, 'msg' => '');
    }

    public function save()
    {
        $data = $this->request->data;
        if(empty($data['id'])){
            unset($data['id']);
        }
        array_walk_recursive($data, array($this,'trimByReference') );

        if(!$person = $this->People->save($data)){
            $this->_result = array('response' => 'KO', 'data' => $person, 'msg' => 'Errore durante il salvataggio.');
            return;
        }

        $this->_result = array('response' => 'OK', 'data' => $person, 'msg' => '');
    }

    public function delete($id=0)
    {
        if($id > 0 ) {
            $msg = $this->People->checkDelete($id);
            if( $msg !== true){
                $this->_result = array('response' => 'KO', 'data' => '', 'msg' => $msg);
                return;
            }
            if(!$this->People->delete($id)){
                $this->_result = array('response' => 'KO', 'data' => '', 'msg' => 'Errore durante la cancellazione.');
                return;
            }
        }
        $this->_result = array('response' => 'OK', 'data' => '', 'msg' => '');
    }

    public function indirizzario($xls = false)
    {
        $pass['query'] = $this->request->query;
        $people = $this->People->tableIndirizzario($pass, $xls);
    }

    public function orders($id)
    {
        $orders = $this->People->getOrders($id);

        $this->_result = array('response' => 'OK', 'data' => $orders, 'msg' => '');
    }

}
