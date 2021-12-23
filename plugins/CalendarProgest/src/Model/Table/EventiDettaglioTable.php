<?php
namespace Calendar\Model\Table;

use Cake\ORM\Table;

class EventiDettaglioTable extends Table
{

    public function initialize(array $config)
    {
        $this->table('calendar_events_detail');
        $this->primaryKey('id');
		$this->addBehavior('Timestamp');
	}

	public function getEventDetailsById($event_id){

		$eventDetails = $this->find('all', ['conditions' => ['event_id' => $event_id]]);
		return $eventDetails;

	}

}
