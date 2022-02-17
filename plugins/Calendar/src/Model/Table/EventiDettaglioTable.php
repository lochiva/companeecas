<?php
namespace Calendar\Model\Table;

use Cake\ORM\Table;

class EventiDettaglioTable extends Table
{

    public function initialize(array $config)
    {
        $this->setTable('calendar_events_detail');
        $this->setPrimaryKey('id');
		$this->addBehavior('Timestamp');

		//$this->hasMany('Calendar.EventiDettaglioAttivita')
          //  ->setConditions(['id_event_detail' => 'EventiDettaglio.id']);

            $this->hasMany('EventiDettaglioAttivita',[
            'foreignKey' => 'id_event_detail',
            'propertyName' => 'EventiDettaglioAttivita',
            'className' => 'Calendar.EventiDettaglioAttivita'
        ]);
        /*
        $this->HasOne('Contatti', [
            'foreignKey' => 'operator_id',
            'className' => 'Aziende.Contatti'
        ]);
        */
        $this->belongsTo('Aziende.Contatti',['foreignKey' => 'operator_id','propertyName' => 'Contatti']);

	}

	public function getEventDetailsById($event_id){

		//$eventDetails = $this->find('all', ['conditions' => ['event_id' => $event_id]]);
		$eventDetails = $this->find()
			
			->where(['event_id' => $event_id])
			->contain(['Contatti']);
		return $eventDetails;

	}

}
