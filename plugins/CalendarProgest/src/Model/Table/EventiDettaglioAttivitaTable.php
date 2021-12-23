<?php
namespace Calendar\Model\Table;

use Cake\ORM\Table;

class EventiDettaglioAttivitaTable extends Table
{

    public function initialize(array $config)
    {
        $this->table('calendar_events_detail_activities');
        $this->primaryKey('id');
		$this->addBehavior('Timestamp');
	}


}
