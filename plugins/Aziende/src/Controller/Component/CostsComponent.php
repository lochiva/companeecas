<?php
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
        if($all == 'all') {
            $companies =  TableRegistry::get('Aziende.StatementCompany')->find('list')
            ->select('company_id')
            ->where(['statement_id' => $id])
            ->toList();

            $ret = TableRegistry::get('Aziende.CostsCategories')->find('all')
                ->contain('Costs', function ($q) use ($companies){
                    return $q->where(['Costs.statement_company IN' => $companies, 'Costs.deleted' => 0]);
                })
                ->toArray();

        } else {
            $ret = TableRegistry::get('Aziende.CostsCategories')->find('all')
                ->contain('Costs', function ($q) use ($id){
                    return $q->where(['Costs.statement_company' => $id, 'Costs.deleted' => 0]);
                })
                ->toArray();
        }

        $grandTotal = 0;

        if (count($ret)) {
            foreach($ret as $cat) {
                $tot = 0;
                foreach($cat['costs'] as $cost) {
                    $tot += $cost['amount'];
                    $grandTotal += $tot;
                    $cost['amount'] = number_format($cost['amount'], 2, '.', '');
                    $cost['share'] = number_format($cost['share'], 2, '.', '');
                    $cost['date'] = $cost['date']->format('d/m/Y');;
                }

                $cat['tot'] = number_format($tot, 2, '.', '');
            }
        }
        array_unshift($ret, ['id' => 'grandTotal', 'name' => 'Totale generale', 'tot' => number_format($grandTotal, 2, '.', '')]);
        return $ret;
    }
}