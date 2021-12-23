<?php
namespace Progest\Controller\Admin;

use Progest\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

/**
 * PersonTypes Controller
 *
 * @property \Progest\Model\Table\PersonTypesTable $PersonTypes
 */
class PersonTypesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $personTypes = $this->paginate($this->PersonTypes);

        $this->set(compact('personTypes'));
        $this->set('_serialize', ['personTypes']);
    }

    /**
     * View method
     *
     * @param string|null $id Person Type id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $personType = $this->PersonTypes->get($id, [
            'contain' => []
        ]);

        $this->set('personType', $personType);
        $this->set('_serialize', ['personType']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $personType = $this->PersonTypes->newEntity();
        if ($this->request->is('post')) {
            $personType = $this->PersonTypes->patchEntity($personType, $this->request->data);
            if ($this->PersonTypes->save($personType)) {
                $this->Flash->success(__('The person type has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The person type could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('personType'));
        $this->set('_serialize', ['personType']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Person Type id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $personType = $this->PersonTypes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $personType = $this->PersonTypes->patchEntity($personType, $this->request->data);
            if ($this->PersonTypes->save($personType)) {
                $this->Flash->success(__('The person type has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The person type could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('personType'));
        $this->set('_serialize', ['personType']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Person Type id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $personType = $this->PersonTypes->get($id);
        if(TableRegistry::get('Progest.Orders')->find()->where(['id_person_type' => $id])->count() > 0){
            $this->Flash->error(__('The person type could not be deleted. Please, try again.'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->PersonTypes->delete($personType)) {
            $this->Flash->success(__('The person type has been deleted.'));
        } else {
            $this->Flash->error(__('The person type could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
