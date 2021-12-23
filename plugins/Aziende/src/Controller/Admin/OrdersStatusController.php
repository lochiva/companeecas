<?php
namespace Aziende\Controller\Admin;

use Aziende\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

/**
 * OrdersStatus Controller
 *
 * @property \Aziende\Model\Table\OrdersStatusTable $OrdersStatus
 */
class OrdersStatusController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Aziende.Contatti');
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $ordersStatus = $this->paginate($this->OrdersStatus);

        $this->set(compact('ordersStatus'));
        $this->set('_serialize', ['ordersStatus']);
    }

    /**
     * View method
     *
     * @param string|null $id Orders Status id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $ordersStatus = $this->OrdersStatus->get($id, [
            'contain' => []
        ]);

        $this->set('ordersStatus', $ordersStatus);
        $this->set('_serialize', ['ordersStatus']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $ordersStatus = $this->OrdersStatus->newEntity();
        if ($this->request->is('post')) {
            $ordersStatus = $this->OrdersStatus->patchEntity($ordersStatus, $this->request->data);
            if ($this->OrdersStatus->save($ordersStatus)) {
                $this->Flash->success(__('The orders status has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The orders status could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('ordersStatus'));
        $this->set('_serialize', ['ordersStatus']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Orders Status id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $ordersStatus = $this->OrdersStatus->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $ordersStatus = $this->OrdersStatus->patchEntity($ordersStatus, $this->request->data);
            if ($this->OrdersStatus->save($ordersStatus)) {
                $this->Flash->success(__('The orders status has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The orders status could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('ordersStatus'));
        $this->set('_serialize', ['ordersStatus']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Orders Status id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $ordersStatus = $this->OrdersStatus->get($id);
        if(TableRegistry::get('Aziende.Orders')->find()->where(['id_status' => $id])->count() > 0){
            $this->Flash->error(__('The orders status could not be deleted. Please, try again.'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->OrdersStatus->delete($ordersStatus)) {
            $this->Flash->success(__('The orders status has been deleted.'));
        } else {
            $this->Flash->error(__('The orders status could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
