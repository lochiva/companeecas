<?php
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Collection\Collection;

/**
 * StatementCompany component
 */
class StatementCompanyComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public $components = ['Auth'];

    public function getStatements($pass = []) {

         $user = $this->Auth->user();

        if ($user['role'] == 'ente_contabile') {
            $azienda = TableRegistry::get('Aziende.Aziende')->getAziendaByUser($user['id']);
            $agreements = TableRegistry::get('Aziende.Agreements')->find('all')->select(['id'])->where(['azienda_id' => $azienda['id']])->toArray();

            $col = new Collection($agreements);
            $agreements = $col->extract('id')->toList();

            if(count($agreements)) {
                unset($col);
                
                $agreements_companies = TableRegistry::get('Aziende.AgreementsCompanies')->find('all')->select(['id'])->where(['agreement_id IN' => $agreements])->toArray();
                
                $col = new Collection($agreements_companies);
                
                $agreements_companies = $col->extract('id');
                
                $opt['conditions'] = ['StatementCompany.company_id IN' => $agreements_companies->toList()];

            } else {
                $toRet['res'] = [];
                $toRet['tot'] = 0;
        
                return $toRet;
            }

        }
 
        $table = TableRegistry::get('Aziende.StatementCompany');
        $columns = [
            0 => ['val' => 'AgreementsCompanies.name', 'type' => 'text'],
            1 => ['val' => 'Agreements.cig', 'type' => 'text'],
            2 => ['val' => 'Statements.period_label', 'type' => 'text'],
            3 => ['val' => 'Status.name' , 'type' => 'text'],
            4 => ['val' => 'StatementCompany.approved_date' , 'type' => 'date']
        ];

        $opt['contain'] = ['Status', 'Statements' => ['Periods'], 'AgreementsCompanies' => ['Agreements']];
        $opt['fields'] = ['StatementCompany.id', 'StatementCompany.approved_date', 'AgreementsCompanies.name', 'Agreements.cig', 'Statements.period_label', 'Status.name', 'Status.id', 'Statements.period_id', 'Statements.id', 'StatementCompany.uploaded_path'];
        $opt['conditions']['Statements.deleted'] = false;

        $toRet['res'] = $table->queryForTableSorter($columns,$opt,$pass);
        $toRet['tot'] = $table->queryForTableSorter($columns,$opt,$pass,true);

        return $toRet;
    }

    public function checkDownloads ($statement_id) {
        $ret = false;
        $downloads = TableRegistry::get('Aziende.StatementCompany')
            ->find('all')
            ->where(['StatementCompany.statement_id' => $statement_id])
            ->contain(['Costs'])
            ->toArray();

        foreach ($downloads as $company) {
            if ($company->uploaded_path) {
                $ret = true;
            }
            foreach ($company['costs'] as $cost) {
                if ($cost->attachment) {
                    $ret = true;
                }

            }
        }

        return $ret;
    }
}


