<?php
namespace Aziende\Controller\Admin;

use Aziende\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

/**
 * GuestsExitTypes Controller
 *
 * @property \Aziende\Model\Table\GuestsExitTypesTable $GuestsExitTypes
 */
class GuestsExitTypesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $guestsExitTypes = $this->paginate($this->GuestsExitTypes, ['contain' => ['Tipi', 'Decreti', 'Notifiche']]);

        $this->set(compact('guestsExitTypes'));
        $this->set('_serialize', ['guestsExitTypes']);
    }

    /**
     * View method
     *
     * @param string|null $id
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $guestsExitType = $this->GuestsExitTypes->get($id, [
            'contain' => ['Tipi', 'Decreti', 'Notifiche']
        ]);

        $this->set('guestsExitType', $guestsExitType);
        $this->set('_serialize', ['guestsExitType']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $guestsExitType = $this->GuestsExitTypes->newEntity();
        $surveys = TableRegistry::get('Surveys.Surveys')->find('list')
        ->order(['title' => 'ASC'])
        ->toArray();
        $aziendeTipi = TableRegistry::get('Aziende.AziendeTipi')->getList();
        $tipi = [];
        foreach ($aziendeTipi as $t) {
            $tipi[$t->id] = $t->name;
        }
        if ($this->request->is('post')) {
            $guestsExitType = $this->GuestsExitTypes->patchEntity($guestsExitType, $this->request->data);
            if ($this->GuestsExitTypes->save($guestsExitType)) {
                $this->Flash->success(__('The contatti ruoli has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The contatti ruoli could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('guestsExitType'));
        $this->set('_serialize', ['guestsExitType']);
        $this->set('tipi', $tipi);
        $this->set('surveys', $surveys);
    }

    /**
     * Edit method
     *
     * @param string|null $id
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $guestsExitType = $this->GuestsExitTypes->get($id, [
            'contain' => []
        ]);
        $aziendeTipi = TableRegistry::get('Aziende.AziendeTipi')->getList();
        $surveys = TableRegistry::get('Surveys.Surveys')->find('list')
            ->order(['title' => 'ASC'])
            ->toArray();
        $tipi = [];
        foreach ($aziendeTipi as $t) {
            $tipi[$t->id] = $t->name;
        }
        if ($this->request->is(['patch', 'post', 'put'])) {
            $guestsExitType = $this->GuestsExitTypes->patchEntity($guestsExitType, $this->request->data);
            if ($this->GuestsExitTypes->save($guestsExitType)) {
                $this->Flash->success(__('The contatti ruoli has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The contatti ruoli could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('guestsExitType'));
        $this->set('_serialize', ['guestsExitType']);
        $this->set('tipi', $tipi);
        $this->set('surveys', $surveys);
    }

    /**
     * Delete method
     *
     * @param string|null $id
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $guestsExitType = $this->GuestsExitTypes->get($id);
        if(TableRegistry::get('Aziende.Contatti')->find()->where(['id_ruolo' => $id])->count() > 0){
            $this->Flash->error(__('The contatti ruoli could not be deleted. Please, try again.'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->GuestsExitTypes->delete($guestsExitType)) {
            $this->Flash->success(__('The contatti ruoli has been deleted.'));
        } else {
            $this->Flash->error(__('The contatti ruoli could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
