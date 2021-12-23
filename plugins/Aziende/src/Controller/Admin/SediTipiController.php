<?php
namespace Aziende\Controller\Admin;

use Aziende\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

/**
 * SediTipi Controller
 *
 * @property \Aziende\Model\Table\SediTipiTable $SediTipi
 */
class SediTipiController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $sediTipi = $this->paginate($this->SediTipi);

        $this->set(compact('sediTipi'));
        $this->set('_serialize', ['sediTipi']);
    }

    /**
     * View method
     *
     * @param string|null $id Sedi Tipi id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $sediTipi = $this->SediTipi->get($id, [
            'contain' => []
        ]);

        $this->set('sediTipi', $sediTipi);
        $this->set('_serialize', ['sediTipi']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $sediTipi = $this->SediTipi->newEntity();
        if ($this->request->is('post')) {
            $sediTipi = $this->SediTipi->patchEntity($sediTipi, $this->request->data);
            if ($this->SediTipi->save($sediTipi)) {
                $this->Flash->success(__('The sedi tipi has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The sedi tipi could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('sediTipi'));
        $this->set('_serialize', ['sediTipi']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Sedi Tipi id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $sediTipi = $this->SediTipi->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $sediTipi = $this->SediTipi->patchEntity($sediTipi, $this->request->data);
            if ($this->SediTipi->save($sediTipi)) {
                $this->Flash->success(__('The sedi tipi has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The sedi tipi could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('sediTipi'));
        $this->set('_serialize', ['sediTipi']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Sedi Tipi id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $sediTipi = $this->SediTipi->get($id);
        if(TableRegistry::get('Aziende.Sedi')->find()->where(['id_tipo' => $id])->count() > 0){
            $this->Flash->error(__('The sedi tipi could not be deleted. Please, try again.'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->SediTipi->delete($sediTipi)) {
            $this->Flash->success(__('The sedi tipi has been deleted.'));
        } else {
            $this->Flash->error(__('The sedi tipi could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
