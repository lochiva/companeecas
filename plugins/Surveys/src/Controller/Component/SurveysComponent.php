<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Surveys (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
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
			0 => ['val' => 'Surveys.title', 'type' => 'text'],
			1 => ['val' => 'Surveys.subtitle', 'type' => 'text'],
			2 => ['val' => 'Surveys.description', 'type' => 'text'],
			3 => ['val' => 'Surveys.status', 'type' => '']
		];
		
		$opt['join'] = [
			[
				'table' => 'surveys_statuses',
				'alias' => 'ss',
				'type' => 'LEFT',
				'conditions' => 'ss.id = Surveys.status',
			]
		];

		$opt['fields'] = ['Surveys.id', 'Surveys.title', 'Surveys.subtitle', 'Surveys.description', 'Surveys.status'];
		$opt['order'] = ['ss.ordering ASC','Surveys.title ASC'];

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

			if($question->type == 'fixed_text' || $question->type == 'answer_text_editor'){
				//sostituzione segnaposto
				$search = [];
				$replace = [];
				foreach ($values as $label => $value) {
					$search[] = '{{'.$label.'}}';
					$replace[] = $value;
				}
				if (isset($question->answer)) {
					$question->value_to_show = str_replace($search, $replace, $question->answer);
				}
				if (isset($question->value)) {
					$question->value_to_show = str_replace($search, $replace, $question->value);
				}
				
				//$question->value_to_show = str_replace($search, $replace, $question->value);
				//var_dump($question);
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

	public function getValuePlaceholders($interview)
	{
		$guest = TableRegistry::get('Aziende.Guests')->get($interview->guest->guest_id, ['contain' => ['FamilyGuests', 'Sedi' => ['Aziende' => ['SedeLegale' => ['Comuni', 'Province']], 'Comuni', 'Province'], 'Countries']]);

		//recupero ospiti della stessa famiglia
		$guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
		$guestHasFamily = $guestsFamilies->find()->where(['guest_id' => $guest->id])->contain(['Guests' => ['Countries']])->first();

		$guest['family_id'] = '';
		$guest['family'] = [];

		if($guestHasFamily){
			$familyId = $guestHasFamily['family_id'];
			$guest['family_id'] = $familyId;
			$guest['family'] = $guestsFamilies->find()->where(['family_id' => $familyId])->contain(['Guests' => ['Countries']])->all();
		}

		//echo "<pre>"; print_r($guest); die();

		$ente_indirizzo = '/';
		if (isset($guest->sedi->azienda->sede_legale)) {
			$ente_indirizzo = isset($guest->sedi->azienda->sede_legale['indirizzo']) ? $guest->sedi->azienda->sede_legale['indirizzo'].' ' : '';
			$ente_indirizzo .= isset($guest->sedi->azienda->sede_legale['num_civico']) ? $guest->sedi->azienda->sede_legale['num_civico']. ', ' : '';
			$ente_indirizzo .= isset($guest->sedi->azienda->sede_legale['cap']) ? $guest->sedi->azienda->sede_legale['cap'].' ' : '';
			$ente_indirizzo .= isset($guest->sedi->azienda->sede_legale->com['des_luo']) ? $guest->sedi->azienda->sede_legale->com['des_luo'].' ' : '';
			$ente_indirizzo .=  isset($guest->sedi->azienda->sede_legale->prov['s_prv']) ? '('.$guest->sedi->azienda->sede_legale->prov['s_prv'].')' : '';
		}

		$cap = '';
		if (isset($guest->sedi['cap'])) {
			$cap = $guest->sedi['cap'];
		}

		$comune = "";
		if (isset($guest->sedi->comune['des_luo'])) {
			$comune = $guest->sedi->comune['des_luo'];

		}

		$provincia = "";
		if (isset($guest->sedi->provincia['s_prv'])) {
			$provincia = $guest->sedi->provincia['s_prv'];
		}

		$sede_indirizzo = '';

		if (!empty($guest->sedi['indirizzo'])) {
			$sede_indirizzo = $guest->sedi['indirizzo'].' ';
			$sede_indirizzo .= isset($guest->sedi['num_civico']) ? $guest->sedi['num_civico'].', ' : '';
			$sede_indirizzo .= !empty($cap) ? $cap.' ' : '';
			$sede_indirizzo .= !empty($comune) ? $comune .' ' : '';
			$sede_indirizzo .= !empty($provincia) ? '('.$provincia.')' : '';
		}


		$values = [
			'ente_denominazione' => empty($guest->sedi->azienda['denominazione']) ? '/' : $guest->sedi->azienda['denominazione'],
			'ente_responsabile' => empty($guest->sedi['referente']) ? '/' : $guest->sedi['referente'],

			'ente_indirizzo' => $ente_indirizzo,
			'ente_email' => empty($guest->sedi->azienda['email_info']) ? '/' : $guest->sedi->azienda['email_info'],
			
			'struttura_indirizzo' => $sede_indirizzo,

			'struttura_cap' => $cap,
			'struttura_comune' => $comune,
			'struttura_provincia' => $provincia,
			'struttura_email' => empty($guest->sedi['email']) ? '/' : $guest->sedi['email']
		];

		$values['soggetto_e_familiari'] = '';

		if(!empty($guest['surname'])) {
			$values['ospite_cognome'] = $guest['surname'];
			$values['soggetto_e_familiari'] .= "$guest[surname] ";
		} else {
			$values['ospite_cognome'] = "/";
			$values['soggetto_e_familiari'] .= "/ ";
		}

		if(!empty($guest['name'])) {
			$values['ospite_nome'] = $guest['name'];
			$values['soggetto_e_familiari'] .= "$guest[name] ";
		} else {
			$values['ospite_nome'] = "/";
			$values['soggetto_e_familiari'] .= "/ ";
		}

		if(!empty($guest['birthdate'])) {
			$bDay = $guest['birthdate']->format('d/m/Y');
			$values['ospite_data_nascita'] = $bDay;
			$values['soggetto_e_familiari'] .= "nato/a il $bDay ";
		} else {
			$values['ospite_data_nascita'] = "/";
			$values['soggetto_e_familiari'] .= "nato/a il / ";
		}

		if(!empty($guest['country_birth'])) {
			$country = $guest->country['des_luo'];
			$values['ospite_luogo_nascita'] = $country;
			$values['soggetto_e_familiari'] .= "in $country, ";
		} else {
			$values['ospite_luogo_nascita'] = "in /, ";
		}

		if(!empty($guest['vestanet_id'])) {
			$values['ospite_vestanet'] = $guest['vestanet_id'];
			$values['soggetto_e_familiari'] .= "Vestanet $guest[vestanet_id], ";
		} else {
			$values['ospite_vestanet'] = "/";
			$values['soggetto_e_familiari'] .= "Vestanet /, ";
		}

		if(!empty($guest['cui'])) {
			$values['ospite_cui'] = $guest['cui'];
			$values['soggetto_e_familiari'] .= "CUI $guest[cui]";
		} else {
			$values['ospite_cui'] = "/";
			$values['soggetto_e_familiari'] .= "CUI / ";
		}

		if(!empty($guest['family'])) {
			$values['soggetto_e_familiari'] .= "<br>unitamente ai componenti del nucleo famigliare che seguono: ";

			foreach($guest['family'] as $fam) {

				if($guest['id'] !== $fam->guest['id']) {
					$values['soggetto_e_familiari'] .= "<br>";

					if(!empty($fam->guest['surname'])) {
						$values['soggetto_e_familiari'] .= $fam->guest['surname'] . " ";
					} else {
						$values['soggetto_e_familiari'] .= "/ ";
					}
			
					if(!empty($fam->guest['name'])) {
						$values['soggetto_e_familiari'] .= $fam->guest['name'] . " ";
					} else {
						$values['soggetto_e_familiari'] .= "/ ";
					}
			
					if(!empty($fam->guest['birthdate'])) {
						$bDay = $fam->guest['birthdate']->format('d/m/Y');
						$values['soggetto_e_familiari'] .= "nato/a il $bDay ";
					} else {
						$values['soggetto_e_familiari'] .= "nato/a il / ";
					}
			
					if(!empty($fam->guest['country_birth'])) {
						$country = $fam->guest->country['des_luo'];
						$values['soggetto_e_familiari'] .= "in $country, ";
					} else {
						$values['soggetto_e_familiari'] .= "in /, ";
					}
			
					if(!empty($fam->guest['vestanet_id'])) {
						$values['soggetto_e_familiari'] .= "Vestanet " . $fam->guest['vestanet_id'] . ", ";
					} else {
						$values['soggetto_e_familiari'] .= "Vestanet /, ";
					}
			
					if(!empty($fam->guest['cui'])) {
						$values['soggetto_e_familiari'] .= "CUI " . $fam->guest['cui'];
					} else {
						$values['soggetto_e_familiari'] .= "CUI / ";
					}
					
				}

			}

		}

		if ($guest->sedi->police_station_id > 0) {
			$station = $guest = TableRegistry::get('Aziende.PoliceStations')->get($guest->sedi->police_station_id, ['contain' => ['PoliceStationTypes']]);
			$values['ente_stazione'] = $station->type->label_in_letter . '<br>' . $station->name;
		} else {
			$values['ente_stazione'] = '';
		}

		return $values;
	}

}
