<?php
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class SediComponent extends Component
{
    
    
    public function getSedi($pass = array()){
        
        $az = TableRegistry::get('Aziende.Sedi');
        
        $opt['limit'] = 50;
        $opt['page'] = 1;
        $opt['order'] = "";
        $opt['conditions'] = "";
        
        if(isset($pass['query']) && !empty($pass['query'])){
            
            //echo "<pre>"; print_r($pass); echo "</pre>";
            ######################################################################################################
            
            $col[0] = "SediTipi.tipo";
            $col[1] = "indirizzo";
            $col[2] = "num_civico";
            $col[3] = "cap";
            $col[4] = "comune";
            $col[5] = "provincia";
            
            
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
            
                $opt['order'] = "Sedi.id_tipo ASC";
                
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
        
        if(isset($pass['idAzienda']) && $pass['idAzienda'] != 0){
            $opt['conditions']['AND'][]['Sedi.id_azienda'] = $pass['idAzienda'];
        }
        
        //echo "<pre>"; print_r($opt); echo "</pre>";
        
        $query = $az->find('all')->contain(['SediTipi'])->where($opt['conditions'])->order($opt['order'])->limit($opt['limit'])->page($opt['page']);
        
        //echo "<pre>"; print_r($query); echo "</pre>";
        
        //exit;
        
        $results = $query->toArray();
        
        //echo "<pre>"; print_r($results); echo "</pre>";
        
        return $results;
        
    }
    
    public function getTotSedi($pass = array()){
        $az = TableRegistry::get('Aziende.Sedi');
        
        if(isset($pass['idAzienda']) && $pass['idAzienda'] != 0){
            $opt['conditions']['AND'][]['id_azienda'] = $pass['idAzienda'];
        }else{
            $opt['conditions'] = "";
        }
        
        $query = $az->find('all')->where($opt['conditions']);
        
        $results = $query->toArray();
        
        //echo "<pre>"; print_r($results); echo "</pre>";
        
        return count($results);
        
    }
    
    public function getSediTipi(){
        
        $tipi = TableRegistry::get('Aziende.SediTipi');
        
        $res = $tipi->find('all');
        
        return $res->toArray();
        
    }
    
    public function getById($id){
        $az = TableRegistry::get('Aziende.Sedi');
        $res = $az->find('all')->contain(['SediTipi','Aziende'])->where(['Sedi.id' => $id])->toArray();
        return $res[0];
        //return $az->get($id);
        
    }
    
    public function _newEntity(){
        $az = TableRegistry::get('Aziende.Sedi');
        return $az->newEntity();
    }
    
    public function _patchEntity($doc,$request){
        $az = TableRegistry::get('Aziende.Sedi');
        return $az->patchEntity($doc,$request);
    }
    
    public function _save($doc){
        $az = TableRegistry::get('Aziende.Sedi');
        return $az->save($doc);
    }
    
    public function _get($id){
        $az = TableRegistry::get('Aziende.Sedi');
        return $az->get($id);
        
    }
    
    public function _delete($doc){
        $az = TableRegistry::get('Aziende.Sedi');
        return $az->delete($doc);
    }
    
}