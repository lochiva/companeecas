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

class ProfilesTable extends Table
{
	public function initialize(array $config)
	{
		$this->table('profiles');
		$this->primaryKey('id');

		$this->hasOne('Pmm.Users',[
            'className' => 'Pmm.Users',
            'bindingKey' => 'user_id',
            'foreignKey' => 'id',
            'propertyName' => 'User'
        ]);
	}

	/**
	* metodo getPosList
	*
	* restitusce la lista dei pos in formato id => nome
	*
	* @api
	* @author Sergio Frasca
	* @return array
	*
	*/

	public function getPosList()
	{
		try
		{

			$opt['conditions']['Users.group_id IN'] = Configure::read('localConfig.pos_group');
			$opt['contain'] = ['Users'];
			$opt['fields'] = ['user_id','display_name'];
			$opt['order'] = ['display_name ASC'];


			$pos_list = $this->_getProfile($opt,'all');

			//echo "<pre>";print_r($pos_list);die;

			$toRet = [];

			foreach($pos_list as $pos)
			{
				$toRet[$pos->user_id] = $pos->display_name;
			} 

			return $toRet;

		}catch(\Exception $e)
		{
			return [];
		}
	}

	/************************************************************** FUNZIONI PRIVATE ****************************************************************/

	/**
	* metodo _getProfile
	*
	* Legge da profiles
	*
	* @api
	* @author Sergio Frasca
	* @param array $opt l'array di opzioni
	* @param string $action il tipo di azione da eseguire (lettura/conteggio ecc)
	* @return array
	*/

	private function _getProfile($opt = [],$action = 'all')
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