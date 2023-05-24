<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Surveys Interviews Statuses  (https://www.companee.it)
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
 * SurveysInterviewsStatuses Controller
 *
 * @property \Surveys\Model\Table\SurveysInterviewsStatusesTable $SurveysInterviewsStatuses
 *
 * @method \Surveys\Model\Entity\SurveysInterviewsStatus[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SurveysInterviewsStatusesController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $surveysInterviewsStatuses = $this->paginate($this->SurveysInterviewsStatuses);

        $this->set(compact('surveysInterviewsStatuses'));
        $this->set('_serialize', ['surveysInterviewsStatuses']);
    }


    /**
     * View method
     *
     * @param string|null $id Surveys Interviews Status id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $surveysInterviewsStatus = $this->SurveysInterviewsStatuses->get($id, [
            'contain' => []
        ]);

        $this->set('surveysInterviewsStatus', $surveysInterviewsStatus);
        $this->set('_serialize', ['surveysInterviewsStatus']);
    }


    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $surveysInterviewsStatus = $this->SurveysInterviewsStatuses->newEntity();
        if ($this->request->is('post')) {
            $surveysInterviewsStatus = $this->SurveysInterviewsStatuses->patchEntity($surveysInterviewsStatus, $this->request->data);
            if ($this->SurveysInterviewsStatuses->save($surveysInterviewsStatus)) {
                $this->Flash->success(__('The surveys interviews status has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The surveys interviews status could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('surveysInterviewsStatus'));
        $this->set('_serialize', ['surveysInterviewsStatus']);
    }


    /**
     * Edit method
     *
     * @param string|null $id Surveys Interviews Status id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $surveysInterviewsStatus = $this->SurveysInterviewsStatuses->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $surveysInterviewsStatus = $this->SurveysInterviewsStatuses->patchEntity($surveysInterviewsStatus, $this->request->data);
            if ($this->SurveysInterviewsStatuses->save($surveysInterviewsStatus)) {
                $this->Flash->success(__('The surveys interviews status has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The surveys interviews status could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('surveysInterviewsStatus'));
        $this->set('_serialize', ['surveysInterviewsStatus']);
    }


    /**
     * Delete method
     *
     * @param string|null $id Surveys Interviews Status id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $surveysInterviewsStatus = $this->SurveysInterviewsStatuses->get($id);
        if ($this->SurveysInterviewsStatuses->delete($surveysInterviewsStatus)) {
            $this->Flash->success(__('The surveys interviews status has been deleted.'));
        } else {
            $this->Flash->error(__('The surveys interviews status could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
