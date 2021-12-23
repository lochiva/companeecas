<?php
namespace Progest\Controller\Admin;

use Progest\Controller\Admin\AppController;
use Cake\ORM\TableRegistry;

/**
 * Activities Controller
 *
 * @property \Progest\Model\Table\ActivitiesTable $Activities
 */
class ActivitiesController extends AppController
{

	public function add($id_service)
	{
		$activities = TableRegistry::get('Progest.Activities');
		$activity = $activities->newEntity();
		$activity->id_service = $id_service;
		$activity->name = $this->request->getData('name');
		$activity->order_value = $this->request->getData('order_value');

		if($this->request->getData('note') == 'si'){
			$activity->hasNote = '1';
		}else{
			$activity->hasNote = '0';
		}

		$activities->save($activity);

		$this->redirect('/admin/progest/services/edit/' . $id_service);
	}

	public function edit($id_service)
    {
		$activities = TableRegistry::get('Progest.Activities');
		$id = $this->request->getData('id_activity');
		$activity = $activities->get($id);

		$activity->name = $this->request->getData('name');
		$activity->order_value = $this->request->getData('order_value');

		if($this->request->getData('note') == 'si'){
			$activity->hasNote = '1';
		}else{
			$activity->hasNote = '0';
		}

		$activities->save($activity);

		$this->redirect('/admin/progest/services/edit/' . $id_service);
    }

	public function delete($id = null, $id_service)
    {
        $this->request->allowMethod(['post', 'delete']);
        $activity = $this->Activities->get($id);

        if ($this->Activities->delete($activity)) {
            $this->Flash->success(__('The activity has been deleted.'));
        } else {
            $this->Flash->error(__('The activity could not be deleted. Please, try again.'));
        }

        return $this->redirect(['controller' => 'Services', 'action' => 'edit', $id_service]);
    }

}
