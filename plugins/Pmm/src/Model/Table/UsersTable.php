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


class UsersTable extends Table
{

	public function initialize(array $config)
	{
		$this->table('users');
		$this->primaryKey('id');
    $this->hasOne('Pmm.Profiles',[
			'className' => 'Pmm.Profiles',
			'foreignKey' => 'user_id',
			'propertyName' => 'Profiles'
		]);
    $this->hasMany('Pmm.CrmContratti',[
      'className' => 'Pmm.CrmContratti',
      'foreignKey' => 'contratto_creator_fk_user',
      'propertyName' => 'CrmContratti'
    ]);
    $this->hasMany('ContrattiPdr',[
      'className' => 'Pmm.ContrattiPdr',
      'foreignKey' => 'pdr_fk_user',
      'propertyName' => 'ContrattiPdr'
    ]);
		$this->belongsTo('Pmm.CrmCoopconsorzi',[
			'className' => 'Pmm.CrmCoopconsorzi',
			'foreignKey' => 'coopconsorzi_id',
			'propertyName' => 'CrmCoopconsorzi'
		]);
		$this->hasMany('Pmm.CrmSchede',[
				'className' => 'Pmm.CrmSchede',
				'foreignKey' => 'scheda_fk_user',
				'propertyName' => 'CrmSchede'
		]);

  }

    /**
    * metodo getPOSForTable
    *
    * restituisce un array contente le informazioni sui Pos e relativi ContrattiPdr
    * nel formato richiesto da tabelsorter
    *
    * @api
    * @author Rafael Esposito
    * @param array $params filtri e ordinamento
    * @return array
    * @throws Exception
    */


    public function retrievePOSForTable($params = [])
    {
        // array con i campi corrispondenti alle colonne della tabella
        $columns = [
						0 => ['field' => 'Consorzio','type' => 'text'],
            1 => ['field' => 'POS','type' => 'text'],
            2 => ['field' => 'Adesioni','type' => 'num'],
            3 => ['field' => 'Indirizzo','type' => 'text'],
            4 => ['field' => 'Citta','type' => 'text'],
            5 => ['field' => 'Provincia','type' => 'text'],
            6 => ['field' => 'Riferimento','type' => 'text'],
            7 => ['field' => 'Cell','type' => 'text'],
            8 => ['field' => 'Tel','type' => 'text']
        ];

        $orders = [
            0 => 'ASC',
            1 => 'DESC'
        ];

        #####################################################################
        // setto le condizioni


        $opt['conditions'] = [];
				$opt['having'] = [];
        $opt['page'] = intval($params['page']) + 1;
				$opt['order'] = [];


        // limit
        if(isset($params['size']))
            $opt['limit'] = $params['size'];

        //ordinamento
        if(is_array($params['column']) && count($params['column']) > 0)
        {

            foreach($params['column'] as $column => $order)
            {
                $opt['order'][$columns[$column]['field']] = $orders[$order];
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

                        $opt['having']['AND'][$columns[$column]['field'] . " LIKE "] =  "%" . $filter . "%";

                    break;

                    case 'date':

                        $exp = explode('/',$filter);
                        $date = implode('-',array_reverse($exp));

                        $opt['having']['AND'][$columns[$column]['field'] . " LIKE "] =  "%" . $date . "%";

                    break;

										case 'num':
											$opt['having']['AND'][$columns[$column]['field']] = $filter ;

                    default:

                    break;
                }
            }
        }

				$posList = $this->_queryPOSForTable($opt);
        $rows = [];
				//echo "<pre>";print_r($posList);die;

        foreach($posList as $pos)
        {
            $rows[] = [
								$pos['Consorzio'],
                $pos['POS'],
                $pos['Adesioni'],
								($pos['Indirizzo'] == null ? '' : $pos['Indirizzo']),
								($pos['Citta'] == null ? '' : $pos['Citta']),
								($pos['Provincia']== null ? '' : $pos['Provincia']),
								($pos['Riferimento']== null ? '' : $pos['Riferimento']),
								($pos['Cell']== null ? '' : $pos['Cell']),
								($pos['Tel']== null ? '' : $pos['Tel']),
                "<a class=\"fa fa-search fa-lg btn btn-sm btn-flat btn-default action-pos\" href=\"adesioni/pos/".$pos['POSid']."\"></a> "
            ];
        }



        $toRet = [
            'rows' => $rows,
            'total_rows' => $this->_queryPOSForTable($opt, true)
        ];

        return $toRet;

    }

		/******************************************************* FUNZIONI PRIVATE *******************************************************************************/

		/**
		* metodo _getUsers
		*
		* restitusce un array
		*
		* @api
		* @author Rafael Esposito
		* @param array $opt l'array di opzioni
		* @param string $action l'azione da eseguire (lettura/conteggio ecc)
		* @return array
		*/

		private function _getUsers($opt = [],$action = 'all')
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

				}catch(Exception $e)
				{
						return [];
				}
		}

		/**
		* metodo _queryPOSForTable
		*
		* restitusce un array o il count della query
		*
		* @api
		* @author Rafael Esposito
		* @param array $opt l'array di opzioni
		* @param bool $count determina se l'azione è un count o meno
		* @return array
		*/
		private function _queryPOSForTable($opt = [], $count=false)
		{
			try
			{

				$posQuery = $this->find()->select(['POS'=>'Profiles.display_name', 'POSid'=>'Users.id','Consorzio'=>'CrmCoopconsorzi.coop_nome',
				'Adesioni' => 'SUM( IF(CrmContratti.contratto_fk_statiContratti = :stato,1,0)*(CrmContratti.contratto_attivo IS TRUE) )' ])->contain(['Profiles'])
				->leftJoinWith('CrmSchede', function($q){ return $q->leftJoinWith('CrmContratti')->where(['CrmSchede.scheda_attiva' => 1]); })->leftJoinWith('CrmCoopconsorzi')->leftJoinWith('ContrattiPdr' , function ($q) {
				       return $q
							 	->leftJoinWith('CrmComuni')
								->select(['Citta' => 'CrmComuni.comune_nome',
								'Provincia' => 'CrmComuni.comune_sigla_provincia','Indirizzo' => 'ContrattiPdr.pdr_indirizzo','Riferimento' => 'ContrattiPdr.pdr_riferimento_nominativo',
									'Cell' => 'ContrattiPdr.pdr_riferimento_telefono','Tel' => 'ContrattiPdr.pdr_telefono'])
								->where(['ContrattiPdr.pdr_issede' => 1]);
				    })->where(['Users.group_id'=>Configure::read('localConfig.PMM')])->having($opt['having'])
						->bind(':stato',Configure::read('localConfig.STATOPMM'),'integer')->group('Users.id');

				if($count){
					return $posQuery->count();
				}else{
					return $posQuery->limit($opt['limit'])->page($opt['page'])->order($opt['order'])->toArray();
				}

			}catch(Exception $e)
			{
					return [];
			}

		}



}
