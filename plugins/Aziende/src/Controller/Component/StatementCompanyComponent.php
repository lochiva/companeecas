<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Statement Company (https://www.companee.it)
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
            4 => ['val' => 'history.created' , 'type' => 'date'],
        ];

        if($user['role'] == 'admin' || $user['role'] == 'ragioneria' || $user['role'] = 'ragioneria_adm') {
            $columns[5] = ['val' => 'StatementCompany.due_date' , 'type' => 'date'];
        }

        $opt['contain'] = ['Status', 'Statements' => ['Periods'], 'AgreementsCompanies' => ['Agreements']];
        $opt['fields'] = ['StatementCompany.id', 'StatementCompany.approved_date', 'AgreementsCompanies.name', 'Agreements.cig', 'Statements.period_label', 'Status.name', 'Status.id', 'Statements.period_id', 'Statements.id', 'StatementCompany.uploaded_path', 'history.created', 'StatementCompany.due_date'];
        $opt['conditions']['Statements.deleted'] = false;

        $opt['join'] = [
            [
                'table' => 'statements_status_history',
                'alias' => 'history',
                'type' => 'LEFT',
                'conditions' => ['StatementCompany.id = history.statement_company_id AND history.created =
                    (
                        SELECT MAX(h2.created)
                        FROM statements_status_history AS h2
                        WHERE h2.statement_company_id=StatementCompany.id
                    )
                ']
            ]
        ];

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
            if ($company->compliance) {
                $ret = true;
            }
            foreach ($company['costs'] as $cost) {
                if ($cost->attachment && !$cost->deleted) {
                    $ret = true;
                }

            }
        }

        return $ret;
    }

    public function saveStatusHistory($id, $status, $note)
    {
        $history = TableRegistry::get('Aziende.StatementsStatusHistory');

        $entity = $history->newEntity();

        $data = [
            'statement_company_id' => $id,
            'user_id' => $this->request->session()->read('Auth.User.id'),
            'status_id' => $status,
            'note' => $note
        ];

        $entity = $history->patchEntity($entity, $data);

        return $history->save($entity);
    }

    public function checkStatus($userRole, $newStatus, $oldStatus) {
        $updateAllowed = false;
        if($userRole === 'ente_contabile') {
            if(($oldStatus == 1 || $oldStatus == 3) && $newStatus == 4) {
                $updateAllowed = true;
            }
        } else if($userRole === 'ragioneria' || $userRole === 'ragioneria_adm') {
            if(($oldStatus == 4 || $oldStatus == 5) && in_array($newStatus, [2, 3, 4, 5])) {
                $updateAllowed = true;
            }
        } else if( $userRole === 'admin') {
            $updateAllowed = true;
        }
        return $updateAllowed;
    }
}


