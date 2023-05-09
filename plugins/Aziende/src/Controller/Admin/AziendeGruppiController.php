<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Aziende Gruppi  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
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
use Cake\ORM\TableRegistry;


/**
 * AziendeGruppi Controller
 *
 * @property \Aziende\Model\Table\AziendeGruppiTable $AziendeGruppi
 */
class AziendeGruppiController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $aziendeGruppi = $this->paginate($this->AziendeGruppi);

        $this->set(compact('aziendeGruppi'));
        $this->set('_serialize', ['aziendeGruppi']);
    }

    /**
     * View method
     *
     * @param string|null $id Aziende Gruppi id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $aziendeGruppi = $this->AziendeGruppi->get($id, [
            'contain' => []
        ]);

        $this->set('aziendeGruppi', $aziendeGruppi);
        $this->set('_serialize', ['aziendeGruppi']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $aziendeGruppi = $this->AziendeGruppi->newEntity();
        if ($this->request->is('post')) {
            $aziendeGruppi = $this->AziendeGruppi->patchEntity($aziendeGruppi, $this->request->data);
            if ($this->AziendeGruppi->save($aziendeGruppi)) {
                $this->Flash->success(__('The aziende gruppi has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The aziende gruppi could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('aziendeGruppi'));
        $this->set('_serialize', ['aziendeGruppi']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Aziende Gruppi id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $aziendeGruppi = $this->AziendeGruppi->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $aziendeGruppi = $this->AziendeGruppi->patchEntity($aziendeGruppi, $this->request->data);
            if ($this->AziendeGruppi->save($aziendeGruppi)) {
                $this->Flash->success(__('The aziende gruppi has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The aziende gruppi could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('aziendeGruppi'));
        $this->set('_serialize', ['aziendeGruppi']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Aziende Gruppi id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $aziendeGruppi = $this->AziendeGruppi->get($id);
        if(TableRegistry::get('Aziende.AziendeToGruppi')->find()->where(['id_gruppo' => $id])->count() > 0){
            $this->Flash->error(__('The aziende gruppi could not be deleted. Please, try again.'));
            return $this->redirect(['action' => 'index']);
        }
        if ($this->AziendeGruppi->delete($aziendeGruppi)) {
            $this->Flash->success(__('The aziende gruppi has been deleted.'));
        } else {
            $this->Flash->error(__('The aziende gruppi could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
