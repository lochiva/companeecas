<?php
namespace Reports\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

class ReportsComponent extends Component
{

	public function getReports($pass, $user)
	{
		$reports = TableRegistry::get('Reports.Reports');

		$columns[] = ['val' => 'CONCAT(province_code, code)', 'type' => 'text'];
		if($user['role'] == 'admin' || $user['role'] == 'centro') {
			$columns[] = ['val' => 'IF(n.denominazione IS NULL, "Centro Regionale", n.denominazione)', 'type' => 'text'];
		}
		$columns[] = ['val' => 'IF(w.type = "person", CONCAT(w.firstname, " ", w.lastname), w.business_name)', 'type' => 'text'];
		$columns[] = ['val' => 'Reports.status', 'type' => ''];
		$columns[] = ['val' => 'IF(Reports.status = "close", DATEDIFF(Reports.closing_date, Reports.opening_date), IF(Reports.status = "transfer" OR Reports.status = "transfer_accepted", DATEDIFF(Reports.transfer_date, Reports.opening_date), DATEDIFF(NOW(), Reports.opening_date)))', 'type' => 'text'];

		$opt['fields'] = [
			'Reports.id', 'Reports.status', 'code' => 'CONCAT(province_code, code)',
			'victim' => 'CONCAT(v.firstname, " ", v.lastname)', 
			'witness' => 'IF(w.type = "person", CONCAT(w.firstname, " ", w.lastname), w.business_name)',
			'node' => 'IF(n.denominazione IS NULL, "Centro Regionale", n.denominazione)',
			'user_create' => 'CONCAT(uc.nome, " ", uc.cognome)', 
			'user_update' => 'CONCAT(uu.nome, " ", uu.cognome)', 
			'days_open' => 'IF(Reports.status = "close", DATEDIFF(Reports.closing_date, Reports.opening_date), IF(Reports.status = "transfer" OR Reports.status = "transfer_accepted", DATEDIFF(Reports.transfer_date, Reports.opening_date), DATEDIFF(NOW(), Reports.opening_date)))'
		];

		$opt['join'] = [
			[
				'table' => 'reports_victims',
				'alias' => 'v',
				'type' => 'LEFT',
				'conditions' => 'v.id = Reports.victim_id',
			],
			[
				'table' => 'reports_witnesses',
				'alias' => 'w',
				'type' => 'LEFT',
				'conditions' => 'w.id = Reports.witness_id',
			],
			[
				'table' => 'aziende',
				'alias' => 'n',
				'type' => 'LEFT',
				'conditions' => 'n.id = Reports.node_id',
			],
			[
				'table' => 'users',
				'alias' => 'uc',
				'type' => 'LEFT',
				'conditions' => 'uc.id = Reports.user_create_id',
			],
			[
				'table' => 'users',
				'alias' => 'uu',
				'type' => 'LEFT',
				'conditions' => 'uu.id = Reports.user_update_id',
			]
		];

		$questions = TableRegistry::get('Surveys.SurveysQuestionMetadata')->getTableQuestions();

		foreach($questions as $question){
			$opt['fields'][] = 'question'.$question['question_id'].'.final_value';

			$opt['join'][] = [
				'table' => 'surveys_answer_data',
				'alias' => 'question'.$question['question_id'],
				'type' => 'LEFT',
				'conditions' => [
					'question'.$question['question_id'].'.question_id' => $question['question_id'], 
					'question'.$question['question_id'].'.interview_id = Reports.interview_id'
				]
			];

			$columns[] = [
				'val' => 'question'.$question['question_id'].'.final_value',
				'type' => 'text'
			];
		}

		if ($user['role'] == 'nodo') {
			$contatto = TableRegistry::get('Aziende.Contatti')->getContattoByUser($user['id']);
			$opt['conditions']['n.id'] = $contatto['id_azienda'];
		}

		if ($user['role'] == 'admin') {
			$opt['conditions'][] = 'Reports.status != "transfer_accepted"';
		}

		if (!filter_var($pass['query']['showTransferAccepted'], FILTER_VALIDATE_BOOLEAN) && $user['role'] == 'centro') {
			$opt['conditions'][] = 'Reports.status != "transfer_accepted"';
		} else {
			$opt['conditions'][] = 'Reports.status != IF(Reports.node_id IS NOT NULL, "transfer_accepted", "")';
		}

        $toRet['res'] = $reports->queryForTableSorter($columns, $opt, $pass);
        $toRet['tot'] = $reports->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;
	}

	public function getExportData($filters, $user)
	{
		$reportsTable = TableRegistry::get('Reports.Reports');

		if($user['role'] == 'admin' || $user['role'] == 'centro') {
			$columns[] = ['val' => 'Reports.id', 'type' => 'number'];
		}
		$columns[] = ['val' => 'CONCAT(province_code, code)', 'type' => 'text'];
		if($user['role'] == 'admin' || $user['role'] == 'centro') {
			$columns[] = ['val' => 'IF(n.denominazione IS NULL, "Centro Regionale", n.denominazione)', 'type' => 'text'];
		}
		$columns[] = ['val' => 'IF(w.type = "person", CONCAT(w.firstname, " ", w.lastname), w.business_name)', 'type' => 'text'];
		$columns[] = ['val' => 'Reports.status', 'type' => 'text'];
		$columns[] = ['val' => 'IF(Reports.status = "close", DATEDIFF(Reports.closing_date, Reports.opening_date), IF(Reports.status = "transfer" OR Reports.status = "transfer_accepted", DATEDIFF(Reports.transfer_date, Reports.opening_date), DATEDIFF(NOW(), Reports.opening_date)))', 'type' => 'text'];
	
		$opt['fields'] = [
			'Reports.id', 'Reports.code', 'Reports.province_code', 'Reports.type_reporter', 'Reports.interview_id', 'node' => 'IF(n.denominazione IS NULL, "Centro Regionale", n.denominazione)',
			'w.id', 'w.type', 'w.firstname', 'w.lastname', 'wrg.name', 'wrg.user_text', 'w.gender_user_text', 'wn.des_luo', 'w.birth_year', 
			'wl.des_luo', 'wl.user_text', 'w.citizenship_user_text', 
			'wreq.name', 'wreq.user_text', 'w.educational_qualification_user_text', 'wrr.name', 'wrr.user_text', 'w.religion_user_text', 
			'wrot.name', 'wrot.user_text', 'w.type_occupation_user_text', 'wrms.name', 'wrms.user_text', 'w.marital_status_user_text', 'w.in_italy_from_year', 
			'wrrp.name', 'wrrp.user_text', 'w.residency_permit_user_text', 'w.lives_with', 
			'w.telephone', 'w.mobile', 'w.email', 'wr.des_luo', 'wp.des_luo', 'wc.des_luo', 'w.business_name', 'w.piva', 'w.address_legal',
			'wrl.des_luo', 'wpl.des_luo', 'wcl.des_luo', 'w.address_operational', 'wro.des_luo', 'wpo.des_luo', 'wco.des_luo', 'w.legal_representative',
			'w.telephone_legal', 'w.mobile_legal', 'w.email_legal', 'w.operational_contact', 'w.telephone_operational', 'w.mobile_operational', 'w.email_operational'
		];

		if ($user['role'] == 'nodo') {
			$contatto = TableRegistry::get('Aziende.Contatti')->getContattoByUser($user['id']);
			$opt['conditions']['n.id'] = $contatto['id_azienda'];
		}

		$opt['join'] = [
			[
				'table' => 'reports_witnesses',
				'alias' => 'w',
				'type' => 'LEFT',
				'conditions' => 'w.id = Reports.witness_id',
			],
			[
				'table' => 'aziende',
				'alias' => 'n',
				'type' => 'LEFT',
				'conditions' => 'n.id = Reports.node_id',
			],
			[
				'table' => 'users',
				'alias' => 'uc',
				'type' => 'LEFT',
				'conditions' => 'uc.id = Reports.user_create_id',
			],
			[
				'table' => 'users',
				'alias' => 'uu',
				'type' => 'LEFT',
				'conditions' => 'uu.id = Reports.user_update_id',
			],
			[
				'table' => 'reports_genders',
				'alias' => 'wrg',
				'type' => 'LEFT',
				'conditions' => 'wrg.id = w.gender_id',
			],
			[
				'table' => 'reports_educational_qualifications',
				'alias' => 'wreq',
				'type' => 'LEFT',
				'conditions' => 'wreq.id = w.educational_qualification_id',
			],
			[
				'table' => 'reports_marital_statuses',
				'alias' => 'wrms',
				'type' => 'LEFT',
				'conditions' => 'wrms.id = w.marital_status_id',
			],
			[
				'table' => 'reports_occupation_types',
				'alias' => 'wrot',
				'type' => 'LEFT',
				'conditions' => 'wrot.id = w.type_occupation_id',
			],
			[
				'table' => 'reports_religions',
				'alias' => 'wrr',
				'type' => 'LEFT',
				'conditions' => 'wrr.id = w.religion_id',
			],
			[
				'table' => 'reports_residency_permits',
				'alias' => 'wrrp',
				'type' => 'LEFT',
				'conditions' => 'wrrp.id = w.residency_permit_id',
			],
			[
				'table' => 'luoghi',
				'alias' => 'wn',
				'type' => 'LEFT',
				'conditions' => 'wn.c_luo = w.country_id',
			],
			[
				'table' => 'luoghi',
				'alias' => 'wl',
				'type' => 'LEFT',
				'conditions' => 'wl.c_luo = w.citizenship_id',
			],
			[
				'table' => 'luoghi',
				'alias' => 'wr',
				'type' => 'LEFT',
				'conditions' => 'wr.c_luo = w.region_id',
			],
			[
				'table' => 'luoghi',
				'alias' => 'wp',
				'type' => 'LEFT',
				'conditions' => 'wp.c_luo = w.province_id',
			],
			[
				'table' => 'luoghi',
				'alias' => 'wc',
				'type' => 'LEFT',
				'conditions' => 'wc.c_luo = w.city_id',
			],
			[
				'table' => 'luoghi',
				'alias' => 'wrl',
				'type' => 'LEFT',
				'conditions' => 'wrl.c_luo = w.region_id_legal',
			],
			[
				'table' => 'luoghi',
				'alias' => 'wpl',
				'type' => 'LEFT',
				'conditions' => 'wpl.c_luo = w.province_id_legal',
			],
			[
				'table' => 'luoghi',
				'alias' => 'wcl',
				'type' => 'LEFT',
				'conditions' => 'wcl.c_luo = w.city_id_legal',
			],
			[
				'table' => 'luoghi',
				'alias' => 'wro',
				'type' => 'LEFT',
				'conditions' => 'wro.c_luo = w.region_id_operational',
			],
			[
				'table' => 'luoghi',
				'alias' => 'wpo',
				'type' => 'LEFT',
				'conditions' => 'wpo.c_luo = w.province_id_operational',
			],
			[
				'table' => 'luoghi',
				'alias' => 'wco',
				'type' => 'LEFT',
				'conditions' => 'wco.c_luo = w.city_id_operational',
			]
		];

		$questions = TableRegistry::get('Surveys.SurveysQuestionMetadata')->getTableQuestions();

		foreach($questions as $question){
			$opt['fields'][] = 'question'.$question['question_id'].'.final_value';

			$opt['join'][] = [
				'table' => 'surveys_answer_data',
				'alias' => 'question'.$question['question_id'],
				'type' => 'LEFT',
				'conditions' => [
					'question'.$question['question_id'].'.question_id' => $question['question_id'], 
					'question'.$question['question_id'].'.interview_id = Reports.interview_id'
				]
			];

			$columns[] = [
				'val' => 'question'.$question['question_id'].'.final_value',
				'type' => 'text'
			];
		}

        if (!empty($filters) && is_array($filters)) {
			foreach ($filters as $key => $value) {
				if(isset($columns[$key])){
					switch ($columns[$key]['type']) {
						case 'text':
							$condition = [ $columns[$key]['val']. ' LIKE' => "%" . $value . "%" ];
							break;
						case 'date':
							$value = implode('-', array_reverse(explode('/',$value) ) );
							$condition = [ $columns[$key]['val']. ' LIKE' => "%" . $value . "%" ];
							break;
						case 'currency':
							$value = str_replace(',','.', $value);
							$condition = [ $columns[$key]['val'] =>  $value ];
							break;
						case 'array':
							foreach($value as $val){
								$condition['OR'][] = [ $columns[$key]['val']. ' LIKE' => "%" . $val . "%" ];
							}
							break;
						default:
							$condition = [ $columns[$key]['val'] => $value  ];
							break;
					}
					if(!empty($columns[$key]['having'])){
						$opt['having']['AND'][] = $condition;
					}else{
						$opt['conditions']['AND'][] = $condition;
					}
				}
			}
		}

		$res = $reportsTable->find('all', $opt)->toArray();

		//riga intestazioni
		$reports[0] = [
			'','Anagrafica segnalante','', '','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''
		];

		//riga domande
		$reports[1] = [
			'Codice caso', 'Tipologia segnalante', 'Tipologia anagrafica', 
			'Cognome', 'Nome', 'Sesso', 'Anno di nascita', 'Nazione di nascita', 'Cittadinanza', 'In Italia dall\'anno', 'Permesso di soggiorno', 
			'Stato civile', 'Vive in Italia con', 'Religione', 'Titolo di studio', 'Tipologia occupazione', 'Tel. fisso', 'Cellulare', 
			'E-mail', 'Regione', 'Provincia', 'Comune', 'Ragione sociale', 'Partita IVA', 'Indirizzo della sede legale', 'Comune della sede legale', 
			'Provincia della sede legale', 'Regione della sede legale', 'Indirizzo della sede operativa', 'Comune della sede operativa',
			'Provincia della sede operativa', 'Regione della sede operativa', 'Legale rappresentante', 'Tel. fisso (legale rappresentante)', 
			'Cellulare (legale rappresentante)', 'E-mail (legale rappresentante)', 'Referente operativo', 'Tel. fisso (referente operativo)', 
			'Cellulare (referente operativo)', 'E-mail (referente operativo)'
		];

		$questions = TableRegistry::get('Surveys.SurveysQuestionMetadata')->getExportQuestions();

		$first = true;
		foreach ($questions as $question) {
			if ($first) {
				$reports[0][] = 'Scheda caso';
				$first = false;
			} else {
				$reports[0][] = '';
			}
			$reports[1][] = $question['label_export'];
		}

		if ($user['role'] == 'admin' || $user['role'] == 'centro') {
			array_unshift($reports[0], '');
			array_unshift($reports[1], 'Intestatario');
		}

		//righe dati
		$i = 2;
        foreach ($res as $report) {
			$report['w']['lives_with'] = explode(',', $report['w']['lives_with']);
			$livesWithWitness = [];
			foreach ($report['w']['lives_with'] as $person) {
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
			$livesWithWitness = implode(', ', $livesWithWitness);
			$report['type_reporter'] = empty($report['type_reporter']) ? '' : ($report['type_reporter'] == 'victim' ? 'Vittima' : 'Testimone');
			$report['w']['type'] = $report['w']['type'] == 'person' ? 'Persona' : 'Ente/Associazione';
            $reports[$i] = [
				$report['province_code'].$report['code'], $report['type_reporter'], 
				$report['w']['type'], $report['w']['lastname'], $report['w']['firstname'], 
				($report['wrg']['user_text'] == 1 ? $report['wrg']['name'].' ('.$report['w']['gender_user_text'].')' : $report['wrg']['name']), 
				$report['w']['birth_year'], $report['wn']['des_luo'], 
				($report['wl']['user_text'] == 1 ? $report['wl']['des_luo'].' ('.$report['w']['citizenship_user_text'].')' : $report['wl']['des_luo']),
				$report['w']['in_italy_from_year'], 
				($report['wrrp']['user_text'] == 1 ? $report['wrrp']['name'].' ('.$report['w']['residency_permit_user_text'].')' : $report['wrrp']['name']), 
				($report['wrms']['user_text'] == 1 ? $report['wrms']['name'].' ('.$report['w']['marital_status_user_text'].')' : $report['wrms']['name']), 
				$livesWithWitness, ($report['wrr']['user_text'] == 1 ? $report['wrr']['name'].' ('.$report['w']['religion_user_text'].')' : $report['wrr']['name']), 
				($report['wreq']['user_text'] == 1 ? $report['wreq']['name'].' ('.$report['w']['educational_qualification_user_text'].')' : $report['wreq']['name']), 
				($report['wrot']['user_text'] == 1 ? $report['wrot']['name'].' ('.$report['w']['type_occupation_user_text'].')' : $report['wrot']['name']),
				$report['w']['telephone'], $report['w']['mobile'], $report['w']['email'], $report['wr']['des_luo'], $report['wp']['des_luo'], $report['wc']['des_luo'], 
				$report['w']['business_name'], $report['w']['piva'], $report['w']['address_legal'], $report['wcl']['des_luo'], $report['wpl']['des_luo'], $report['wrl']['des_luo'],
				$report['w']['address_operational'], $report['wco']['des_luo'], $report['wpo']['des_luo'], $report['wro']['des_luo'],
				$report['w']['legal_representative'], $report['w']['telephone_legal'], $report['w']['mobile_legal'], $report['w']['email_legal'],
				$report['w']['operational_contact'], $report['w']['telephone_operational'], $report['w']['mobile_operational'], $report['w']['email_operational'],
			];

			if ($user['role'] == 'admin' || $user['role'] == 'centro') {
				array_unshift($reports[$i], $report['node']);
			}

			//Domande scheda
			$interviewAnswers = TableRegistry::get('Surveys.SurveysAnswerData')->find()
				->where(['SurveysAnswerData.interview_id' => $report['interview_id'], 'sqm.show_in_export' => 1])
				->join([
					[
						'table' => 'surveys_interviews',
						'alias' => 'i',
						'type' => 'LEFT',
						'conditions' => 'i.id = SurveysAnswerData.interview_id'
					],
					[
						'table' => 'surveys_question_metadata',
						'alias' => 'sqm',
						'type' => 'LEFT',
						'conditions' => ['sqm.survey_id = i.id_survey', 'sqm.question_id = SurveysAnswerData.question_id']
					]
				])
				->order(['sqm.id' => 'ASC'])
				->toArray();

			foreach ($interviewAnswers as $answer) {
				switch ($answer->type) {
					case 'yes_no':
						$a = '';
						if (json_decode($answer->value) == 'yes') {
							$a = 'Sì';
						} elseif (json_decode($answer->value) == 'no') {
							$a = 'No';
						}
						$reports[$i][] = $a;
						break;
					case 'date':
						$a = '';
						if (json_decode($answer->value)) {
							$a = date('d/m/Y', strtotime(json_decode($answer->value)));
						}
						$reports[$i][] = $a;
						break;
					case 'single_choice':
						$a = '';
						$value = json_decode($answer->value);
						$options = json_decode($answer->options);
						if ($value->check) {
							$a = $options[$value->check]->text;
							if ($options[$value->check]->extended && $value->extensions[$value->check]) {
								$a .= ' ('.$value->extensions[$value->check].')';
							}
						}
						$reports[$i][] = $a;
						break;
					case 'multiple_choice':
						$a = [];
						$value = json_decode($answer->value);
						$options = json_decode($answer->options);
						foreach ($value->answer as $index => $check) {
							$c = '';
							if ($check->check) {
								$c = $options[$index]->text;
								if ($options[$index]->extended && $check->extended) {
									$c .= ' ('.$check->extended.')';
								}
							}
							if (!empty($c)) {
								$a[] = $c;
							}
						}
						if ($value->other_answer_check) {
							$a[] = 'altro: '.$value->other_answer;
						}
						$reports[$i][] = implode(', ', $a);
						break;
					default:
						$reports[$i][] = json_decode($answer->value);
				}
			}

			$i++;
        }

        return $reports;
	}

	public function getDocuments($segnalazioneId, $pass = [])
    {
        $documents = TableRegistry::get('Reports.Documents');

        $columns = [
            0 => ['val' => 'file', 'type' => 'text'],
            1 => ['val' => 'title', 'type' => 'text'],
            2 => ['val' => 'description', 'type' => 'text']
        ];

        $opt['conditions']['report_id'] = $segnalazioneId;
   
        $res['res'] = $documents->queryForTableSorter($columns, $opt, $pass); 
        $res['tot'] = $documents->queryForTableSorter($columns, $opt, $pass, true);

        return $res;
    }

	public function saveHistory($reportId, $nodeId, $event, $date, $motivation = '', $outcomeId = 0)
	{
		if (empty($reportId) || empty($event) || empty($date)) {
			return false;
		} else {
			$historyTable = TableRegistry::get('Reports.Histories');
			$history = $historyTable->newEntity();

			if (empty($nodeId)) {
				$nodeName = 'Centro Regionale';
			} else {
				$node = TableRegistry::get('Aziende.Aziende')->get($nodeId);
				$nodeName = 'nodo '.$node['denominazione'];	
			}

			$data = [
				'report_id' => $reportId,
				'node_id' => $nodeId,
				'date' => $date,
				'event' => $event
			];

			switch ($event) {
				case 'open':
					$data['message'] = 'Creazione caso in data '.$date->format('d/m/Y').'.';
					break;
				case 'close':
					$outcome = TableRegistry::get('Reports.ClosingOutcomes')->get($outcomeId);
					$data['message'] = 'Chiusura caso in data '.$date->format('d/m/Y').' con esito '.$outcome['name'].'. Motivazione: '.$motivation.'.';
					$data['motivation'] = $motivation;
					$data['outcome_id'] = $outcomeId;
					break;
				case 'reopen':
					$data['message'] = 'Riapertura del caso in data '.$date->format('d/m/Y').' con motivazione: '.$motivation.'.';
					$data['motivation'] = $motivation;
					break;
				case 'transfer':
					$data['message'] = 'Richiesta trasferimento del caso dal '.$nodeName.' in data '.$date->format('d/m/Y').' con motivazione: '.$motivation.'.';
					$data['motivation'] = $motivation;
					break;
				case 'transfer_accepted':
					$data['message'] = 'Conferma trasferimento del caso verso il '.$nodeName.' in data '.$date->format('d/m/Y').'.';
					break;
			}

			$historyTable->patchEntity($history, $data);

			return $historyTable->save($history);
		}
	}

}
