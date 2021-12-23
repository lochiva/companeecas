<?php
namespace Progest\Controller\Admin;

use Progest\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

/**
 * InvoiceTypes Controller
 *
 * @property \Progest\Model\Table\InvoiceTypesTable $InvoiceTypes
 */
class InvoiceTypesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $invoiceTypes = $this->paginate($this->InvoiceTypes);

        $this->set(compact('invoiceTypes'));
        $this->set('_serialize', ['invoiceTypes']);
    }

    /**
     * View method
     *
     * @param string|null $id Invoice Type id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $invoiceType = $this->InvoiceTypes->get($id, [
            'contain' => []
        ]);

        $this->set('invoiceType', $invoiceType);
        $this->set('_serialize', ['invoiceType']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $invoiceType = $this->InvoiceTypes->newEntity();
        if ($this->request->is('post')) {
            $invoiceType = $this->InvoiceTypes->patchEntity($invoiceType, $this->request->data);
            if ($this->InvoiceTypes->save($invoiceType)) {
                $this->Flash->success(__('The invoice type has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The invoice type could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('invoiceType'));
        $this->set('_serialize', ['invoiceType']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Invoice Type id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $invoiceType = $this->InvoiceTypes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $invoiceType = $this->InvoiceTypes->patchEntity($invoiceType, $this->request->data);
            if ($this->InvoiceTypes->save($invoiceType)) {
                $this->Flash->success(__('The invoice type has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The invoice type could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('invoiceType'));
        $this->set('_serialize', ['invoiceType']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Invoice Type id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $invoiceType = $this->InvoiceTypes->get($id);
        if(TableRegistry::get('Progest.Orders')->find()->where(['id_invoice_type' => $id])->count() > 0){
            $this->Flash->error(__('The invoice type could not be deleted. Please, try again.'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->InvoiceTypes->delete($invoiceType)) {
            $this->Flash->success(__('The invoice type has been deleted.'));
        } else {
            $this->Flash->error(__('The invoice type could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
