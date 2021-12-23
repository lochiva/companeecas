<?php
namespace Consulenza\Controller;

use Consulenza\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

require_once(ROOT . DS . 'vendor' . DS  . 'phpoffice' . DS . 'phpexcel' . DS . 'Classes' . DS . 'PHPExcel.php');
require_once(ROOT . DS . 'vendor' . DS  . 'phpoffice' . DS . 'phpexcel' . DS . 'Classes' . DS . 'PHPExcel'. DS . 'Writer'. DS . 'Excel2007.php');

/**
 * Home Controller
 *
 * @property \Consulenza\Model\Table\HomeTable $Home */
class ReportController extends AppController
{

	// Lista delle action consentite ai non admin
	public $allowedActions = array(
		'inviiUnico',
		'inviiCausali',
		'inviiUnicoSc',
		'inviiUnicoEnc'
	);

	public function initialize(){
		 $this->loadComponent('Consulenza.Job');
		 $this->loadComponent('Consulenza.Office');
		 $this->loadComponent('Consulenza.Azienda');
		 $this->loadComponent('Consulenza.Task');
	}


	public function beforeFilter(Event $event)
	{

		parent::beforeFilter($event);
		//$this->Auth->allow(['index','info']);

		$user = $this->request->session()->read('Auth.User');

		if(!isset($user)){
			$this->redirect('/');
		}

		if($user['role']!='admin' && !in_array($this->request['action'], $this->allowedActions)){
			$this->redirect('/calendar');
		}
	}

	/**
	 * Index method
	 *
	 * @return void
	 */
	public function inviiUnico($causaleId = -1, $year = -1,$office = -1,$xls=false)
	{
        $Jobsattributes = TableRegistry::get('Consulenza.Jobsattributes');
        $Orders = TableRegistry::get('Consulenza.Orders');

	    $jobs = $Jobsattributes->getJobsFiltered('UNICO')->toArray();
	    $jobs = $jobs[0]['jobs'];

        if(!$xls){

	        $years = array_keys($Orders->getAllYears()->toArray());
			$offices = $this->Office->getOffices();

	        $this->set(compact('jobs', 'years','offices'));
        } else {

        	foreach($jobs as $key => $val){
        		if($causaleId == $val->id )
        			$name = $val->name;
        	}

            $out = $this->Job->jobInviiCausali($causaleId,$year,$office,true);

            //print_r($out);exit;

			$this->autoRender = false;
			$this->layout = 'xls';
			$objPHPExcel = new \PHPExcel;

			$objPHPExcel = new \PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);


			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Cliente');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Operatore');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Stato');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Inviato');

			// filtri
			$objPHPExcel->getActiveSheet()->setAutoFilter('A1:D1');

			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);


			$objPHPExcel->getActiveSheet()->fromArray($out['rows'], NULL, 'A2');

			$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
			if(isset($name)){
				$filename = strtolower(str_replace(' ','_',trim(str_replace('.','',$name)))) . "_(" . $year . ')_'.date('Y-m-d_H-i');
			}else{
				$filename = "report-" . $year . '-'.date('H-m-s');
			}
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');

        }
	}

    public function inviiCausali($causaleId = -1, $year = -1,$office = -1,$xls=false) {
        $Jobsattributes = TableRegistry::get('Consulenza.Jobsattributes');
        $Orders = TableRegistry::get('Consulenza.Orders');

	    $jobs = $Jobsattributes->getJobsFiltered('DICHIARATIVI')->toArray();
	    $jobs = $jobs[0]['jobs'];

        if(!$xls){

	        $years = array_keys($Orders->getAllYears()->toArray());
			$offices = $this->Office->getOffices();

	        $this->set(compact('jobs', 'years','offices'));
        } else {

        	foreach($jobs as $key => $val){
        		if($causaleId == $val->id )
        			$name = $val->name;
        	}

            $out = $this->Job->jobInviiCausali($causaleId,$year,$office,true);

            //print_r($out);exit;

			$this->autoRender = false;
			$this->layout = 'xls';
			$objPHPExcel = new \PHPExcel;

			$objPHPExcel = new \PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);


			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Cliente');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Socio di Riferimento');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Operatore');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Stato');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Inviato');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Note');

			// filtri
			$objPHPExcel->getActiveSheet()->setAutoFilter('A1:F1');

			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
        	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
        	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

			$objPHPExcel->getActiveSheet()->fromArray($out['rows'], NULL, 'A2');

			$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
			if(isset($name)){
				$filename = strtolower(str_replace(' ','_',trim(str_replace('.','',$name)))) . "_(" . $year . ')_'.date('Y-m-d_H-i');
			}else{
				$filename = "report-" . $year . '-'.date('H-m-s');
			}
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
			//$objWriter->save('/Applications/XAMPP/htdocs/tmp/'.$filename.'.xlsx');

        }
	}

	public function inviiUnicoEnc($causaleId = -1, $year = -1,$office = -1,$xls=false)
	{
		$Jobsattributes = TableRegistry::get('Consulenza.Jobsattributes');
        $Orders = TableRegistry::get('Consulenza.Orders');

	    $jobs = $Jobsattributes->getJobsFiltered('UNICOENC')->toArray();
	    $jobs = $jobs[0]['jobs'];

        if(!$xls){

	        $years = array_keys($Orders->getAllYears()->toArray());
			$offices = $this->Office->getOffices();

	        $this->set(compact('jobs', 'years','offices'));
        } else {

        	foreach($jobs as $key => $val){
        		if($causaleId == $val->id )
        			$name = $val->name;
        	}

            $out = $this->Job->jobInviiCausaliUNICOENC($causaleId,$year,$office,true);

            //print_r($out);exit;

			$this->autoRender = false;
			$this->layout = 'xls';
			$objPHPExcel = new \PHPExcel;

			$objPHPExcel = new \PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);

			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Cliente');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Socio di Riferimento');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Consegna Bilancino');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Partita IVA');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Operatore Contabilità');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Operatore UNICO');
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Stato Contabilità');
			$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Stato UNICO');
			$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Stato IRAP');
			$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'UNICO Inviato');
			$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'IRAP Inviato');
			$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Note');

			// filtri
			$objPHPExcel->getActiveSheet()->setAutoFilter('A1:L1');

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

			$objPHPExcel->getActiveSheet()->fromArray($out['rows'], NULL, 'A2');

			$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
			if(isset($name)){
				$filename = strtolower(str_replace(' ','_',trim(str_replace('.','',$name)))) . "_(" . $year . ')_'.date('Y-m-d_H-i');
			}else{
				$filename = "report-UNICO-IRAP-ENC" . $year . '-'.date('H-m-s');
			}
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
			//$objWriter->save('/Applications/XAMPP/htdocs/tmp/'.$filename.'.xlsx');

        }
	}

	public function inviiUnicoSc($causaleId = -1, $year = -1,$office = -1,$xls=false)
	{
		$Jobsattributes = TableRegistry::get('Consulenza.Jobsattributes');
        $Orders = TableRegistry::get('Consulenza.Orders');

	    $jobs = $Jobsattributes->getJobsFiltered('UNICOSC')->toArray();
	    $jobs = $jobs[0]['jobs'];

        if(!$xls){

	        $years = array_keys($Orders->getAllYears()->toArray());
			$offices = $this->Office->getOffices();

	        $this->set(compact('jobs', 'years','offices'));
        } else {

        	foreach($jobs as $key => $val){
        		if($causaleId == $val->id )
        			$name = $val->name;
        	}

            $out = $this->Job->jobInviiCausaliUNICOSC($causaleId,$year,$office,true);

            //print_r($out);exit;

			$this->autoRender = false;
			$this->layout = 'xls';
			$objPHPExcel = new \PHPExcel;

			$objPHPExcel = new \PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);

			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Cliente');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Socio di Riferimento');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Consegna Bilancino');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Partita IVA');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Operatore Contabilità');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Operatore UNICO');
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Stato Bilancio');
			$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Stato Contabilità');
			$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Stato UNICO');
			$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'UNICO Inviato');
			$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'IRAP Inviato');
			$objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Bilancio Depositato');
			$objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Note');

			// filtri
			$objPHPExcel->getActiveSheet()->setAutoFilter('A1:M1');

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
					$objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);

			$objPHPExcel->getActiveSheet()->fromArray($out['rows'], NULL, 'A2');

			$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
			if(isset($name)){
				$filename = strtolower(str_replace(' ','_',trim(str_replace('.','',$name)))) . "_(" . $year . ')_'.date('Y-m-d_H-i');
			}else{
				$filename = "report-UNICO-IRAP-SC" . $year . '-'.date('H-m-s');
			}
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
			//$objWriter->save('/Applications/XAMPP/htdocs/tmp/'.$filename.'.xlsx');

        }
	}

    public function inviiCausaliUnico($causaleId = -1, $year = -1,$office = -1,$xls=false) {
        $Jobsattributes = TableRegistry::get('Consulenza.Jobsattributes');
        $Orders = TableRegistry::get('Consulenza.Orders');

	    $jobs = $Jobsattributes->getJobsFiltered('UNICO')->toArray();
	    $jobs = $jobs[0]['jobs'];

        if(!$xls){

	        $years = array_keys($Orders->getAllYears()->toArray());
			$offices = $this->Office->getOffices();

	        $this->set(compact('jobs', 'years','offices'));
        } else {

        	foreach($jobs as $key => $val){
        		if($causaleId == $val->id )
        			$name = $val->name;
        	}

            $out = $this->Job->jobInviiCausaliUNICO($causaleId,$year,$office,true);

            //print_r($out);exit;

			$this->autoRender = false;
			$this->layout = 'xls';
			$objPHPExcel = new \PHPExcel;

			$objPHPExcel = new \PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);


			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Cliente');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Socio di Riferimento');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Consegna Bilancino');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Partita IVA');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Operatore Contabilità');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Operatore UNICO');
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Stato Contabilità');
			$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Stato UNICO');
			$objPHPExcel->getActiveSheet()->SetCellValue('I1', 'UNICO Inviato');
			$objPHPExcel->getActiveSheet()->SetCellValue('J1', 'IRAP Inviato');
			$objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Note');

			// filtri
			$objPHPExcel->getActiveSheet()->setAutoFilter('A1:K1');

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

			$objPHPExcel->getActiveSheet()->fromArray($out['rows'], NULL, 'A2');

			$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);
			if(isset($name)){
				$filename = strtolower(str_replace(' ','_',trim(str_replace('.','',$name)))) . "_(" . $year . ')_'.date('Y-m-d_H-i');
			}else{
				$filename = "report-UNICO-IRAP-" . $year . '-'.date('H-m-s');
			}
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
			//$objWriter->save('/Applications/XAMPP/htdocs/tmp/'.$filename.'.xlsx');

        }
	}

	public function caricoLavoro($year='',$xls=false) {

		if (isset($year) and $year>0) {
			$this->year=$year;
		}else{
			$this->year=date('Y');
		}

		$this->request->session()->write('Report.CaricoLavoro.year',$this->year);


		$Orders = TableRegistry::get('Consulenza.Orders');
	 	$years = array_keys($Orders->getAllYears()->toArray());

		$this->jobs = TableRegistry::get('Consulenza.Jobsattributes');
		$this->jobsOrders = TableRegistry::get('Consulenza.JobsOrders', [
			'className' => 'Consulenza\Model\Table\JobsOrdersTable',
			'table' => 'jobs_orders'
		]);
		$this->users = TableRegistry::get('Consulenza.Users');
		$usersList = $this->users->find('all')->order(['cognome'=>'ASC'])->toArray();

		// get jobs ids
		$this->jobList = $this->jobs->getWorkloadJobs()->toArray();
		$this->jobList = $this->jobList['jobs'];
		$this->set('jobList', $this->jobList);
		// get jobs with account attribute, in order to determin who is the accounter for the order
		$this->jobAccountingList = $this->jobs->getAccountingJobs()->toArray();
		$this->jobAccountingList = $this->jobAccountingList['jobs'];

		// get Count for each user, one by one
		$i=0;
		foreach($usersList as $user){
			// create a clean array, only with useful data
			$workLoad[$i]['nome']=$user['nome'];
			$workLoad[$i]['cognome']=$user['cognome'];
			$rc = $this->jobsOrders->getRigheContabili($this->jobAccountingList, $this->year, $user);
			$rc = $rc->toArray();
	//debug($rc);
			if( isset($rc[0]) && $rc[0]->tot ) {
				$workLoad[$i]['righe'] = $rc[0]->tot;

			} else {
				$workLoad[$i]['righe'] = 0;
			}
			// get count for current user
			$userCount=$this->jobsOrders->getCount($this->jobList, $this->year, $user)->toArray();
			// use jobId as key with associated count (for current user)
			$workLoad[$i]['load']=[]; // initialize an empty array, so template will not complain
			foreach($userCount as $jobCount){
				$k = 'key_' . $jobCount['job_id'];
				$workLoad[$i]['load'][$k]=$jobCount['count'] ;
			};

			$i++;
		}
//exit;
/*
		echo "<pre>";
		print_r($workLoad);
		exit;
*/

		if(!$xls){

			$this->set(compact('workLoad','years'));

		} else {

            $out = array('rows'=>array(0=>'test'));

			$this->autoRender = false;
			$this->layout = 'xls';
			$objPHPExcel = new \PHPExcel;

			$objPHPExcel = new \PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);

			$alphas_colonne = range('A', 'Z');

			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'OPERATORE');
			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);

			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'RIGHE CONTABILI');
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);


			// da c1 in giu' devo generare i titoli dinamicamente
			$cnt=2;
			foreach($this->jobList as $job){
				$objPHPExcel->getActiveSheet()->SetCellValue($alphas_colonne[$cnt].'1', $job['name']);
				$objPHPExcel->getActiveSheet()->getColumnDimension($alphas_colonne[$cnt])->setAutoSize(true);
				$cnt++;
			}


			//setto i filtri
			$objPHPExcel->getActiveSheet()->setAutoFilter('A1:'.$alphas_colonne[$cnt-1].'1');

			// anche questo va generato dinamicamente quante sono le colonne

			$j=0;
			$jobz = 0;
			foreach($workLoad as $wk){
				$i=2;

				$excel_data[$j]['A'] = $wk['cognome'] .  $wk['nome'];
				$excel_data[$j]['B'] = $wk['righe'];

				foreach($this->jobList as $job){
						if(isset($wk['load']['key_'.$job['id']])){
							$jobz = $wk['load']['key_'.$job['id']];
						} else {
							$jobz = 0;
						}
					$excel_data[$j][$alphas_colonne[$i]] = $jobz;
					$i++;
				}

				$j++;
			}

			/*
			echo "<pre>";
			print_r($excel_data);exit;
			*/

			$objPHPExcel->getActiveSheet()->fromArray($excel_data, NULL, 'A2');

			$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

			$filename = "Report_carico_di_lavoro_(" . $year . ')_'.date('Y-m-d_H-m');
			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
        }

	}

    public function clienti($year ='',$office=-1,$xls = false) {


			$Orders = TableRegistry::get('Consulenza.Orders');

			if(!$xls){
				$years = array_keys($Orders->getAllYears()->toArray());

				$offices = $this->Office->getOffices();

				$this->set(compact( 'years','offices'));
			}else{

				$out = $this->Azienda->getReportScostamenti($year,$office,true);

				//debug($out);exit;

				$this->autoRender = false;
				$this->layout = 'xls';
				$objPHPExcel = new \PHPExcel;

				$objPHPExcel = new \PHPExcel();
				$objPHPExcel->setActiveSheetIndex(0);


				$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Cliente');
				$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Pianificato');
				$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Programmato');
				$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Consuntivato');

				// filtri
				$objPHPExcel->getActiveSheet()->setAutoFilter('A1:D1');

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
						$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);


				$objPHPExcel->getActiveSheet()->fromArray($out['rows'], NULL, 'A2');

				$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);


				$startDate = $this->request->session()->read('Report.ScostamentiClienti.startDate');
				$endDate = $this->request->session()->read('Report.ScostamentiClienti.endDate');

	      if(!empty($startDate) && !empty($endDate )){
					$filename = "report-ORE-SCOSTAMENTI-RIF-" . $year."-DAL-". str_replace("/","-",$startDate) .'-AL-'.str_replace("/","-",$endDate).'-DEL-'.date('Y-m-d_H-i');
				}else{
					$filename = "report-ORE-SCOSTAMENTI-RIF-" . $year . '-DEL-'.date('Y-m-d_H-i');
				}


				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
				header('Cache-Control: max-age=0');
				$objWriter->save('php://output');


			}




	}

    public function cliente($year = '',$aziendaId = 1, $office = -1, $xls = false) {

			$azienda = $this->Azienda->_get($aziendaId);
			$Orders = TableRegistry::get('Consulenza.Orders');


			if(!$xls){

				$years = array_keys($Orders->getAllYears()->toArray());

	   	  $offices = $this->Office->getOffices();

				$this->set(compact('years','offices','azienda'));
			}else{
				$out = $this->Azienda->getReportScostamentiPerAzienda($year,$aziendaId,$office,true);

				//debug($out);exit;

				$this->autoRender = false;
				$this->layout = 'xls';
				$objPHPExcel = new \PHPExcel;

				$objPHPExcel = new \PHPExcel();
				$objPHPExcel->setActiveSheetIndex(0);


				$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Casuale');
				$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Stato');
				$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Pianificato');
				$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Programmato');
				$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Consuntivato');

				// filtri
				$objPHPExcel->getActiveSheet()->setAutoFilter('A1:E1');

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);


				$objPHPExcel->getActiveSheet()->fromArray($out['rows'], NULL, 'A2');


				$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

				$startDate = $this->request->session()->read('Report.ScostamentiClienti.startDate');
				$endDate = $this->request->session()->read('Report.ScostamentiClienti.endDate');

	      if(!empty($startDate) && !empty($endDate)){
					$filename = "report-".$azienda['denominazione']."-ORE-SCOSTAMENTI-RIF-" . $year."-DAL-". str_replace("/","-",$startDate) .'-AL-'.str_replace("/","-",$endDate).'-DEL-'.date('Y-m-d_H-i');
				}else{
					$filename = "report-".$azienda['denominazione']."-ORE-SCOSTAMENTI-RIF-" . $year . '-DEL-'.date('Y-m-d_H-i');
				}


				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
				header('Cache-Control: max-age=0');
				$objWriter->save('php://output');



			}


	}

	public function totaleTasks($year = '', $office = -1, $xls = false){

		$Orders = TableRegistry::get('Consulenza.Orders');
		$offices = $this->Office->getOffices();


		if(!$xls){

			$years = array_keys($Orders->getAllYears()->toArray());

			$this->set(compact('years','offices'));
		}else{
			$out = $this->Task->getAllTask($year,$office,true);

			//debug($out);exit;

			$this->autoRender = false;
			$this->layout = 'xls';
			$objPHPExcel = new \PHPExcel;

			$objPHPExcel = new \PHPExcel();
			$objPHPExcel->setActiveSheetIndex(0);


			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Cliente');
			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Causale');
			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Operatore');
			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Note');
			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Inizio');
			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Fine');
			$objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Durata');
			$objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Completato');

			// filtri
			$objPHPExcel->getActiveSheet()->setAutoFilter('A1:H1');

			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
			$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);


			$objPHPExcel->getActiveSheet()->fromArray($out['rows'], NULL, 'A2');


			$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

			$startDate = $this->request->session()->read('Report.TotaleTasks.startDate');
			$endDate = $this->request->session()->read('Report.TotaleTasks.endDate');


			if(!empty($startDate) && !empty($endDate)){
				$filename = "report-TOTALE-ATTIVITA" . ($year == -1? '-' : '-RIF-'.$year.'-' ) ."DAL-". str_replace("/","-",$startDate) .'-AL-'.str_replace("/","-",$endDate).'-DEL-'.date('Y-m-d_H-i');
			}else{
				$filename = "report-TOTALE-ATTIVITA" . ($year == -1? '-' : '-RIF-'.$year.'-' ) . 'DEL-'.date('Y-m-d_H-i');
			}


			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
			header('Cache-Control: max-age=0');
			$objWriter->save('php://output');
			//$objWriter->save('/Applications/XAMPP/htdocs/tmp/'.$filename.'.xlsx');


			}


		}

		public function reportCausali($year = '', $office = -1, $xls = false) {


			$Orders = TableRegistry::get('Consulenza.Orders');


			if(!$xls){

				$years = array_keys($Orders->getAllYears()->toArray());

				$offices = $this->Office->getOffices();

				$this->set(compact('years','offices'));
			}else{
				$out = $this->Task->getReportScostamentiPerCausale($year,$office,true);

				//debug($out);exit;

				$this->autoRender = false;
				$this->layout = 'xls';
				$objPHPExcel = new \PHPExcel;

				$objPHPExcel = new \PHPExcel();
				$objPHPExcel->setActiveSheetIndex(0);


				$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Cliente');
				$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Causale');
				$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Pianificato');
				$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Programmato');
				$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Consuntivato');

				// filtri
				$objPHPExcel->getActiveSheet()->setAutoFilter('A1:E1');

				$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
				$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);


				$objPHPExcel->getActiveSheet()->fromArray($out['rows'], NULL, 'A2');


				$objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

				$startDate = $this->request->session()->read('Report.ScostamentiCausale.startDate');
				$endDate = $this->request->session()->read('Report.ScostamentiCausale.endDate');

				if(!empty($startDate) && !empty($endDate)){
					$filename = "report-ORE-SCOSTAMENTI-CON-CAUSALE-RIF-" . $year."-DAL-". str_replace("/","-",$startDate) .'-AL-'.str_replace("/","-",$endDate).'-DEL-'.date('Y-m-d_H-i');
				}else{
					$filename = "report-ORE-SCOSTAMENTI-CON-CAUSALE-RIF-" . $year . '-DEL-'.date('Y-m-d_H-i');
				}


				header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
				header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
				header('Cache-Control: max-age=0');
				$objWriter->save('php://output');
				//$objWriter->save('/Applications/XAMPP/htdocs/tmp/'.$filename.'.xlsx');


			}


	}

}
