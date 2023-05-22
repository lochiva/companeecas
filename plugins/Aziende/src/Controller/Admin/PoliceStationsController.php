<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Police Stations  (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
namespace Aziende\Controller\Admin;

use Aziende\Controller\Admin\AppController;

/**
 * PoliceStations Controller
 *
 * @property \Aziende\Model\Table\PoliceStationsTable $PoliceStations
 *
 * @method \Aziende\Model\Entity\PoliceStation[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PoliceStationsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['PoliceStationTypes']
        ];
        $policeStations = $this->paginate($this->PoliceStations);

        $this->set(compact('policeStations'));
        $this->set('_serialize', ['policeStations']);
    }


    /**
     * View method
     *
     * @param string|null $id Police Station id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $policeStation = $this->PoliceStations->get($id, [
            'contain' => ['PoliceStationTypes']
        ]);

        $this->set('policeStation', $policeStation);
        $this->set('_serialize', ['policeStation']);
    }


    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $policeStation = $this->PoliceStations->newEntity();
        if ($this->request->is('post')) {
            $policeStation = $this->PoliceStations->patchEntity($policeStation, $this->request->data);
            if ($this->PoliceStations->save($policeStation)) {
                $this->Flash->success(__('La stazione di polizia è stata salvata correttamente.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The police station could not be saved. Si prega di riprovare.'));
            }
        }
        $policeStationTypes = $this->PoliceStations->PoliceStationTypes->find('list', ['limit' => 200]);
        $this->set(compact('policeStation', 'policeStationTypes'));
        $this->set('_serialize', ['policeStation']);
    }


    /**
     * Edit method
     *
     * @param string|null $id Police Station id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $policeStation = $this->PoliceStations->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $policeStation = $this->PoliceStations->patchEntity($policeStation, $this->request->data);
            if ($this->PoliceStations->save($policeStation)) {
                $this->Flash->success(__('La stazione di polizia è stata salvata correttamente.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('Impossibile salvare la stazione di polizia, si prega di riprovare.'));
            }
        }
        $policeStationTypes = $this->PoliceStations->PoliceStationTypes->find('list', ['limit' => 200]);
        $this->set(compact('policeStation', 'policeStationTypes'));
        $this->set('_serialize', ['policeStation']);
    }


    /**
     * Delete method
     *
     * @param string|null $id Police Station id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $policeStation = $this->PoliceStations->get($id);
        if ($this->PoliceStations->delete($policeStation)) {
            $this->Flash->success(__('La stazione di polizia è stata eliminata.'));
        } else {
            $this->Flash->error(__('Impossibile eliminare la stazione di polizia, si prega di riprovare.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
