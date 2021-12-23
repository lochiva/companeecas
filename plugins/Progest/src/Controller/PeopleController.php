<?php
namespace Progest\Controller;

use Progest\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * Aziende Controller
 *
 * @property \Aziende\Model\Table\AziendeTable $Aziende
 */
class PeopleController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->set('title', 'Persone');
		$this->loadComponent('Progest.People');
    }

    public function index()
    {

        $services = TableRegistry::get('Progest.Services')->getListSorter(1);

        $this->set('services',json_encode($services));
    }

	public function getAllCoordinates(){

	    $peopleExtension = TableRegistry::get('Progest.PeopleExtension');

	    $allPeopleExtension = $peopleExtension->find()->where(['OR' => ['address_lat IS NULL', 'address_lat' => '', 'address_long IS NULL', 'address_long' => '']])->toArray();

	    set_time_limit(300);

		$result = [
			'processed' => 0,
			'geolocated' => 0,
		];

	    foreach($allPeopleExtension as $personExtension){
			$fullAddress = $personExtension['address'] . ' ' . $personExtension['comune'] . ' ' . $personExtension['provincia'];
		    $coordinates = $this->People->getCoordinatesFromAddress($fullAddress);

		    if(!empty($coordinates)){
		        $personExtension->address_lat = $coordinates['lat'];
		        $personExtension->address_long = $coordinates['long'];
		        if($peopleExtension->save($personExtension)){
					$result['geolocated']++;
				}
		    }

			$result['processed']++;
	    }

		$this->response->type('json');
		$this->response->body(json_encode([
			'result' => 'OK',
			'message' => 'Operazione completata',
			'data' => $result,
		]));

		return $this->response;

    }
}
