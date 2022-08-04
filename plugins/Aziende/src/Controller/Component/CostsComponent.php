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

            $ret = TableRegistry::get('Aziende.Costs')->find('all')
            ->where(['Costs.statement_company IN' => $companies, 'Costs.deleted' => 0])
            ->contain(['CostsCategories']);

        } else {
            $ret = TableRegistry::get('Aziende.Costs')->find('all')
                ->where(['Costs.statement_company' => $id, 'Costs.deleted' => 0])
                ->contain(['CostsCategories']); 
        }

        $c = new Collection($ret);
        $ret = $c->groupBy('category_id');
        $ret = $ret->toArray();

        if (count($ret)) {
            foreach($ret as $key => &$cat) {
                $tot = 0;
                foreach($cat as $cost) {
                    $tot += $cost['amount'];
                    $cost['date'] = $cost['date']->format('d/m/Y');;
                }
                $toRet[$key]['costs'] = $cat;
                $toRet[$key]['name'] = $cat[0]['category']['name'];
                $toRet[$key]['id'] = $cat[0]['category']['id'];
                $toRet[$key]['tot'] = number_format($tot, 2, ',', '');
            }
        } else {
            $toRet = $ret;
        }

        return $toRet;
    }
}