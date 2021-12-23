<?php
namespace Progest\Controller\Ws;

use Progest\Controller\Ws\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
/**
 * Ws Familiari Controller
 *
 */
class FamiliariController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Progest.People');
    }

    /**
     * Restituisce un familiare secondo l'id dato.
     * @param  integer $id  id del familiare
     * @return void
     */
    public function get($id)
    {
        $familiare = $this->People->getFamiliare($id);

        $this->_result = array('response' => 'OK', 'data' => $familiare, 'msg' => '');
    }

    /**
     * Salva un familiare, e resituisce la lista di tutti i familiari della persona
     * considerando l'id_persona presente nel familiare.
     * @return void
     */
    public function save()
    {
        $data = $this->request->data;
        if(empty($data['id'])){
            unset($data['id']);
        }
        array_walk_recursive($data, array($this,'trimByReference') );

        if(!$familiari = $this->People->saveFamiliare($data)){
            $this->_result = array('response' => 'KO', 'data' => $familiari, 'msg' => 'Errore durante il salvataggio.');
            return;
        }

        $this->_result = array('response' => 'OK', 'data' => $familiari, 'msg' => '');
    }

    /**
     * Fa la delete di un familiare.
     * @param  integer $id del familiare
     * @return void
     */
    public function delete($id=0)
    {
        if($id > 0 ) {
            $msg = $this->People->checkDeleteFamiliare($id);
            if( $msg !== true){
                $this->_result = array('response' => 'KO', 'data' => '', 'msg' => $msg);
                return;
            }
            if(!$this->People->deleteFamiliare($id)){
                $this->_result = array('response' => 'KO', 'data' => '', 'msg' => 'Errore durante la cancellazione.');
                return;
            }
        }
        $this->_result = array('response' => 'OK', 'data' => '', 'msg' => '');
    }

    /**
     * Restituisce la lista dei gradi di parentela presenti nel db
     * @return void
     */
    public function getParentele()
    {
      $gradoPrantela = TableRegistry::get('Progest.GradoParentela')->find()->order(['ordering' => 'ASC'])->toArray();

      $this->_result = array('response' => 'OK', 'data' => $gradoPrantela, 'msg' => 'Lista grado parentela.');

    }

}
