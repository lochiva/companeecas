<?php
namespace Surveys\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

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

		if($user['role'] == 'user'){
			$opt['join'][] = [
				'table' => 'contatti',
				'alias' => 'c',
				'type' => 'LEFT',
				'conditions' => ['c.id_user' => $user['id'], 'c.deleted' => '0']
			];

			$opt['join'][] = [
				'table' => 'aziende',
				'alias' => 'a',
				'type' => 'LEFT',
				'conditions' => ['a.id = c.id_azienda', 'a.deleted = 0']
			];
			$opt['join'][] = [
				'table' => 'surveys_to_aziende',
				'alias' => 'sa',
				'type' => 'LEFT',
				'conditions' => 'sa.id_survey = Surveys.id',
			];

			$opt['conditions'][] = ['sa.id_azienda = a.id'];
			$opt['conditions'][] = ['Surveys.status = 1'];
		}

		$opt['order'] = ['ss.ordering ASC', 'Surveys.title ASC'];

        $toRet['res'] = $surveys->queryForTableSorter($columns, $opt, $pass);
        $toRet['tot'] = $surveys->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;
	}

	public function getInterviews($pass, $surveyId)
	{
		$interviews = TableRegistry::get('Surveys.SurveysInterviews');
		
		$columns = [
			0 => ['val' => 'a.denominazione', 'type' => 'text'],
			1 => ['val' => 'CONCAT(UPPER(s.comune), " - ", s.indirizzo)', 'type' => 'text'],
			2 => ['val' => 'SurveysInterviews.title', 'type' => 'text'],
			3 => ['val' => 'SurveysInterviews.subtitle', 'type' => 'text'],
			4 => ['val' => 'SurveysInterviews.description', 'type' => 'text'],
			5 => ['val' => 'SurveysInterviews.created', 'type' => 'date'],
			6 => ['val' => 'CONCAT(c.cognome, " ", c.nome)', 'type'=> 'text'],
			7 => ['val' => 'SurveysInterviews.status', 'type' => ''],
			8 => ['val' => 'SurveysInterviews.not_valid', 'type' => ''],
			9 => ['val' => 'SurveysInterviews.signature_date', 'type' => 'date']
		];

		$opt['fields'] = ['SurveysInterviews.id', 'SurveysInterviews.title', 'SurveysInterviews.subtitle', 'SurveysInterviews.description', 'SurveysInterviews.status',
							'SurveysInterviews.signature_date', 'SurveysInterviews.created', 'contatto' => 'CONCAT(c.cognome, " ", c.nome)', 'azienda' => 'a.denominazione', 
							'struttura' => 'CONCAT(UPPER(s.comune), " - ", s.indirizzo)', 'SurveysInterviews.not_valid'];

		$opt['join'][] = [
			'table' => 'contatti',
			'alias' => 'c',
			'type' => 'LEFT',
			'conditions' => ['c.id_user = SurveysInterviews.id_user']
		];

		$opt['join'][] = [
			'table' => 'aziende',
			'alias' => 'a',
			'type' => 'LEFT',
			'conditions' => ['a.id = SurveysInterviews.id_azienda']
		];

		$opt['join'][] = [
			'table' => 'sedi',
			'alias' => 's',
			'type' => 'LEFT',
			'conditions' => ['s.id = SurveysInterviews.id_sede']
		];

		$opt['group'] = ['SurveysInterviews.id'];

		$opt['conditions'][] = ['SurveysInterviews.id_survey' => $surveyId];

		$opt['order'] = ['SurveysInterviews.created DESC', 'SurveysInterviews.title ASC'];

        $toRet['res'] = $interviews->queryForTableSorter($columns, $opt, $pass);
        $toRet['tot'] = $interviews->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;
	}

	public function getSurveyPartners($idSurvey)
	{
		$aziende = [];

		$surveysStructures = TableRegistry::get('Surveys.SurveysToStructures');
		$resStructures = $surveysStructures->getStructuresForSurvey($idSurvey);

		foreach($resStructures as $structure){
			$sediTable = TableRegistry::get('Aziende.Sedi');
			$sedi = $sediTable->getSediSurveyPartner($structure['id_azienda']);

			$structures = [];

			foreach($sedi as $sede){
				$structures[] = [
					'id' => $sede['id'],
					'label' => strtoupper($sede['comune']).' - '.$sede['indirizzo'],
					'selected' => in_array($sede['id'], explode(',', $structure['sedi']))
				];
			}

			$aziende[] = [
				'code' => $structure['id_azienda'],
				'label' => $structure['label'],
				'structures' => $structures
			];
		}
		
		return $aziende;
	}

	public function getManagingEntities($pass, $userId)
	{
		$aziende = TableRegistry::get('Aziende.Aziende');
		
		$columns = [
			0 => ['val' => 'Aziende.denominazione', 'type' => 'text'],
		];

		$opt['fields'] = ['Aziende.id', 'Aziende.denominazione'];

		$opt['conditions'] = [
			'Aziende.id = sats.id_azienda',
		];
		
		$opt['join'] = [
			[
				'table' => 'surveys',
				'alias' => 's',
				'type' => 'LEFT',
				'conditions' => 's.status != 2'
			],
			[
				'table' => 'surveys_to_structures',
				'alias' => 'sats',
				'type' => 'LEFT',
				'conditions' => 'sats.id_survey = s.id'
			]
		];

		$opt['order'] = ['Aziende.denominazione ASC'];
		$opt['group'] = ['Aziende.id'];

        $toRet['res'] = $aziende->queryForTableSorter($columns, $opt, $pass);
        $toRet['tot'] = $aziende->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;
	}

	public function getStructures($pass, $userId, $idManagingEntity)
	{
		$sedi = TableRegistry::get('Aziende.Sedi');
		
		$columns = [
			0 => ['val' => 'Sedi.comune', 'type' => 'text'],
			1 => ['val' => 'Sedi.indirizzo', 'type' => 'text'],
		];

		$opt['fields'] = ['Sedi.id', 'Sedi.comune', 'Sedi.indirizzo', 'survey' => 'GROUP_CONCAT(s.id)'];

		$opt['conditions'] = [
			'Sedi.id = sats.id_sede',
			's.id IS NOT NULL'
		];
		
		$opt['join'] = [
			[
				'table' => 'surveys',
				'alias' => 's',
				'type' => 'LEFT',
				'conditions' => ['s.status != 2']
			],
			[
				'table' => 'surveys_to_structures',
				'alias' => 'sats',
				'type' => 'LEFT',
				'conditions' => ['sats.id_survey = s.id', 'sats.id_azienda' => $idManagingEntity]
			]
		];

		$opt['order'] = ['Sedi.comune ASC'];
		$opt['group'] = ['Sedi.id'];

        $toRet['res'] = $sedi->queryForTableSorter($columns, $opt, $pass);
        $toRet['tot'] = $sedi->queryForTableSorter($columns, $opt, $pass, true);

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

	/*public function replacePlaceholdersTexts($item, $values)
	{
		foreach($item->questions as $key => $question){

			if($question->type == 'free_text' || $question->type == 'standard_text'){
				//sostituzione segnaposto nome azienda
				$question->value = str_replace('{{azienda_nome}}', $values['azienda_nome'], $question->value);
			}
		}

		foreach($item->items as $subItem){
            $subItem = $this->replacePlaceholdersTexts($subItem, $values);
        }

		return $item;
	}*/

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

}
