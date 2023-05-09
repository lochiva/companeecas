<?php
/**
* Component is a plugin for manage attachment
*
* Companee :    Statements Notifications (https://www.companee.it)
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
namespace aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\Controller\ComponentRegistry;
use Cake\ORM\TableRegistry;

/**
 * StatementsNotifications component
 */
class StatementsNotificationsComponent extends Component
{
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public function getStatementsNotifications($pass = array()) {
        $statementsNotificationsTable = TableRegistry::get('Aziende.StatementsNotifications');

        $columns[0] = ['val' => 'AgreementsCompanies.name.name', 'type' => 'text'];
        $columns[1] = ['val' => 'Agreements.cig', 'type' => 'text'];
        $columns[2] = ['val' => 'Statements.period_label', 'type' => 'text'];
        $columns[3] = ['val' => 'Statements.period_start_date', 'type' => 'date'];
        $columns[4] = ['val' => 'Statements.period_end_date', 'type' => 'date'];
        $columns[6] = ['val' => 'StatementsNotifications.done', 'type' => 'bool'];

        $opt['fields'] = [
            'Agreements.cig',
            'AgreementsCompanies.name',
            'Statements.period_label',
            'Statements.period_start_date',
            'Statements.period_end_date',
            'Statements.id',
            'StatementCompany.id',
            'StatementsNotifications.id',
            'StatementsNotifications.done'
        ];

        $opt['join'] = [
            [
				'table' => 'statements',
				'alias' => 'Statements',
				'type' => 'INNER',
				'conditions' => 'Statements.id = StatementsNotifications.statement_id'
			],
            [
				'table' => 'statement_company',
				'alias' => 'StatementCompany',
				'type' => 'INNER',
				'conditions' => 'StatementCompany.id = StatementsNotifications.statement_company_id'
			],
            [
				'table' => 'agreements',
				'alias' => 'Agreements',
				'type' => 'LEFT',
				'conditions' => 'Agreements.id = Statements.agreement_id'
			],
            [
				'table' => 'agreements_companies',
				'alias' => 'AgreementsCompanies',
				'type' => 'LEFT',
				'conditions' => 'AgreementsCompanies.id = StatementCompany.company_id'
			],
        ];

        $opt['conditions'] = ['Statements.deleted' => false];

        $all = filter_var($pass['query']['all'], FILTER_VALIDATE_BOOLEAN);

		if (!$all) {
			$opt['conditions']['AND']['StatementsNotifications.done'] = 0;
		}

        $toRet['res'] = $statementsNotificationsTable->queryForTableSorter($columns, $opt, $pass); 
        $toRet['tot'] = $statementsNotificationsTable->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;

    }

    public function getStatementsNotificationsForBulkMarking($pass = array()) {
        $statementsNotificationsTable = TableRegistry::get('Aziende.StatementsNotifications');

        $columns[0] = ['val' => 'AgreementsCompanies.name.name', 'type' => 'text'];
        $columns[1] = ['val' => 'Agreements.cig', 'type' => 'text'];
        $columns[2] = ['val' => 'Statements.period_label', 'type' => 'text'];
        $columns[3] = ['val' => 'Statements.period_start_date', 'type' => 'date'];
        $columns[4] = ['val' => 'Statements.period_end_date', 'type' => 'date'];
        $columns[6] = ['val' => 'StatementsNotifications.done', 'type' => 'bool'];

        $opt['fields'] = [
            'Agreements.cig',
            'AgreementsCompanies.name',
            'Statements.period_label',
            'Statements.period_start_date',
            'Statements.period_end_date',
            'Statements.id',
            'StatementCompany.id',
            'StatementsNotifications.id',
            'StatementsNotifications.done'
        ];

        $opt['join'] = [
            [
				'table' => 'statements',
				'alias' => 'Statements',
				'type' => 'INNER',
				'conditions' => 'Statements.id = StatementsNotifications.statement_id'
			],
            [
				'table' => 'statement_company',
				'alias' => 'StatementCompany',
				'type' => 'INNER',
				'conditions' => 'StatementCompany.id = StatementsNotifications.statement_company_id'
			],
            [
				'table' => 'agreements',
				'alias' => 'Agreements',
				'type' => 'LEFT',
				'conditions' => 'Agreements.id = Statements.agreement_id'
			],
            [
				'table' => 'agreements_companies',
				'alias' => 'AgreementsCompanies',
				'type' => 'LEFT',
				'conditions' => 'AgreementsCompanies.id = StatementCompany.company_id'
			],
        ];

        $opt['conditions'] = ['Statements.deleted' => false];
        $opt['conditions']['StatementsNotifications.done'] = 0;

        $notifications = $statementsNotificationsTable->queryForTableSorter($columns, $opt, $pass); 

        return $notifications;
    }
}
