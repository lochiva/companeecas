<?php
namespace Calendar\Model\Table;

use Cake\ORM\Table;

class EventiDettaglioAttivitaTable extends Table
{

    public function initialize(array $config)
    {
        $this->setTable('calendar_events_detail_activities');
        $this->setPrimaryKey('id');
		$this->addBehavior('Timestamp');
	}


}
