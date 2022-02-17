<?php
namespace Calendar\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use Cake\ORM\Behavior\TimestampBehavior;

class RepeatedEventsTable extends Table
{

    public function initialize(array $config)
    {
        $this->setTable('calendar_repeated_events');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Calendar.RepeatedEvents');
        $this->belongsTo('Calendar.Eventi',['foreignKey' => 'id_event', 'propertyName' => 'Evento']);
        //$this->belongsTo('Calendar.Eventi',['foreignKey' => 'id_event', 'propertyName' => 'Evento']);
        //$this->belongsTo('Document.Projects',['foreignKey' => 'id_project']);
    }

    public function getEvents(array $opt = array())
    {
      $opt['fields'] = [
        'id'=>'id_event',
        'title'=>'Eventi.title',
        'id_user'=>'Eventi.id_user',
        'id_group'=>'Eventi.id_group',
        'id_azienda'=>'Eventi.id_azienda',
        'azienda_denominazione' => 'Aziende.denominazione',
        'id_sede'=>'Eventi.id_sede',
        'id_contatto'=>'Eventi.id_contatto',
        'id_order'=>'Eventi.id_order',
        'start'=>'RepeatedEvents.start',
        'end'=>'RepeatedEvents.end',
        'allDay'=>'Eventi.allDay',
        'repeated'=>'Eventi.repeated',
        'backgroundColor'=>'Eventi.backGroundColor',
        'borderColor'=>'Eventi.borderColor',
        'note'=>'Eventi.note',
        'vobject' => 'Eventi.vobject',
        'nome_order' => 'Orders.name',
        'id_google' => 'Eventi.id_google'
      ];

      return $this->find('all',$opt)->autoFields(false)->toArray();
    }



}
