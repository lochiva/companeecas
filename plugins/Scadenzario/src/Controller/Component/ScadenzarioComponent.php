<?php
namespace Scadenzario\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class ScadenzarioComponent extends Component
{
    public function convertDate($date) {

		list($day,$month,$year) = explode("/",$date);
		return $year . "-" . $month . "-" . $day;

	}

    public function getScadenzario($pass = array(),$future){

        $az = TableRegistry::get('Scadenzario.Scadenzario');
        $opt = array();
        $col = array();
        $col[0] = ['val' => 'descrizione', 'type' => 'text'];
        $col[1] = ['val' => 'data', 'type' => 'date'];
        $col[2] = ['val' => 'data_eseguito', 'type' => 'date'];
        $col[3] = ['val' => 'note', 'type' => 'text'];
        if($future){
          $opt['conditions'] = ['data >='=>date('Y-m-d')];
        }
        $opt['order'] = ['data'=>'ASC'];


        return $az->queryForTableSorter($col,$opt,$pass);

    }

    public function getTotScadenzario($pass = array(),$future){

        $az = TableRegistry::get('Scadenzario.Scadenzario');
        $opt = array();
        $col = array();
        $col[0] = ['val' => 'descrizione', 'type' => 'text'];
        $col[1] = ['val' => 'data', 'type' => 'date'];
        $col[2] = ['val' => 'data_eseguito', 'type' => 'date'];
        $col[3] = ['val' => 'note', 'type' => 'text'];
        if($future){
          $opt['conditions'] = ['data >='=>date('Y-m-d')];
        }
        $opt['order'] = ['data'=>'ASC'];

        return $az->queryForTableSorter($col,$opt,$pass,true);
    }

    /**
     * Restituisce una lista di scadenze per la home, limitata al numero dato
     * come parametro. Prima fa la query per quelle scadute, e in seguito per quelli
     * della settimana corrente e prossima. Poi la lista viene formattata aggiugendo i label
     * secondo varie condizioni. "danger" => scaduti , "warning" => settimana corrente
     * "info" => settimana prossima , "success" => eseguiti
     *
     * @param  integer $limit [description]
     * @return array          lista formattata
     */
    public function getScadenzarioHome($limit = 7)
    {
        $az = TableRegistry::get('Scadenzario.Scadenzario');
        $res = array();

        $res['danger'] = $az->find()->where(['data <' => date('Y-m-d'), 'data_eseguito' => '0000-00-00'])
            ->order(['data' => 'ASC'])->limit($limit)->toArray();
        $limit -= count($res['danger']);
        $res['warning'] = $az->find()->where(['data >=' => date('Y-m-d'), 'data <' => date("Y-m-d", strtotime("+1 week")),'data_eseguito' => '0000-00-00' ])
            ->order(['data' => 'ASC'])->limit($limit>0 ? $limit : 0)->toArray();
        $limit -= count($res['warning']);
        $res['info'] = $az->find()->where([ 'data >=' => date("Y-m-d", strtotime("+1 week")),'data_eseguito' => '0000-00-00' ])
            ->order(['data' => 'ASC'])->limit($limit>0 ? $limit : 0)->toArray();

        foreach ($res as $key => $value) {
          foreach ($value as $key2 => $val) {
              if(!empty($res[$key][$key2]['data'])){
                  $res[$key][$key2]['data'] = $res[$key][$key2]['data']->i18nFormat('dd/MM/yyyy');
                  if(empty($res[$key][$key2]['data_eseguito'])){
                    $res[$key][$key2]['label'] = $key;
                  }else{
                    $res[$key][$key2]['label'] = 'success';
                  }
              }
          }
        }
        return array_merge($res['danger'],$res['warning'],$res['info']);
    }

    public function _newEntity(){
        $az = TableRegistry::get('Scadenzario.Scadenzario');
        return $az->newEntity();
    }

    public function _patchEntity($doc,$request){
        $az = TableRegistry::get('Scadenzario.Scadenzario');
        return $az->patchEntity($doc,$request);
    }

    public function _save($doc){
        $az = TableRegistry::get('Scadenzario.Scadenzario');
        return $az->save($doc);
    }

    public function _get($id){
        $az = TableRegistry::get('Scadenzario.Scadenzario');
        return $az->get($id);

    }

    public function _delete($doc){
        $az = TableRegistry::get('Scadenzario.Scadenzario');
        return $az->softDelete($doc);
    }

}
