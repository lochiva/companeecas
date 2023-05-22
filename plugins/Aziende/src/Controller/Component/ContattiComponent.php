<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Contatti  (https://www.companee.it)
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
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class ContattiComponent extends Component
{


    public function getContattiTable($pass = array()){

        $contatti = TableRegistry::get('Aziende.Contatti');
        $opt = array();
        if(isset($pass['id']) && $pass['id'] != 0 && isset($pass['tipo']) && $pass['tipo'] == "sede"){
            $opt['conditions']['AND'][]['Contatti.id_sede'] = $pass['id'];
        }

        if(isset($pass['id']) && $pass['id'] != 0 && isset($pass['tipo']) && $pass['tipo'] == "azienda"){
            $opt['conditions']['AND'][]['Contatti.id_azienda'] = $pass['id'];
        }
        $columns = [
          0 => ['val' => 'Contatti.cognome', 'type' => 'text'],
          1 => ['val' => 'Contatti.nome', 'type' => 'text'],
          2 => ['val' => 'Aziende.denominazione', 'type' => 'text'],
          3 => ['val' => 'ContattiRuoli.ruolo', 'type' => 'text', ],
          4 => ['val' => 'Users.username', 'type' => 'text'],
          5 => ['val' => 'Contatti.telefono', 'type' => 'text'],
          6 => ['val' => 'Contatti.cellulare', 'type' => 'text'],
          7 => ['val' => 'Contatti.email', 'type' => 'text'],
        ];
        $opt['fields'] = ['Contatti.id', 'Contatti.id_user','userRole' => 'Users.role', 'userName' => 'Users.username','azienda'=>'Aziende.denominazione','Contatti.cognome','Contatti.nome','ruolo'=>'ContattiRuoli.ruolo',
                    'Contatti.telefono','Contatti.cellulare','Contatti.email'];
        
        $opt['contain'] = ['ContattiRuoli','Sedi','Aziende', 'Users'];
        $opt['order'] = ['Contatti.cognome' => 'ASC',' Contatti.nome' => 'ASC', 'Aziende.denominazione' => 'ASC'];
        $res['res'] = $contatti->queryForTableSorter($columns,$opt,$pass);
        $res['tot'] = $contatti->queryForTableSorter($columns,$opt,$pass,true);

        return $res;

    }

    public function getContatti($pass)
    {
      $contatti = TableRegistry::get('Aziende.Contatti');
      $opt = array();
      if(isset($pass['id']) && $pass['id'] != 0 && isset($pass['tipo']) && $pass['tipo'] == "sede"){
          $opt['conditions']['AND'][]['Contatti.id_sede'] = $pass['id'];
      }

      if(isset($pass['id']) && $pass['id'] != 0 && isset($pass['tipo']) && $pass['tipo'] == "azienda"){
          $opt['conditions']['AND'][]['Contatti.id_azienda'] = $pass['id'];
      }

      $query = $contatti->find('all')->contain(['ContattiRuoli','Sedi'])->where($opt['conditions']);

      $results = $query->toArray();

      return $results;
    }

    public function getTotContatti($pass = array()){
        $contatti = TableRegistry::get('Aziende.Contatti');
        $opt['conditions'] = "";

        if(isset($pass['id']) && $pass['id'] != 0 && isset($pass['tipo']) && $pass['tipo'] == "sede"){
            $opt['conditions']['AND'][]['id_sede'] = $pass['id'];
        }
        if(isset($pass['id']) && $pass['id'] != 0 && isset($pass['tipo']) && $pass['tipo'] == "azienda"){
            $opt['conditions']['AND'][]['Contatti.id_azienda'] = $pass['id'];
        }

        $query = $contatti->find('all')->where($opt['conditions']);

        $results = $query->count();

        //echo "<pre>"; print_r($results); echo "</pre>";

        return $results;

    }

    public function getContattiAzienda($id=0, $role = 0)
    {
      $contatti = TableRegistry::get('Aziende.Contatti');
      $query = $contatti->find('all')->select(['id' => 'id','text' => 'CONCAT(nome,SPACE(1),cognome)']);
      if(!empty($id)){
          $query = $query->where(['id_azienda' => $id]);
      }
      if(!empty($role)){
          $query = $query->where(['id_ruolo' => $role]);
      }
      return $query->toArray();
    }

    public function getRuoli(){

        $ruoli = TableRegistry::get('Aziende.ContattiRuoli');
        return $ruoli->find('all')->order(['ordering'=>'ASC'])->toArray();

    }

    public function _newEntity(){
        $cont = TableRegistry::get('Aziende.Contatti');
        return $cont->newEntity();
    }

    public function _patchEntity($doc,$request){
        $cont = TableRegistry::get('Aziende.Contatti');
        return $cont->patchEntity($doc,$request);
    }

    public function _save($doc){
        $cont = TableRegistry::get('Aziende.Contatti');
        return $cont->save($doc);
    }

    public function _get($id){
        $cont = TableRegistry::get('Aziende.Contatti');
        return $cont->get($id,['contain' => ['Aziende','Skills', 'Users']]);

    }

    public function _delete($doc){
        $cont = TableRegistry::get('Aziende.Contatti');
        return $cont->softDelete($doc);
    }

    /**
     * Metodo che calcola il numero di contatti per ruolo.
     * @return array
     */
    public function getContattiChart()
    {
        $ruoli = TableRegistry::get('Aziende.ContattiRuoli')->find('all')
            ->select(['value'=>'COUNT(Contatti.id)','label'=>'ContattiRuoli.ruolo',
            'color'=>'ContattiRuoli.color' ,'highlight' => 'ContattiRuoli.color'])
            ->contain('Contatti')->group('ContattiRuoli.id')->order(['ContattiRuoli.ordering'=>'ASC'])->toArray();

        return $ruoli;

    }

    public function _newEntityRuolo(){
        $cont = TableRegistry::get('Aziende.ContattiRuoli');
        return $cont->newEntity();
    }

    public function _patchEntityRuolo($doc,$request){
        $cont = TableRegistry::get('Aziende.ContattiRuoli');
        return $cont->patchEntity($doc,$request);
    }

    public function _saveRuolo($doc){
        $cont = TableRegistry::get('Aziende.ContattiRuoli');
        return $cont->save($doc);
    }

    public function _getRuolo($id){
        $cont = TableRegistry::get('Aziende.ContattiRuoli');
        return $cont->get($id);

    }

    public function _deleteRuolo($doc){
        $cont = TableRegistry::get('Aziende.ContattiRuoli');
        return $cont->delete($doc);
    }

    public function getTotContattiRuolo($id)
    {
        return TableRegistry::get('Aziende.Contatti')->find('all')->
            where(['id_ruolo' => $id])->count();
    }


}
