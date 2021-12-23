<?php
namespace Reports\Controller\Admin;

use Reports\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

/**
 * Genders Controller
 *
 * @property \Reports\Model\Table\GendersTable $Genders
 */
class GendersController extends AppController
{
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $genders = $this->paginate($this->Genders);

        $this->set(compact('genders'));
        $this->set('_serialize', ['genders']);
    }

    /**
     * View method
     *
     * @param string|null $id Genders id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $gender = $this->Genders->get($id, [
            'contain' => []
        ]);

        $this->set('gender', $gender);
        $this->set('_serialize', ['gender']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $gender = $this->Genders->newEntity();
        if ($this->request->is('post')) {
            $gender = $this->Genders->patchEntity($gender, $this->request->data);
            if ($this->Genders->save($gender)) {
                $this->Flash->success(__('Il sesso è stato salvato.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Il sesso non può essere salvato. Si prega di riprovare.'));
            }
        }
        $this->set(compact('gender'));
        $this->set('_serialize', ['gender']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Genders id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $gender = $this->Genders->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $gender = $this->Genders->patchEntity($gender, $this->request->data);
            if ($this->Genders->save($gender)) {
                $this->Flash->success(__('Il sesso è stato salvato.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Il sesso non può essere salvato. Si prega di riprovare.'));
            }
        }
        $this->set(compact('gender'));
        $this->set('_serialize', ['gender']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Genders id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $gender = $this->Genders->get($id);
        $victims = TableRegistry::get('Reports.Victims')->find()->where(['gender_id' => $id])->count();
        $witnesses = TableRegistry::get('Reports.Witnesses')->find()->where(['gender_id' => $id])->count();
        if($victims > 0 || $witnesses > 0){
            $this->Flash->error(__('Il sesso non può essere cancellato perchè gia in uso da una o più anagrafiche.'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->Genders->delete($gender)) {
            $this->Flash->success(__('Il sesso è stato cancellato.'));
        } else {
            $this->Flash->error(__('Il sesso non può essere cancellato. Si prega di riprovare.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
