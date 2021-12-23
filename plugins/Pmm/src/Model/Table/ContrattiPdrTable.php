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

class ContrattiPdrTable extends Table
{

	public function initialize(array $config)
	{
		$this->table('contratti_pdr');
		$this->primaryKey('pdr_id');
		$this->addBehavior('Timestamp');
		$this->belongsTo('CrmComuni',[
				'className' => 'Pmm.CrmComuni',
				'foreignKey' => 'pdr_fk_comune',
				'propertyName' => 'Comune'
		]);
		$this->belongsTo('Pmm.Users',[
			'className' => 'Pmm.Users',
			'foreignKey' => 'pdr_fk_user',
			'propertyName' => 'User'
		]);

	}

	/**
	* metodo getPdrList
	*
	* restitusce la lista dei pdr in formato id => nome
	*
	* @api
	* @author Sergio Frasca
	* @return array
	*
	*/

	public function getPdrList()
	{
		try
		{

			$opt['conditions']['pdr_ispdr'] = 1;
			$opt['fields'] = ['pdr_id','pdr_nome'];
			$opt['order'] = ['pdr_nome ASC'];

			$pdr_list = $this->_getContrattiPdr($opt,'all');

			//echo "<pre>";print_r($pdr_list);die;

			$toRet = [];

			foreach($pdr_list as $pdr)
			{
				$toRet[$pdr->pdr_id] = $pdr->pdr_nome;
			}

			return $toRet;

		}catch(\Exception $e)
		{
			return [];
		}
	}

	/******************************************************* FUNZIONI PRIVATE *******************************************************************************/

	/**
	* metodo _getContrattiPdr
	*
	* Legge da contratti_pdr
	*
	* @api
	* @author Sergio Frasca
	* @param array $opt l'array di opzioni
	* @param string $action il tipo di azione da eseguire (lettura/conteggio ecc)
	* @return array
	*/

	private function _getContrattiPdr($opt = array(),$action = 'all')
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

}
