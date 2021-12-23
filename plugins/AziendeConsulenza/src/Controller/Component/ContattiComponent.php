<?php
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class ContattiComponent extends Component
{
    
    
    public function getContatti($pass = array()){
        
        $contatti = TableRegistry::get('Aziende.Contatti');
        
        $opt['limit'] = 50;
        $opt['page'] = 1;
        $opt['order'] = "";
        $opt['conditions'] = "";
        
        if(isset($pass['query']) && !empty($pass['query'])){
            
            //echo "<pre>"; print_r($pass); echo "</pre>";
            ######################################################################################################
            
            $col[0] = "cognome";
            $col[1] = "nome";
            $col[2] = "ContattiRuoli.ruolo";
            $col[3] = "telefono";
            $col[4] = "cellulare";
            $col[5] = "email";
            
            
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
            
                $opt['order'] = "Contatti.cognome ASC";
                
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
        
        if(isset($pass['id']) && $pass['id'] != 0 && isset($pass['tipo']) && $pass['tipo'] == "sede"){
            $opt['conditions']['AND'][]['Contatti.id_sede'] = $pass['id'];
        }
        
        if(isset($pass['id']) && $pass['id'] != 0 && isset($pass['tipo']) && $pass['tipo'] == "azienda"){
            $opt['conditions']['AND'][]['Contatti.id_azienda'] = $pass['id'];
        }
        
        $query = $contatti->find('all')->contain(['ContattiRuoli','Sedi'])->where($opt['conditions'])->order($opt['order'])->limit($opt['limit'])->page($opt['page']);
        
        $results = $query->toArray();
        
        //echo "<pre>"; print_r($results); echo "</pre>";
        
        return $results;
        
    }
    
    public function getTotContatti($pass = array()){
        $contatti = TableRegistry::get('Aziende.Contatti');
        
        if(isset($pass['id']) && $pass['id'] != 0 && isset($pass['tipo']) && $pass['tipo'] == "sede"){
            $opt['conditions']['AND'][]['id_sede'] = $pass['id'];
        }else{
            $opt['conditions'] = "";
        }
        
        $query = $contatti->find('all')->where($opt['conditions']);
        
        $results = $query->toArray();
        
        //echo "<pre>"; print_r($results); echo "</pre>";
        
        return count($results);
        
    }
    
    public function getRuoli(){
        
        $ruoli = TableRegistry::get('Aziende.ContattiRuoli');
        return $ruoli->find('all')->toArray();
        
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
        return $cont->get($id);
        
    }
    
    public function _delete($doc){
        $cont = TableRegistry::get('Aziende.Contatti');
        return $cont->delete($doc);
    }
    
}