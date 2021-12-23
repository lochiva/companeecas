<?php
namespace Aziende\Controller\Admin;

use Aziende\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

/**
 * ContattiRuoli Controller
 *
 * @property \Aziende\Model\Table\ContattiRuoliTable $ContattiRuoli
 */
class ContattiRuoliController extends AppController
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
        $contattiRuoli = $this->paginate($this->ContattiRuoli);

        $this->set(compact('contattiRuoli'));
        $this->set('_serialize', ['contattiRuoli']);
    }

    /**
     * View method
     *
     * @param string|null $id Contatti Ruoli id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $contattiRuoli = $this->ContattiRuoli->get($id, [
            'contain' => []
        ]);

        $this->set('contattiRuoli', $contattiRuoli);
        $this->set('_serialize', ['contattiRuoli']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $contattiRuoli = $this->ContattiRuoli->newEntity();
        if ($this->request->is('post')) {
            $contattiRuoli = $this->ContattiRuoli->patchEntity($contattiRuoli, $this->request->data);
            if ($this->ContattiRuoli->save($contattiRuoli)) {
                $this->Flash->success(__('The contatti ruoli has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The contatti ruoli could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('contattiRuoli'));
        $this->set('_serialize', ['contattiRuoli']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Contatti Ruoli id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $contattiRuoli = $this->ContattiRuoli->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $contattiRuoli = $this->ContattiRuoli->patchEntity($contattiRuoli, $this->request->data);
            if ($this->ContattiRuoli->save($contattiRuoli)) {
                $this->Flash->success(__('The contatti ruoli has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The contatti ruoli could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('contattiRuoli'));
        $this->set('_serialize', ['contattiRuoli']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Contatti Ruoli id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $contattiRuoli = $this->ContattiRuoli->get($id);
        if(TableRegistry::get('Aziende.Contatti')->find()->where(['id_ruolo' => $id])->count() > 0){
            $this->Flash->error(__('The contatti ruoli could not be deleted. Please, try again.'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->ContattiRuoli->delete($contattiRuoli)) {
            $this->Flash->success(__('The contatti ruoli has been deleted.'));
        } else {
            $this->Flash->error(__('The contatti ruoli could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
