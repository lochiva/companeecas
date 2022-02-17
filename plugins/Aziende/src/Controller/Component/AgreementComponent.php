<?php
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class AgreementComponent extends Component
{
    public function getAgreements($aziendaId, $pass = array()){

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

}
