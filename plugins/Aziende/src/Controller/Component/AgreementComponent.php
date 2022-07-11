<?php
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class AgreementComponent extends Component
{
    public function getAgreements($aziendaId, $pass = array()){

        $agreements = TableRegistry::get('Aziende.Agreements');
		
		$columns = [
			0 => ['val' => 'spa.name', 'type' => 'text'],
			1 => ['val' => 'Agreements.date_agreement', 'type' => 'date'],
			2 => ['val' => 'Agreements.date_agreement_expiration', 'type' => 'date'],
			3 => ['val' => 'Agreements.date_extension_expiration', 'type' => 'date'],
			4 => ['val' => 'Agreements.guest_daily_price', 'type' => 'number'],
			5 => ['val' => 'Agreements.capacity_increment', 'type' => 'number']
        ];
        
        $opt['fields'] = [
			'Agreements.id',
			'Agreements.date_agreement',
			'Agreements.date_agreement_expiration',
			'Agreements.date_extension_expiration',
			'Agreements.guest_daily_price',
			'Agreements.capacity_increment',
			'spa.name',
			'Aziende.denominazione'
		];

		$opt['join'] = [
			[
				'table' => 'sedi_procedure_affidamento',
				'alias' => 'spa',
				'type' => 'LEFT',
				'conditions' => 'Agreements.procedure_id = spa.id'
			],
			[
				'table' => 'aziende',
				'alias' => 'Aziende',
				'type' => 'LEFT',
				'conditions' => 'Agreements.azienda_id = Aziende.id'
			]
		];
        
		$opt['conditions'] = ['Agreements.azienda_id' => $aziendaId];

        $toRet['res'] = $agreements->queryForTableSorter($columns, $opt, $pass); 
        $toRet['tot'] = $agreements->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;

    }

}
