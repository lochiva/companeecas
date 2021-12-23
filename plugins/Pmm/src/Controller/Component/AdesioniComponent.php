<?php

namespace Pmm\Controller\Component;

use Cake\Controller\Component;
use Cake\I18n\Time;
use Cake\Utility\Text;
use Cake\Controller\ComponentRegistry;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
* classe AdesioniComponent
* Gestisce i metodi relativi alle adesioni
*
* @api
* @category Component
*/

class AdesioniComponent extends Component
{

	public $components = ['Pmm.Utility'];

	/**
	* @api
	* @var $_controller - contiene l'istanza del controller
	*/

	protected $_controller;

	public function startup(Event $event)
	{
		$this->_controller = $this->_registry->getController();
		$this->_crm_contratti = TableRegistry::get('Pmm.CrmContratti');

		$this->Utility->startup($event);

	}

	/**
	* metodo getAdesioniForTable
	*
	* restituisce un array di adesioni nel formato richiesto da tablesorter
	*
	* @api
	* @author Sergio Frasca
	* @param array $params filtri e ordinamento
	* @return array
	*/

	public function getAdesioniForTable($params = [])
	{
		try
		{
			return $this->_crm_contratti->getAdesioniForTable($params);

		}catch(Exception $e)
		{
			return [];
		}
	}

	public function getXlsAdesioni()
	{
		try
		{

	        $adesioni = $this->_crm_contratti->getAdesioniForTable([],true);

	        //echo "<pre>";print_r($adesioni);die;

	        if(isset($adesioni['rows']))
	        {
	        	$objPHPExcel = new \PHPExcel;
				$objPHPExcel->setActiveSheetIndex(0);


				$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Data adesione');
				$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Nome');
				$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Partita IVA');
				$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Comune');
				$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'CAP');
				$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Provincia');
				$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Cell (Tit)');
				$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Cell (Rap)');
				$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Stato');
				$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'POS');
				$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'PDR');
				$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Data');

				// filtri
				$objPHPExcel->getActiveSheet()->setAutoFilter('A1:L1');

				// Autosize delle celle
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	        	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	        	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	        	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	        	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	        	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	        	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	        	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

	        	$objPHPExcel->getActiveSheet()->fromArray($adesioni['rows'], NULL, 'A2');

						// Setto per ogni cella dell'excel il tipo di dato a stringa.
						$cells = $objPHPExcel->getActiveSheet()->getCellCollection();
						foreach($cells as $cell){
							$objPHPExcel->getActiveSheet()->getCell($cell)->setDataType(\PHPExcel_Cell_DataType::TYPE_STRING);
						}

	        	$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

	        	header("Content-Type: application/xls");
		        header("Content-Disposition: attachment; filename=lista_adesioni_". date('d_m_Y') .".xlsx");
		        header("Pragma: no-cache");

		        $objWriter->save('php://output');
						//$objWriter->save('/Applications/XAMPP/htdocs/tmp/'."lista_adesioni_schede_". date('d_m_Y') .".xlsx");
	        }else
	        {
	        	throw new Exception("Errore durante la lettura delle adesioni");
	        }

		}catch(\Exception $e)
		{
			die("Impossibile generare il file xls.");
		}


	}

	public function getSecondXlsAdesioni()
	{
		try
		{

	        $adesioni = $this->_crm_contratti->getAdesioniForSecondXls();

	        //echo "<pre>";print_r($adesioni);die;

	        if(isset($adesioni['rows']))
	        {
	        	$objPHPExcel = new \PHPExcel;
				$objPHPExcel->setActiveSheetIndex(0);


				$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Nome');
				$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Partita IVA');
				$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Codice Fiscale');
				$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Indirizzo Sede Legale');
				$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Comune Sede Legale');
				$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'CAP Sede Legale');
				$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Provincia Sede Legale');
				$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Annuo €');
				$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Tel');
				$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Cell');
				$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'POS');
				$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Data');

				// filtri
				$objPHPExcel->getActiveSheet()->setAutoFilter('A1:L1');

				// Autosize delle celle
				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
	        	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
	        	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
	        	$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
	        	$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
	        	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
	        	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
	        	$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);



	        	$objPHPExcel->getActiveSheet()->fromArray($adesioni['rows'], NULL, 'A2');

						// Setto per ogni cella dell'excel il tipo di dato a stringa.
						$cells = $objPHPExcel->getActiveSheet()->getCellCollection();
						foreach($cells as $cell){
							$objPHPExcel->getActiveSheet()->getCell($cell)->setDataType(\PHPExcel_Cell_DataType::TYPE_STRING);
						}

	        	$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

	        	header("Content-Type: application/xls");
		        header("Content-Disposition: attachment; filename=lista_adesioni_schede_". date('d_m_Y') .".xlsx");
		        header("Pragma: no-cache");

		        $objWriter->save('php://output');
						//$objWriter->save('/Applications/XAMPP/htdocs/tmp/'."lista_adesioni_schede_". date('d_m_Y') .".xlsx");
	        }else
	        {
	        	throw new Exception("Errore durante la lettura delle adesioni");
	        }

		}catch(\Exception $e)
		{
			die("Impossibile generare il file xls.");
		}


	}

	/**
	* metodo getAdesione
	*
	* Dato l'id di un contratto ne restituisce i dati
	*
	* @api
	* @author Sergio Frasca
	* @param integer $id l'id del contratto
	* @param boolean $associated se true va in join con le altre tabelle
	* @return array
	*/


	public function getAdesione($id = "",$associated = false)
	{
		try
		{
			if($id != "")
				return $this->_crm_contratti->getContrattoById($id,$associated);
			else
				throw new Exception("getAdesione, id contratto mancante");

		}catch(\Exception $e)
		{
			return [];
		}
	}

	/**
	* metodo saveAdesione
	*
	* crea/aggiorna un record in crm_contratti
	*
	* @api
	* @author Sergio Frasca
	* @param array $data i dati da salvare
	* @return boolean
	*/

	public function saveAdesione($data = [])
	{
		try
		{
			//trasformo l'eventuale data italiana in formato mysql
			if(isset($data['contratto_data_pdr']) && $this->Utility->isItalianDate($data['contratto_data_pdr']))
				$data['contratto_data_pdr'] = date('Y-m-d',strtotime(str_replace("/", "-", $data['contratto_data_pdr'])));

			return $this->_crm_contratti->saveContratto($data);

		}catch(\Exception $e)
		{
			return false;
		}
	}

	/**
	* metodo saveAdesioniMultiple
	*
	* salva più record in crm contratti
	*
	* @api
	* @author Sergio Frasca
	* @param array $data i dati da salvare
	* @return array
	*/

	public function saveAdesioniMultiple($data = [])
	{
		try
		{
			if(is_array($data) && count($data) > 0)
			{
				if(isset($data['ids']))
				{
					$toRet = [];

					#########################################################################################
					//leggo i dati delle adesioni, mi serviranno dopo
					$opt['conditions']['contratto_id IN'] = $data['ids'];

					$adesioni = $this->_crm_contratti->getAdesioni($opt,true,'contratto_id');

					//echo "<pre>";print_r($adesioni);die;

					#########################################################################################

					foreach($data['ids'] as $id)
					{
						// merge tra l'id contratto e gli altri campi
						$toSave = array_merge(['contratto_id' => $id],array_diff_key($data, ['ids' => ""]));

						// controllo le date
						foreach($toSave as $key => $val)
						{
							if($this->Utility->isItalianDate($val))
								$toSave[$key] = date('Y-m-d',strtotime(str_replace("/", "-", $val)));
						}

						//echo "<pre>";print_r($toSave);die;

						//salvo l'esito del salvataggio
						if($this->_crm_contratti->saveContratto($toSave))
							$esito = "Salvato con successo.";
						else
							$esito = "Errore nel salvataggio.";

						$toRet['data'][$adesioni[$toSave['contratto_id']]['Scheda']['scheda_nome']] = $esito;
					}

					#########################################################################################

					$toRet['response'] = 'OK';

					return $toRet;

				}else
				{
					throw new Exception("Impossibile salvare le adesioni, nessun id contratto ricevuto",0);
				}

			}else
			{
				throw new Exception("Impossibile salvare le adesioni, dati mancanti o incorretti",0);
			}
		}catch(\Exception $e)
		{
			if($e->getCode() == 0)
				$msg = $e->getMessage();
			else
				$msg = "Si è verificato un errore.";

			return [
				'response' => 'KO',
				'msg' => $msg
			];
		}
	}

	 /**
	  * Genera il file excel della lista delle provvigioni.
	  *
	  * @param int $anno
	  * @param int $mese
	  * @return void
	  */
		public function getProvvigioniForXls($anno,$mese)
		{
			$this->crm_contrattiTable= TableRegistry::get('Pmm.CrmContratti');
			try {
					$listaProvvigioni = $this->crm_contrattiTable->getProvvigioni($anno,$mese);
				//echo "<pre>";print_r($listaProvvigioni);die;

				if (!empty($listaProvvigioni)) {
						$objPHPExcel = new \PHPExcel();
						$objPHPExcel->setActiveSheetIndex(0);

						$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'COMMERCIALE');
						$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'DATA');
						$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'ASS/STU/TDZ');
						$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'IMPORTO');
						$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'TIPO CONTRATTO');
						$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'DURATA');
						$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'NOTE');
						$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'PROVINCIA');
						$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'REGIONE');
						$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'VALIDITA\' CONTRATTO');
						$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'DIFFERENZA IMPORTO NON CALCOLATO');
						$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'CONTRATTO ID');

			// filtri
			$objPHPExcel->getActiveSheet()->setAutoFilter('A1:L1');

			// Autosize delle celle
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);

						$objPHPExcel->getActiveSheet()->fromArray($listaProvvigioni, null, 'A2');

					// Setto per ogni cella dell'excel il tipo di dato coretto, saltando la prima riga.
					$cells = $objPHPExcel->getActiveSheet()->getCellCollection();
						foreach ($cells as $key => $cell) {
							if($key >	10){
								if(strpos($cell,'D') !== false){
									$objPHPExcel->getActiveSheet()->getCell($cell)->setDataType(\PHPExcel_Cell_DataType::TYPE_NUMERIC);
								}else{
									$objPHPExcel->getActiveSheet()->getCell($cell)->setDataType(\PHPExcel_Cell_DataType::TYPE_STRING);
								}
							}
						}

						$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

						header('Content-Type: application/xls');
						header('Content-Disposition: attachment; filename=lista_provvigioni_'.$anno.(!empty($mese)?'-'.$mese:'').'.xlsx');
						header('Pragma: no-cache');

						$objWriter->save('php://output');

				} else {
						throw new \Exception('Nessuna provvigione trovata per il periodo selezionato.');
				}
			} catch (\Exception $e) {
					die('Impossibile generare il file xls. '.$e->getMessage());
			}

		}

	/******************************************************* FUNZIONI PRIVATE *******************************************************************************/


}
