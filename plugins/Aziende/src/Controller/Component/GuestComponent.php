<?php
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Core\Configure;

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
		$columns[] = ['val' => 'l.des_luo', 'type' => 'text'];
		if ($aziendaTipo == 1) {
			$columns[] = ['val' => 'Guests.draft', 'type' => 'number'];
			$columns[] = ['val' => 'Guests.draft_expiration', 'type' => 'date'];
			$columns[] = ['val' => 'Guests.suspended', 'type' => 'number'];
		}
		$columns[] = ['val' => 'CONCAT(gs.name, IF(exit_request_status_id IS NOT NULL, CONCAT(" - ", gers.name), ""))', 'type' => 'text'];
        
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
			'Guests.exit_request_status_id',
			'Guests.original_guest_id',
			'Guests.created',
			'gs.name',
			'gs.color',
			'gers.name',
			'gers.color',
			'l.des_luo'
		];

		$opt['join'] = [
			[
				'table' => 'guests_statuses',
				'alias' => 'gs',
				'type' => 'LEFT',
				'conditions' => 'Guests.status_id = gs.id'
			],
			[
				'table' => 'guests_exit_request_statuses',
				'alias' => 'gers',
				'type' => 'LEFT',
				'conditions' => 'Guests.exit_request_status_id = gers.id'
			],
			[
				'table' => 'luoghi',
				'alias' => 'l',
				'type' => 'LEFT',
				'conditions' => 'Guests.country_birth = l.c_luo'
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

	public function getGuestsNotifications($enteType = 1, $pass = [])
	{
        $guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');

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

		$opt['conditions']['AND']['t.ente_type'] = $enteType;

		$all = filter_var($pass['query']['all'], FILTER_VALIDATE_BOOLEAN);

		if (!$all) {
			$opt['conditions']['AND']['GuestsNotifications.done'] = 0;
		}

		$opt['order'] = ['GuestsNotifications.created' => 'ASC'];

        $toRet['res'] = $guestsNotifications->queryForTableSorter($columns, $opt, $pass);
        $toRet['tot'] = $guestsNotifications->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;

    }

	public function getGuestsNotificationsForBulkMarking($enteType = 1, $pass = [])
	{
        $guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');

		$columns = [
			0 => ['val' => 'a.denominazione', 'type' => 'text'],
			1 => ['val' => 'CONCAT(s.indirizzo, " ", s.num_civico, " - ", l.des_luo)', 'type' => 'text'],
			2 => ['val' => 'CONCAT(g.name, " ", g.surname)', 'type' => 'text'],
			3 => ['val' => 'CONCAT(u.nome, " ", u.cognome)', 'type' => 'text'],
			4 => ['val' => 't.msg_singular', 'type' => 'text'],
			5 => ['val' => 'GuestsNotifications.done', 'type' => '']
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
				'table' => 'guests_notifications_types',
				'alias' => 't',
				'type' => 'LEFT',
				'conditions' => ['GuestsNotifications.type_id = t.id']
			]
		];

		$opt['conditions']['AND']['t.ente_type'] = $enteType;
		$opt['conditions']['AND']['GuestsNotifications.done'] = 0;

        $notifications = $guestsNotifications->queryForTableSorter($columns, $opt, $pass);

        return $notifications;

    }

	public function requestExitGuest($guest, $data, $today, $filePath)
	{
		$error = '';

		// Se stato diverso da "In struttura" non eseguo nessuna operazione sull'ospite
		if ($guest->status_id == 1) {

			$sede = TableRegistry::get('Aziende.Sedi')->get($guest->sede_id);

			//aggiornamento storico
			$guestsHistory = TableRegistry::get('Aziende.GuestsHistories');
			$history = $guestsHistory->newEntity();

			$historyData['guest_id'] = $guest->id;
			$historyData['azienda_id'] = $sede->id_azienda;
			$historyData['sede_id'] = $guest->sede_id;
			$historyData['operator_id'] = $this->request->session()->read('Auth.User.id');
			$historyData['operation_date'] = $today->format('Y-m-d');
			$historyData['guest_status_id'] = 1;
			$historyData['guest_exit_request_status_id'] = 1;
			$historyData['exit_type_id'] = $data['exit_type_id'];
			$historyData['file'] = $filePath;
			$historyData['note'] = $data['note'];

			$guestsHistory->patchEntity($history, $historyData);

			if ($guestsHistory->save($history)) {
				//aggiornamento stato richiesta uscita ospite
				$guests = TableRegistry::get('Aziende.Guests');

				$guest->exit_request_status_id = 1; 

				if ($guests->save($guest)) {
					//creazione notifica richiesta uscita ospite
					$azienda = TableRegistry::get('Aziende.Aziende')->get($sede->id_azienda);
					if ($azienda->id_tipo == 1) {
						$saveType = 'REQUEST_EXIT_GUEST';
					} else if ($azienda->id_tipo == 2) {
						$saveType = 'REQUEST_EXIT_GUEST_UKRAINE';
					}

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

	public function authorizeRequestExitGuest($guest, $data, $today, $filePath)
	{
		$error = '';

		// Se stato diverso da "In struttura" non eseguo nessuna operazione sull'ospite
		if ($guest->status_id == 1) {

			$sede = TableRegistry::get('Aziende.Sedi')->get($guest->sede_id);

			//aggiornamento storico
			$guestsHistory = TableRegistry::get('Aziende.GuestsHistories');
			$history = $guestsHistory->newEntity();

			$historyData['guest_id'] = $guest->id;
			$historyData['azienda_id'] = $sede->id_azienda;
			$historyData['sede_id'] = $guest->sede_id;
			$historyData['operator_id'] = $this->request->session()->read('Auth.User.id');
			$historyData['operation_date'] = $today->format('Y-m-d');
			$historyData['guest_status_id'] = 1;
			$historyData['guest_exit_request_status_id'] = 2;
			$historyData['exit_type_id'] = $data['exit_type_id'];
			$historyData['file'] = $filePath;
			$historyData['note'] = $data['note'];

			$guestsHistory->patchEntity($history, $historyData);

			if ($guestsHistory->save($history)) {
				//aggiornamento stato richiesta uscita ospite
				$guests = TableRegistry::get('Aziende.Guests');

				$guest->exit_request_status_id = 2; 

				if (!$guests->save($guest)) {
					$error = "Errore nell'aggiornamento dello stato dell'ospite.";
				}
			} else {
				$error = "Errore nell'aggiornamento dello storico dell'ospite.";
			}
		}

		return $error;
	}

	public function exitGuest($guest, $data, $today, $status, $filePath)
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
				$historyData['file'] = $filePath;
				$historyData['note'] = $data['note'];

				$guestsHistory->patchEntity($history, $historyData);

				if ($guestsHistory->save($history)) {
					//aggiornamento stato ospite e data di check-out
					$guests = TableRegistry::get('Aziende.Guests');

					$guest->status_id = $status;
					$guest->exit_request_status_id = null;
					$guest->check_out_date = $today->format('Y-m-d');  

					if ($guests->save($guest)) {
						//creazione notifica uscita ospite
						$azienda = TableRegistry::get('Aziende.Aziende')->get($sede->id_azienda);
						if ($status == 2) {
							if ($azienda->id_tipo == 1) {
								$saveType = 'CONFIRM_EXIT_GUEST';
							} else if ($azienda->id_tipo == 2) {
								$saveType = 'CONFIRM_EXIT_GUEST_UKRAINE';
							}

						} else if ($status == 3) {
							if ($azienda->id_tipo == 1) {
								$saveType = 'EXITED_GUEST';
							} else if ($azienda->id_tipo == 2) {
								$saveType = 'EXITED_GUEST_UKRAINE';
							}
						}
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

			$checkoutDate = new Time(substr($data['check_out_date'], 0, 33));

			//Presenza future
			$guestPresenza = TableRegistry::get('Aziende.Presenze')->getGuestPresenzeByDate($guest->id, $checkoutDate);

			if (!$guestPresenza) {

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
				$historyData['file'] = $lastHistory->file;
				$historyData['note'] = $lastHistory->note;
		
				$guestsHistory->patchEntity($history, $historyData);

				if ($guestsHistory->save($history)) {
					//aggiornamento stato ospite e data di check-out
					$guests = TableRegistry::get('Aziende.Guests');

					//aggiornamento stato ospite e data di check-out
					$guest->status_id = 3;
					$guest->check_out_date = $checkoutDate;

					if ($guests->save($guest)) {
						$azienda = TableRegistry::get('Aziende.Aziende')->get($sede->id_azienda);
						if ($azienda->id_tipo == 1) {
							$getType = 'CONFIRM_EXIT_GUEST';
							$saveType = 'EXITED_GUEST';
						} else if ($azienda->id_tipo == 2) {
							$getType = 'CONFIRM_EXIT_GUEST_UKRAINE';
							$saveType = 'EXITED_GUEST_UKRAINE';
						}

						$guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');
						$guestsNotificationsTypes = TableRegistry::get('Aziende.GuestsNotificationsTypes');

						//notifica di conferma uscita segnata come gestita
						$confirmNotificationType = $guestsNotificationsTypes->find()->where(['name' => $getType])->first();
						$confirmNotification = $guestsNotifications->find()->where(['type_id' => $confirmNotificationType->id, 'guest_id' => $guest->id])->first();
						if ($confirmNotification) {
							$confirmNotification->done = 1;
							$guestsNotifications->save($confirmNotification);
						}

						//creazione notifica uscita ospite
						$exitedNotification = $guestsNotifications->newEntity();
						$exitedNotificationType = $guestsNotificationsTypes->find()->where(['name' => $saveType])->first();
						$notificationData = [
							'type_id' => $exitedNotificationType->id,
							'azienda_id' => $sede->id_azienda,
							'sede_id' => $sede->id,
							'guest_id' => $guest->id,
							'user_maker_id' => $this->request->session()->read('Auth.User.id')
						];
						$guestsNotifications->patchEntity($exitedNotification, $notificationData);
						$guestsNotifications->save($exitedNotification);
					} else {
						$error = "Errore nell'aggiornamento dello stato dell'ospite.";
					}
				} else {
					$error = "Errore nell'aggiornamento dello storico dell'ospite.";
				}
			} else {
				$error = "L'ospite è segnato come presente in giorni successivi alla data di check-out. Non è possibile confermare la procedura di uscita.";
			}
		}

		return $error;
	}

	public function transferGuest($guest, $data, $checkOutDate)
	{
		$error = '';

		// Se stato diverso da "In struttura" non eseguo nessuna operazione sull'ospite
		if ($guest->status_id == 1) {

			//Presenza alla data di uscite
			$guestPresenza = TableRegistry::get('Aziende.Presenze')->getGuestPresenzeByDate($guest->id, $checkOutDate);

			if (empty($guestPresenza)) {
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
				$historyData['operation_date'] = $checkOutDate;
				$historyData['guest_status_id'] = $status;
				$historyData['destination_id'] = $data['sede'];
				$historyData['note'] = $data['note'];

				$guestsHistory->patchEntity($history, $historyData);

				if ($guestsHistory->save($history)) {
					//aggiornamento stato ospite e data di check-out
					$guests = TableRegistry::get('Aziende.Guests');

					$guest->status_id = $status;
					$guest->check_out_date = $checkOutDate;

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
							$dataClonedGuest['check_in_date'] = $checkOutDate;
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
							$historyClonedGuestData['operation_date'] = $checkOutDate;
							$historyClonedGuestData['guest_status_id'] = $clonedGuest->status_id;
							$historyClonedGuestData['cloned_guest_id'] = $guest->id;
							$historyClonedGuestData['provenance_id'] = $sede->id;
							$historyClonedGuestData['note'] = $data['note'];
					
							$guestsHistory->patchEntity($historyClonedGuest, $historyClonedGuestData);
					
							if ($guestsHistory->save($historyClonedGuest)) {
								// Assegno l'ospite clonato alla famiglia
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
									$azienda = TableRegistry::get('Aziende.Aziende')->get($sede->id_azienda);
									if ($azienda->id_tipo == 1) {
										$saveType = 'TRANSFERRED_GUEST';
									} else if ($azienda->id_tipo == 2) {
										$saveType = 'TRANSFERRED_GUEST_UKRAINE';
									}
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
				$error = "L'ospite è segnato come presente nel giorno ".$checkOutDate->format('d/m/Y')." o nelle giornate successive. Non è possibile avviare la procedura di trasferimento.";
			}
		}

		return $error;
	}

	public function acceptTransferGuest($guest, $data, $checkInDate)
	{
		$error = '';

		// Se stato diverso da "Trasferimento in ingresso" non eseguo nessuna operazione sull'ospite
		if ($guest->status_id == 5) {
			//aggiornamento storico
			$guestsHistory = TableRegistry::get('Aziende.GuestsHistories');

			$lastHistory = $guestsHistory->getLastGuestHistoryByStatus($guest->id, 5);

			if ($checkInDate->format('Y-m-d') >= $lastHistory->operation_date->format('Y-m-d')) {
				$history = $guestsHistory->newEntity();	
				
				$sede = TableRegistry::get('Aziende.Sedi')->get($guest->sede_id);
		
				$historyData['guest_id'] = $guest->id;
				$historyData['azienda_id'] = $sede->id_azienda;
				$historyData['sede_id'] = $guest->sede_id;
				$historyData['operator_id'] = $this->request->session()->read('Auth.User.id');
				$historyData['operation_date'] = $checkInDate;
				$historyData['guest_status_id'] = 1;
		
				$guestsHistory->patchEntity($history, $historyData);
		
				if ($guestsHistory->save($history)) {
					//aggiornamento stato ospite e data check-in
					$guests = TableRegistry::get('Aziende.Guests');
					
					$guest->status_id = 1;
					$guest->check_in_date = $checkInDate;
		
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
						$originalHistoryData['operation_date'] = $checkInDate;
						$originalHistoryData['guest_status_id'] = 6;
						$originalHistoryData['cloned_guest_id'] = $lastOriginalHistory->cloned_guest_id;
						$originalHistoryData['destination_id'] = $lastOriginalHistory->destination_id;
						$originalHistoryData['note'] = $lastOriginalHistory->note;
		
						$guestsHistory->patchEntity($originalHistory, $originalHistoryData);
		
						if ($guestsHistory->save($originalHistory)) {
							$originalGuest->status_id = 6;

							if ($guests->save($originalGuest)) {
								//creazione notifica trasferimento ospite
								$azienda = TableRegistry::get('Aziende.Aziende')->get($sede->id_azienda);
								if ($azienda->id_tipo == 1) {
									$saveType = 'TRANSFERRED_GUEST';
								} else if ($azienda->id_tipo == 2) {
									$saveType = 'TRANSFERRED_GUEST_UKRAINE';
								}
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
			} else {
				$error =  "Errore: la data di check-in deve essere maggiore o uguale alla data di check-out dalla struttura precedente.";
			}
		}

		return $error;
	}

	public function readmissionGuest($guest, $data, $today)
	{
		$res = [
			'id' => null,
			'error' => ''
		];

		// Se stato diverso da "Uscito" non eseguo nessuna operazione sull'ospite
		if ($guest->status_id == 3) {
			$guests = TableRegistry::get('Aziende.Guests');

			// Verifico che questo ospite non sia già stato riammesso
			$alreadyReadmissed = $guests->checkIfExistsFutureGuest($guest);

			if (!$alreadyReadmissed) {
				// Inserimento nuovo ospite per struttura di destinazione
				$dataGuest = clone $guest;
				$dataClonedGuest = $dataGuest->toArray();
				$dataClonedGuest['sede_id'] = $data['sede'];
				$dataClonedGuest['status_id'] = 1;
				$dataClonedGuest['original_guest_id'] = empty($guest->original_guest_id) ? $guest->id : $guest->original_guest_id;
				$dataClonedGuest['check_in_date'] = $today->format('Y-m-d');
				unset($dataClonedGuest['id']);
				unset($dataClonedGuest['created']);
				unset($dataClonedGuest['modified']);
				unset($dataClonedGuest['check_out_date']);

				$clonedGuest = $guests->newEntity($dataClonedGuest);

				if ($guests->save($clonedGuest)) {
					//clonazione storico
					$guestsHistory = TableRegistry::get('Aziende.GuestsHistories');
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
					$historyClonedGuestData['note'] = $data['note'];
			
					$guestsHistory->patchEntity($historyClonedGuest, $historyClonedGuestData);
			
					$guestsHistory->save($historyClonedGuest);

					$res['id'] = $clonedGuest->id;
				} else {
					$res['error'] = "Errore nella clonazione dell'ospite nella struttura di destinazione.";
				}
			} else {
				$res['error'] = "L'ospite risulta già riammesso. Non è possibile avviare nuovamente la procedura di riammissione.";
			}
		} else {
			$res['error'] = "L'ospite risulta in stato diverso da 'Uscito'. Non è possibile avviare la procedura di riammissione.";
		}

		return $res;
	}

	public function getDataForReportGuestsEmergenzaUcraina($date = "")
	{
		if (empty($date)) {
            $date = date('Y-m-d');
        }

		$data[0] = ['REGIONE', 'PROVINCIA'];
		$data[1] = ['PIEMONTE', 'TORINO'];
		$data[2] = [''];
		$data[3] = ['', 'N. CITTADINI UCRAINI IN ACCOGLIENZA (CAS, CPA)', 'N. CITTADINI UCRAINI IN STRUTTURE EXTRA'];

		$guestsTable = TableRegistry::get('Aziende.Guests');

		// Privo di titolo di studio
        $privo = $guestsTable->countTitoliStudioEmergenzaUcraina($date, 2);
		$data[4] = [
			'N. CITTADINI UCRAINI PRIVI DI TITOLO DI STUDIO', 
			'0', 
			empty($privo) ? '0' : $privo
		];

		// Titolo di studio scuola primaria
        $primaria = $guestsTable->countTitoliStudioEmergenzaUcraina($date, 3);
		$data[5] = [
			'N. CITTADINI UCRAINI CON TITOLO DI STUDIO DI SCUOLA PRIMARIA', 
			'0', 
			empty($primaria) ? '0' : $primaria
		];

		// Titolo di studio scuola secondaria primo grado
        $secondariaIGrado = $guestsTable->countTitoliStudioEmergenzaUcraina($date, 4);
		$data[6] = [
			'N. CITTADINI UCRAINI CON TITOLO DI STUDIO DI SCUOLA SECONDARIA DI 1° GRADO', 
			'0', 
			empty($secondariaIGrado) ? '0' : $secondariaIGrado
		];

		// Titolo di studio scuola secondaria secondo grado
        $secondariaIIGrado = $guestsTable->countTitoliStudioEmergenzaUcraina($date, 5);
		$data[7] = [
			'N. CITTADINI UCRAINI CON TITOLO DI STUDIO DI SCUOLA SECONDARIA DI 2° GRADO (liceale, tecnica o professionale)', 
			'0', 
			empty($secondariaIIGrado) ? '0' : $secondariaIIGrado
		];

		// Titolo di studio universitario
        $universitario = $guestsTable->countTitoliStudioEmergenzaUcraina($date, 6);
		$data[8] = [
			'N. CITTADINI UCRAINI CON TITOLO DI STUDIO UNIVERSITARIO', 
			'0', 
			empty($universitario) ? '0' : $universitario
		];

		// Titolo di studio area sanitaria
        $sanitaria = $guestsTable->countTitoliStudioEmergenzaUcraina($date, 7);
		$data[9] = [
			'Di cui LAUREA AREA SANITARIA (professioni sanitarie, infermieristiche, ostetricia, riabilitative, medicina e chirurgia, medicina veterinaria...)', 
			'0', 
			empty($sanitaria) ? '0' : $sanitaria
		];

		// Titolo di studio area scientifica
        $scientifica = $guestsTable->countTitoliStudioEmergenzaUcraina($date, 13);
		$data[10] = [
			'Di cui LAUREA AREA SCIENTIFICA (scienze biologiche, chimiche, fisiche, matematiche, geologiche, zootecniche...)', 
			'0', 
			empty($scientifica) ? '0' : $scientifica
		];

		// Titolo di studio area scientifico-tecnologica
        $scientificoTecnologica = $guestsTable->countTitoliStudioEmergenzaUcraina($date, 8);
		$data[11] = [
			'Di cui LAUREA AREA SCIENTIFICO-TECNOLOGICA (architettura, ingegneria civile ed ambientale, industriale, meccanica, elettronica, delle telecomunicazioni, informatiche...)', 
			'0', 
			empty($scientificoTecnologica) ? '0' : $scientificoTecnologica
		];

		// Titolo di studio area giuridico-economica
        $giuridicoEconomica = $guestsTable->countTitoliStudioEmergenzaUcraina($date, 9);
		$data[12] = [
			'Di cui LAUREA AREA GIURIDICO-ECONOMICA (scienze dei servizi giuridici, scienze dell\'economia e della gestione azindale, relazioni internazionali, scienze politiche, cooperazione e sviluppo...)', 
			'0', 
			empty($giuridicoEconomica) ? '0' : $giuridicoEconomica
		];

		// Titolo di studio area umanistica
        $umanistica = $guestsTable->countTitoliStudioEmergenzaUcraina($date, 10);
		$data[13] = [
			'Di cui LAUREA AREA UMANISTICA (lettere, lingue, mediazione, linguistica, filologia, interpretariato...)', 
			'0', 
			empty($umanistica) ? '0' : $umanistica
		];

		// Titolo di studio area sociale
        $sociale = $guestsTable->countTitoliStudioEmergenzaUcraina($date, 11);
		$data[14] = [
			'Di cui LAUREA AREA SOCIALE (psicologia, antropologia, filosofia, storia dell\'arte, scienze pedagogiche e della formazione primaria...)', 
			'0', 
			empty($sociale) ? '0' : $sociale
		];

		// Titolo di studio altra area
        $altra = $guestsTable->countTitoliStudioEmergenzaUcraina($date, 12);
		$data[15] = [
			'ALTRA AREA DI RIFERIMENTO', 
			'0', 
			empty($altra) ? '0' : $altra
		];

		// Titolo di studio non disponibile
        $nonDisponibile = $guestsTable->countTitoliStudioEmergenzaUcraina($date, 1);
		$data[16] = [
			'N. CITTADINI UCRAINI PER I QUALI NON è DISPONIBILE L\'INFORMAZIONE RELATIVA AL TITOLO DI STUDIO', 
			'0', 
			(empty($nonDisponibile) ? '0' : $nonDisponibile)
		];

		return $data;
	}

}
