<?php
namespace App\Model\Table;
################################################################################
#
# Companee :   App (https://www.companee.it)
# Copyright (c) lochiva , (http://www.lochiva.it)
#
# Licensed under The GPL  License
# For full copyright and license information, please see the LICENSE.txt
# Redistributions of files must retain the above copyright notice.
#
# @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
# @link          https://www.companee.it Companee project
# @since         1.2.0
# @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
#
################################################################################

use Cake\ORM\Table;
use Cake\Datasource\ConnectionManager;
use Cake\Log\Log;
use Cake\Core\Configure;

class AppTable extends Table
{

	public $beforeFind = true;
	public $afterSave = true;
	public $afterDelete = true;

	public function beforeSave($event, $entity, $options){

		$level = $entity->getOriginal('level');
		if($level !== null ){
			/*if(!isset($_SESSION['Auth']['User']['level'])){
				return false;
			}*/
			if(isset($_SESSION['Auth']['User']['level']) && ($_SESSION['Auth']['User']['level'] < $level || $_SESSION['Auth']['User']['level'] < $entity->level)){
				return false;
			}
		}

		return true;

	}

	public function afterSave($event, $entity, $options){

		if(Configure::read('dbconfig.generico.LOG_DB') && $this->afterSave){
				$conn = ConnectionManager::get('default');

				$action = 'insert';
				$uid = 0;
				if(!$entity->isNew()){
						$action = 'update';
				}
				if(!empty($_SESSION['Auth']['User']['id'])){
					$uid = $_SESSION['Auth']['User']['id'];
				}

				$conn->execute('INSERT INTO `action_log` (`id_user`, `table_name`,`id_record`, `action`, `entity`, `created`)
				 	VALUES (:user,:table,:id_record,:action,:entity,NOW()) ',[
					':table' => $this->table(),
					':action' => $action,
					':entity' => json_encode($entity),
					':user' => $uid,
					':id_record' => $entity->id,
				]);
			}

	}

	public function beforeDelete($event, $entity, $options){

		$level = $entity->getOriginal('level');
		if($level !== null ){
			/*if(!isset($_SESSION['Auth']['User']['level'])){
				return false;
			}*/
			if(isset($_SESSION['Auth']['User']['level']) && ($_SESSION['Auth']['User']['level'] < $level || $_SESSION['Auth']['User']['level'] < $entity->level)){
				return false;
			}
		}

		return true;

	}

	public function afterDelete($event, $entity, $options){

		if(Configure::read('dbconfig.generico.LOG_DB') && $this->afterDelete){
				$conn = ConnectionManager::get('default');

				$action = 'delete';
				$uid = 0;
				if(!empty($_SESSION['Auth']['User']['id'])){
					$uid = $_SESSION['Auth']['User']['id'];
				}

				$conn->execute('INSERT INTO `action_log` (`id_user`, `table_name`,`id_record`, `action`, `entity`, `created`)
				 	VALUES (:user,:table,:id_record,:action,:entity,NOW()) ',[
					':table' => $this->table(),
					':action' => $action,
					':entity' => json_encode($entity),
					':user' => $uid,
					':id_record' => $entity->id,
				]);
		 }

	}

	public function softDelete($entity)
	{
			if($this->beforeDelete(null, $entity, null)){
					$this->afterSave = false;
					$entity['deleted'] = 1;
					if($this->save($entity)){
							$this->afterDelete(null, $entity, null);
							$this->afterSave = true;
							return true;
					}
					$this->afterSave = true;
					return false;
			}
			return false;

	}

	public function triggerBeforeFind($val = true)
	{
			if(is_bool($val)){
					$this->beforeFind = $val;
			}
			return $this;
	}

	public function beforeFind( $event,  $query,  $options, $primary)
	{

		if (empty($options['retrieveDeleted'])) { 
			if(array_search('deleted',$this->schema()->columns()) && $this->beforeFind){
				$query = $query->where([$this->alias().'.deleted' => 0]);
			}
		}

		return $query;
	}

/**
 * [queryForTableSorter description]
 * esempio di come si aspetta le colonne
 * $columns = [
 *	 0 => ['val' => 'Orders.name', 'type' => 'text'],
 *	 1 => ['val' => 'Orders.note', 'type' => 'text'],
 *	 2 => ['val' => 'contatto', 'type' => 'text', 'having' => 1],
 *	 3 => ['val' => 'Orders.created', 'type' => 'text']
 *  ];
 * @param  array  $columns array delle colonne
 * @param  array  $opt     normali opzioni per la query, come 'fields' o 'conditions'
 * @param  array  $pass    la query di tablesorter
 * @param  boolean $count  se si richiede un count o no
 * @return array|int       array dei risultati, il count
 */
	public function queryForTableSorter($columns,$opt,$pass,$count = false)
	{

				//$opt['conditions'] = "";
				//debug($pass);
				if(isset($pass['query']) && !empty($pass['query'])){

						######################################################################################################
						//Gestione paginazione
						if(isset($pass['query']['size']) && isset($pass['query']['page'])){
								$size = intval($pass['query']['size']);
								$page = intval($pass['query']['page']) + 1;
						}else{
								$size = 50;
								$page = 1;
						}

						if($size != "all"){
								$opt['limit'] = $size;
								$opt['page'] = $page;
						}

						######################################################################################################
						//Gestione ordinamento
						if(!empty($pass['query']['column']) && is_array($pass['query']['column'])){
								$opt['order'] = array();
								foreach ($pass['query']['column'] as $key => $value) {

										if(isset($columns[$key])){

											if($value == 1){
													$order = " DESC";
											}else{
													$order = " ASC";
											}
											$opt['order'][$columns[$key]['val']] =  $order ;
										}

								}
						}

						#######################################################################################################
						//Gestione filtro
						if(isset($pass['query']['filter']) && !empty($pass['query']['filter']) && is_array($pass['query']['filter'])){

								foreach ($pass['query']['filter'] as $key => $value) {

										if(isset($columns[$key])){
											switch ($columns[$key]['type']) {
												case 'text':
													$condition = [ $columns[$key]['val']. ' LIKE' => "%" . $value . "%" ];
													break;
												case 'date':
													$value = implode('-', array_reverse(explode('/',$value) ) );
													$condition = [ $columns[$key]['val']. ' LIKE' => "%" . $value . "%" ];
													break;
												case 'currency':
													$value = str_replace(',','.', $value);
													$condition = [ $columns[$key]['val'] =>  $value ];
													break;
												case 'array':
													foreach($value as $val){
														$condition['OR'][] = [ $columns[$key]['val']. ' LIKE' => "%" . $val . "%" ];
													}
													break;
												default:
													$condition = [ $columns[$key]['val'] => $value  ];
													break;
											}

											if(!empty($columns[$key]['having'])){
												$opt['having']['AND'][] = $condition;
											}else{
												$opt['conditions']['AND'][]= $condition;
											}

										}

								}
						}

				}
				//debug($opt);die;
				if($count){
					unset($opt['limit'],$opt['page']);
					return $this->find('all', $opt)->count();

				}else{
					return $this->find('all', $opt)->toArray();
				}

	}

	public function getList($conditions = array())
	{
			return $this->find()->select(['id'=>'id','text'=>'name'])->order(['ordering' => 'ASC'])
				->where($conditions)->toArray();
	}
}
