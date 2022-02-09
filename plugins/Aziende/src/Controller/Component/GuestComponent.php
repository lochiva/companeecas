<?php
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class GuestComponent extends Component
{


    public function getGuests($sedeId, $pass = array()){

        $guests = TableRegistry::get('Aziende.Guests');
		
		$columns = [
			0 => ['val' => 'SediGuests.code', 'type' => 'text'],
			1 => ['val' => 'SediGuests.name', 'type' => 'text'],
			2 => ['val' => 'SediGuests.surname', 'type' => 'text']
        ];
        
        $opt['fields'] = [
			'SediGuests.id', 
			'SediGuests.code', 
			'SediGuests.name', 
			'SediGuests.surname'
		];

		$opt['join'] = [];
        
		$opt['conditions'] = ['SediGuests.sede_id' => $sedeId];
		
		if(isset($pass['query']['filter'][5])){
			$today = date('Y-m-d');
			if($pass['query']['filter'][5] == 'Scaduto'){
				$opt['conditions']['SediGuests.status'] = 1;
				$opt['conditions']['SediGuests.due_date <'] = $today;
			}elseif($pass['query']['filter'][5] == 'In scadenza'){
				$opt['conditions']['SediGuests.status'] = 1;
				$opt['conditions']['SediGuests.notice_date <='] = $today;
				$opt['conditions']['SediGuests.due_date >='] = $today;
			}elseif($pass['query']['filter'][5] == 'In struttura'){
				$opt['conditions']['SediGuests.status'] = 1;
				$opt['conditions']['SediGuests.notice_date >'] = $today;
			}else{
				$opt['conditions']['gs.name'] = $pass['query']['filter'][0];
			}
		}

        $toRet['res'] = $guests->queryForTableSorter($columns, $opt, $pass); 
        $toRet['tot'] = $guests->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;

    }

}
