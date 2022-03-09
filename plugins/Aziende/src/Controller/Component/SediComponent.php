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
        $opt['conditions'] = array();

        ######################################################################################################

        $col[0] = "code_centro";
        $col[1] = "stm.name";
        $col[2] = "stc.name";
        $col[3] = "indirizzo";
        $col[4] = "num_civico";
        $col[5] = "cap";
        $col[6] = "c.des_luo";
        $col[7] = "p.des_luo";

        if(isset($pass['query']) && !empty($pass['query'])){

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
                        case '6':
                        case '7':
                            $opt['conditions']['AND'][$key][$col[$key] . ' LIKE'] = "%" . $value . "%";
                        break;

                        case '5':
                            $opt['conditions']['AND'][$key][$col[$key]] = $value ;
                        break;

                        default:

                        break;
                    }


                }
            }
        }

        if(isset($pass['idAzienda']) ){
            $opt['conditions']['AND'][]['Sedi.id_azienda'] = $pass['idAzienda'];
        }

        $opt['join'] = [
            [
                'table' => 'luoghi',
                'alias' => 'c',
                'type' => 'LEFT',
                'conditions' => 'c.c_luo = Sedi.comune'
            ],
            [
                'table' => 'luoghi',
                'alias' => 'p',
                'type' => 'LEFT',
                'conditions' => 'p.c_luo = Sedi.provincia'
            ],
            [
                'table' => 'sedi_tipi_ministero',
                'alias' => 'stm',
                'type' => 'LEFT',
                'conditions' => 'stm.id = Sedi.id_tipo_ministero'
            ],
            [
                'table' => 'sedi_tipi_capitolato',
                'alias' => 'stc',
                'type' => 'LEFT',
                'conditions' => 'stc.id = Sedi.id_tipo_capitolato'
            ]
        ];

        $col[] = 'id';
        $col[] = 'id_tipo_ministero';
        $col[] = 'id_tipo_capitolato';
        $col[] = 'nazione';
        $col[] = 'n_posti_effettivi';

        //echo "<pre>"; print_r($opt); echo "</pre>";

        $query = $az->find('all')
            ->select($col)
            ->contain(['SediTipiMinistero', 'SediTipiCapitolato'])
            ->where($opt['conditions'])
            ->join($opt['join'])
            ->order($opt['order'])
            ->limit($opt['limit'])
            ->page($opt['page']);

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

    public function getSediTipiMinistero(){

        $tipi = TableRegistry::get('Aziende.SediTipiMinistero');

        $res = $tipi->find('all')->order(['ordering' => 'ASC']);

        return $res->toArray();

    }

    public function getSediTipiCapitolato(){

        $tipi = TableRegistry::get('Aziende.SediTipiCapitolato');

        $res = $tipi->find('all')->order(['ordering' => 'ASC']);

        return $res->toArray();

    }

    public function getById($id){
        $az = TableRegistry::get('Aziende.Sedi');
        $res = $az->find('all')->contain(['SediTipiMinistero', 'SediTipiCapitolato','Aziende'])->where(['Sedi.id' => $id])->toArray();
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
        $sede = $az->get($id);
        $agreements = TableRegistry::get('Aziende.Agreements');
        $agreement = $agreements->find()
            ->select(['Agreements.procedure_id', 'ats.capacity'])
            ->where(['ats.sede_id' => $id, 'ats.active' => 1])
            ->join([
                [
                    'table' => 'agreements_to_sedi',
                    'alias' => 'ats',
                    'left' => 'LEFT',
                    'conditions' => 'Agreements.id = ats.agreement_id'
                ]
            ])
            ->first();
        $sede['n_posti_convenzione'] = empty($agreement) ? '' : $agreement['ats']['capacity'];
        $sede['id_procedura_affidamento'] = empty($agreement) ? '' : $agreement['procedure_id'];
        return $sede;
    }

    public function _delete($doc){
        $az = TableRegistry::get('Aziende.Sedi');
        return $az->softDelete($doc);
    }

}
