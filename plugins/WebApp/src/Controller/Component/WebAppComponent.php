<?php

namespace WebApp\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class WebAppComponent extends Component
{
	public function getEventsByOperator($date, $id_operator){
		$eventi = TableRegistry::get('Calendar.Eventi');

		$dataMattino = $eventi->getEventsDataByInterval($date . ' 00:00:00', $date . ' 12:59:59', $id_operator);
		$mattino = $this->getJsonData($dataMattino, $id_operator);
		$m['periodo'] = 'mattino';
		$m['sessions'] = $mattino;

		$dataPomeriggio = $eventi->getEventsDataByInterval($date . ' 13:00:00', $date . ' 23:59:59', $id_operator);
		$pomeriggio = $this->getJsonData($dataPomeriggio, $id_operator);
		$p['periodo'] = 'pomeriggio';
		$p['sessions'] = $pomeriggio;

		$r['calendar_events'][] = ['groups' => [$m, $p]];
		$r['speakers'][] = [];
		$r['map'][] = [];

		return $r;
	}

	public function getJsonData($data, $id_operator){
		//$services = TableRegistry::get('Progest.Services');
		$orders = TableRegistry::get('Aziende.Orders');
		//$people = TableRegistry::get('Progest.People');
		//$peopleExtension = TableRegistry::get('Progest.PeopleExtension');
		$eventsDetail = TableRegistry::get('Calendar.EventiDettaglio');
		$aziende = TableRegistry::get('Aziende.Aziende');
		//$activities = TableRegistry::get('Progest.Activities');
		//$eventsDetailActivities = TableRegistry::get('Calendar.EventiDettaglioAttivita');

		$result = [];

		foreach($data as $event){

			/*
			$service = $services->getServiceById($event['id_service']);
			$service0 = array_shift($service);
			$personId = $orders->getPersonId($event['id_order']);
			$personId0 = array_shift($personId);
			$person = $people->getPersonById($personId0['id_person']);
			$person0 = array_shift($person);
			$personExtension = $peopleExtension->find()
											->where(['id_person' => $personId0['id_person'], 'last' => '1', 'deleted' => '0'])
											->first();
			*/

			if($event['id_order'] != '0'){
				$order = $orders->get($event['id_order']);
				$azienda = $aziende->get($order['id_azienda']);
				$committente = $azienda['denominazione'];
				$oggetto = $order['name'];

			}else{
				$committente = 'Non disponibile';
				$oggetto = 'Non disponibile';
			}

			$eventDetail = $eventsDetail->find()->where(array('event_id' => $event['id'], 'operator_id' => $id_operator))->toArray();

			if($eventDetail){
				$userStart = $eventDetail[0]['user_start'];
				$userStop = $eventDetail[0]['user_end'];
				$status = $eventDetail[0]['status'];
				$noteDetail = $eventDetail[0]['note'];
				if($eventDetail[0]['note_importanza'] == 1){
					$noteDetailImportanza = 'true';
				}elseif($eventDetail[0]['note_importanza'] == 0){
					$noteDetailImportanza = 'false';
				}

				//$eventDetailActivities = $eventsDetailActivities->find()->where(['id_event_detail' => $eventDetail[0]['id']])->toArray();

			}else{
				$userStart = '';
				$userStop = '';
				$status = 'TODO';
				$noteDetail = '';
				$noteDetailImportanza = false;
				//$eventDetailActivities = [];
			}

			/*
			$serviceActivities = $activities->find()->where(['id_service' => $service0['id']])->toArray();

			if(!empty($serviceActivities)){
				$hasActivities = '1';
			}else{
				$hasActivities = '0';
			}
			*/
			
			$condiviso = 0;
			$utentiCondivisi = [];
			if($event['id_group'] != '0'){
				$usersGroup =TableRegistry::get('UsersToGroups');
				$users = TableRegistry::get('Users');
				$groups = $usersGroup->find()->where(['id_group' => $event['id_group']])->toArray();
				foreach($groups as $group){
					if($group['id_user'] != $id_operator){
						$user = $users->find()->where(['id' => $group['id_user']])->toArray();
						$utentiCondivisi[] = [
							'nome' => $user[0]['nome'],
							'cognome' => $user[0]['cognome']
						];
					}
				}

				$condiviso = 1;
			}

			array_push($result, [
					'id' => $event['id'],
					'title' => $event['title'],
					'start' => $event['start']->format('H:i'),
					'end' => $event['end']->format('H:i'),
					'note' => $event['note'],
					//'service_name' => $service0['name'],
					'service_name' => 'nome servizio',
					//'id_service' => $service0['id'],
					'id_service' => '1',
					//'hasActivities' => $hasActivities,
					'hasActivities' => '0',
					//'cognome' => $person0['surname'],
					'cognome' => 'persona cognome',
					//'nome' => $person0['name'],
					'nome' => 'persona nome',
					//'address' => $personExtension['address'],
					'address' => 'persona indirizzo',
					//'address_lat' => $personExtension['address_lat'],
					'address_lat' => 'persona indirizzo lat',
					//'address_long' => $personExtension['address_long'],
					'address_long' => 'persona indirizzo long',
					//'citta' => $personExtension['comune'],
					'citta' => 'persona comune',
					//'provincia' => $personExtension['provincia'],
					'provincia' => 'persona provincia',
					//'tel' => $personExtension['tel'],
					'tel' => 'persona tel',
					//'cell' => $personExtension['cell'],
					'cell' => 'persona cell',
					'status' => $status,
					'ordine_committente' => $committente,
					'ordine_oggetto' => $oggetto,
					'user_start' => $userStart,
					'user_stop' => $userStop,
					'note_detail' => $noteDetail,
					'note_detail_importanza' => $noteDetailImportanza,
					//'event_detail_activities' => $eventDetailActivities,
					'event_detail_activities' => [],
					'condiviso' => $condiviso,
					'utenti_condivisi' => $utentiCondivisi,
			        'tracks'=> [''],
				]
			);
		}

		return $result;
	}

	public function getTokenById($id_operatore){

		$users = TableRegistry::get('Users');
		$contatti = TableRegistry::get('Aziende.Contatti');
		$operatore = $contatti->get($id_operatore);
		$user = $users->get($operatore['id_user']);
		$secret_key = Configure::read('localconfig.SecretKey');

		if($user['auth_email']){
			$user_status = '1';
		}else{
			$user_status = '0';
		}

		$token = hash('sha256', $secret_key."_".$user['id']."_".$user_status);

		return $token;
	}
}
