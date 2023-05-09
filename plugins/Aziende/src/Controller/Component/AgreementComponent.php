<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Agreement (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/

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

	public function checkRendiconti($id)
	{
        $table = TableRegistry::get('Aziende.AgreementsCompanies');
        $rendiconti = $table->find('all')->where(['agreement_id' => $id, 'isDefault' => true])->toArray();


        if(empty($rendiconti)) {
            $agreement = TableRegistry::get('Aziende.Agreements')->get($id, ['contain' => ['Aziende']]);

            $new = $table->newEntity();
            $new->agreement_id = $id;
            $new->name = $agreement->aziende->denominazione;
            $new->isDefault = 1;

            if ($table->save($new)) {
				return $new;
            } else {
                return false;
            }
                        
        } else {
            return $rendiconti;
        }
	
    }

}
