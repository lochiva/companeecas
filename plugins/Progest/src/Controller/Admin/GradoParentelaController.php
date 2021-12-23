<?php
namespace Progest\Controller\Admin;

use Progest\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

/**
 * GradoParentela Controller
 *
 * @property \Progest\Model\Table\GradoParentelaTable $GradoParentela
 */
class GradoParentelaController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $gradoParentela = $this->paginate($this->GradoParentela);

        $this->set(compact('gradoParentela'));
        $this->set('_serialize', ['gradoParentela']);
    }

    /**
     * View method
     *
     * @param string|null $id Grado Parentela id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $gradoParentela = $this->GradoParentela->get($id, [
            'contain' => []
        ]);

        $this->set('gradoParentela', $gradoParentela);
        $this->set('_serialize', ['gradoParentela']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $gradoParentela = $this->GradoParentela->newEntity();
        if ($this->request->is('post')) {
            $gradoParentela = $this->GradoParentela->patchEntity($gradoParentela, $this->request->data);
            if ($this->GradoParentela->save($gradoParentela)) {
                $this->Flash->success(__('The grado parentela has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The grado parentela could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('gradoParentela'));
        $this->set('_serialize', ['gradoParentela']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Grado Parentela id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $gradoParentela = $this->GradoParentela->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $gradoParentela = $this->GradoParentela->patchEntity($gradoParentela, $this->request->data);
            if ($this->GradoParentela->save($gradoParentela)) {
                $this->Flash->success(__('The grado parentela has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The grado parentela could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('gradoParentela'));
        $this->set('_serialize', ['gradoParentela']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Grado Parentela id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $gradoParentela = $this->GradoParentela->get($id);
        if(TableRegistry::get('Progest.Familiari')->find()->where(['id_grado_parentela' => $id])->count() > 0){
            $this->Flash->error(__('The Grado Parentela could not be deleted. Please, try again.'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->GradoParentela->delete($gradoParentela)) {
            $this->Flash->success(__('The grado parentela has been deleted.'));
        } else {
            $this->Flash->error(__('The grado parentela could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
