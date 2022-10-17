<?php
namespace Surveys\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class SurveysComponent extends Component
{

	public function getSurveys($pass, $user)
	{
		$surveys = TableRegistry::get('Surveys.Surveys');
		
		$columns = [
			0 => ['val' => 'c.name', 'type' => 'text'],
			1 => ['val' => 'Surveys.title', 'type' => 'text'],
			2 => ['val' => 'Surveys.subtitle', 'type' => 'text'],
			3 => ['val' => 'Surveys.description', 'type' => 'text'],
			4 => ['val' => 'Surveys.status', 'type' => '']
		];
		
		$opt['join'] = [
			[
				'table' => 'surveys_statuses',
				'alias' => 'ss',
				'type' => 'LEFT',
				'conditions' => 'ss.id = Surveys.status',
			],
			[
				'table' => 'bui_configurators',
				'alias' => 'c',
				'type' => 'LEFT',
				'conditions' => 'c.id = Surveys.id_configurator',
			]
		];

		$opt['fields'] = ['Surveys.id', 'Surveys.title', 'Surveys.subtitle', 'Surveys.description', 'Surveys.status', 'c.name'];
		$opt['order'] = ['ss.ordering ASC', 'c.name ASC', 'Surveys.title ASC'];

        $toRet['res'] = $surveys->queryForTableSorter($columns, $opt, $pass);
        $toRet['tot'] = $surveys->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;
	}

	public function getInterviews($pass, $surveyId)
	{
		$interviews = TableRegistry::get('Surveys.SurveysInterviews');
		
		$columns = [
			0 => ['val' => 'SurveysInterviews.title', 'type' => 'text'],
			1 => ['val' => 'SurveysInterviews.subtitle', 'type' => 'text'],
			2 => ['val' => 'SurveysInterviews.description', 'type' => 'text'],
			3 => ['val' => 'SurveysInterviews.created', 'type' => 'date'],
			4 => ['val' => 'CONCAT(c.cognome, " ", c.nome)', 'type'=> 'text'],
			5 => ['val' => 'SurveysInterviews.status', 'type' => ''],
			6 => ['val' => 'SurveysInterviews.not_valid', 'type' => ''],
			7 => ['val' => 'SurveysInterviews.signature_date', 'type' => 'date']
		];

		$opt['fields'] = ['SurveysInterviews.id', 'SurveysInterviews.title', 'SurveysInterviews.subtitle', 'SurveysInterviews.description', 'SurveysInterviews.status',
							'SurveysInterviews.signature_date', 'SurveysInterviews.created', 'contatto' => 'CONCAT(c.cognome, " ", c.nome)', 'SurveysInterviews.not_valid'];

		$opt['join'][] = [
			'table' => 'contatti',
			'alias' => 'c',
			'type' => 'LEFT',
			'conditions' => ['c.id_user = SurveysInterviews.id_user']
		];

		$opt['group'] = ['SurveysInterviews.id'];

		$opt['conditions'][] = ['SurveysInterviews.id_survey' => $surveyId];

		$opt['order'] = ['SurveysInterviews.created DESC', 'SurveysInterviews.title ASC'];

        $toRet['res'] = $interviews->queryForTableSorter($columns, $opt, $pass);
        $toRet['tot'] = $interviews->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;
	}

	public function getInterviewsUser($pass, $userId, $managingEntityId, $structureId)
	{
		$interviews = TableRegistry::get('Surveys.SurveysInterviews');
		
		$columns = [
			0 => ['val' => 'SurveysInterviews.title', 'type' => 'text'],
			1 => ['val' => 'SurveysInterviews.subtitle', 'type' => 'text'],
			2 => ['val' => 'SurveysInterviews.description', 'type' => 'text'],
			3 => ['val' => 'SurveysInterviews.created', 'type' => 'date'],
			4 => ['val' => 'CONCAT(c.cognome, " ", c.nome)', 'type'=> 'text'],
			5 => ['val' => 'SurveysInterviews.status', 'type' => ''],
			6 => ['val' => 'SurveysInterviews.not_valid', 'type' => ''],
			7 => ['val' => 'SurveysInterviews.signature_date', 'type' => 'date']
		];

		$opt['fields'] = ['SurveysInterviews.id', 'SurveysInterviews.id_survey', 'SurveysInterviews.title', 'SurveysInterviews.subtitle', 
							'SurveysInterviews.description', 'SurveysInterviews.status', 'SurveysInterviews.signature_date', 'SurveysInterviews.created', 
							'contatto' => 'CONCAT(c.cognome, " ", c.nome)', 'SurveysInterviews.not_valid'];

		$opt['join'] = [
			[
				'table' => 'contatti',
				'alias' => 'c',
				'type' => 'LEFT',
				'conditions' => ['c.id_user = SurveysInterviews.id_user']
			],	
		];

		$opt['conditions'][] = [
			'SurveysInterviews.id_azienda' => $managingEntityId,
			'SurveysInterviews.id_sede' => $structureId
		];

		$opt['order'] = ['SurveysInterviews.created DESC', 'SurveysInterviews.title ASC'];

        $toRet['res'] = $interviews->queryForTableSorter($columns, $opt, $pass);
        $toRet['tot'] = $interviews->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;
	}

	public function replacePlaceholdersTexts($item, $values)
	{
		foreach($item->questions as $key => $question){

			if($question->type == 'fixed_text'){
				//sostituzione segnaposto
				$search = [];
				$replace = [];
				foreach ($values as $label => $value) {
					$search[] = '{{'.$label.'}}';
					$replace[] = $value;
				}
				$question->value_to_show = str_replace($search, $replace, $question->value);
			}
		}

		foreach($item->items as $subItem){
            $subItem = $this->replacePlaceholdersTexts($subItem, $values);
        }

		return $item;
	}

	public function getChapters($pass)
	{
		$chapters = TableRegistry::get('Surveys.SurveysChaptersContents');
		
		$columns = [
			0 => ['val' => 'name', 'type' => 'text'],
			1 => ['val' => 'ordering', 'type' => 'text'],
		];

		$opt['order'] = ['id ASC'];

        $toRet['res'] = $chapters->queryForTableSorter($columns, $opt, $pass);
        $toRet['tot'] = $chapters->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;
	}

	public function setQuestionsValue($items, $answers) 
	{
		foreach ($items as $item) {
			foreach ($item->questions as $question) {
				if (!empty($answers[$question->id])) {
					if ($question->type == 'multiple_choice') {
						$answer = json_decode($answers[$question->id]->value);
						$question->answer = $answer->answer;
						if ($question->other) {
							$question->other_answer_check = $answer->other_answer_check;
							$question->other_answer = $answer->other_answer;
						}
					} else {
						$question->answer = json_decode($answers[$question->id]->value);
					}
				}
			}

			if (!empty($item->items)) {
				$item->items = $this->setQuestionsValue($item->items, $answers);
			}
		}

		return $items;
	}

	public function reorderItemsForDoubleLayout($item)
	{
		$item->left_column = [];
		$item->right_column = [];

		foreach($item->questions as $key => $question){

			if ($question->layout_alignment == 'left') {
				$item->left_column[] = $question;
			} elseif ($question->layout_alignment == 'right') {
				$item->right_column[] = $question;
				// Se scheda tecnica, le immagini vengono messe a sinistra
				if ($question->type == 'data_sheet') {
					$imagesQuestion = new \stdClass();
   					$imagesQuestion->type = 'data_sheet_images';
					$imagesQuestion->visible = $question->visible;
					$imagesQuestion->data_sheet_id = $question->data_sheet->id;
					$imagesQuestion->visibility_by_component = $question->visibility_by_component;
					$imagesQuestion->components = $question->components;
					$item->left_column[] = $imagesQuestion;
				}
			} else {
				$item->left_column[] = $question;
			}
		}

		foreach($item->items as $subItem){
            $subItem = $this->reorderItemsForDoubleLayout($subItem);
        }

		return $item;
	}

	public function getDataSheetsInfo($item, $dataSheetsInfo)
	{
		$dataSheetsTable = TableRegistry::get('Building.DataSheets');
		$attachmentsTable = TableRegistry::get('AttachmentManager.Attachments');

		foreach($item->questions as $key => $question){

			if($question->type == 'data_sheet'){
				$info = $dataSheetsTable->find()->where(['id' => $question->data_sheet->id])->first();
				if ($info) {
					//Immagini scheda tecnica
					$attachments = $attachmentsTable->find()->where(['context' => 'data_sheets', 'id_item' => $question->data_sheet->id])->toArray();
					$info['images'] = [];

					if (!empty($attachments)) {
						foreach ($attachments as $a) {
							$channel = Configure::read('localconfig.AttachmentStorage.channels.'.$a['storage_channel']);
							$path = ROOT.DS.$channel['base_path'].$a['file_path'];
							if(file_exists($path)){
								$content = base64_encode(file_get_contents($path));
								$type = mime_content_type($path);
								$info['images'][] = [
									'content' => $content,
									'type' => $type
								];
							}
						}
					}
					
					$dataSheetsInfo[$question->data_sheet->id] = $info;
				}
			}
		}

		foreach($item->items as $subItem){
            $dataSheetsInfo = $this->getDataSheetsInfo($subItem, $dataSheetsInfo);
        }

		return $dataSheetsInfo;
	}

	public function getValuePlaceholders($quotationId)
	{
		$quotation = TableRegistry::get('Building.Quotations')->get($quotationId);
		$offer = TableRegistry::get('Crm.Offers')->get($quotation['offer_id'], ['contain' => ['AziendaDest', 'Sedi' => ['Comuni', 'Province']]]);

		$values = [
			'azienda_denominazione' => empty($offer['azienda_dest']['denominazione']) ? '/' : $offer['azienda_dest']['denominazione'],
			'sede_indirizzo' => empty($offer['sede_dest']['indirizzo']) ? '/' : $offer['sede_dest']['indirizzo'],
			'sede_civico' => empty($offer['sede_dest']['num_civico']) ? '/' : $offer['sede_dest']['num_civico'],
			'sede_comune' => empty($offer['sede_dest']['comune']['des_luo']) ? '/' : $offer['sede_dest']['comune']['des_luo'],
			'sede_provincia' => empty($offer['sede_dest']['provincia']['des_luo']) ? '/' : $offer['sede_dest']['provincia']['des_luo'],
			'sede_telefono' => empty($offer['sede_dest']['telefono']) ? '/' : $offer['sede_dest']['telefono'],
			'offerta_data' => empty($offer['emission_date']) ? '/' : $offer['emission_date']->format('d/m/Y'),
			'offerta_descrizione' => empty($offer['description']) ? '/' : $offer['description'],
			'quotazione_importo' => empty($quotation['amount']) ? '/' : $quotation['amount']
		];

		return $values;
	}

}
