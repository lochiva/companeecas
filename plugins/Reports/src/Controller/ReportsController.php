<?php
namespace Reports\Controller;

use Reports\Controller\AppController;
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
		$this->loadComponent('Reports.Reports');
    }

    public function isAuthorized($user = null)
    {
        if($user['role'] == 'admin' || $user['role'] == 'centro' || $user['role'] == 'nodo'){
			return true;
		}

        // Default deny
        return false;
    }

    public function index()
    {
        $user = $this->request->session()->read('Auth.User');
        if($user['role'] == 'nodo' && !TableRegistry::get('Aziende.Contatti')->isValidUserNodo($user['id'])) {
            $this->redirect('/');
		}
		
		$questions = TableRegistry::get('Surveys.SurveysQuestionMetadata')->getTableQuestions();

		$this->set('questions', $questions);
    }

    public function add()
    {
        $user = $this->request->session()->read('Auth.User');
        if($user['role'] == 'nodo' && !TableRegistry::get('Aziende.Contatti')->isValidUserNodo($user['id'])) {
            $this->redirect('/');
		}
		
		$genders = TableRegistry::get('Reports.Genders')->find()->toArray();
		$educationalQualifications = TableRegistry::get('Reports.EducationalQualifications')->find()->toArray();
		$maritalStatuses = TableRegistry::get('Reports.MaritalStatuses')->find()->toArray();
		$occupationTypes = TableRegistry::get('Reports.OccupationTypes')->find()->toArray();
		$religions = TableRegistry::get('Reports.Religions')->find()->toArray();
		$residencyPermits = TableRegistry::get('Reports.ResidencyPermits')->find()->toArray();
		$closingOutcomes = TableRegistry::get('Reports.ClosingOutcomes')->find()->order(['ordering' => 'ASC'])->toArray();

		$this->set('genders', $genders);
		$this->set('educationalQualifications', $educationalQualifications);
		$this->set('maritalStatuses', $maritalStatuses);
		$this->set('occupationTypes', $occupationTypes);
		$this->set('religions', $religions);
		$this->set('residencyPermits', $residencyPermits);
		$this->set('closingOurcomes', $closingOutcomes);
    }

    public function edit()
    { 
        $user = $this->request->session()->read('Auth.User');
        if($user['role'] == 'nodo' && !TableRegistry::get('Aziende.Contatti')->isValidUserNodo($user['id'])) {
            $this->redirect('/');
		}
		
		$genders = TableRegistry::get('Reports.Genders')->find()->toArray();
		$educationalQualifications = TableRegistry::get('Reports.EducationalQualifications')->find()->toArray();
		$maritalStatuses = TableRegistry::get('Reports.MaritalStatuses')->find()->toArray();
		$occupationTypes = TableRegistry::get('Reports.OccupationTypes')->find()->toArray();
		$religions = TableRegistry::get('Reports.Religions')->find()->toArray();
		$residencyPermits = TableRegistry::get('Reports.ResidencyPermits')->find()->toArray();
		$closingOutcomes = TableRegistry::get('Reports.ClosingOutcomes')->find()->order(['ordering' => 'ASC'])->toArray();

		$this->set('genders', $genders);
		$this->set('educationalQualifications', $educationalQualifications);
		$this->set('maritalStatuses', $maritalStatuses);
		$this->set('occupationTypes', $occupationTypes);
		$this->set('religions', $religions);
		$this->set('residencyPermits', $residencyPermits);
		$this->set('closingOutcomes', $closingOutcomes);
        
        $this->render('add');
    }

    public function exportReports() 
    {
		set_time_limit(120);
		
        $filters = array_filter(explode(',', $this->request->query['filters']));
        
        $user = $this->request->session()->read('Auth.User');

		if($user['role'] == 'admin' || $user['role'] == 'centro') {
            $statusIndex = 6;
            $n = 1;
		}else {
            $statusIndex = 5;
            $n = 0;
		}

		if(isset($filters[$statusIndex])){
			if($filters[$statusIndex] == 'Aperto'){
				$filters[$statusIndex] = 'open';
			}elseif($filters[$statusIndex] == 'Chiuso'){
				$filters[$statusIndex] = 'closed';
			}elseif($filters[$statusIndex] == 'Trasferito'){
				$filters[$statusIndex] = 'transferred';
			}
		}

		$reports = $this->Reports->getExportData($filters, $user);

		$spreadsheet = new Spreadsheet();
		
		$c = 'A';
		for($i = 1; $i < count($reports[0]); $i++){
			++$c;
		}

		//unione celle delle sezioni
		$spreadsheet->getActiveSheet()->mergeCells($this->calcCell('B', $n).'1:'.$this->calcCell('AN', $n).'1');
		$spreadsheet->getActiveSheet()->mergeCells($this->calcCell('AO', $n).'1:'.$c.'1');

		//colore righe sezioni e grassetto
		$spreadsheet->getActiveSheet()->getStyle($this->calcCell('B', $n).'1')
		    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle($this->calcCell('B', $n).'1')
		    ->getFill()->getStartColor()->setARGB('FF64E3FC');
		$spreadsheet->getActiveSheet()->getStyle($this->calcCell('B', $n).'1')
	    	->getFont()->setBold(true);
		$spreadsheet->getActiveSheet()->getStyle($this->calcCell('AO', $n).'1')
		    ->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
		$spreadsheet->getActiveSheet()->getStyle($this->calcCell('AO', $n).'1')
		    ->getFill()->getStartColor()->setARGB('FF6AE278');
		$spreadsheet->getActiveSheet()->getStyle($this->calcCell('AO', $n).'1')
			->getFont()->setBold(true);

		//filtri riga intestazione
        $spreadsheet->getActiveSheet()->setAutoFilter('A2:'.$c.'2');

		//grassetto riga intestazione
		$spreadsheet->getActiveSheet()->getStyle('A2:'.$c.'2')
			->getFont()->setBold(true);


		//dimensione automatica delle celle
		$i = 'A';
		foreach($reports[1] as $col){
			$spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
			++$i;
		}

		$spreadsheet->getActiveSheet()->fromArray($reports, NULL);
		
		$spreadsheet->getActiveSheet()->freezePane('A3');

		$spreadsheet->setActiveSheetIndex(0);

        $filename = "segnalazioni";

		setcookie('downloadStarted', '1', false, '/');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

		$writer->save('php://output');

		exit;
    }

    public function calcCell($cell, $numVariableFields)
	{
		for($i=0; $i < $numVariableFields; $i++){
			++$cell;
		}

		return $cell;
	}

	public function reportPdf($reportId)
	{
		if(!empty($reportId)){

			$this->viewBuilder()->layout('default');

			$this->viewBuilder()->setClassName('CakePdf.Pdf');

			$report = TableRegistry::get('Reports.Reports')->get($reportId);

			$report['code'] = str_pad($report['code'], 5, "0", STR_PAD_LEFT);

			if(!empty($report['node_id'])){
				$report['node'] = TableRegistry::get('Aziende.Aziende')->get($report['node_id']);
			}

			$witness = null;
			if(!empty($report['witness_id'])){
				$witness = TableRegistry::get('Reports.Witnesses')->get($report['witness_id'], [
					'contain' => ['Genders', 'EducationalQualifications', 'MaritalStatuses', 'OccupationTypes', 'Religions', 'ResidencyPermits',
						'Users', 'Countries', 'Citizenships', 'Regions', 'Provinces', 'Cities', 
						'RegionLegal', 'ProvinceLegal', 'CityLegal', 'RegionOperational', 'ProvinceOperational', 'CityOperational'
					]
				]);
				$witness['birth_year'] = empty($witness['birth_year']) ? '' : $witness['birth_year'];
				$witness['in_italy_from_year'] = empty($witness['in_italy_from_year']) ? '' : $witness['in_italy_from_year'];
				
				$witness['lives_with'] = explode(',', $witness['lives_with']);
				$livesWithWitness = [];
				foreach ($witness['lives_with'] as $person) {
					switch ($person) {
						case 'mother':
							$livesWithWitness[] = 'Madre';
							break;
						case 'father':
							$livesWithWitness[] = 'Padre';
							break;
						case 'partner':
							$livesWithWitness[] = 'Moglie/Marito/Partner';
							break;
						case 'son':
							$livesWithWitness[] = 'Figlio/i';
							break;
						case 'brother':
							$livesWithWitness[] = 'Fratello/i';
							break;
						case 'other_relatives':
							$livesWithWitness[] = 'Altri parenti';
							break;
						case 'none':
							$livesWithWitness[] = 'Nessuno (vive da sola/o)';
							break;
						case 'other_non_relatives':
							$livesWithWitness[] = 'Altri non parenti';
							break;
					}
				}
				$witness['lives_with'] = implode(', ', $livesWithWitness);
			}

			$interview = null;
			if(!empty($report['interview_id'])){
				$interviews = TableRegistry::get('Surveys.SurveysInterviews');
				$interview = $interviews->get($report['interview_id']);
				$surveysAnswers = TableRegistry::get('Surveys.SurveysAnswers');
				$interview['answers'] = $surveysAnswers->getAnswersByInterview($report['interview_id']);
			}

			$history = TableRegistry::get('Reports.Histories')->find()
				->where(['report_id' => $reportId])
				->order(['date' => 'ASC'])
				->toArray();

			$this->viewBuilder()->options([
				'pdfConfig' => [
					'download' => true,
					'filename' => 'segnalazione_'.$report['province_code'].$report['code'].'.pdf'
				]
			]);

			$viewVars = [
				'report' => $report,
				'witness' => $witness,
				'interview' => $interview,
				'history' => $history
			];

			$this->set($viewVars);

		}
		
		setcookie('downloadStarted', '1', false, '/');
	}

}
