<?php
namespace Aziende\Controller\Admin;

use Aziende\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

/**
 * CostsCategories Controller
 *
 * @property \Aziende\Model\Table\CostsCategoriesTable $CostsCategories
 *
 * @method \Aziende\Model\Entity\CostsCategory[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class CostsCategoriesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $costsCategories = $this->paginate($this->CostsCategories);

        $this->set(compact('costsCategories'));
        $this->set('_serialize', ['costsCategories']);
    }


    /**
     * View method
     *
     * @param string|null $id Costs Category id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $costsCategory = $this->CostsCategories->get($id, [
            'contain' => ['Costs']
        ]);

        $this->set('costsCategory', $costsCategory);
        $this->set('_serialize', ['costsCategory']);
    }


    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $costsCategory = $this->CostsCategories->newEntity();
        if ($this->request->is('post')) {
            $costsCategory = $this->CostsCategories->patchEntity($costsCategory, $this->request->data);
            if ($this->CostsCategories->save($costsCategory)) {
                $this->Flash->success(__('The costs category has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The costs category could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('costsCategory'));
        $this->set('_serialize', ['costsCategory']);
    }


    /**
     * Edit method
     *
     * @param string|null $id Costs Category id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $costsCategory = $this->CostsCategories->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $costsCategory = $this->CostsCategories->patchEntity($costsCategory, $this->request->data);
            if ($this->CostsCategories->save($costsCategory)) {
                $this->Flash->success(__('The costs category has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The costs category could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('costsCategory'));
        $this->set('_serialize', ['costsCategory']);
    }


    /**
     * Delete method
     *
     * @param string|null $id Costs Category id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $costsCategory = $this->CostsCategories->get($id);
        if ($this->CostsCategories->delete($costsCategory)) {
            $this->Flash->success(__('The costs category has been deleted.'));
        } else {
            $this->Flash->error(__('The costs category could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
