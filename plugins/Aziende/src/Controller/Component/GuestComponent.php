<?php
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class GuestComponent extends Component
{
    public function getGuests($sedeId, $aziendaTipo, $showOld = false, $pass = array()){

        $guests = TableRegistry::get('Aziende.Guests');
		
		$columns[] = ['val' => 'Guests.check_in_date', 'type' => 'date'];
		if ($aziendaTipo == 1) {
			$columns[] = ['val' => 'Guests.cui', 'type' => 'text'];
			$columns[] = ['val' => 'Guests.vestanet_id', 'type' => 'text'];
		}
		$columns[] = ['val' => 'Guests.name', 'type' => 'text'];
		$columns[] = ['val' => 'Guests.surname', 'type' => 'text'];
		$columns[] = ['val' => 'Guests.birthdate', 'type' => 'date'];
		$columns[] = ['val' => 'Guests.sex', 'type' => 'text'];
		if ($aziendaTipo == 1) {
			$columns[] = ['val' => 'Guests.draft', 'type' => 'number'];
			$columns[] = ['val' => 'Guests.draft_expiration', 'type' => 'date'];
			$columns[] = ['val' => 'Guests.suspended', 'type' => 'number'];
		}
		$columns[] = ['val' => 'gs.name', 'type' => 'text'];
        
        $opt['fields'] = [
			'Guests.id', 
			'Guests.check_in_date', 
			'Guests.cui', 
			'Guests.vestanet_id', 
			'Guests.name',
			'Guests.surname', 
			'Guests.birthdate', 
			'Guests.sex', 
			'Guests.draft',
			'Guests.draft_expiration', 
			'Guests.suspended',
			'Guests.status_id',
			'gs.name',
			'gs.color'
		];

		$opt['join'] = [
			[
				'table' => 'guests_statuses',
				'alias' => 'gs',
				'type' => 'LEFT',
				'conditions' => 'Guests.status_id = gs.id'
			]
		];
        
		$opt['conditions'] = ['Guests.sede_id' => $sedeId];

		if (!$showOld) {
			$opt['conditions']['gs.visibility'] = 1;
		}

        $toRet['res'] = $guests->queryForTableSorter($columns, $opt, $pass); 
        $toRet['tot'] = $guests->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;

    }

	public function getGuestsNotifications($pass = [])
	{
        $guests = TableRegistry::get('Aziende.GuestsNotifications');
		
		$columns = [
			0 => ['val' => 'a.denominazione', 'type' => 'text'],
			1 => ['val' => 'CONCAT(s.indirizzo, " ", s.num_civico, " - ", l.des_luo)', 'type' => 'text'],
			2 => ['val' => 'CONCAT(g.name, " ", g.surname)', 'type' => 'text'],
			3 => ['val' => 'CONCAT(u.nome, " ", u.cognome)', 'type' => 'text'],
			4 => ['val' => 't.msg_singular', 'type' => 'text'],
			5 => ['val' => 'GuestsNotifications.done', 'type' => ''],
			//6 => ['val' => 'CONCAT(u2.nome, " ", u2.cognome)', 'type' => 'text'],
			//7 => ['val' => 'GuestsNotifications.done_date', 'type' => 'date']
        ];
        
        $opt['fields'] = [
			'GuestsNotifications.id',
			'GuestsNotifications.done',
			'GuestsNotifications.done_date',
			'a.id',
			'a.denominazione',
			's.id',
			's.indirizzo',
			's.num_civico',
			'l.des_luo',
			'g.id',
			'g.name',
			'g.surname',
			'u.nome',
			'u.cognome',
			'u2.nome',
			'u2.cognome',
			't.msg_singular'
		];

		$opt['join'] = [
			[
				'table' => 'aziende',
				'alias' => 'a',
				'type' => 'LEFT',
				'conditions' => ['GuestsNotifications.azienda_id = a.id']
			],
			[
				'table' => 'sedi',
				'alias' => 's',
				'type' => 'LEFT',
				'conditions' => ['GuestsNotifications.sede_id = s.id']
			],
			[
				'table' => 'luoghi',
				'alias' => 'l',
				'type' => 'LEFT',
				'conditions' => ['s.comune = l.c_luo']
			],
			[
				'table' => 'guests',
				'alias' => 'g',
				'type' => 'LEFT',
				'conditions' => ['GuestsNotifications.guest_id = g.id']
			],
			[
				'table' => 'users',
				'alias' => 'u',
				'type' => 'LEFT',
				'conditions' => ['GuestsNotifications.user_maker_id = u.id']
			],
			[
				'table' => 'users',
				'alias' => 'u2',
				'type' => 'LEFT',
				'conditions' => ['GuestsNotifications.user_done_id = u2.id']
			],
			[
				'table' => 'guests_notifications_types',
				'alias' => 't',
				'type' => 'LEFT',
				'conditions' => ['GuestsNotifications.type_id = t.id']
			]
		];

		$all = filter_var($pass['query']['all'], FILTER_VALIDATE_BOOLEAN);

		if (!$all) {
			$opt['conditions']['AND']['GuestsNotifications.done'] = 0;
		}

        $toRet['res'] = $guests->queryForTableSorter($columns, $opt, $pass);
        $toRet['tot'] = $guests->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;

    }

	public function exitGuest($guest, $data, $today, $status)
	{
		$error = '';

		// Se stato diverso da "In struttura" non eseguo nessuna operazione sull'ospite
		if ($guest->status_id == 1) {

			//Presenza oggi
			$guestPresenza = TableRegistry::get('Aziende.Presenze')->getGuestPresenzaByDate($guest->id, date('Y-m-d'));

			if (!$guestPresenza) {
				$sede = TableRegistry::get('Aziende.Sedi')->get($guest->sede_id);

				//aggiornamento storico
				$guestsHistory = TableRegistry::get('Aziende.GuestsHistories');
				$history = $guestsHistory->newEntity();

				$historyData['guest_id'] = $guest->id;
				$historyData['azienda_id'] = $sede->id_azienda;
				$historyData['sede_id'] = $guest->sede_id;
				$historyData['operator_id'] = $this->request->session()->read('Auth.User.id');
				$historyData['operation_date'] = $today->format('Y-m-d');
				$historyData['guest_status_id'] = $status;
				$historyData['exit_type_id'] = $data['exit_type_id'];
				$historyData['note'] = $data['note'];

				$guestsHistory->patchEntity($history, $historyData);

				if ($guestsHistory->save($history)) {
					//aggiornamento stato ospite e data di check-out
					$guests = TableRegistry::get('Aziende.Guests');

					$guest->status_id = $status;
					$guest->check_out_date = $today->format('Y-m-d');  

					if ($guests->save($guest)) {
						if ($status == 3) {
							//creazione notifica uscita ospite
							$saveType = 'EXITED_GUEST';
							$guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');
							$notification = $guestsNotifications->newEntity();
							$notificationType = TableRegistry::get('Aziende.GuestsNotificationsTypes')->find()->where(['name' => $saveType])->first();
							$notificationData = [
								'type_id' => $notificationType->id,
								'azienda_id' => $sede->id_azienda,
								'sede_id' => $sede->id,
								'guest_id' => $guest->id,
								'user_maker_id' => $this->request->session()->read('Auth.User.id')
							];
							$guestsNotifications->patchEntity($notification, $notificationData);
							$guestsNotifications->save($notification);
						}
					} else {
						$error = "Errore nell'aggiornamento dello stato dell'ospite.";
					}
				} else {
					$error = "Errore nell'aggiornamento dello storico dell'ospite.";
				}  
			} else {
				$error = "L'ospite è segnato come presente nella giornata di oggi. Non è possibile avviare la procedura di uscita.";
			}
		}

		return $error;
	}

	public function confirmExitGuest($guest, $data, $today)
	{
		$error = '';

		// Se stato diverso da "In uscita" non eseguo nessuna operazione sull'ospite
		if ($guest->status_id == 2) {
			$sede = TableRegistry::get('Aziende.Sedi')->get($guest->sede_id);

			//aggiornamento storico
			$guestsHistory = TableRegistry::get('Aziende.GuestsHistories');
			$history = $guestsHistory->newEntity();
	
			$lastHistory = $guestsHistory->getLastGuestHistoryByStatus($guest->id, 2);
			$exitType = TableRegistry::get('Aziende.GuestsExitTypes')->get($lastHistory->exit_type_id); 

			$historyData['guest_id'] = $guest->id;
			$historyData['azienda_id'] = $sede->id_azienda;
			$historyData['sede_id'] = $guest->sede_id;
			$historyData['operator_id'] = $this->request->session()->read('Auth.User.id');
			$historyData['operation_date'] = $today->format('Y-m-d');
			$historyData['guest_status_id'] = 3;
			$historyData['exit_type_id'] = $lastHistory->exit_type_id;
			$historyData['note'] = $lastHistory->note;
	
			$guestsHistory->patchEntity($history, $historyData);

			if ($guestsHistory->save($history)) {
				//aggiornamento stato ospite e data di check-out
				$guests = TableRegistry::get('Aziende.Guests');

				//aggiornamento stato ospite e data di check-out
				$guest->status_id = 3;
				$guest->check_out_date = new Time(substr($data['check_out_date'], 0, 33));

				if ($guests->save($guest)) {
					//creazione notifica uscita ospite
					$saveType = 'EXITED_GUEST';
					$guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');
					$notification = $guestsNotifications->newEntity();
					$notificationType = TableRegistry::get('Aziende.GuestsNotificationsTypes')->find()->where(['name' => $saveType])->first();
					$notificationData = [
						'type_id' => $notificationType->id,
						'azienda_id' => $sede->id_azienda,
						'sede_id' => $sede->id,
						'guest_id' => $guest->id,
						'user_maker_id' => $this->request->session()->read('Auth.User.id')
					];
					$guestsNotifications->patchEntity($notification, $notificationData);
					$guestsNotifications->save($notification);
				} else {
					$error = "Errore nell'aggiornamento dello stato dell'ospite.";
				}
			} else {
				$error = "Errore nell'aggiornamento dello storico dell'ospite.";
			}  
		}

		return $error;
	}

	public function transferGuest($guest, $data, $today)
	{
		$error = '';

		// Se stato diverso da "In struttura" non eseguo nessuna operazione sull'ospite
		if ($guest->status_id == 1) {

			//Presenza oggi
			$guestPresenza = TableRegistry::get('Aziende.Presenze')->getGuestPresenzaByDate($guest->id, date('Y-m-d'));

			if (!$guestPresenza) {
				$sede = TableRegistry::get('Aziende.Sedi')->get($guest->sede_id);

				// se rimane nello stesso ente non serve conferma trasferimento
				if ($sede->id_azienda == $data['azienda']) {
					$status = 6;
					$statusCloned = 1;
				} else {
					$status = 4;
					$statusCloned = 5;
				}

				//aggiornamento storico
				$guestsHistory = TableRegistry::get('Aziende.GuestsHistories');
				$history = $guestsHistory->newEntity();

				$historyData['guest_id'] = $guest->id;
				$historyData['azienda_id'] = $sede->id_azienda;
				$historyData['sede_id'] = $guest->sede_id;
				$historyData['operator_id'] = $this->request->session()->read('Auth.User.id');
				$historyData['operation_date'] = $today->format('Y-m-d');
				$historyData['guest_status_id'] = $status;
				$historyData['destination_id'] = $data['sede'];
				$historyData['note'] = $data['note'];

				$guestsHistory->patchEntity($history, $historyData);

				if ($guestsHistory->save($history)) {
					//aggiornamento stato ospite e data di check-out
					$guests = TableRegistry::get('Aziende.Guests');

					$guest->status_id = $status;
					$guest->check_out_date = $today->format('Y-m-d');

					if ($guests->save($guest)) {
						//inserimento ospite clonato per struttura di destinazione
						$dataGuest = clone $guest;
						$dataClonedGuest = $dataGuest->toArray();
						$dataClonedGuest['sede_id'] = $data['sede'];
						$dataClonedGuest['status_id'] = $statusCloned;
						$dataClonedGuest['original_guest_id'] = empty($guest->original_guest_id) ? $guest->id : $guest->original_guest_id;
						unset($dataClonedGuest['id']);
						unset($dataClonedGuest['created']);
						unset($dataClonedGuest['modified']);
						unset($dataClonedGuest['check_out_date']);

						// se rimane nello stesso ente setta già la nuova data di check-in, altrimenti verrà compilata sulla conferma del trasferimento
						if ($sede->id_azienda == $data['azienda']) {
							$dataClonedGuest['check_in_date'] = $today->format('Y-m-d');
						} else {
							unset($dataClonedGuest['check_in_date']);
						}

						$clonedGuest = $guests->newEntity($dataClonedGuest);

						if ($guests->save($clonedGuest)) {
							$history->cloned_guest_id = $clonedGuest->id;
							$guestsHistory->save($history);

							//clonazione storico
							$oldHistory = $guestsHistory->find()
								->where(['guest_id' => $guest->id])
								->toArray();

							foreach ($oldHistory as $h) {
								$clonedHistory = $guestsHistory->newEntity();
								$oldHistoryData = $h->toArray();
								unset($oldHistoryData['id']);
								unset($oldHistoryData['created']);
								unset($oldHistoryData['modified']);
								$oldHistoryData['guest_id'] = $clonedGuest->id;

								$clonedHistory = $guestsHistory->patchEntity($clonedHistory, $oldHistoryData); 

								$guestsHistory->save($clonedHistory);
							}

							//aggiornamento storico ospite clonato
							$historyClonedGuest = $guestsHistory->newEntity();

							$sedeClonedGuest = TableRegistry::get('Aziende.Sedi')->get($data['sede'], ['contain' => ['Comuni', 'Aziende']]);
					
							$historyClonedGuestData['guest_id'] = $clonedGuest->id;
							$historyClonedGuestData['azienda_id'] = $sedeClonedGuest->id_azienda;
							$historyClonedGuestData['sede_id'] = $data['sede'];
							$historyClonedGuestData['operator_id'] = $this->request->session()->read('Auth.User.id');
							$historyClonedGuestData['operation_date'] = $today->format('Y-m-d');
							$historyClonedGuestData['guest_status_id'] = $clonedGuest->status_id;
							$historyClonedGuestData['cloned_guest_id'] = $guest->id;
							$historyClonedGuestData['provenance_id'] = $sede->id;
							$historyClonedGuestData['note'] = $data['note'];
					
							$guestsHistory->patchEntity($historyClonedGuest, $historyClonedGuestData);
					
							if ($guestsHistory->save($historyClonedGuest)) {
								// Assegno l'ospite clonato alla famiglie
								$guestsFamilies = TableRegistry::get('Aziende.GuestsFamilies');
            					$guestFamily = $guestsFamilies->find()->where(['guest_id' => $guest->id])->first();
								if (!empty($guestFamily->family_id)) {
									$clonedGuestFamily = $guestsFamilies->newEntity();
									$clonedGuestFamily->family_id = $guestFamily->family_id;
									$clonedGuestFamily->guest_id = $clonedGuest->id;
									$guestsFamilies->save($clonedGuestFamily);
								}
								if ($status == 6) {
									//creazione notifica trasferimento ospite
									$saveType = 'TRANSFERRED_GUEST';
									$guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');
									$notification = $guestsNotifications->newEntity();
									$notificationType = TableRegistry::get('Aziende.GuestsNotificationsTypes')->find()->where(['name' => $saveType])->first();
									$notificationData = [
										'type_id' => $notificationType->id,
										'azienda_id' => $sede->id_azienda,
										'sede_id' => $sede->id,
										'guest_id' => $guest->id,
										'user_maker_id' => $this->request->session()->read('Auth.User.id')
									];
									$guestsNotifications->patchEntity($notification, $notificationData);
									$guestsNotifications->save($notification);
								}
							} else {
								$error = "Errore nell'aggiornamento dello storico dell'ospite clonato per la struttura di destinazione.";
							}
						} else {
							$error = "Errore nella clonazione dell'ospite nella struttura di destinazione.";
						}
					} else {
						$error = "Errore nell'aggiornamento dello stato dell'ospite.";
					}
				} else {
					$error = "Errore nell'aggiornamento dello storico dell'ospite.";
				}
			} else {
				$error = "L'ospite è segnato come presente nella giornata di oggi. Non è possibile avviare la procedura di trasferimento.";
			}
		}

		return $error;
	}

	public function acceptTransferGuest($guest, $data, $today)
	{
		$error = '';

		// Se stato diverso da "Trasferimento in ingresso" non eseguo nessuna operazione sull'ospite
		if ($guest->status_id == 5) {
			//aggiornamento storico
			$guestsHistory = TableRegistry::get('Aziende.GuestsHistories');
			$history = $guestsHistory->newEntity();
	
			$lastHistory = $guestsHistory->getLastGuestHistoryByStatus($guest->id, 5);
			$sede = TableRegistry::get('Aziende.Sedi')->get($guest->sede_id);
	
			$historyData['guest_id'] = $guest->id;
			$historyData['azienda_id'] = $sede->id_azienda;
			$historyData['sede_id'] = $guest->sede_id;
			$historyData['operator_id'] = $this->request->session()->read('Auth.User.id');
			$historyData['operation_date'] = $today->format('Y-m-d');
			$historyData['guest_status_id'] = 1;
	
			$guestsHistory->patchEntity($history, $historyData);
	
			if ($guestsHistory->save($history)) {
				//aggiornamento stato ospite e data check-in
				$guests = TableRegistry::get('Aziende.Guests');
				
				$guest->status_id = 1;
				$guest->check_in_date = $today->format('Y-m-d');
	
				if ($guests->save($guest)) {
					$originalGuest = $guests->get($lastHistory->cloned_guest_id);
					
					//aggiornamento stato ospite originale e data check-out
					$originalHistory = $guestsHistory->newEntity();
	
					$lastOriginalHistory = $guestsHistory->getLastGuestHistoryByStatus($originalGuest->id, 4);
					$sede = TableRegistry::get('Aziende.Sedi')->get($originalGuest->sede_id);
	
					$originalHistoryData['guest_id'] = $originalGuest->id;
					$originalHistoryData['azienda_id'] = $sede->id_azienda;
					$originalHistoryData['sede_id'] = $guest->sede_id;
					$originalHistoryData['operator_id'] = $this->request->session()->read('Auth.User.id');
					$originalHistoryData['operation_date'] = $today->format('Y-m-d');
					$originalHistoryData['guest_status_id'] = 6;
					$originalHistoryData['cloned_guest_id'] = $lastOriginalHistory->cloned_guest_id;
					$originalHistoryData['destination_id'] = $lastOriginalHistory->destination_id;
					$originalHistoryData['note'] = $lastOriginalHistory->note;
	
					$guestsHistory->patchEntity($originalHistory, $originalHistoryData);
	
					if ($guestsHistory->save($originalHistory)) {
						$originalGuest->status_id = 6;
						$originalGuest->check_out_date = $today->format('Y-m-d');
	
						if ($guests->save($originalGuest)) {
							//creazione notifica trasferimento ospite
							$saveType = 'TRANSFERRED_GUEST';
							$guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');
							$notification = $guestsNotifications->newEntity();
							$notificationType = TableRegistry::get('Aziende.GuestsNotificationsTypes')->find()->where(['name' => $saveType])->first();
							$notificationData = [
								'type_id' => $notificationType->id,
								'azienda_id' => $sede->id_azienda,
								'sede_id' => $sede->id,
								'guest_id' => $originalGuest->id,
								'user_maker_id' => $this->request->session()->read('Auth.User.id')
							];
							$guestsNotifications->patchEntity($notification, $notificationData);
							$guestsNotifications->save($notification);
						} else {
							$error =  "Errore nell'aggiornamento dello stato dell'ospite originale.";
						}
					} else {
						$error =  "Errore nell'aggiornamento dello storico dell'ospite originale.";
					}
				}else{
					$error =  "Errore nell'aggiornamento dello stato dell'ospite.";
				}	
			} else {
				$error =  "Errore nell'aggiornamento dello storico dell'ospite.";
			}
		}

		return $error;
	}

}
