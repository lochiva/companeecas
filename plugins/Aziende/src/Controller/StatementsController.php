<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Statements (https://www.companee.it)
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
namespace Aziende\Controller;

use Aziende\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\I18n\Time;
use Cake\I18n\Date;
use Cake\ORM\Query;
use Cake\Database\Expression\QueryExpression;
use Cake\Filesystem\Folder;
use Cake\Filesystem\File;
use Cake\Collection\Collection;
use RuntimeException;
/**
 * Statements Controller
 *
 * @property \Aziende\Model\Table\StatementsTable $Statements
 *
 * @method \Aziende\Model\Entity\Statement[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class StatementsController extends AppController
{
    public function initialize()
    {
        parent::initialize();

        $this->loadComponent('Aziende.Azienda');
        $this->loadComponent('Aziende.StatementCompany');

        $this->user = $this->request->session()->read('Auth.User');

        $form_template = Configure::read('localconfig.formTemplate');

        $this->set('title', 'Rendiconti');
        $this->set('form_template', $form_template);
        $this->set('user', $this->user);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

    }

    public function isAuthorized($user)
    {
        if(
            $user['role'] == 'admin' || 
            $user['role'] == 'ente_contabile'
        ){
            return true;
        }

        $authorizedActions = [
            'area_iv' => [
                'index', 'view'
            ],
            'ragioneria' => [
                'index', 'view', 'updateStatusStatementCompany'
            ]
        ];

        if (
            !empty($user['role']) && 
            !empty($authorizedActions[$user['role']]) && 
            in_array($this->request->getParam('action'), $authorizedActions[$user['role']])
        ) {
            return true;
        }

        // Default deny
        return false;
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $periods = TableRegistry::get('Aziende.Periods')->find('list')->where(['visible' => true])->order(['ordering'])->toArray();
        $this->set('periods', $periods);
    }


    /**
     * View method
     *
     * @param string|null $id Statement id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id, $company = null)
    {
        if (isset($id)) {

            $statement = $this->Statements->get($id, [
                'contain' => [
                    'Agreements' => ['AgreementsCompanies', 'Aziende', 'Procedures'], 
                    'Periods', 
                    'StatementCompany' => [
                        'Status', 
                        'History' => ['Users', 'Status']
                    ]
                ]
            ]);

            if ($statement->deleted) {
                $this->Flash->error('Il rendiconto è stato eliminato.');
                $this->redirect(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'index']);
            }
    
            $azienda = TableRegistry::get('Aziende.Aziende')->getAziendaByUser($this->user['id']);
    
            if(
                $this->user['role'] == 'admin' ||
                $this->user['role'] == 'area_iv' ||
                $this->user['role'] == 'ragioneria' ||
                $this->user['role'] == 'ente_contabile' && $azienda['id'] == $statement->agreement->azienda_id
            ){

                $presenzeQuery = TableRegistry::get('Aziende.Presenze')->countPresenze($statement);
                $presenze = $presenzeQuery['presenze'];
                $minors = $presenzeQuery['minori'];

                $families = $presenzeQuery['families'];

                $pocketMoney = [
                    'heads' => 0,
                    'total' => 0,
                    'factor' => 2.5
                ];

                if($families) {
                    foreach ($families as $day) {
                        foreach ($day as $family_id => $count) {
                            if(empty($family_id)) {
                                $pocketMoney['total'] += $count * $pocketMoney['factor'];
                                $pocketMoney['heads'] += $count;
                            } else {
                                if($count <= 3) {
                                    $pocketMoney['total'] += $count * $pocketMoney['factor'];
                                    $pocketMoney['heads'] += $count;
                                } else {
                                    $pocketMoney['total'] += 3 * $pocketMoney['factor'];
                                    $pocketMoney['heads'] += 3;
                                }
                            }
                        }
                    }
                }

                $companies = TableRegistry::get('Aziende.StatementCompany')->find('list', [
                    'keyField' => 'id',
                    'valueField' => 'company.name'
                ])
                ->contain('AgreementsCompanies')
                ->where(['StatementCompany.statement_id' => $statement->id])
                ->toArray();
    
                if(count($companies) > 1) {
                    $companies['all'] = 'Tutti';
                    $categories = [];
                    $ati = 1;
                } else if (count($statement->companies) > 0) {
                    if (isset($statement->companies[0]->billing_date)) {
                        $statement->companies[0]->billing_date = $statement->companies[0]->billing_date->format('Y-m-d');
                    }
                    $statement->companies[0]->billing_net_amount = number_format($statement->companies[0]->billing_net_amount, 2, '.', '');
                    $statement->companies[0]->billing_vat_amount = number_format($statement->companies[0]->billing_vat_amount, 2, '.', '');

                    $ati = 0;
                    if (!isset($company)) {
                        $company = $statement->companies[0]->id;
                    }
                } else {
                    $ati = 0;
                }

                $periods = TableRegistry::get('Aziende.Periods')->find('list')->where(['visible' => true])->toArray();
                $statement->period_start_date = $statement->period_start_date->format('Y-m-d');
                $statement->period_end_date = $statement->period_end_date->format('Y-m-d');

                $this->set(compact('statement', 'companies', 'periods', 'company', 'ati', 'presenze', 'minors', 'pocketMoney'));
                $this->set('_serialize', ['statement']);
            }else{
                $this->Flash->error('Accesso negato. Non sei autorizzato.');
                $this->redirect(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'index']);
            }
        } else {
            $this->redirect(['plugin' => 'Aziende', 'controller' => 'Statements', 'action' => 'index']);
        }

    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function new()
    {   
        if($this->user['role'] == 'admin') {
            $agreements = TableRegistry::get('Aziende.Agreements')->find('list', [
                'keyField' => 'Agreements.id',
                'valueField' => 'procedure.name'
            ])
            ->where(['Agreements.deleted' => false])
            ->contain(['Procedures'])
            ->toArray();

        } else {
            $azienda = TableRegistry::get('Aziende.Aziende')->getAziendaByUser($this->user['id']);
        
            $agreements = TableRegistry::get('Aziende.Agreements')->find('list', [
                'keyField' => 'Agreements.id',
                'valueField' => 'procedure.name'
            ])
            ->where(['Agreements.azienda_id' => $azienda['id'], 'Agreements.deleted' => false])
            ->contain(['Procedures'])
            ->toArray();
        }

        
        $periods = TableRegistry::get('Aziende.Periods')->find('list')->where(['visible' => true])->toArray();

        $statement = $this->Statements->newEntity(['associated' => 'StatementCompany']);


        if ($this->request->is('post')) {
            $statement = $this->Statements->patchEntity($statement, $this->request->data);
            if ($this->Statements->save($statement)) {
                $this->Flash->success(__('The statement has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The statement could not be saved. Please, try again.'));
            }
        }

        $this->set(compact('statement', 'agreements', 'periods'));
        $this->set('_serialize', ['statement']);
    }


    /**
     * Edit method
     *
     * @param string|null $id Statement id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        if ($this->request->is(['patch', 'post', 'put'])) {

            $data = $this->request->data;
            $attachment = $this->request->getUploadedFile('file');

            $attachment_compliance = $this->request->getUploadedFile('file_compliance');

            $statement = $this->Statements->get($id, [
                'contain' => ['StatementCompany']
            ]);

            // Controllo file dichiarazione
            $uploadPath = ROOT.DS.Configure::read('dbconfig.aziende.STATEMENTS_UPLOAD_PATH');

            if(strlen($attachment_compliance->getClientFilename())) {

                $filePath = $data['companies'][0]['id'] . DS . 'compliance';

                $dir = new Folder($uploadPath . $filePath, true, 0755);
                
                $fName = uniqid().'_'.$attachment_compliance->getClientFilename();

                try {
                    $attachment_compliance->moveTo($uploadPath . $filePath . DS . $fName);
                    $data['companies'][0]['compliance'] = $filePath . DS . $fName;
                    $data['companies'][0]['compliance_filename'] = 'DICH_' . $attachment_compliance->getClientFilename();

                } catch (RuntimeException $e) {
                    $this->Flash->error(__("Impossibile salvare il rendiconto. Si è verificato un errore durante l'upload del file"));
                    return $this->redirect(['action' => 'view', $id]);
                }

            } 

            // Controllo se è stato allegato un file
            if(strlen($attachment->getClientFilename())) {
                $uploadPath = ROOT.DS.Configure::read('dbconfig.aziende.STATEMENTS_UPLOAD_PATH');

                $filePath = $data['companies'][0]['id'];

               $dir = new Folder($uploadPath . $filePath, true, 0755);
                
                $fName = uniqid().'_'.$attachment->getClientFilename();

                try {
                    $attachment->moveTo($uploadPath . $filePath . DS . $fName);
                    $data['companies'][0]['uploaded_path'] = $filePath . DS . $fName;
                    $data['companies'][0]['filename'] =  'FATT_' . $attachment->getClientFilename();
                
                    $statement = $this->Statements->patchEntity($statement, $data);
                } catch (RuntimeException $e) {
                    $this->Flash->error(__("Impossibile salvare il rendiconto. Si è verificato un errore durante l'upload del file"));
                    return $this->redirect(['action' => 'view', $id]);

                }

            }
            $statement = $this->Statements->patchEntity($statement, $data);

            if ($this->Statements->save($statement, ['associated' => 'StatementCompany'])) {
                $this->Flash->success(__('Il rendiconto è stato aggiornato.'));

                return $this->redirect(['action' => 'view', $id, $data['companies'][0]['id']]);
            } else {
                $this->Flash->error(__('Si è verificato un errore durante il salvataggio del rendiconto.'));
                return $this->redirect(['action' => 'view', $id, $data['companies'][0]['id']]);
            }

        }
    }


    /**
     * Delete method
     *
     * @param string|null $id Statement id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $statement = $this->Statements->get($id);
        $statement->deleted = 1;
        if ($this->Statements->save($statement)) {
            $this->Flash->success(__('Il rendiconto è stato eliminato'));
        } else {
            $this->Flash->error(__('Non è stato possibile eliminare il rendiconto.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function updateStatusStatementCompany($id)
    {
        try {
            $this->request->allowMethod(['post']);

            $table = TableRegistry::get('Aziende.StatementCompany');

            if (isset($id)) {
                $userRole = $this->user['role'];

                $data = $this->request->data;

                $newStatus = $data['status'];

                $associated = [];

                $entity = $table->get($id);

                $oldStatus = $entity->status_id;

                if($this->StatementCompany->checkStatus($userRole, $newStatus, $oldStatus)) {
                    $entity->status_id = $newStatus;

                    // in approvazione
                    if ($newStatus == 4) {
                        // Se si richiede l'approvazione salva la notifica
                        $tableN = TableRegistry::get('Aziende.StatementsNotifications');
                        $entityN = $tableN->newEntity();
                        $entityN->statement_id = $entity->statement_id;
                        $entityN->user_maker_id = $this->user['id'];
                        $entity->notifications = [$entityN];
                        $associated = ['StatementsNotifications'];

                        $due_date = new Date();
                        $due_date->addMonth(1);
                        $entity->due_date = $due_date->format('Y-m-d');
                    } else {
                        // Approvato
                        if ($newStatus == 2) {
                            $entity->approved_date = date('Y-m-d');
                        }
                        if($newStatus !== 5) {
                            $entity->due_date = null;
                        }
                    }
                    //echo "<pre>"; print_r($entity);die();
    
                    $ret = $table->save($entity, ['checkRules' => true, 'associated' => $associated]);
                    
    
                    if ($ret) {
                        //Salvataggio stato nello storico
                        $this->StatementCompany->saveStatusHistory($id, $newStatus, isset($data['notes']) ? $data['notes'] : '');
                        $this->Flash->success(__('Il rendiconto è stato aggiornato.'));
                    } else {
                        $this->Flash->error(__('Si è verificato un errore durante il salvataggio del rendiconto.'));
                    }

                } else {
                    $this->Flash->error(__('Si è verificato un errore durante il salvataggio del rendiconto. Non è possibile impostare il rendiconto sullo stato selezionato.'));
                }
            }
            return $this->redirect(['action' => 'view', $entity->statement_id, $id]);
        } catch (\Exception $e) {
            $this->Flash->error(__($e->getmessage()));
            return $this->redirect(['action' => 'view', $entity->statement_id, $id]);
        }
    }
}
