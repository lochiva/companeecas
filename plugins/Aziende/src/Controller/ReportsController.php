<?php
namespace Aziende\Controller;

use Aziende\Controller\AppController;
use Cake\ORM\TableRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Reports Controller
 */
class ReportsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Aziende.Guest');
    }

    public function isAuthorized($user)
    {
        if($user['role'] == 'admin' || $user['role'] == 'ente'){
            return true;
        }else{
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return true;
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $user = $this->request->session()->read('Auth.User');
		$azienda = TableRegistry::get('Aziende.Aziende')->getAziendaByUser($user['id']);

		$this->set('role', $user['role']);
		$this->set('azienda', $azienda);
    }

	public function reportGuestsCas()
    {
		set_time_limit(120);

        $date = implode('-', array_reverse(explode('/', $this->request->query['date'])));

		$spreadsheet = new Spreadsheet();

		// RIEPILOGO STRUTTURE
		$spreadsheet->getActiveSheet()->setTitle("Report");

		$sediTable = TableRegistry::get('Aziende.Sedi');
        $dataReport = $sediTable->getDataForReportGuestsCas($date);
		
		//ultima colonna e dimensione automatica delle celle
		$c = 'A';
		for($i = 1; $i < count($dataReport[0]); $i++){
			$spreadsheet->getActiveSheet()->getColumnDimension($c)->setAutoSize(true);
			++$c;
		}

		//filtri riga intestazione
        $spreadsheet->getActiveSheet()->setAutoFilter('A1:'.$c.'1');

		//grassetto riga intestazione
		$spreadsheet->getActiveSheet()->getStyle('A1:'.$c.'1')
			->getFont()->setBold(true);

		$spreadsheet->getActiveSheet()->fromArray($dataReport, NULL);
		
		$spreadsheet->getActiveSheet()->freezePane('A2');


		// DETTAGLIO STRUTTURE
		$spreadsheet->createSheet(NULL, 1);
        $spreadsheet->setActiveSheetIndex(1);
		$spreadsheet->getActiveSheet()->setTitle("Dettaglio Ucraini in CAS");

        $dataDettaglio = $sediTable->getDataForDettaglioGuestsCas($date);

		//ultima colonna e dimensione automatica delle celle
		$c = 'A';
		for($i = 1; $i < count($dataDettaglio[0]); $i++){
			$spreadsheet->getActiveSheet()->getColumnDimension($c)->setAutoSize(true);
			++$c;
		}

		//filtri riga intestazione
        $spreadsheet->getActiveSheet()->setAutoFilter('A1:'.$c.'1');

		//grassetto riga intestazione
		$spreadsheet->getActiveSheet()->getStyle('A1:'.$c.'1')
			->getFont()->setBold(true);

		$spreadsheet->getActiveSheet()->fromArray($dataDettaglio, NULL);

		$spreadsheet->setActiveSheetIndex(0);

        $filename = str_replace('-', '.', $date)." TORINO";

		setcookie('downloadStarted', '1', false, '/');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

		$writer->save('php://output');

		exit;

    }

    public function reportGuestsEmergenzaUcraina()
    {
		set_time_limit(120);

        $date = implode('-', array_reverse(explode('/', $this->request->query['date'])));

		$spreadsheet = new Spreadsheet();

		// RIEPILOGO STRUTTURE
		$spreadsheet->getActiveSheet()->setTitle("Dettaglio Ucraini extra CAS");

		$sediTable = TableRegistry::get('Aziende.Sedi');
        $dataSedi = $sediTable->getDataForReportGuestsEmergenzaUcraina($date);
		
		//ultima colonna
		$c = 'A';
		for($i = 1; $i < count($dataSedi[0]); $i++){
			++$c;
		}

		//filtri riga intestazione
        $spreadsheet->getActiveSheet()->setAutoFilter('A1:'.$c.'1');

		//grassetto riga intestazione
		$spreadsheet->getActiveSheet()->getStyle('A1:'.$c.'1')
			->getFont()->setBold(true);

		//dimensione automatica delle celle
		$i = 'A';
		foreach($dataSedi[0] as $col){
			$spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
			++$i;
		}

		$spreadsheet->getActiveSheet()->fromArray($dataSedi, NULL);
		
		$spreadsheet->getActiveSheet()->freezePane('A2');


		// RIEPILOGO TITOLI DI STUDIO
		$spreadsheet->createSheet(NULL, 1);
        $spreadsheet->setActiveSheetIndex(1);
		$spreadsheet->getActiveSheet()->setTitle("Titolo di studio");

        $dataTitoliStudio = $this->Guest->getDataForReportGuestsEmergenzaUcraina($date);

		//grassetto celle di intestazione
		$spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('B4')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('C4')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A5')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A6')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A7')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A8')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A9')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A10')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A11')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A12')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A13')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A14')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A15')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A16')->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle('A17')->getFont()->setBold(true);

		//bordi celle
		$spreadsheet->getActiveSheet()->getStyle('A1')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$spreadsheet->getActiveSheet()->getStyle('B1')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$spreadsheet->getActiveSheet()->getStyle('A2')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$spreadsheet->getActiveSheet()->getStyle('B2')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$spreadsheet->getActiveSheet()->getStyle('B4')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		$spreadsheet->getActiveSheet()->getStyle('C4')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		for ($i = 5; $i <= 17; $i++) {
			$spreadsheet->getActiveSheet()->getStyle('A'.$i)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$spreadsheet->getActiveSheet()->getStyle('B'.$i)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
			$spreadsheet->getActiveSheet()->getStyle('C'.$i)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
		}

		//dimensione automatica delle celle
		$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(25);
		$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(25);
		$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(25);

		$spreadsheet->getActiveSheet()->getStyle('A1:C17')->getAlignment()->setWrapText(true);
		$spreadsheet->getActiveSheet()->getStyle('A1:C17')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		$spreadsheet->getActiveSheet()->fromArray($dataTitoliStudio, NULL);

		$spreadsheet->setActiveSheetIndex(0);

        $filename = "REPORT SETTIMANALE";

		setcookie('downloadStarted', '1', false, '/');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

		$writer->save('php://output');

		exit;

    }

	public function exportGuestsEmergenzaUcraina()
    {
		set_time_limit(120);

		$spreadsheet = new Spreadsheet();

		// RIEPILOGO STRUTTURE
		$spreadsheet->getActiveSheet()->setTitle("Lista ospiti Emergenza Ucraina");

		$guestsTable = TableRegistry::get('Aziende.Guests');

		// Se ruolo ente, ricerca ospiti solo per quell'ente
        $user = $this->request->session()->read('Auth.User');
        if ($user['role'] == 'ente') {
            $contatto = TableRegistry::get('Aziende.Contatti')->getContattoByUser($user['id']);
			$data = $guestsTable->getDataForExportGuests(2, $contatto['id_azienda']);
        } else {
			$data = $guestsTable->getDataForExportGuests(2);
		}
        
		
		//ultima colonna
		$c = 'A';
		for($i = 1; $i < count($data[0]); $i++){
			++$c;
		}

		//filtri riga intestazione
        $spreadsheet->getActiveSheet()->setAutoFilter('A1:'.$c.'1');

		//grassetto riga intestazione
		$spreadsheet->getActiveSheet()->getStyle('A1:'.$c.'1')
			->getFont()->setBold(true);

		//dimensione automatica delle celle
		$i = 'A';
		foreach($data[0] as $col){
			$spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
			++$i;
		}

		$spreadsheet->getActiveSheet()->fromArray($data, NULL);
		
		$spreadsheet->getActiveSheet()->freezePane('A2');

		$spreadsheet->setActiveSheetIndex(0);

        $filename = "LISTA OSPITI EMERGENZA UCRAINA";

		setcookie('downloadStarted', '1', false, '/');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

		$writer->save('php://output');

		exit;

    }

	public function exportGuestsCasPresenze()
    {
		set_time_limit(120);
		
		$year = $this->request->query['year'];
		$month = $this->request->query['month'];

		$sediTable = TableRegistry::get('Aziende.Sedi');

		$user = $this->request->session()->read('Auth.User');
		$contatto = TableRegistry::get('Aziende.Contatti')->getContattoByUser($user['id']);
		$azienda = TableRegistry::get('Aziende.Aziende')->get($contatto['id_azienda']);

        $data = $sediTable->getDataForExportGuestsCasPresenze($contatto['id_azienda'], $year, $month);

		$spreadsheet = new Spreadsheet();

		foreach ($data as $index => $sedeData) {
			// DETTAGLIO STRUTTURE
			if ($index > 0) {
				$spreadsheet->createSheet(NULL, 1);
			}
			$spreadsheet->setActiveSheetIndex($index);
			$spreadsheet->getActiveSheet()->setTitle($sedeData['name']);

			//ultima colonna e dimensione automatica delle celle
			$c = 'A';
			for($i = 1; $i < count($sedeData['data'][0]); $i++){
				$spreadsheet->getActiveSheet()->getColumnDimension($c)->setAutoSize(true);
				++$c;
			}

			//unione righe instestazione
			$spreadsheet->getActiveSheet()->mergeCells('A1:E1');
			$spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$spreadsheet->getActiveSheet()->mergeCells('A2:E2');
			$spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$spreadsheet->getActiveSheet()->mergeCells('F1:W1');
			$spreadsheet->getActiveSheet()->getStyle('F1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$spreadsheet->getActiveSheet()->mergeCells('F2:W2');
			$spreadsheet->getActiveSheet()->getStyle('F2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$spreadsheet->getActiveSheet()->mergeCells('X1:Z1');
			$spreadsheet->getActiveSheet()->getStyle('X1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$spreadsheet->getActiveSheet()->mergeCells('AA1:AG1');
			$spreadsheet->getActiveSheet()->getStyle('AA1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$spreadsheet->getActiveSheet()->mergeCells('AH1:AJ1');
			$spreadsheet->getActiveSheet()->getStyle('AH1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
			$spreadsheet->getActiveSheet()->mergeCells('AK1:AO1');
			$spreadsheet->getActiveSheet()->getStyle('AK1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

			$spreadsheet->getActiveSheet()->mergeCells('A3:I3');
			$spreadsheet->getActiveSheet()->mergeCells('J3:AO3');
			$spreadsheet->getActiveSheet()->getStyle('J3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

			//Dimensione testo prima intestazione
			$spreadsheet->getActiveSheet()->getStyle('A1:'.$c.'1')->getFont()->setSize(16);
			$spreadsheet->getActiveSheet()->getStyle('A2:'.$c.'2')->getFont()->setSize(16);

			//filtri riga intestazione
			$spreadsheet->getActiveSheet()->setAutoFilter('A4:'.$c.'4');

			//grassetto riga intestazione
			$spreadsheet->getActiveSheet()->getStyle('A1:'.$c.'1')
				->getFont()->setBold(true);
			$spreadsheet->getActiveSheet()->getStyle('A2:'.$c.'2')
				->getFont()->setBold(true);
			$spreadsheet->getActiveSheet()->getStyle('A3:'.$c.'3')
				->getFont()->setBold(true);
			$spreadsheet->getActiveSheet()->getStyle('A4:'.$c.'4')
				->getFont()->setBold(true);

			$spreadsheet->getActiveSheet()->fromArray($sedeData['data'], NULL);
		}

		$spreadsheet->setActiveSheetIndex(0);

		$monthLabels = [
			'01' => 'GENNAIO',
			'02' => 'FEBBRAIO',
			'03' => 'MARZO',
			'04' => 'APRILE',
			'05' => 'MAGGIO',
			'06' => 'GIUGNO',
			'07' => 'LUGLIO',
			'08' => 'AGOSTO',
			'09' => 'SETTEMBRE',
			'10' => 'OTTOBRE',
			'11' => 'NOVEMBRE',
			'12' => 'DICEMBRE',
		];

        $filename = "LISTA OSPITI ".$azienda['denominazione']." ".$monthLabels[$month]." ".$year;

		setcookie('downloadStarted', '1', false, '/');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

		$writer->save('php://output');

		exit;

    }

	public function exportGuestsCas()
    {
		set_time_limit(120);

		$spreadsheet = new Spreadsheet();

		// RIEPILOGO STRUTTURE
		$spreadsheet->getActiveSheet()->setTitle("Lista ospiti CAS");

		$guestsTable = TableRegistry::get('Aziende.Guests');

		$data = $guestsTable->getDataForExportGuests(1);
		
		//ultima colonna
		$c = 'A';
		for($i = 1; $i < count($data[0]); $i++){
			++$c;
		}

		//filtri riga intestazione
        $spreadsheet->getActiveSheet()->setAutoFilter('A1:'.$c.'1');

		//grassetto riga intestazione
		$spreadsheet->getActiveSheet()->getStyle('A1:'.$c.'1')
			->getFont()->setBold(true);

		//dimensione automatica delle celle
		$i = 'A';
		foreach($data[0] as $col){
			$spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
			++$i;
		}

		$spreadsheet->getActiveSheet()->fromArray($data, NULL);
		
		$spreadsheet->getActiveSheet()->freezePane('A2');

		$spreadsheet->setActiveSheetIndex(0);

        $filename = "LISTA OSPITI CAS";

		setcookie('downloadStarted', '1', false, '/');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

		$writer->save('php://output');

		exit;

    }
}
