<?php

namespace Pmm\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\Network\Session;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\I18n\Time;
use Cake\Core\Exception\Exception;
use Cake\Core\Configure;

class CrmContrattiTable extends Table
{

    /**
    * @api
    * @var object $_session contiene la sessione
    */

	public function initialize(array $config)
	{
		$this->table("crm_contratti");
		$this->primaryKey('contratto_id');
        $this->addBehavior('Timestamp');

        $this->_session = new Session();

        $this->belongsTo('Pmm.CrmComuni',[
            'className' => 'Pmm.CrmComuni',
            'foreignKey' => 'contratto_fk_comune_sede',
            'propertyName' => 'Comune'
        ]);

        $this->belongsTo('Pmm.CrmProvince',[
        	'className' => 'Pmm.CrmProvince',
            'foreignKey' => 'contratto_fk_prov_sede',
            'propertyName' => 'Provincia'
        ]);


        $this->belongsTo('Pmm.CrmStatiContratti',[
        	'className' => 'Pmm.CrmStatiContratti',
            'foreignKey' => 'contratto_fk_statiContratti',
            'propertyName' => 'Stato_contratto'
        ]);


        $this->belongsTo('Pmm.ContrattiPdr',[
        	'className' => 'Pmm.ContrattiPdr',
            'foreignKey' => 'contratto_fk_pdr',
            'propertyName' => 'Contratto_pdr'
        ]);

        $this->belongsTo('Pmm.CrmSchede',[
            'className' => 'Pmm.CrmSchede',
            'foreignKey' => 'contratto_fk_scheda',
            'propertyName' => 'Scheda'
        ]);

        $this->belongsTo('Pmm.Profiles',[
            'className' => 'Pmm.Profiles',
            'foreignKey' => 'contratto_creator_fk_user',
            'bindingKey' => 'user_id',
            'propertyName' => 'Profile'
        ]);

        $this->belongsTo('Pmm.CrmTipicontratti',[
            'className' => 'Pmm.CrmTipicontratti',
            'foreignKey' => 'contratto_fk_tipocontratto',
            'propertyName' => 'TipoContratto'
        ]);

        $this->hasMany('Pmm.CrmServiziAcquistati',[
            'className' => 'Pmm.CrmServiziAcquistati',
            'foreignKey' => 'serviziAcquistati_fk_contratti',
            'propertyName' => 'ServiziAcquistati'
        ]);

        $this->_contain_adesione = ['CrmSchede'=>['Users'=>['Profiles']],'CrmComuni','CrmProvince','ContrattiPdr','CrmStatiContratti'];

	}

    /**
    * metodo getAdesioniForTable
    *
    * restituisce un array di adesioni nel formato richiesto da tabelsorter
    *
    * @api
    * @author Sergio Frasca
    * @param array $params filtri e ordinamento
    * @param boolean $xls se true i dati vengono preparati per l'esportazione su xls
    * @return array
    * @throws Exception
    */


    public function getAdesioniForTable($params = [],$xls = false)
    {
        // array con i campi corrispondenti alle colonne della tabella
        $columns = [
            0 => ['field' => 'CrmContratti.contratto_data_contratto','type' => 'date'],
            1 => ['field' => 'CrmSchede.scheda_nome','type' => 'text'],
            2 => ['field' => 'CrmSchede.scheda_piva','type' => 'text'],
            3 => ['field' => 'CrmComuni.comune_nome','type' => 'text'],
            4 => ['field' => 'CrmContratti.contratto_cap_sede','type' => 'text'],
            5 => ['field' => 'CrmProvince.provincia_sigla','type' => 'text'],
            6 => ['field' => 'CrmSchede.scheda_tel_mobile','type' => 'text'],
            7 => ['field' => 'CrmSchede.scheda_tel_fisso','type' => 'text'],
            8 => ['field' => 'CrmStatiContratti.statiContratti_descrizione','type' => 'text'],
            9 => ['field' => 'Profiles.display_name','type' => 'text'],
            10 => ['field' => 'ContrattiPdr.pdr_nome','type' => 'text'],
            11 => ['field' => 'CrmContratti.contratto_data_pdr','type' => 'date']
        ];

        $orders = [
            0 => 'ASC',
            1 => 'DESC'
        ];

        #####################################################################
        // setto le condizioni

        $opt['conditions'] = [];
        $opt['conditions']['AND']["CrmSchede.scheda_fk_provenienza IN"] = Configure::read('localConfig.provenienza_schede');
        $opt['conditions']['AND']['CrmContratti.contratto_attivo'] = 1;
        $opt['conditions']['AND']['CrmSchede.scheda_attiva'] = 1;

        $opt['contain'] = $this->_contain_adesione;

        $opt['fields'] = [
            'contratto_id',
            'CrmSchede.scheda_nome',
            'CrmSchede.scheda_piva',
            'CrmComuni.comune_nome',
            'CrmContratti.contratto_cap_sede',
            'CrmProvince.provincia_sigla',
            'CrmSchede.scheda_tel_mobile',
            'CrmSchede.scheda_tel_fisso',
            'CrmStatiContratti.statiContratti_descrizione',
            'display_name'=>'Profiles.display_name',
            'ContrattiPdr.pdr_nome',
            'CrmContratti.contratto_data_pdr',
            'CrmContratti.contratto_data_contratto'
        ];


        ###########################################################################################################
        // filtri e clausule riservati al view
        if(!$xls)
        {
            //paginazione
            $opt['page'] = intval($params['page']) +1;

            // limit
            if(isset($params['size']))
                $opt['limit'] = $params['size'];

            //ordinamento
            if(is_array($params['column']) && count($params['column']) > 0)
            {
                $opt['order'] = [];

                foreach($params['column'] as $column => $order)
                {
                    $opt['order'][] = $columns[$column]['field'] . " " . $orders[$order];
                }
            }

            //filtraggio
            if(is_array($params['filter']) && count($params['filter']) > 0)
            {

                foreach($params['filter'] as $column => $filter)
                {
                    switch($columns[$column]['type'])
                    {
                        case 'text':

                            $opt['conditions']['AND'][$columns[$column]['field'] . " LIKE "] =  "%" . $filter . "%";

                        break;

                        case 'date':

                            $exp = explode('/',$filter);
                            $date = implode('-',array_reverse($exp));

                            $opt['conditions']['AND'][$columns[$column]['field'] . " LIKE "] =  "%" . $date . "%";

                        break;

                        default:

                        break;
                    }
                }
            }
        }

        ###########################################################################################################

        // eventuali filtri in sessione
        if($this->_session->check(Configure::read('localConfig.adesioni_filter_prefix')))
        {
            $filters = $this->_session->read(Configure::read('localConfig.adesioni_filter_prefix'));

            //echo "<pre>";print_r($filters);die;

            foreach($filters as $filter => $value)
            {
                switch($filter)
                {
                    case 'filter-pos':

                        $opt['conditions']['AND']['CrmSchede.scheda_fk_user'] = $value;

                    break;

                    case 'filter-pdr':

                        $opt['conditions']['AND']['contratto_fk_pdr'] = $value;

                    break;

                    case 'filter-status':

                        $opt['conditions']['AND']['CrmStatiContratti.statiContratti_id'] = $value;

                    break;

                    case 'filter-date':

                        switch($value)
                        {
                            case 'past':
                                $opt['conditions']['AND']['CrmContratti.contratto_data_pdr <'] = date('Y-m-d');
                            break;

                            case 'future':
                                $opt['conditions']['AND']['CrmContratti.contratto_data_pdr >='] = date('Y-m-d');
                            break;
                        }

                    break;
                }
            }
        }

        $adesioni = $this->_getAdesioni($opt,'all');

        //echo "<pre>";print_r($adesioni);die;

        $rows = [];

        foreach($adesioni as $adesione)
        {

            if(!empty( $adesione['contratto_data_pdr']) )
            {
                $contratto_data_pdr = $adesione['contratto_data_pdr']->i18nFormat('dd/MM/yyyy');
            }else
            {
                $contratto_data_pdr = "";
            }

            if(!empty($adesione['contratto_data_contratto']) ){
              $contratto_data = $adesione['contratto_data_contratto']->i18nFormat('dd/MM/yyyy');
            }else{
              $contratto_data = '';
            }

            $row = [
                $contratto_data,
                $adesione['Scheda']['scheda_nome'],
                $adesione['Scheda']['scheda_piva'],
                $adesione['Comune']['comune_nome'],
                $adesione['contratto_cap_sede'],
                $adesione['Provincia']['provincia_sigla'],
                $adesione['Scheda']['scheda_tel_mobile'],
                $adesione['Scheda']['scheda_tel_fisso'],
                $adesione['Stato_contratto']['statiContratti_descrizione'],
                $adesione['display_name'],
                (isset($adesione['Contratto_pdr']['pdr_nome']) ? $adesione['Contratto_pdr']['pdr_nome'] : ""),
                $contratto_data_pdr

            ];

            // pulsanti tabella
            if(!$xls)
            {
                $row[] = '<a class="edit-adesione btn btn-flat btn-default" data-id="'.$adesione['contratto_id'].'" title="Modifica l\'adesione di '.$adesione['Scheda']['scheda_nome'].'"><i class="glyphicon glyphicon-pencil"></i></a>';
                $row[] = '<input type="checkbox" class="select-adesione" data-id="'.$adesione['contratto_id'].'" />';
            }

            $rows[] = $row;
        }

        unset($opt['limit'],$opt['page'],$opt['fields']);

        $toRet = [
            'rows' => $rows,
            'total_rows' => $this->_getAdesioni($opt,'count')
        ];

        return $toRet;

    }

    /**
    * metodo getContrattoById
    *
    * Dato l'id di un contratto ne restituisce i dati
    *
    * @api
    * @author Sergio Frasca
    * @param integer $id l'id del contratto
    * @param boolean $associated se true va in join con le altre tabelle
    * @return array
    * @throws Exception
    */

    public function getContrattoById($id = "",$associated = false)
    {
        if($id != "")
        {
            if($associated)
            {
                $opt['conditions']['contratto_id'] = $id;
                $opt['contain'] = $this->_contain_adesione;

                return $this->_getAdesioni($opt,'first');

            }else
            {
                return $this->get($id)->toArray();
            }

        }else
        {
            throw new Exception("getContrattoById, id contratto mancante");
        }
    }

    /**
    * metodo saveContratto
    *
    * crea/aggiorna un record in crm_contratti
    * @api
    * @author Sergio Frasca
    * @param array $data i dati da salvare
    * @return boolean
    * @throws Exception
    */

    public function saveContratto($data = [])
    {
        return $this->_saveContratto($data);
    }

    /**
    * metodo getAdesioni
    *
    * restituisce un array di adesioni in base alle opzioni ricevute
    *
    * @api
    * @author Sergio Frasca
    * @param array $opt le opzioni per la lettura
    * @param boolean $associated se true crm_contratti viene messa in join con le altre tabelle
    * @param string $indexBy il campo in base a cui indicizzare l'array di input
    * @return array
    */

    public function getAdesioni($opt = [],$associated = false,$indexBy = "")
    {
        try
        {
            #################################################
            // Eventuali join
            if($associated)
                $opt['contain'] = $this->_contain_adesione;

            #################################################
            // Lettura
            $adesioni = $this->_getAdesioni($opt,'all');

            #################################################
            // eventuale indicizzazione
            if($indexBy != "")
            {
                $toRet = [];
                $indexes = explode(".",$indexBy);

                foreach($adesioni as $adesione)
                {
                    $index = "";
                    for($i = 0;$i < count($indexes);$i++)
                    {
                        $index = $adesione[$indexes[$i]];
                    }

                    $toRet[$index] = $adesione;
                }

            }else
            {
                $toRet = $adesioni;
            }

            return $toRet;

        }catch(\Exception $e)
        {
            return [];
        }
    }

    public function getAdesioniForSecondXls()
    {
        // array con i campi corrispondenti alle colonne della tabella
        $columns = [
            0 => ['field' => 'CrmSchede.scheda_nome','type' => 'text'],
            1 => ['field' => 'CrmComuni.comune_nome','type' => 'text'],
            2 => ['field' => 'CrmContratti.contratto_cap_sede','type' => 'text'],
            3 => ['field' => 'CrmProvince.provincia_sigla','type' => 'text'],
            4 => ['field' => 'CrmSchede.scheda_tel_mobile','type' => 'text'],
            5 => ['field' => 'CrmSchede.scheda_tel_fisso','type' => 'text'],
            6 => ['field' => 'CrmStatiContratti.statiContratti_descrizione','type' => 'text'],
            7 => ['field' => 'Profiles.display_name','type' => 'text'],
            8 => ['field' => 'ContrattiPdr.pdr_nome','type' => 'text'],
            9 => ['field' => 'CrmContratti.contratto_data_pdr','type' => 'date']
        ];

        $orders = [
            0 => 'ASC',
            1 => 'DESC'
        ];
        $contain = ['CrmSchede'=>['Users'=>['Profiles'],'CrmComuni','CrmProvince'],'ContrattiPdr','CrmStatiContratti'];

        #####################################################################
        // setto le condizioni

        $opt['conditions'] = [];
        $opt['conditions']['AND']['CrmContratti.contratto_attivo'] = 1;
        $opt['conditions']['AND']["CrmSchede.scheda_fk_provenienza IN"] = Configure::read('localConfig.provenienza_schede');
        $opt['conditions']['AND']['CrmSchede.scheda_attiva'] = 1;

        $opt['contain'] = $contain;
        //$opt['limit'] = 50000;

        $opt['fields'] = [
            'contrattoId'=>'contratto_id',
            'nome'=>'CrmSchede.scheda_nome',
            'comune'=>'CrmComuni.comune_nome',
            'cap'=>'CrmSchede.scheda_cap',
            'provincia'=>'CrmProvince.provincia_sigla',
            'tel_mobile'=>'CrmSchede.scheda_tel_mobile',
            'tel_fisso'=>'CrmSchede.scheda_tel_fisso',
            'stato'=>'CrmStatiContratti.statiContratti_descrizione',
            'POS'=>'Profiles.display_name',
            'pdr_nome'=>'ContrattiPdr.pdr_nome',
            'data_pdr'=>'CrmContratti.contratto_data_pdr',
            'indirizzo' => 'CONCAT(TRIM(CrmSchede.scheda_indirizzo),SPACE(1),TRIM(CrmSchede.scheda_num_civ))',
            'partita_iva'=>'CrmSchede.scheda_piva',
            'codice_fiscale'=>'CrmSchede.scheda_cf',
            'annuo' => '(35 + IF(CrmSchede.scheda_volume_affari=2 ,13, 0))',
            'data'=>'CrmContratti.contratto_data_pdr'
        ];


        ###########################################################################################################

        // eventuali filtri in sessione
        if($this->_session->check(Configure::read('localConfig.adesioni_filter_prefix')))
        {
            $filters = $this->_session->read(Configure::read('localConfig.adesioni_filter_prefix'));

            //echo "<pre>";print_r($filters);die;

            foreach($filters as $filter => $value)
            {
                switch($filter)
                {
                    case 'filter-pos':

                        $opt['conditions']['AND']['CrmSchede.scheda_fk_user'] = $value;

                    break;

                    case 'filter-pdr':

                        $opt['conditions']['AND']['contratto_fk_pdr'] = $value;

                    break;

                    case 'filter-status':

                        $opt['conditions']['AND']['CrmStatiContratti.statiContratti_id'] = $value;

                    break;

                    case 'filter-date':

                        switch($value)
                        {
                            case 'past':
                                $opt['conditions']['AND']['CrmContratti.contratto_data_pdr <'] = date('Y-m-d');
                            break;

                            case 'future':
                                $opt['conditions']['AND']['CrmContratti.contratto_data_pdr >='] = date('Y-m-d');
                            break;
                        }

                    break;
                }
            }
        }

        $adesioni = $this->_getAdesioni($opt,'all');

        //echo "<pre>";print_r($adesioni);die;

        $rows = [];

        foreach($adesioni as $adesione)
        {



            $row = [
                $adesione['nome'],
                $adesione['partita_iva'],
                $adesione['codice_fiscale'],
                $adesione['indirizzo'],
                $adesione['comune'],
                $adesione['cap'],
                $adesione['provincia'],
                $adesione['annuo'],
                $adesione['tel_fisso'],
                $adesione['tel_mobile'],
                $adesione['POS'],
                $adesione['data']
            ];


            $rows[] = $row;
        }

        unset($opt['limit'],$opt['page'],$opt['fields']);

        $toRet = [
            'rows' => $rows,
            'total_rows' => $this->_getAdesioni($opt,'count')
        ];

        return $toRet;

    }

   /**
    * Restituisce la lista formattata per l'excel delle provvigioni filtrate per
    * anno e mese impostati.
    *
    * @param int $anno
    * @param int $mese
    * @return array lista delle provvigioni
    */
    public function getProvvigioni($anno, $mese)
    {

        #####################################################################
        // setto le condizioni

        $opt['conditions'] = array();
        $opt['conditions']['AND']['CrmContratti.contratto_provvigioni_anno'] = $anno;
        if($mese != 0){
            $opt['conditions']['AND']['CrmContratti.contratto_provvigioni_mese'] = $mese;
        }
        $opt['conditions']['AND']['CrmContratti.contratto_attivo'] = 1;
        $opt['conditions']['AND']['CrmSchede.scheda_attiva'] = 1;

        $opt['contain'] = [ 'CrmSchede'=>[ 'Users'=>['Profiles'], 'CrmComuni' ],
          'CrmProvince'=>['CrmRegioni'],'CrmTipicontratti','ContrattiPdr','CrmStatiContratti','CrmServiziAcquistati'];

        $opt['fields'] = [
            'CrmContratti.contratto_id',
            'commerciale' => 'Users.email',
            'data' => 'CrmContratti.contratto_data_contratto',
            'ass/stu/tdz' => 'CrmContratti.contratto_proposto_da',
            'importo' => 'CrmContratti.contratto_importo_annuo',
            'tipicontratto_nome' => 'CrmTipicontratti.tipocontratto_nome',
            'durata' => 'IF( CrmTipicontratti.tipocontratto_pluriennale = 1 , "PLURIENNALE", "SPOT" )',
            'note' => 'CrmSchede.scheda_nome',
            'scheda_attiva' => 'CrmSchede.scheda_attiva',
            'provincia' => 'CrmProvince.provincia_sigla',
            'regione' => 'CrmRegioni.regione_nome',
            'importo_ritiro'=>'CrmContratti.contratto_importo_ritiro',
            'tipo_contratto_fk'=>'CrmContratti.contratto_fk_tipocontratto',
            'scontoRC' => 'CrmContratti.contratto_scontoRC',
            'scontoAnnuo' => ' CrmContratti.contratto_scontoAnnuo'
        ];

        $res = $this->find('all',$opt)->toArray();
        $toRet = array();

        foreach ($res as $value) {

            if($value['durata'] == 'SPOT'){
              $value['validita_contratto'] = 'SPOT';
            }else{

              $value['validita_contratto'] = '24 mesi';

              foreach($value['ServiziAcquistati'] as $servizi){
                if($servizi['serviziAcquistati_opzione'] == 'ZF' || $servizi['serviziAcquistati_opzione'] == 'ZZ'){
                  $value['validita_contratto'] = '36 mesi';
                }
              }
            }
            $val = array(
              'commerciale' => $value['commerciale'],
              'data'=> $value['data'],
              'ass/stu/tdz'=> $value['ass/stu/tdz'],
              'importo'=> (string)floatval($value['importo']-$value['scontoAnnuo']),
              'tipo_contratto'=> $value['tipicontratto_nome'],
              'durata'=> $value['durata'],
              'note'=> $value['note'],
              'provincia'=> $value['provincia'],
              'regione'=> $value['regione'],
              'validita_contratto'=> $value['validita_contratto'],
              'differenza'=> '',
              'contratto_id' => $value['contratto_id']

            );
            $toRet[] = $val;

            if( ($value['tipo_contratto_fk'] == 1 || $value['tipo_contratto_fk'] == 2) && floatval($value['importo_ritiro']) > 0){
                $val['importo'] = (string)floatval($value['importo_ritiro']-$value['scontoRC']);
                $val['tipo_contratto'] = 'RC';
                $val['durata'] = 'SPOT';
                $val['validita_contratto'] = 'SPOT';
                $toRet[] = $val;

            }



        }

        return $toRet;


    }


    /******************************************************* FUNZIONI PRIVATE *******************************************************************************/

    /**
    * metodo _getAdesioni
    *
    * restitusce un array delle adesioni
    *
    * @api
    * @author Sergio Frasca
    * @param array $opt l'array di opzioni
    * @param string $action l'azione da eseguire (lettura/conteggio ecc)
    * @return array
    */

    private function _getAdesioni($opt = [],$action = 'all')
    {
        try
        {

            $query = $this->find('all',$opt);//debug($query);die;

            switch($action)
            {
                case 'count':
                    return $query->count();
                break;

                case 'first':
                    return $query->first();
                break;

                default:
                    return $query->toArray();
                break;
            }

        }catch(\Exception $e)
        {
            return [];
        }
    }

    /**
    * metodo _saveContratto
    *
    * crea/aggiorna un record in crm_contratti
    * @api
    * @author Sergio Frasca
    * @param array $data i dati da salvare
    * @return boolean
    * @throws Exception
    */

    private function _saveContratto($data = [])
    {
        if(is_array($data) && count($data) > 0)
        {
            $entity = $this->newEntity();

            foreach($data as $field => $val)
            {
                $entity->$field = $val;
            }

            return $this->save($entity);

        }else
        {
            throw new Exception("_saveContratto, dati mancanti o incorretti.");
        }
    }

}
