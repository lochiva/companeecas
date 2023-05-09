<?php
/**
* Component is a plugin for manage attachment
*
* Companee :    Costs (https://www.companee.it)
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
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\I18n\Date;
use Cake\Collection\Collection;

/**
 * StatementCompany component
 */
class CostsComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function getCosts($all, $id) {
        $return = false;
        if($all == 'all') {
            $companies =  TableRegistry::get('Aziende.StatementCompany')->find('list')
            ->select('company_id')
            ->where(['statement_id' => $id])
            ->toList();

            $ret = TableRegistry::get('Aziende.CostsCategories')->find('all')
                ->contain('Costs', function ($q) use ($companies){
                    return $q->where(['Costs.statement_company IN' => $companies, 'Costs.deleted' => 0]);
                })
                ->order(['CostsCategories.ordering'])
                ->toArray();

        } else {
            $ret = TableRegistry::get('Aziende.CostsCategories')->find('all')
                ->contain('Costs', function ($q) use ($id){
                    return $q->where(['Costs.statement_company' => $id, 'Costs.deleted' => 0]);
                })
                ->order(['CostsCategories.ordering'])
                ->toArray();

            $cs = TableRegistry::get('Aziende.StatementCompany')->get($id, ['contain' => 'Statements']);

            $statement['status_id'] = $cs->status_id;
            $statement['start'] = $cs->statement->period_start_date;
            $statement['end'] = $cs->statement->period_end_date;
        }

        $grandTotal = 0;

        if (count($ret)) {
            foreach($ret as $cat) {
                $tot = 0;
                foreach($cat['costs'] as $cost) {
                    $tot += $cost['share'];
                    $cost['amount'] = number_format($cost['amount'], 2, '.', '');
                    $cost['share'] = number_format($cost['share'], 2, '.', '');
                    $cost['real_date'] = $cost['date'];
                    $cost['date'] = isset($cost['date']) ? $cost['date']->format('d/m/Y') : '' ;
                }
                $grandTotal += $tot;
                $cat['tot'] = number_format($tot, 2, '.', '');
            }
            array_unshift($ret, ['id' => 'grandTotal', 'name' => 'Totale generale', 'tot' => number_format($grandTotal, 2, '.', '')]);
            $return['costs'] = $ret;
            if (isset($statement)) {
                $return['statement'] = $statement;
            }
        }
        
        return $return;
    }
}