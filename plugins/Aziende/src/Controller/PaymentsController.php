<?php

namespace Aziende\Controller;

use Aziende\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\I18n\Date;



/**
 * Payments Controller
 *
 * @property \Aziende\Model\Table\PaymentsTable $Payments
 *
 * @method \Aziende\Model\Entity\Payment[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PaymentsController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        $this->ajax = false;
        $this->ajaxActions = ['getPaymentsbyStatementCompany', 'add', 'view', 'edit', 'delete'];

        $this->user = $this->request->session()->read('Auth.User');
        $this->set('user', $this->user);
    }

    public function isAuthorized($user)
    {
        if (
            $user['role'] == 'admin' ||
            $user['role'] == 'ragioneria'
        ) {
            return true;
        }

        $authorizedActions = [
            'ente_contabile' => [
                'index', 'view', 'getPaymentsbyStatementCompany'
            ],
            'area_iv' => [
                'index', 'view', 'getPaymentsbyStatementCompany'
            ],
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

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $action = $event->getSubject()->request->getParam('action');

        if (in_array($action, $this->ajaxActions)) {
            $this->ajax = true;
            $this->viewBuilder()->layout('ajax');
            $this->viewBuilder()->templatePath('Async');
            $this->viewBuilder()->template('default');
            $this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");
        }
    }

    public function beforeRender(Event $event)
    {
        parent::beforeFilter($event);

        if ($this->ajax) {
            $this->set('result', json_encode($this->_result));
        }
    }


    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['StatementCompanies', 'Users']
        ];
        $payments = $this->paginate($this->Payments);

        $this->set(compact('payments'));
        $this->set('_serialize', ['payments']);
    }


    /**
     * View method
     *
     * @param string|null $id Payment id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        try {
            $this->request->allowMethod(['get']);
            $payment = $this->Payments->get($id);
            $this->_result['data'] = compact('payment');
            $this->_result['response'] = 'OK';
        } catch (\Exception $e) {
            $this->_result['response'] = 'KO';
            $this->_result['msg'] = "Non è stato possibile recuperare i dettagli relativi al pagamento. " . $e->getMessage();
        }
    }


    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        try {
            $this->request->allowMethod(['post']);
            $payment = $this->Payments->newEntity();
            $data = $this->request->data;
            $data['user_id'] = $this->user['id'];
            $payment = $this->Payments->patchEntity($payment, $data);
            if ($this->Payments->save($payment)) {
                $this->_result['data'] = compact('payment');
                $this->_result['response'] = 'OK';
                $this->_result['msg'] = 'Pagamento salvato correttamente';
            } else {
                $this->_result['response'] = 'KO';
                $this->_result['msg'] = 'Non è stato possibile salvare il pagamento. ' . json_encode($payment->getErrors());
            }
        } catch (\Exception $e) {
            $this->_result['response'] = 'KO';
            $this->_result['msg'] = "Non è stato possibile salvare il pagamento. " . $e->getMessage();
        }
    }


    /**
     * Edit method
     *
     * @param string|null $id Payment id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        try {
            $this->request->allowMethod(['patch', 'post', 'put']);
            $data = $this->request->input('json_decode', 'as_array');
            $payment = $this->Payments->get($id);
            $payment = $this->Payments->patchEntity($payment, $data);

            if ($this->Payments->save($payment)) {
                $this->_result['data'] = compact('payment');
                $this->_result['response'] = 'OK';
                $this->_result['msg'] = 'Pagamento salvato correttamente';
            } else {
                $this->_result['response'] = 'KO';
                $this->_result['msg'] = 'Non è stato possibile salvare il pagamento. ' . json_encode($payment->getErrors());
            }

        } catch (\Exception $e) {
            $this->_result['response'] = 'KO';
            $this->_result['msg'] = "Non è stato possibile salvare il pagamento. " . $e->getMessage();
        }
    }


    /**
     * Delete method
     *
     * @param string|null $id Payment id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        try {
            $this->request->allowMethod(['post', 'delete']);
            $payment = $this->Payments->get($id);
            $payment->deleted = new Date();
            if ($this->Payments->save($payment)) {
                $this->_result['response'] = 'OK';
                $this->_result['msg'] = 'Pagamento eliminato.';
            } else {
                $this->_result['response'] = 'KO';
                $this->_result['msg'] = 'Non è stato possibile cancellare il pagamento';
            }
        } catch (\Exception $e) {
            $this->_result['response'] = 'KO';
            $this->_result['msg'] = "Non è stato possibile salvare il pagamento. " . $e->getMessage();
        }
    }


    public function getPaymentsbyStatementCompany($statement_company_id)
    {
        try {
            $this->request->allowMethod(['get']);
            $payments = $this->Payments->find()->where(['statement_company_id' => $statement_company_id])->contain(['Documents'])->orderAsc('created');
            $this->_result['data'] = compact('payments');
            $this->_result['response'] = 'OK';
            $this->_result['msg'] = 'Pagamenti recuperrati correttamente';
        } catch (\Exception $e) {
            $this->_result['response'] = 'KO';
            $this->_result['msg'] = $e->getMessage();
        }
    }
}
