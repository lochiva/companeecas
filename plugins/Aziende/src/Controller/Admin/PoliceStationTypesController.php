<?php
namespace Aziende\Controller\Admin;

use Aziende\Controller\Admin\AppController;

/**
 * PoliceStationTypes Controller
 *
 * @property \Aziende\Model\Table\PoliceStationTypesTable $PoliceStationTypes
 *
 * @method \Aziende\Model\Entity\PoliceStationType[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PoliceStationTypesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $policeStationTypes = $this->paginate($this->PoliceStationTypes);

        $this->set(compact('policeStationTypes'));
        $this->set('_serialize', ['policeStationTypes']);
    }


    /**
     * View method
     *
     * @param string|null $id Police Station Type id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $policeStationType = $this->PoliceStationTypes->get($id, [
            'contain' => ['PoliceStations']
        ]);

        $this->set('policeStationType', $policeStationType);
        $this->set('_serialize', ['policeStationType']);
    }


    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $policeStationType = $this->PoliceStationTypes->newEntity();
        if ($this->request->is('post')) {
            $policeStationType = $this->PoliceStationTypes->patchEntity($policeStationType, $this->request->data);
            if ($this->PoliceStationTypes->save($policeStationType)) {
                $this->Flash->success(__('Il tipo è stato salvato correttamente.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The police station type could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('policeStationType'));
        $this->set('_serialize', ['policeStationType']);
    }


    /**
     * Edit method
     *
     * @param string|null $id Police Station Type id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $policeStationType = $this->PoliceStationTypes->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $policeStationType = $this->PoliceStationTypes->patchEntity($policeStationType, $this->request->data);
            if ($this->PoliceStationTypes->save($policeStationType)) {
                $this->Flash->success(__('Il tipo è stato salvato correttamente.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Impossibile salvare il tipo, si prega di riprovare.'));
            }
        }
        $this->set(compact('policeStationType'));
        $this->set('_serialize', ['policeStationType']);
    }


    /**
     * Delete method
     *
     * @param string|null $id Police Station Type id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $policeStationType = $this->PoliceStationTypes->get($id);
        if ($this->PoliceStationTypes->delete($policeStationType)) {
            $this->Flash->success(__('Il tipo è stato eliminato.'));
        } else {
            $this->Flash->error(__('Impossibile eliminare il tipo, si prega di riprovare.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
