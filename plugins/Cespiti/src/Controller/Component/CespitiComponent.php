<?php
/**
* Cespiti is a plugin for manage attachment
*
* Companee :    Cespiti  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
namespace Cespiti\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class CespitiComponent extends Component
{

    public function getCespiti($pass = array()){
        $cespiti = TableRegistry::get('Cespiti.Cespiti');
        $opt = array(
			'fields' => ['Cespiti.id', 'Cespiti.id_azienda', 'Cespiti.id_fattura_passiva', 'Cespiti.num', 'Cespiti.descrizione', 'Cespiti.stato', 'Cespiti.note', 'a.denominazione', 'i.num', 'i.emission_date'],
			'join' => [
				[
					'table' => 'aziende',
			        'alias' => 'a',
			        'type' => 'INNER',
			        'conditions' => 'a.id = Cespiti.id_azienda'
				],
				[
					'table' => 'invoices',
			        'alias' => 'i',
			        'type' => 'INNER',
			        'conditions' => 'i.id = Cespiti.id_fattura_passiva'
				]

			],
			'conditions' => ['Cespiti.cancellato' => 0],
			'order' => ['a.denominazione ASC']
		);
        $col = array();
        $col[0] = ['val' => 'a.denominazione', 'type' => 'text'];
        $col[1] = ['val' => 'i.num', 'type' => 'text'];
        $col[2] = ['val' => 'num', 'type' => 'text'];
		$col[3] = ['val' => 'descrizione', 'type' => 'text'];
		$col[4] = ['val' => 'stato', 'type' => 'number'];
        $col[5] = ['val' => 'note', 'type' => 'text'];

        return $cespiti->queryForTableSorter($col, $opt, $pass);

    }

    public function getTotCespiti($pass = array()){

		$cespiti = TableRegistry::get('Cespiti.Cespiti');
        $opt = array(
			'fields' => ['Cespiti.id', 'Cespiti.id_azienda', 'Cespiti.id_fattura_passiva', 'Cespiti.num', 'Cespiti.descrizione', 'Cespiti.stato', 'Cespiti.note', 'a.denominazione', 'i.num'],
			'join' => [
				[
					'table' => 'aziende',
			        'alias' => 'a',
			        'type' => 'INNER',
			        'conditions' => 'a.id = Cespiti.id_azienda'
				],
				[
					'table' => 'invoices',
			        'alias' => 'i',
			        'type' => 'INNER',
			        'conditions' => 'i.id = Cespiti.id_fattura_passiva'
				]
			],
			'conditions' => ['Cespiti.cancellato' => 0]
		);
        $col = array();
        $col[0] = ['val' => 'a.denominazione', 'type' => 'text'];
        $col[1] = ['val' => 'i.num', 'type' => 'text'];
        $col[2] = ['val' => 'num', 'type' => 'text'];
		$col[3] = ['val' => 'descrizione', 'type' => 'text'];
		$col[4] = ['val' => 'stato', 'type' => 'number'];
        $col[5] = ['val' => 'note', 'type' => 'text'];

        return $cespiti->queryForTableSorter($col, $opt, $pass, true);
    }


    public function _newEntity(){
        $cespiti = TableRegistry::get('Cespiti.Cespiti');
        return $cespiti->newEntity();
    }

    public function _patchEntity($doc,$request){
        $cespiti = TableRegistry::get('Cespiti.Cespiti');
        return $cespiti->patchEntity($doc,$request);
    }

    public function _save($doc){
        $cespiti = TableRegistry::get('Cespiti.Cespiti');
        return $cespiti->save($doc);
    }

    public function _get($id){
        $cespiti = TableRegistry::get('Cespiti.Cespiti');
        return $cespiti->get($id);

    }

    public function _delete($doc){
        $cespiti = TableRegistry::get('Cespiti.Cespiti');
        return $cespiti->delete($doc);
    }

}
