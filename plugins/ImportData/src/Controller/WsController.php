<?php
namespace ImportData\Controller;

use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\Filesystem\File;
use Cake\ORM\TableRegistry;

/**
 * ImportData Controller
 */
class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('ImportData.ImportData');
		$this->loadComponent('ImportData.Filters');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

		$this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');
        $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");


    }

    public function beforeRender(Event $event) {
        parent::beforeFilter($event);
        $this->set('result', json_encode($this -> _result));
    }

	public function preElaborazione(){

		$file = '';
		$table = [];
		$tables = Configure::read('importDataConfig.Tables');
		$path = Configure::read('importDataConfig.filesPath');

		if($this->request->is('post')){

			$tmpFile = $this->request->data['file']['tmp_name'];
			$table = $tables[$this->request->data['table']];
			$heading = $this->request->data['heading'];
			$delimiter = trim($this->request->data['delimiter']);

	        if($tmpFile != '' && isset($table) && !empty($table)){

				$csv = $file = new File($tmpFile, true, 0644);

				$contents = $csv->read();

				if(trim($contents) != ''){

					$data = [];

					//Salvo il file
					$res = $this->ImportData->uploadFile($tmpFile, $path);

					if($res){

						$file = $res;

						$data['file'] = $file;

						//Prendo schema della tabella
						$schema = $this->ImportData->getSchemaTable($table['tableName']);

						$fields = [];

						foreach($schema as $field){
							$active = true;

							//Se da configurazione il campo e bloccato lo setto
							if(in_array($field['Field'], $table['fieldsLocked'])){
								$active = false;
							}

							$fieldType = explode('(', $field['Type']);

							$fields[] = [
								'field' => $field['Field'],
								'field_type' => $fieldType[0],
								'active' => $active
							];
						}

						$data['fields'] = $fields;

						$columns = [];

						//Prendo file e splitto le righe
						$csv = new File($path.'/'.$file, true, 0644);

						$contents = $csv->read();

						$lines = preg_split("/\\r\\n|\\r|\\n/", $contents);
						$lines = array_filter($lines);
						$lines = array_values($lines);
						$linesSplitted = [];

						if($lines[0] == str_getcsv(utf8_encode($lines[0]), $delimiter)[0]){
							$this->_result['response'] = 'KO';
		                    $this->_result['data'] = -1;
		                    $this->_result['msg'] = 'Separatore dei valori del file errato. Parametro non trovato.';
						}else{

							foreach($lines as $line){
								$linesSplitted[] = str_getcsv(utf8_encode($line), $delimiter);
							}

							//Se prima riga intesazione ne prendo i valori da usare come campi file
							//e i valori della seconda riga diventano i valori prima riga
							if($heading == 'true'){
								for($i = 0; $i < sizeof($linesSplitted[0]); $i++){
									$columns[] = [
										'field' => $linesSplitted[0][$i],
										'value' => $linesSplitted[1][$i]
									];
								}
								$data['total_rows'] = sizeof($linesSplitted)-1;
							//altrimenti valori campi file = colonna più numero crescente
							}else{
								for($i = 0; $i < sizeof($linesSplitted[0]); $i++){
									$columns[] = [
										'field' => 'colonna '.($i+1),
										'value' => $linesSplitted[0][$i]
									];
								}
								$data['total_rows'] = sizeof($linesSplitted);
							}

							$data['columns'] = $columns;

							//Prendo la lista dei filtri
							$filters = $this->ImportData->getFilters();
							$data['filters'] = $filters;

							//Prendo la rispettiva lista delle label per i filtri
							$filterLabels = Configure::read('importDataConfig.filterLabels');
							$data['filter_labels'] = $filterLabels;

		                    $this->_result['response'] = 'OK';
		                    $this->_result['data'] = $data;
		                    $this->_result['msg'] = '';
						}
					}else{
						$this->_result['response'] = 'KO';
	                    $this->_result['data'] = -1;
	                    $this->_result['msg'] = 'Errore nel caricamento del file.';
					}
                }else{
                    $this->_result['response'] = 'KO';
                    $this->_result['data'] = -1;
                    $this->_result['msg'] = 'Errore. Il file è vuoto.';
                }

	        }else{
	            $this->_result['response'] = 'KO';
	            $this->_result['data'] = -1;
	            $this->_result['msg'] = 'Errore nella ricezione dei dati';
	        }
		}else{
			$this->_result['response'] = 'KO';
			$this->_result['data'] = -1;
			$this->_result['msg'] = 'Metodo della chiamata errato.';
		}

	}

	public function elaborazione(){

		$file = '';
		$table = '';
		$overwrite = false;
		$heading = false;
		$tables = Configure::read('importDataConfig.Tables');
		$path = Configure::read('importDataConfig.filesPath');

		if($this->request->is('post')){

			$file = $this->request->data['file'];
			$table = $tables[$this->request->data['table']]['tableName'];
			$overwrite = $this->request->data['overwrite'];
			$heading = $this->request->data['heading'];
			$data_fields = $this->request->data['data_fields'];
			$delimiter = trim($this->request->data['delimiter']);

	        if($file != '' && $table != '' && is_array($data_fields) && !empty($data_fields) && isset($heading) && isset($overwrite)){

				$db = ConnectionManager::get('default');

				//Prendo file e splitto le righe
				$csv = new File($path.'/'.$file, true, 0644);

				$contents = $csv->read();

				$lines = preg_split("/\\r\\n|\\r|\\n/", $contents);
				$lines = array_filter($lines);

				if($heading == 'true'){
					$totalLines = sizeof($lines)-1;
				}else{
					$totalLines = sizeof($lines);
				}

				$linesExecuted = 0;

				//Se è settato ELIMINA VECCHIO CONTENUTO faccio truncate della table
				if($overwrite == 'true'){
					$query = 'TRUNCATE TABLE '.$table.';';
					$db->execute($query);
				}

				$fieldsLocked = $tables[$this->request->data['table']]['fieldsLocked'];
				$created = false;
				$modified = false;

				//Verifico se presenti campi created e modified
				foreach($fieldsLocked as $f){
					if($f == 'created'){
						$created = true;
					}
					if($f == 'modified'){
						$modified = true;
					}
				}

				$count = 0;
				foreach($lines as $line){
					$skipLine = false;
					if($heading != 'true' || $count != 0){
						$lineSplitted = str_getcsv(utf8_encode($line), $delimiter);
						$data = [];

						foreach($data_fields as $field){
							if($field['file_column'] != ''){
								//Prendo valore da salvare dal file
								$fileValue = $lineSplitted[$field['file_column']];

								//Se è un campo richiesto e non ha un valore non salvo la linea
								if($field['required_field'] == 'true' && $fileValue == ''){
									$skipLine = true;
									break;
								}

								//Se settato filtro lo applico al valore prima di salvarlo
								if($field['filter'] != ''){
									$filter = $field['filter'];
									if($field['param'] != ''){
										$data[$field['table_field']] = $this->Filters->$filter($fileValue,$field['param']);
									}else{
										$data[$field['table_field']] = $this->Filters->$filter($fileValue);
									}
								}else{
									$data[$field['table_field']] = $fileValue;
								}
							}
						}

						if($skipLine != true){
							if($created){
								$data['created'] = date('Y-m-d H:i:s');
							}
							if($modified){
								$data['modified'] = date('Y-m-d H:i:s');
							}

							//Eseguo la query per fare l'insert dei valori
							$res = $db->insert($table, $data);

							if($res){
								$linesExecuted++;
							}
						}
					}

					$count++;
				}

				//Loggo nella tabella log_file_upload l'operazione eseguita
				$logFileUpload = TableRegistry::get('ImportData.LogFileUpload');
				$log = $logFileUpload->newEntity();

				$log->user = $this->request->session()->read('Auth.User.id');
				$log->table_name = $table;
				$log->file = $file;
				$log->date = date('Y-m-d H:i:s');

				$logFileUpload->save($log);

				$data['executed_rows'] = $linesExecuted;
				$data['total_rows'] = $totalLines;

				$this->_result['response'] = 'OK';
	            $this->_result['data'] = $data;
	            $this->_result['msg'] = 'Caricamento avvenuto correttamente.';

	        }else{

	            $this->_result['response'] = 'KO';
	            $this->_result['data'] = -1;
	            $this->_result['msg'] = 'Errore nella ricezione dei dati';

	        }
		}else{
			$this->_result['response'] = 'KO';
			$this->_result['data'] = -1;
			$this->_result['msg'] = 'Metodo della chiamata errato.';
		}
	}

	public function checkvalue(){

		$value = $this->request->data['value'];
		$fieldType = $this->request->data['field_type'];

		$check = false;

		switch($fieldType){
			case 'char':
			case 'varchar':
			case 'tinytext':
			case 'text':
			case 'mediumtext':
			case 'longtext':
				$check = true;
				break;
			case 'bit':
			case 'tinyint':
			case 'smallint':
			case 'mediumint':
			case 'int':
			case 'bigint':
				if(is_numeric($value)){
					if((int)$value == $value){
						$check = true;
					}
				}
				break;
			case 'decimal':
			case 'float':
			case 'double':
			case 'real':
				if(is_numeric($value)){
					$check = true;
				}
				break;
			case 'date':
			case 'datetime':
				$timestamp = strtotime($value);
				if($timestamp){
					$date = date('Y-m-d H:i:s', $timestamp);
					if($date == $value){
						$check = true;
					}else{
						if($date == $value.' 00:00:00'){
							$check = true;
						}
					}
				}
				break;
		}

		$this->_result['response'] = 'OK';
		$this->_result['data'] = $check;
		$this->_result['msg'] = '';

	}

	public function applyFilter(){
		$filter = $this->request->data['filter'];
		$value = $this->request->data['value'];
		$param = $this->request->data['param'];

		if($filter != '' && $value != ''){
			if($param != ''){
				$newValue = $this->Filters->$filter($value, $param);
			}else{
				$newValue = $this->Filters->$filter($value);
			}

			if($newValue === false){
				$this->_result['response'] = 'KO';
				$this->_result['msg'] = 'Applicazione del filtro non riuscita.';
			}else{
				$this->_result['response'] = 'OK';
				$this->_result['data'] = $newValue;
				$this->_result['msg'] = '';
			}
		}else{
	        $this->_result['response'] = 'KO';
	        $this->_result['data'] = -1;
	        $this->_result['msg'] = 'Errore nella ricezione dei dati';
		}
	}

	public function saveConfiguration(){

		$name = $this->request->data['name'];

		$tables = Configure::read('importDataConfig.Tables');
		$table_name = $tables[$this->request->data['table']]['tableName'];

		$required = json_encode($this->request->data['required']);
		$fields = json_encode($this->request->data['fields']);
		$functions = json_encode($this->request->data['functions']);

		$configurationsTable = TableRegistry::get('ImportData.ImportDataConfigurations');

		$configuration = $configurationsTable->newEntity([
			'name' => $name,
			'table_name' => $table_name,
			'required' => $required,
			'fields' => $fields,
			'functions' => $functions
		]);

		if($configurationsTable->save($configuration)){
			$this->_result['response'] = 'OK';
			$this->_result['msg'] = 'Salvataggio configurazione avvenuto con successo.';
		}

	}

	public function getConfigurations($table){

		$tables = Configure::read('importDataConfig.Tables');
		$tableName = $tables[$table]['tableName'];

		$configurationsTable = TableRegistry::get('ImportData.ImportDataConfigurations');
		$configurations = $configurationsTable->find()->where(['table_name' => $tableName])->toArray();

		if($configurations){
			$this->_result['response'] = 'OK';
			$this->_result['data'] = $configurations;
			$this->_result['msg'] = 'Configurazioni trovate.';
		}else{
			$this->_result['response'] = 'KO';
			$this->_result['msg'] = 'Nessuna configurazione trovata per questa tabella.';
		}
	}

	public function loadConfiguration($idConfiguration){

		$configurationsTable = TableRegistry::get('ImportData.ImportDataConfigurations');
		$configuration = $configurationsTable->find()->where(['id' => $idConfiguration])->first();

		$configuration['required'] = json_decode($configuration['required']);
		$configuration['fields'] = json_decode($configuration['fields']);
		$configuration['functions'] = json_decode($configuration['functions']);

		if($configuration){
			$this->_result['response'] = 'OK';
			$this->_result['data'] = $configuration;
			$this->_result['msg'] = 'Configurazione trovata.';
		}
	}

}
