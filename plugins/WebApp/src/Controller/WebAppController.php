<?php

namespace WebApp\Controller;

use Calendar\Controller\AppController;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Http\Response;

class WebAppController extends AppController
{
	public function initialize(){
		parent::initialize();
		$this->loadComponent('WebApp.WebApp');
	}

	public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['getEventsByOperator', 'setStartEvent', 'setStopEvent', 'getActivitiesByServiceId', 'isServerReachable']);
		$this->response->header('Access-Control-Allow-Origin','*');
    }

	public function getEventsByOperator(){

		if($this->request->getData('token_di_sicurezza') !== null
			&& $this->request->getData('token_di_sicurezza') !== ''){

			$id_operatore = $this->request->getData('id_operatore');
			$tokenCalcolato = $this->WebApp->getTokenById($id_operatore);

			if($this->request->getData('token_di_sicurezza') === $tokenCalcolato){

				$date = $this->request->getData('date');

				$jsonData = $this->WebApp->getEventsByOperator($date, $id_operatore);

				$jsonEncoded = json_encode($jsonData);

				$this->response->type('json');
				$this->response->body($jsonEncoded);
			}else{
				$this->response->type('json');
				$this->response->body(json_encode([
					'status' => 'KO',
					'message' => 'ERRORE. Token di sicurezza errato.',
					"calendar_events" =>[
						["groups" => [["periodo" => "mattino","sessions" => []],
						["periodo" => "pomeriggio","sessions" => []]
						]]],
						"speakers" => [[]],
						"map" => [[]]
				]));
			}

		}else{
			$this->response->type('json');
			$this->response->body(json_encode([
				'status' => 'KO',
				'message' => 'ERRORE. Token di sicurezza mancante.',
				"calendar_events" =>[
					["groups" => [["periodo" => "mattino","sessions" => []],
					["periodo" => "pomeriggio","sessions" => []]
					]]],
					"speakers" => [[]],
					"map" => [[]]
			]));
		}

		return $this->response;
	}

	public function setStartEvent(){

		if($this->request->getData('token_di_sicurezza') !== null
			&& $this->request->getData('token_di_sicurezza') !== ''){

			$id_operatore = $this->request->getData('id_operatore');
			$tokenCalcolato = $this->WebApp->getTokenById($id_operatore);

			if($this->request->getData('token_di_sicurezza') === $tokenCalcolato){

				$logs = fopen(LOGS . 'app.log', 'a');

				if($this->request->getData('real_start') !== null && $this->request->getData('real_start') !== ''){
					$time = new Time($this->request->getData('real_start'));
					$wasOffline = true;
				} else {
					$time = Time::now();
					$wasOffline = false;
				}

				$now = $time->i18nFormat('yyyy-MM-dd HH:mm:ss');
				$nowRounded = $time->i18nFormat('yyyy-MM-dd HH:mm:ss');

				$minute = $time->i18nFormat('mm');
				$minute = floor(($minute +2)/5)*5;
				$nowRounded = explode(':', $nowRounded);
				$nowRounded = $nowRounded[0] . ':' . $minute . ':' . $nowRounded[2];

				fwrite($logs, $now . '|' . $_SERVER['REMOTE_ADDR'] . '|' . 'setStartEvent chiamata con successo. recovery da offline: ' .$wasOffline.' - ora rounded=' .  $nowRounded . "." . PHP_EOL);

				if($this->request->is('post')){

					if(($this->request->getData('lat') !== null
					    && $this->request->getData('lat') !== ''
						&& $this->request->getData('long') !== null
						&& $this->request->getData('long') !== '')
						|| $this->request->getData('noLatLong') == '1'){

						if($this->request->getData('id_operatore') !== null
						   && $this->request->getData('id_operatore') !== ''
						   && $this->request->getData('id_event') !== null
					       && $this->request->getData('id_event') !== ''){

							$id_operatore = $this->request->getData('id_operatore');
							$id_event = $this->request->getData('id_event');

							$eventsDetail = TableRegistry::get('Calendar.EventiDettaglio');
							$events = TableRegistry::get('Calendar.Eventi');

							if($eventsDetail->find('all')->where(['AND' => ['event_id' => $id_event, 'operator_id' => $id_operatore]])->toArray()){

								$this->response->type('json');
								$this->response->body(json_encode([
									'status' => 'KO',
									'message' => 'ERRORE. Evento già startato.',
									'id_event' => $this->request->getData('id_event'),
								]));

								fwrite($logs, $now . '|' . $_SERVER['REMOTE_ADDR'] . '|' . $id_operatore . '|' . 'ERRORE. Evento gia startato.' . PHP_EOL);

							}else{

								$details = $eventsDetail->newEntity();
								$details->event_id = $id_event;
								$details->operator_id = $id_operatore;
								$details->user_start = $nowRounded;
								$details->real_start = $now;

								if($this->request->getData('noLatLong') == '1'){
									$lat = '';
									$long = '';
								}else{
									$lat = $this->request->getData('lat');
									$long = $this->request->getData('long');
								}

								$details->start_lat = $lat;
								$details->start_long = $long;

								$event = $events->get($id_event);
								$event->status = 'DOING';
								$details->status = 'DOING';

								$recovery_start = Time::now();

								$wasOffline ? $event->recovery_start = $recovery_start : '';

								if($eventsDetail->save($details) && $events->save($event)){
									$this->response->type('json');
									$this->response->body(json_encode([
										'status' => 'OK',
										'message' => 'Start dell\'evento impostato.',
										'id_event' => $this->request->getData('id_event'),
									]));

									fwrite($logs, $now .  '|' . $_SERVER['REMOTE_ADDR'] . '|' . $id_operatore . '|' . 'Start dell\'evento impostato con lat:.' .$lat . ' e long: ' .$long . " e ora start= " . $nowRounded .   PHP_EOL);
								}else{
									$this->response->type('json');
									$this->response->body(json_encode([
										'status' => 'KO',
										'message' => 'ERRORE. Start dell\'evento non impostato.',
										'id_event' => $this->request->getData('id_event'),
									]));

									fwrite($logs, $now . '|' . $_SERVER['REMOTE_ADDR'] . '|' . $id_operatore . '|' . 'ERRORE. Start dell\'evento non impostato.' . PHP_EOL);
								}

							}

						}else{

							$this->response->type('json');
							$this->response->body(json_encode([
								'status' => 'KO',
								'message' => 'ERRORE. id_user o id_event non trovati.',
								'id_event' => $this->request->getData('id_event'),
							]));

							fwrite($logs, $now . '|' . $_SERVER['REMOTE_ADDR'] . '|' . 'ERRORE. id_operatore o id_event non trovati.' . PHP_EOL);
						}

					}else{

						$this->response->type('json');
						$this->response->body(json_encode([
							'status' => 'KO',
							'message' => 'ERRORE. Latitudine e/o longitudine non trovati.',
							'id_event' => $this->request->getData('id_event'),
						]));

						fwrite($logs, $now . '|' . $_SERVER['REMOTE_ADDR'] . '|' . 'ERRORE. Latitudine e/o longitudine non trovati.' . PHP_EOL);
					}

				}else{

					$this->response->type('json');
					$this->response->body(json_encode([
						'status' => 'KO',
						'message' => 'ERRORE. Metodo non valido.',
						'id_event' => $this->request->getData('id_event'),
					]));

					fwrite($logs, $now . '|' . $_SERVER['REMOTE_ADDR'] . '|' . 'ERRORE. Metodo non valido.' . PHP_EOL);

				}

				fclose($logs);

			}else{
				$this->response->type('json');
				$this->response->body(json_encode([
					'status' => 'KO',
					'message' => 'ERRORE. Token di sicurezza errato.',
					'id_event' => $this->request->getData('id_event'),
				]));
			}

		}else{
			$this->response->type('json');
			$this->response->body(json_encode([
				'status' => 'KO',
				'message' => 'ERRORE. Token di sicurezza mancante.',
				'id_event' => $this->request->getData('id_event'),
			]));
		}

		return $this->response;

	}

	public function setStopEvent(){

		if($this->request->getData('token_di_sicurezza') !== null
			&& $this->request->getData('token_di_sicurezza') !== ''){

			$id_operatore = $this->request->getData('id_operatore');
			$tokenCalcolato = $this->WebApp->getTokenById($id_operatore);

			if($this->request->getData('token_di_sicurezza') === $tokenCalcolato){

				$logs = fopen(LOGS . 'app.log', 'a');

				$time = Time::now();
				$now = $time->i18nFormat('yyyy-MM-dd HH:mm:ss');

				fwrite($logs, $now . '|' . $_SERVER['REMOTE_ADDR'] . '|' . 'setStopEvent chiamata con successo.' . PHP_EOL);

				if($this->request->is('post')){

					if(($this->request->getData('lat') !== null
					    && $this->request->getData('lat') !== ''
						&& $this->request->getData('long') !== null
						&& $this->request->getData('long') !== '')
						|| $this->request->getData('noLatLong') == '1'){

						if($this->request->getData('id_operatore') !== null
						   && $this->request->getData('id_operatore') !== ''
						   && $this->request->getData('id_event') !== null
					       && $this->request->getData('id_event') !== ''){

							$id_operatore = $this->request->getData('id_operatore');
							$id_event = $this->request->getData('id_event');

							$eventsDetail = TableRegistry::get('Calendar.EventiDettaglio');
							$events = TableRegistry::get('Calendar.Eventi');
							//$detailActivities = TableRegistry::get('Calendar.EventiDettaglioAttivita');

							$details = $eventsDetail->find('all')
													->where(['AND' => ['event_id' => $id_event, 'operator_id' => $id_operatore]])
													->toArray();
							if($details){

								if($this->request->getData('start') !== null && $this->request->getData('start') !== ''){
									$start = $this->request->getData('start');
									$details[0]->user_start = $start;
								}

								if($this->request->getData('stop') !== null && $this->request->getData('stop') !== ''){
									$stop = $this->request->getData('stop');
									$details[0]->user_end = $stop;
								}else{
									$details[0]->user_end = $now;
								}

								$details[0]->real_end = $now;

								if($this->request->getData('noLatLong') == '1'){
									$lat = '';
									$long = '';
								}else{
									$lat = $this->request->getData('lat');
									$long = $this->request->getData('long');
								}

								$details[0]->stop_lat = $lat;
								$details[0]->stop_long = $long;

								if($this->request->getData('signature') !== null && $this->request->getData('signature') !== ''){
									$signature = $this->request->getData('signature');
									$signature = str_replace(" ", "+", $signature);
									$details[0]->signature = $signature;
								}

								if($this->request->getData('note_detail') !== null && $this->request->getData('note_detail') !== ''){
				                  $note_detail = $this->request->getData('note_detail');
				                  $note_detail_importanza = $this->request->getData('note_detail_importanza');
				                  $details[0]->note = $note_detail;
								  if($note_detail_importanza == 'true'){
									  $details[0]->note_importanza = 1;
								  }else{
									  $details[0]->note_importanza = 0;
								  }

				                }

								$event = $events->get($id_event);
								$event->status = 'DONE';
								$details[0]->status = 'DONE';

								if($this->request->getData('real_stop') !== null && $this->request->getData('real_stop') !== ''){
									$wasOffline = true;
								} else {
									$wasOffline = false;
								}
								$recovery_stop = Time::now();

								$wasOffline ? $event->recovery_stop = $recovery_stop : '';

								/*
								$activities = [];

								if($this->request->getData('activities') !== null && $this->request->getData('activities') !== ''){
									$activities = json_decode($this->request->getData('activities'), true);
								}

								$eventDetailActivities = $detailActivities->find()->where(['id_event_detail' => $details[0]['id']])->toArray();

								foreach($eventDetailActivities as $eventDetailActivity){
									$detailActivities->delete($eventDetailActivity);
								}
								

								foreach($activities as $key => $activity){
									$newEventDetailActivity = $detailActivities->newEntity();
									$newEventDetailActivity->id_event_detail = $details[0]['id'];
									$newEventDetailActivity->id_activity = $activity['id_activity'];

									if(isset($activity['note_activity'])){
										$newEventDetailActivity->note = $activity['note_activity'];
									}else{
										$newEventDetailActivity->note = '';
									}

									$detailActivities->save($newEventDetailActivity);
								}
								*/

								if($eventsDetail->save($details[0]) && $events->save($event)){
									$this->response->type('json');
									$this->response->body(json_encode([
										'status' => 'OK',
										'message' => 'End dell\'evento impostato.',
										'id_event' => $this->request->getData('id_event'),
									]));

									fwrite($logs, $now . '|' . $_SERVER['REMOTE_ADDR'] . '|' . $id_operatore . '|' . 'End dell\'evento impostato con lat:'. $lat . '  e long: '. $long .'  .' . PHP_EOL);

								}else{
									$this->response->type('json');
									$this->response->body(json_encode([
										'status' => 'KO',
										'message' => 'ERRORE. End dell\'evento non impostato.',
										'id_event' => $this->request->getData('id_event'),
									]));

									fwrite($logs, $now . '|' . $_SERVER['REMOTE_ADDR'] . '|' . $id_operatore . '|' . 'ERRORE. End dell\'evento non impostato.' . PHP_EOL);
								}

							}else{
								$this->response->type('json');
								$this->response->body(json_encode([
									'status' => 'KO',
									'message' => 'ERRORE. Evento non startato.',
									'id_event' => $this->request->getData('id_event'),
								]));

								fwrite($logs, $now . '|' . $_SERVER['REMOTE_ADDR'] . '|' . $id_operatore . '|' . 'ERRORE. Evento non startato.' . PHP_EOL);

							}

						}else{
							$this->response->type('json');
							$this->response->body(json_encode([
								'status' => 'KO',
								'message' => 'ERRORE. id_user o id_event non trovati.',
								'id_event' => $this->request->getData('id_event'),
							]));

							fwrite($logs, $now . '|' . $_SERVER['REMOTE_ADDR'] . '|' . 'ERRORE. id_operatore o id_event non trovati.' . PHP_EOL);
						}

					}else{
						$this->response->type('json');
						$this->response->body(json_encode([
							'status' => 'KO',
							'message' => 'ERRORE. Latitudine e/o longitudine non trovati.',
							'id_event' => $this->request->getData('id_event'),
						]));

						fwrite($logs, $now . '|' . $_SERVER['REMOTE_ADDR'] . '|' . 'ERRORE. Latitudine e/o longitudine non trovati.' . PHP_EOL);

					}

				}else{
					$this->response->type('json');
					$this->response->body(json_encode([
						'status' => 'KO',
						'message' => 'ERRORE. Metodo non valido.',
						'id_event' => $this->request->getData('id_event'),
					]));

					fwrite($logs, $now . '|' . $_SERVER['REMOTE_ADDR'] . '|' . 'ERRORE. Metodo non valido.' . PHP_EOL);
				}

				fclose($logs);

			}else{
				$this->response->type('json');
				$this->response->body(json_encode([
					'status' => 'KO',
					'message' => 'ERRORE. Token di sicurezza errato.',
					'id_event' => $this->request->getData('id_event'),
				]));
			}

		}else{
			$this->response->type('json');
			$this->response->body(json_encode([
				'status' => 'KO',
				'message' => 'ERRORE. Token di sicurezza mancante.',
				'id_event' => $this->request->getData('id_event'),
			]));
		}

		return $this->response;
	}

	
	public function getActivitiesByServiceId(){
		if($this->request->getData('token_di_sicurezza') !== null
			&& $this->request->getData('token_di_sicurezza') !== ''){

			$id_operatore = $this->request->getData('id_operatore');
			$tokenCalcolato = $this->WebApp->getTokenById($id_operatore);

			if($this->request->getData('token_di_sicurezza') === $tokenCalcolato){

				//if($this->request->getData('id_service') != null && $this->request->getData('id_service') != ''){
					/*$idService = $this->request->getData('id_service');
					$activities = TableRegistry::get('Progest.Activities');
					if($this->request->getData('id_service') != null && $this->request->getData('id_service') != ''){
						$serviceActivities = $activities->find()->where(['id_service' => $idService])->toArray();
					} else {
						$serviceActivities = $activities->find()->toArray();
					}*/

					$serviceActivities = [];

					if(!empty($serviceActivities)){
						$this->response->type('json');
						$this->response->body(json_encode([
							'status' => 'OK',
							'message' => '',
							"data" => $serviceActivities,
						]));
					}else{
						$this->response->type('json');
						$this->response->body(json_encode([
							'status' => 'OK',
							'message' => 'Nessuna attività trovata per questo servizio',
							"data" => [],
						]));
					}
				// }else{
				// 	$this->response->type('json');
				// 	$this->response->body(json_encode([
				// 		'status' => 'KO',
				// 		'message' => 'ERRORE. Id servizio mancante.',
				// 		"data" => [],
				// 	]));
				// }

			}else{
				$this->response->type('json');
				$this->response->body(json_encode([
					'status' => 'KO',
					'message' => 'ERRORE. Token di sicurezza errato.',
					"data" => [],
				]));
			}

		}else{
			$this->response->type('json');
			$this->response->body(json_encode([
				'status' => 'KO',
				'message' => 'ERRORE. Token di sicurezza mancante.',
				"data" =>[],
			]));
		}

		return $this->response;
	}
	

	public function isServerReachable(){
		$this->response->type('json');
		$this->response->body(json_encode([
			'status' => 'OK',
			'message' => ''
		]));
		return $this->response;
	}

}
