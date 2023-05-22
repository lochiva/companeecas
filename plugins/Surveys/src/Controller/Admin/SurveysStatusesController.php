<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Surveys Statuses   (https://www.companee.it)
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
namespace Surveys\Controller\Admin;

use Surveys\Controller\Admin\AppController;

/**
 * SurveysStatuses Controller
 *
 * @property \Surveys\Model\Table\SurveysStatusesTable $SurveysStatuses
 *
 * @method \Surveys\Model\Entity\SurveysStatus[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SurveysStatusesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $surveysStatuses = $this->paginate($this->SurveysStatuses);

        $this->set(compact('surveysStatuses'));
        $this->set('_serialize', ['surveysStatuses']);
    }


    /**
     * View method
     *
     * @param string|null $id Surveys Status id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $surveysStatus = $this->SurveysStatuses->get($id, [
            'contain' => []
        ]);

        $this->set('surveysStatus', $surveysStatus);
        $this->set('_serialize', ['surveysStatus']);
    }


    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $surveysStatus = $this->SurveysStatuses->newEntity();
        if ($this->request->is('post')) {
            $surveysStatus = $this->SurveysStatuses->patchEntity($surveysStatus, $this->request->data);
            if ($this->SurveysStatuses->save($surveysStatus)) {
                $this->Flash->success(__('The surveys status has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The surveys status could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('surveysStatus'));
        $this->set('_serialize', ['surveysStatus']);
    }


    /**
     * Edit method
     *
     * @param string|null $id Surveys Status id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $surveysStatus = $this->SurveysStatuses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $surveysStatus = $this->SurveysStatuses->patchEntity($surveysStatus, $this->request->data);
            if ($this->SurveysStatuses->save($surveysStatus)) {
                $this->Flash->success(__('The surveys status has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The surveys status could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('surveysStatus'));
        $this->set('_serialize', ['surveysStatus']);
    }


    /**
     * Delete method
     *
     * @param string|null $id Surveys Status id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $surveysStatus = $this->SurveysStatuses->get($id);
        if ($this->SurveysStatuses->delete($surveysStatus)) {
            $this->Flash->success(__('The surveys status has been deleted.'));
        } else {
            $this->Flash->error(__('The surveys status could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
