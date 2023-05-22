<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Surveys Placeholders   (https://www.companee.it)
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
 * SurveysPlaceholders Controller
 *
 * @property \Surveys\Model\Table\SurveysPlaceholdersTable $SurveysPlaceholders
 *
 * @method \Surveys\Model\Entity\SurveysPlaceholder[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class SurveysPlaceholdersController extends AppController
{

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index()
    {
        $surveysPlaceholders = $this->paginate($this->SurveysPlaceholders);

        $this->set(compact('surveysPlaceholders'));
        $this->set('_serialize', ['surveysPlaceholders']);
    }


    /**
     * View method
     *
     * @param string|null $id Surveys Placeholder id.
     * @return \Cake\Network\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $surveysPlaceholder = $this->SurveysPlaceholders->get($id, [
            'contain' => []
        ]);

        $this->set('surveysPlaceholder', $surveysPlaceholder);
        $this->set('_serialize', ['surveysPlaceholder']);
    }


    /**
     * Add method
     *
     * @return \Cake\Network\Response|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $surveysPlaceholder = $this->SurveysPlaceholders->newEntity();
        if ($this->request->is('post')) {
            $surveysPlaceholder = $this->SurveysPlaceholders->patchEntity($surveysPlaceholder, $this->request->data);
            if ($this->SurveysPlaceholders->save($surveysPlaceholder)) {
                $this->Flash->success(__('The surveys placeholder has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The surveys placeholder could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('surveysPlaceholder'));
        $this->set('_serialize', ['surveysPlaceholder']);
    }


    /**
     * Edit method
     *
     * @param string|null $id Surveys Placeholder id.
     * @return \Cake\Network\Response|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Network\Exception\NotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $surveysPlaceholder = $this->SurveysPlaceholders->get($id, [
            'contain' => []
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $surveysPlaceholder = $this->SurveysPlaceholders->patchEntity($surveysPlaceholder, $this->request->data);
            if ($this->SurveysPlaceholders->save($surveysPlaceholder)) {
                $this->Flash->success(__('The surveys placeholder has been saved.'));

                return $this->redirect(['action' => 'index']);
            } else {
                $this->Flash->error(__('The surveys placeholder could not be saved. Please, try again.'));
            }
        }
        $this->set(compact('surveysPlaceholder'));
        $this->set('_serialize', ['surveysPlaceholder']);
    }


    /**
     * Delete method
     *
     * @param string|null $id Surveys Placeholder id.
     * @return \Cake\Network\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $surveysPlaceholder = $this->SurveysPlaceholders->get($id);
        if ($this->SurveysPlaceholders->delete($surveysPlaceholder)) {
            $this->Flash->success(__('The surveys placeholder has been deleted.'));
        } else {
            $this->Flash->error(__('The surveys placeholder could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }
}
