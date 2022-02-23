<?php
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class GuestComponent extends Component
{
    public function getGuests($sedeId, $pass = array()){

        $guests = TableRegistry::get('Aziende.Guests');
		
		$columns = [
			0 => ['val' => 'Guests.cui', 'type' => 'text'],
			1 => ['val' => 'Guests.vestanet_id', 'type' => 'text'],
			2 => ['val' => 'Guests.name', 'type' => 'text'],
			3 => ['val' => 'Guests.surname', 'type' => 'text'],
			4 => ['val' => 'Guests.birthdate', 'type' => 'date'],
			5 => ['val' => 'Guests.sex', 'type' => 'text'],
			6 => ['val' => 'Guests.draft', 'type' => 'number'],
			7 => ['val' => 'Guests.draft_expiration', 'type' => 'date'],
			8 => ['val' => 'Guests.suspended', 'type' => 'number']
        ];
        
        $opt['fields'] = [
			'Guests.id', 
			'Guests.cui', 
			'Guests.vestanet_id', 
			'Guests.name',
			'Guests.surname', 
			'Guests.birthdate', 
			'Guests.sex', 
			'Guests.draft',
			'Guests.draft_expiration', 
			'Guests.suspended'
		];

		$opt['join'] = [];
        
		$opt['conditions'] = ['Guests.sede_id' => $sedeId];

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
			//2 => ['val' => 'CONCAT(g.name, " ", g.surname)', 'type' => 'text'],
			2 => ['val' => 'CONCAT(u.nome, " ", u.cognome)', 'type' => 'text'],
			3 => ['val' => 't.msg_singular', 'type' => 'text'],
			4 => ['val' => 'GuestsNotifications.done', 'type' => ''],
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
