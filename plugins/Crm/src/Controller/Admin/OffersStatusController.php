<?php
/**
* Crm is a plugin for manage attachment
*
* Companee :    Offers Status  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
namespace Crm\Controller\Admin;

use Crm\Controller\AppController;

/**
 * OffersStatus Controller
 *
 * @property \Crm\Model\Table\OffersStatusTable $OffersStatus
 */
class OffersStatusController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Aziende.Contatti');
    }

    public function isAuthorized($user)
    {
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }

        // Default deny
        return false;
    }
    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        //debug(\Cake\I18n\I18n::locale());
        $offersStatus = $this->paginate($this->OffersStatus);

        $this->set(compact('offersStatus'));
        $this->set('_serialize', ['offersStatus']);
    }

    /**
     * View method
     *
     * @param string|null $id Offers Status id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $offersStatus = $this->OffersStatus->get($id, [
            'contain' => []
        ]);

        $this->set('offersStatus', $offersStatus);
        $this->set('_serialize', ['offersStatus']);
    }

    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $offersStatus = $this->OffersStatus->newEntity();
        if ($this->request->is('post')) {
            $offersStatus = $this->OffersStatus->patchEntity($offersStatus, $this->request->data);
            if ($this->OffersStatus->save($offersStatus)) {
                $this->Flash->success(__('The offers status has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The offers status could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('offersStatus'));
        $this->set('_serialize', ['offersStatus']);
    }

    /**
     * Edit method
     *
     * @param string|null $id Offers Status id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $offersStatus = $this->OffersStatus->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $offersStatus = $this->OffersStatus->patchEntity($offersStatus, $this->request->data);
            if ($this->OffersStatus->save($offersStatus)) {
                $this->Flash->success(__('The offers status has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The offers status could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('offersStatus'));
        $this->set('_serialize', ['offersStatus']);
    }

    /**
     * Delete method
     *
     * @param string|null $id Offers Status id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $offersStatus = $this->OffersStatus->get($id);
        if ($this->OffersStatus->delete($offersStatus)) {
            $this->Flash->success(__('The offers status has been deleted.'));
        } else {
            $this->Flash->error(__('The offers status could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
