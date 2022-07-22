<?php
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

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

    public function getStatements($pass = []) {
        $table = TableRegistry::get('Aziende.StatementCompany');
        $columns = [
            0 => ['val' => 'AgreementsCompanies.name', 'type' => 'text'],
            1 => ['val' => 'Agreements.cig', 'type' => 'text'],
            2 => ['val' => 'Statements.period_id', 'type' => 'number'],
            3 => ['val' => 'Statements.year', 'type' => 'number'],
            4 => ['val' => 'Status.id' , 'type' => 'number'],
            5 => ['val' => 'StatementCompany.approved_date' , 'type' => 'date']
        ];

        $opt['contain'] = ['Status', 'Statements' => ['Periods'], 'AgreementsCompanies' => ['Agreements']];
        $opt['fields'] = ['StatementCompany.id', 'StatementCompany.approved_date', 'AgreementsCompanies.name', 'Agreements.cig', 'Statements.period_label', 'Statements.year', 'Status.name', 'Status.id', 'Statements.period_id', 'Statements.id', 'StatementCompany.uploaded_path'];

        $toRet['res'] = $table->queryForTableSorter($columns,$opt,$pass);
        $toRet['tot'] = $table->queryForTableSorter($columns,$opt,$pass,true);

        return $toRet;
    }
}


