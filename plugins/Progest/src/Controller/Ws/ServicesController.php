<?php
namespace Progest\Controller\Ws;

use Progest\Controller\Ws\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;
/**
 * Ws Offers Controller
 *
 */
class ServicesController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Progest.Service');
		$this->loadComponent('Calendar.Calendar');
    }

    public function contactsForService($idService = 0)
    {
        $contatti = $this->Service->contactsForService($idService,$this->request->query);

        $this->_result = array('response' => 'OK', 'data' => $contatti, 'msg' => '');
    }

    public function secondCategory($id_event)
    {
        $contatti = TableRegistry::get('Aziende.Contatti')->listInternal();
        $services = $this->Service->servicePerCategory(2);
		if($id_event != 'undefined' && $id_event != '' && $id_event != null){
			$details = $this->Calendar->getEventDetailForCalendarModal($id_event);
		}else{
			$details = [];
		}

		/*$activitiesTable = TableRegistry::get('Progest.Activities');
		$eventDetailsActivities = TableRegistry::get('Calendar.EventiDettaglioAttivita');

		$activities = $activitiesTable->find()->where(['id_service' => $evento->id_service])->toArray();
		$detailActivities = $eventDetailsActivities->find()->where(['id_event_detail' => $details['id']])->toArray();

		foreach($activities as $activity){
			$activity['checked_activity'] = false;
			$activity['note'] = '';
			foreach($detailActivities as $detailActivity){
				if($activity['id'] == $detailActivity['id_activity']){
					$activity['checked_activity'] = true;
					$activity['note'] = $detailActivity['note'];
				}
			}
		}*/

        $this->_result = array('response' => 'OK', 'data' => ['contatti'=>$contatti,
          'services'=>$services, 'dettagli' => $details], 'msg' => '');
    }

    public function getOrderServices($idOrder = 0)
    {

        $services = $this->Service->getOrderServices($idOrder);

        $this->_result = array('response' => 'OK', 'data' =>$services, 'msg' => '');

    }
}
