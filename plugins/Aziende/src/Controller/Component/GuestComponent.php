<?php
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class GuestComponent extends Component
{
    public function getGuests($sedeId, $showOld = false, $pass = array()){

        $guests = TableRegistry::get('Aziende.Guests');
		
		$columns = [
			0 => ['val' => 'Guests.check_in_date', 'type' => 'date'],
			1 => ['val' => 'Guests.cui', 'type' => 'text'],
			2 => ['val' => 'Guests.vestanet_id', 'type' => 'text'],
			3 => ['val' => 'Guests.name', 'type' => 'text'],
			4 => ['val' => 'Guests.surname', 'type' => 'text'],
			5 => ['val' => 'Guests.birthdate', 'type' => 'date'],
			6 => ['val' => 'Guests.sex', 'type' => 'text'],
			7 => ['val' => 'Guests.draft', 'type' => 'number'],
			8 => ['val' => 'Guests.draft_expiration', 'type' => 'date'],
			9 => ['val' => 'Guests.suspended', 'type' => 'number'],
			10 => ['val' => 'gs.name', 'type' => 'text']
        ];
        
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

}
