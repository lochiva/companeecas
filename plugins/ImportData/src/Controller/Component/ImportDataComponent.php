<?php
namespace ImportData\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;

class ImportDataComponent extends Component
{

	public function uploadFile($file, $path){

		$res = false;

		$year = date('Y');
		$fileName = date('Y-m-d_H-i-s');

		$savePath = $path.'/'.$year;

		//se il path non esiste lo creo
		if(!is_dir($savePath)){
			mkdir($savePath, 0775, true);
		}

		//Copio il file
		$dest = $savePath .'/'. $fileName . '.csv';
        if(copy($file, $dest)){
            $res =  $year . '/' . $fileName . '.csv';
        }

		return $res;

	}

	public function getFilters() {
	    $filtersComponent = 'ImportData\\Controller\\Component\\FiltersComponent';

		$filtersActions = get_class_methods($filtersComponent);
		$parentActions = get_class_methods(get_parent_class($filtersComponent));
		$filters = array_diff($filtersActions, $parentActions);

	    return $filters;
	}

	public function getSchemaTable($tableName){
		$db = ConnectionManager::get('default');

		$schema = array();

		$query = 'SHOW COLUMNS FROM ' . $tableName;
		$schema = $db->execute($query)->fetchAll('assoc');

		return $schema;

	}


}
