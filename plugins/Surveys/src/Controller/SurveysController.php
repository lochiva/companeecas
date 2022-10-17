<?php
namespace Surveys\Controller;

use Surveys\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * Surveys Controller
 *
 * @property \Surveys\Model\Table\SurveysTable $Surveys
 *
 * @method \Surveys\Model\Entity\Survey[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SurveysController extends AppController
{

    public function isAuthorized($user = null)
    {
        if($user['role'] == 'admin'){
			return true;
		}

        // Default deny
        return false;
    }

    public function initialize()
    {
        parent::initialize();
		$this->loadComponent('Surveys.Surveys');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {

    }

    public function add()
    {
        $baseImageUrl = Router::url('/').Configure::read('dbconfig.surveys.SURVEYS_IMAGE_BASE_PATH');
        $placeholders = TableRegistry::get('Surveys.SurveysPlaceholders')->find()->toArray();

        $this->set('baseImageUrl', $baseImageUrl);
        $this->set('placeholders', $placeholders);
    }

    public function edit()
    {
        $baseImageUrl = Router::url('/').Configure::read('dbconfig.surveys.SURVEYS_IMAGE_BASE_PATH');
        $placeholders = TableRegistry::get('Surveys.SurveysPlaceholders')->find()->toArray();

        $this->set('baseImageUrl', $baseImageUrl);
        $this->set('placeholders', $placeholders);

        $this->render('add');
    }

    public function interviews($surveyId)
    {
        $this->set('surveyId', $surveyId);
    }

    public function answers()
    {
        $baseImageUrl = Router::url('/').Configure::read('dbconfig.surveys.SURVEYS_IMAGE_BASE_PATH');

        $this->set('baseImageUrl', $baseImageUrl);
    }

    public function documentPdf($id)
    {
        ini_set("max_execution_time", 120);

        $interviews = TableRegistry::get('Surveys.SurveysInterviews');

        $interview = $interviews->get($id);

        $surveysAnswers = TableRegistry::get('Surveys.SurveysAnswers');
        $interview['answers'] = $surveysAnswers->getAnswersByInterview($id);

        //Sotituzione placeholders
		$valuePlaceholders = $this->Surveys->getValuePlaceholders($interview['id_quotation']);
		foreach($interview['answers'] as $answer){
            $answer = $this->Surveys->replacePlaceholdersTexts($answer, $valuePlaceholders);
        }

        //Dati per schede tecniche
		$dataSheetsInfo = [];
		foreach($interview['answers'] as $answer){
            $dataSheetsInfo = $this->Surveys->getDataSheetsInfo($answer, $dataSheetsInfo);
        }
		$interview['data_sheets_info'] = $dataSheetsInfo;

        //Misure
		$interview['dimensions'] = TableRegistry::get('Building.Dimensions')->getQuotationDimensionsForDocument($interview['id_quotation']);

        //Adeguamento ordine elementi a tipologia layout
        foreach($interview['answers'] as $answer){
            if ($answer->layout == 'double') {
                $answer = $this->Surveys->reorderItemsForDoubleLayout($answer);
            }
        }

        //Lista componenti attivi
        $interview['active_components'] = TableRegistry::get('Building.ComponentQuotation')->getComponentIdsByQuotation($interview['id_quotation']);

        $interviewTitle = str_replace(' ', '_', $interview['title']);
        $pdfName = 'Preventivo_' . $interviewTitle . '.pdf';

        $viewVars = ['interview' => $interview];

        $this->viewBuilder()->setLayout('default');
        $this->viewBuilder()->setTemplate('document');

        $this->viewBuilder()->setClassName('CakePdf.Pdf');

        $pathHeader = ROOT.DS.'plugins'.DS.'Surveys'.DS.'src'.DS.'Template'.DS.'Surveys'.DS.'pdf'.DS.'document_header.html';
        $pathFooter = ROOT.DS.'plugins'.DS.'Surveys'.DS.'src'.DS.'Template'.DS.'Surveys'.DS.'pdf'.DS.'document_footer.html';

        $logoPath = WWW_ROOT.DS.'img'.DS.'building_evolution_logo.png';
        $type = pathinfo($logoPath, PATHINFO_EXTENSION);
        $image = file_get_contents($logoPath);
        $logoSrc = 'data:image/' . $type . ';base64,' . base64_encode($image); 

        $this->viewBuilder()->options([
            'pdfConfig' => [
                'engine' => [
                    'className' => 'CakePdf.WkHtmlToPdf',
                    'binary' => '/usr/local/bin/wkhtmltopdf',
                    'options' => [
                        'header-html' => 'file://'.$pathHeader,
                        'header-spacing' => 7,
                        'replace' => ['logosrc' => $logoSrc],
                        'footer-html' => 'file://'.$pathFooter,
                        'footer-spacing' => 7,
                    ]
                ],
                'orientation' => 'portrait',
                'margin' => [
                    'bottom' => 24,
                    'left' => 7,
                    'right' => 7,
                    'top' => 30
                ],
                'download' => true,
                'filename' => $pdfName
            ]
        ]);

        $this->set($viewVars);

        setcookie('downloadStarted', '1', false, '/');
    }

    public function documentPreview($id)
    {
        $interviews = TableRegistry::get('Surveys.SurveysInterviews');

        $interview = $interviews->get($id);

        $surveysAnswers = TableRegistry::get('Surveys.SurveysAnswers');
        $interview['answers'] = $surveysAnswers->getAnswersByInterview($id);

        //Sotituzione placeholders
		$valuePlaceholders = $this->Surveys->getValuePlaceholders($interview['id_quotation']);
		foreach($interview['answers'] as $answer){
            $answer = $this->Surveys->replacePlaceholdersTexts($answer, $valuePlaceholders);
        }

        //Dati per schede tecniche
		$dataSheetsInfo = [];
		foreach($interview['answers'] as $answer){
            $dataSheetsInfo = $this->Surveys->getDataSheetsInfo($answer, $dataSheetsInfo);
        }
		$interview['data_sheets_info'] = $dataSheetsInfo;

        //Misure
		$interview['dimensions'] = TableRegistry::get('Building.Dimensions')->getQuotationDimensionsForDocument($interview['id_quotation']);

        //Adeguamento ordine elementi a tipologia layout
        foreach($interview['answers'] as $answer){
            if ($answer->layout == 'double') {
                $answer = $this->Surveys->reorderItemsForDoubleLayout($answer);
            }
        }

        //Lista componenti attivi
        $interview['active_components'] = TableRegistry::get('Building.ComponentQuotation')->getComponentIdsByQuotation($interview['id_quotation']);

        $this->viewBuilder()->setLayout('/pdf/default');
        $this->viewBuilder()->setTemplate('/Surveys/pdf/document');

        $viewVars = ['interview' => $interview];

        $this->set($viewVars);
    }

    public function documentWord($id)
    {
        $interviews = TableRegistry::get('Surveys.SurveysInterviews');

        $interview = $interviews->get($id);

        $surveysAnswers = TableRegistry::get('Surveys.SurveysAnswers');
        $interview['answers'] = $surveysAnswers->getAnswersByInterview($id);

        //Sotituzione placeholders
		$valuePlaceholders = $this->Surveys->getValuePlaceholders($interview['id_quotation']);
		foreach($interview['answers'] as $answer){
            $answer = $this->Surveys->replacePlaceholdersTexts($answer, $valuePlaceholders);
        }

        //Dati per schede tecniche
		$dataSheetsInfo = [];
		foreach($interview['answers'] as $answer){
            $dataSheetsInfo = $this->Surveys->getDataSheetsInfo($answer, $dataSheetsInfo);
        }
		$interview['data_sheets_info'] = $dataSheetsInfo;

        //Misure
		$interview['dimensions'] = TableRegistry::get('Building.Dimensions')->getQuotationDimensionsForDocument($interview['id_quotation']);

        //Adeguamento ordine elementi a tipologia layout
        foreach($interview['answers'] as $answer){
            if ($answer->layout == 'double') {
                $answer = $this->Surveys->reorderItemsForDoubleLayout($answer);
            }
        }

        //Lista componenti attivi
        $interview['active_components'] = TableRegistry::get('Building.ComponentQuotation')->getComponentIdsByQuotation($interview['id_quotation']);

        $interviewTitle = str_replace(' ', '_', $interview['title']);
        $fileName = 'Preventivo_' . $interviewTitle . '.docx';

        $this->viewBuilder()->setLayout('/pdf/default');

        $viewVars = ['interview' => $interview];

        $this->set($viewVars);

        setcookie('downloadStarted', '1', false, '/');

        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header("Expires: 0");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header('Content-Disposition: attachment; filename="'.$fileName.'"');
        header('Cache-Control: max-age=0');
    }

    public function chapters()
    {
        $placeholders = [];
        //$placeholders = TableRegistry::get('Surveys.SurveysPlaceholders')->find()->toArray();

        $this->set('placeholders', $placeholders);
    }

    public function chapterPreview($id)
    {
        $chapters = TableRegistry::get('Surveys.SurveysChaptersContents');

        $chapter = $chapters->get($id);	

        $this->viewBuilder()->layout('default');
        $this->viewBuilder()->setClassName('CakePdf.Pdf');

        $viewVars = ['chapter' => $chapter];

        $this->set($viewVars);
    }

}
