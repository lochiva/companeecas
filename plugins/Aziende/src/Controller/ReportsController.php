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

    public function reportGuestsEmergenzaUcraina()
    {
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
		$spreadsheet = new Spreadsheet();

		// RIEPILOGO STRUTTURE
		$spreadsheet->getActiveSheet()->setTitle("Lista ospiti Emergenza Ucraina");

		$guestsTable = TableRegistry::get('Aziende.Guests');
        $data = $guestsTable->getDataForExportGuestsEmergenzaUcraina();
		
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
}
