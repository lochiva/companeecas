<?php
namespace Aziende\Controller;

use Aziende\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\I18n\Date;
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

        $this->user = $this->request->session()->read('Auth.User');

        $form_template = Configure::read('localconfig.formTemplate');

        $this->set('title', 'Rendiconti');
        $this->set('form_template', $form_template);
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

    }

    public function isAuthorized($user)
    {
        if($user['role'] == 'admin' || $user['role'] == 'ente'){
            return true;
        }else{
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return true;
        }
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $periods = TableRegistry::get('Aziende.Periods')->find('list')->where(['visible' => true])->toArray();
        $this->set('periods', $periods);
    }


    /**
     * View method
     *
     * @param string|null $id Statement id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $statement = $this->Statements->get($id, [
            'contain' => ['Agreements' => ['AgreementsCompanies', 'Aziende', 'Procedures'], 'Periods', 'StatementCompany']
        ]);

        $azienda = TableRegistry::get('Aziende.Aziende')->getAziendaByUser($this->user['id']);

        if($this->user['role'] == 'admin' || $this->user['role'] == 'ente' && $azienda['id'] == $statement->agreement->azienda_id){

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
            } else if (count($statement->companies) > 0) {
                $categories = TableRegistry::get('Aziende.CostsCategories')->find('all')
                ->contain('Costs', function (Query $q) use ($statement) {
                    return $q->where(['Costs.statement_company' => $statement->companies[0]->id]);
                })
                ->toArray();
            } else {
                $categories = [];
            }
    
            $periods = TableRegistry::get('Aziende.Periods')->find('list')->where(['visible' => true])->toArray();
            $statement->period_start_date = $statement->period_start_date->format('Y-m-d');
            $statement->period_end_date = $statement->period_end_date->format('Y-m-d');

            $this->set(compact('statement', 'companies', 'periods', 'categories'));
            $this->set('_serialize', ['statement']);
        }else{
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
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
        $statement = $this->Statements->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $statement = $this->Statements->patchEntity($statement, $this->request->data);
            if ($this->Statements->save($statement)) {
                $this->Flash->success(__('The statement has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The statement could not be saved. Please, try again.'));
            }
        }
        $agreements = $this->Statements->Agreements->find('list', ['limit' => 200]);
        $periods = $this->Statements->Periods->find('list', ['limit' => 200]);
        $this->set(compact('statement', 'agreements', 'periods'));
        $this->set('_serialize', ['statement']);
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
        if ($this->Statements->delete($statement)) {
            $this->Flash->success(__('The statement has been deleted.'));
        } else {
            $this->Flash->error(__('The statement could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
