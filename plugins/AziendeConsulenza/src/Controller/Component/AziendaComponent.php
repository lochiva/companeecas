<?php
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class AziendaComponent extends Component
{


    public function getAziende($pass = array()){

        $az = TableRegistry::get('Aziende.Aziende');

        $opt['limit'] = 9999;
        $opt['page'] = 1;
        $opt['order'] = "Aziende.Denominazione ASC";
        $opt['conditions'] = "";

        if(isset($pass['query']) && !empty($pass['query'])){

            //echo "<pre>"; print_r($pass); echo "</pre>";
            ######################################################################################################

            $col[0] = "denominazione";
            $col[1] = "nome";
            $col[2] = "cognome";
            $col[3] = "famiglia";
            $col[4] = "telefono";
            $col[5] = "cod_sispac";


            ######################################################################################################
            //Gestione paginazione

            if(isset($pass['query']['size']) && isset($pass['query']['page'])){
                $size = $pass['query']['size'];
                $page = $pass['query']['page'] + 1;
            }else{
                $size = 50;
                $page = 1;
            }

            //echo $size . "|" . $page;

            if($size != "all"){
                $opt['limit'] = $size;
                $opt['page'] = $page;
            }

            ######################################################################################################
            //Gestione ordinamento

            //echo "<pre>"; print_r($pass['query']); echo "</pre>";

            $order = "";
            $separatore = "";

            if($size != "all"){

                $opt['order'] = "Aziende.Denominazione ASC";

                if(isset($pass['query']['column']) && !empty($pass['query']['column']) && is_array($pass['query']['column'])){

                    foreach ($pass['query']['column'] as $key => $value) {

                        if(isset($col[$key])){

                            $order .= $separatore . $col[$key];
                            $separatore = ", ";

                            if($value == 1){
                                $order .= " DESC";
                            }else{
                                $order .= " ASC";
                            }

                        }

                    }

                    //echo $order;
                    $opt['order'] = $order;

                }

            }

            ######################################################################################################
            if(isset($pass['query']['filter']) && !empty($pass['query']['filter']) && is_array($pass['query']['filter'])){

                foreach ($pass['query']['filter'] as $key => $value) {

                    switch ($key) {
                        case '0':
                        case '1':
                        case '2':
                        case '3':
                        case '4':
                        case '5':
                            $opt['conditions']['AND'][$key][$col[$key] . ' LIKE'] = "%" . $value . "%";
                        break;

                        /*
                        case '5':

                            if(strpos("_" . $value, "<=") > 0){

                                $value = str_replace("<=", "", $value);
                                $opt['conditions']['AND'][$key][$col[$key] . " <="] = $value ;

                            }elseif(strpos("_" . $value, ">=") > 0){

                                $value = str_replace(">=", "", $value);
                                $opt['conditions']['AND'][$key][$col[$key] . " >="] = $value ;

                            }elseif(strpos("_" . $value, ">") > 0){

                                $value = str_replace(">", "", $value);
                                $opt['conditions']['AND'][$key][$col[$key] . " >"] = $value ;

                            }elseif(strpos("_" . $value, "<") > 0){

                                $value = str_replace("<", "", $value);
                                $opt['conditions']['AND'][$key][$col[$key] . " <"] = $value ;

                            }else{

                                $opt['conditions']['AND'][$key][$col[$key]] = $value ;

                            }

                        break;
                        */
                        default:

                        break;
                    }


                }
            }
        }

        $query = $az->find('all')->where($opt['conditions'])->order($opt['order'])->limit($opt['limit'])->page($opt['page']);

        $results = $query->toArray();

        //echo "<pre>"; print_r($results); echo "</pre>";

        return $results;

    }

    public function getTotAziende($pass = array()){
        $az = TableRegistry::get('Aziende.Aziende');

        $query = $az->find('all');

        $results = $query->toArray();

        //echo "<pre>"; print_r($results); echo "</pre>";

        return count($results);

    }

    public function getAziendeXls(){

        $az = TableRegistry::get('Aziende.Aziende');

        $opt['order'] = "Aziende.Denominazione ASC";

        $aziende_array = $az->find('all')->order($opt['order'])->toArray();

        $j=0;
        foreach ($aziende_array as $key => $azienda) {
            $aziende[$j]['denominazione']  = $azienda->denominazione;
            $aziende[$j]['nome']  = $azienda->nome;
            $aziende[$j]['cognome']  = $azienda->cognome;
            $aziende[$j]['famiglia']  = $azienda->famiglia;
            $aziende[$j]['telefono']  = $azienda->telefono;
            $aziende[$j]['fax']  = $azienda->fax;
            $aziende[$j]['email_info']  = $azienda->email_info;
            $aziende[$j]['email_contabilita']  = $azienda->email_contabilita;
            $aziende[$j]['email_solleciti']  = $azienda->email_solleciti;
            $aziende[$j]['codice_paese']  = $azienda->cod_paese;
            $aziende[$j]['partita_iva']  = $azienda->piva;
            $aziende[$j]['codice_fiscale']  = $azienda->cf;
            $aziende[$j]['codice_sispac']  = $azienda->cod_sispac;
            $j++;
        }

        return $aziende;        

        //echo "<pre>"; print_r($aziende); echo "</pre>";

    }

    public function autocomplete($term = ""){

        $out = array();

        if($term != ""){

            $az = TableRegistry::get('Aziende.Aziende');

            $opt['OR'] = array('denominazione LIKE' => '%' . $term . '%', 'nome LIKE' => '%' . $term . '%', 'cognome LIKE' => '%' . $term . '%');

            $aziende = $az->find('all')->where($opt)->order('denominazione ASC, cognome ASC, nome ASC')->toArray();

            //echo "<pre>"; print_r($aziende); echo "</pre>";

            foreach ($aziende as $key => $azienda) {

                $lable = "";

                if($azienda->denominazione != ""){
                    $lable = $azienda->denominazione;
                }else if($azienda->cognome != "" && $azienda->nome != ""){
                    $lable = $azienda->cognome . " " . $azienda->nome;
                }else if($azienda->cognome != ""){
                    $lable = $azienda->cognome;
                }else if($azienda->nome != ""){
                    $lable = $azienda->nome;
                }

                if($lable != ""){
                    $out[] = array('id' => $azienda->id, 'label' => $lable);
                }

            }

            //echo "<pre>"; print_r($aziende); echo "</pre>";

        }

        return $out;

    }

    public function autocompleteFamiglia($term = ""){

        $out = array();

        if($term != ""){

            $az = TableRegistry::get('Aziende.Aziende');

            $opt['OR'] = array('famiglia LIKE' => '%' . $term . '%');

            $aziende = $az->find('all')->where($opt)->order('famiglia ASC')->group('famiglia')->toArray();

            //echo "<pre>"; print_r($aziende); echo "</pre>";

            foreach ($aziende as $key => $azienda) {

                $lable = "";

                if($azienda->famiglia != ""){
                    $lable = $azienda->famiglia;
                }

                if($lable != ""){
                    $out[] = array('id' => $lable, 'label' => $lable);
                }

            }

            //echo "<pre>"; print_r($aziende); echo "</pre>";

        }

        return $out;

    }

    public function _newEntity(){
        $az = TableRegistry::get('Aziende.Aziende');
        return $az->newEntity();
    }

    public function _patchEntity($doc,$request){
        $az = TableRegistry::get('Aziende.Aziende');
        return $az->patchEntity($doc,$request);
    }

    public function _save($doc){
        $az = TableRegistry::get('Aziende.Aziende');
        return $az->save($doc);
    }

    public function _get($id){
        $az = TableRegistry::get('Aziende.Aziende');
        return $az->get($id);

    }

    public function _delete($doc){
        $az = TableRegistry::get('Aziende.Aziende');
        return $az->delete($doc);
    }

    /* Metodo che fa il check se l'azienda ha ancora task in calendario, nel caso
    * ritorna false, altrimenti esegue la cancellazione di tutti gli orders, tasks,
    * frozentasks e jobsorders relativi all'azienda.
    */
    public function _checkBeforDelete($id)
    {
      $az = TableRegistry::get('Aziende.Aziende');
      $jobOrdersTable = TableRegistry::get('Consulenza.JobsOrders');
      $ordersTable = TableRegistry::get('Consulenza.Orders');
      $tasksTable = TableRegistry::get('Consulenza.Tasks');
      $frozentasksTable = TableRegistry::get('Consulenza.Frozentasks');

      $res = $az->retrieveAziendaBeforeDelete($id);
      //debug($res);die;
      //echo $res['Orders'][0]['frozentasks']." ed ".$res['Orders'][0]['tasks']."<br />";

      if(!empty($res['Orders'])){
        foreach($res['Orders'] as $order){
          if(!empty($order['tasks'])){
            return false;
          }
        }
        //debug($res);die;
        foreach($res['Orders'] as $order){
            $jobOrdersTable->deleteAll(['order_id'=>$order['id']]);
            $tasksTable->deleteAll(['order_id'=>$order['id']]);
            $frozentasksTable->deleteAll(['order_id'=>$order['id']]);
        }
        $ordersTable->deleteAll(['azienda_id'=>$id]);
      }

      return true;

    }

}
