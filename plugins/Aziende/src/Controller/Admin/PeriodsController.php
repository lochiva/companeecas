<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Periods  (https://www.companee.it)
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
use Cake\ORM\TableRegistry;

/**
 * Periods Controller
 *
 * @property \Aziende\Model\Table\PeriodsTable $Periods
 *
 * @method \Aziende\Model\Entity\Period[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PeriodsController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $periods = $this->paginate($this->Periods);

        $this->set(compact('periods'));
        $this->set('_serialize', ['periods']);
    }


    /**
     * View method
     *
     * @param string|null $id Period id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $period = $this->Periods->get($id, [
            'contain' => ['Statements']
        ]);

        $this->set('period', $period);
        $this->set('_serialize', ['period']);
    }


    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $period = $this->Periods->newEntity();
        if ($this->request->is('post')) {
            $period = $this->Periods->patchEntity($period, $this->request->data);
            if ($this->Periods->save($period)) {
                $this->Flash->success(__('The period has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The period could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('period'));
        $this->set('_serialize', ['period']);
    }


    /**
     * Edit method
     *
     * @param string|null $id Period id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $period = $this->Periods->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $period = $this->Periods->patchEntity($period, $this->request->data);
            if ($this->Periods->save($period)) {
                $this->Flash->success(__('The period has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The period could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('period'));
        $this->set('_serialize', ['period']);
    }


    /**
     * Delete method
     *
     * @param string|null $id Period id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $period = $this->Periods->get($id);
        if ($this->Periods->delete($period)) {
            $this->Flash->success(__('The period has been deleted.'));
        } else {
            $this->Flash->error(__('The period could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
