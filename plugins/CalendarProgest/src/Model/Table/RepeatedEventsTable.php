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
        $this->table('calendar_repeated_events');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->entityClass('Calendar.RepeatedEvents');
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
        'id_service'=>'Eventi.id_service',
        'start'=>'RepeatedEvents.start',
        'end'=>'RepeatedEvents.end',
        'allDay'=>'Eventi.allDay',
        'repeated'=>'Eventi.repeated',
        'backgroundColor'=>'Eventi.backGroundColor',
        'borderColor'=>'Eventi.borderColor',
        'note'=>'Eventi.note',
        'vobject' => 'Eventi.vobject',
        'nome_order' => 'Orders.name',
        'id_google' => 'Eventi.id_google',
        'operatore' => 'CONCAT(Contatti.cognome,SPACE(1),Contatti.nome)'
      ];

      return $this->find('all',$opt)->autoFields(false)->toArray();
    }

    public function getEventsStampe(array $opt = array())
    {
        $opt['fields'] = [
          'id'=>'id_event',
          'title'=>'Eventi.title',
          'id_user'=>'Eventi.id_user',
          'id_group'=>'Eventi.id_group',
          'id_azienda'=>'Eventi.id_azienda',
          'id_sede'=>'Eventi.id_sede',
          'id_contatto'=>'Eventi.id_contatto',
          'id_order'=>'Eventi.id_order',
          'id_service'=>'Eventi.id_service',
          'start'=>'RepeatedEvents.start',
          'end'=>'RepeatedEvents.end',
          'allDay'=>'Eventi.allDay',
          'repeated'=>'Eventi.repeated',
          'backgroundColor'=>'Eventi.backGroundColor',
          'borderColor'=>'Eventi.borderColor',
          'note'=>'Eventi.note',
          'id_google' => 'Eventi.id_google',
        ];

        return $this->find('all',$opt)->autoFields(false)->toArray();
    }

    public function getEventsMonteOre($operatore,$start,$end)
    {
        $opt['fields'] = ['interventi' => 'SUM(IF( CategoriesGroup.id = 1,1,0))',
          'ore_utenti'=>'SUM(IF( CategoriesGroup.id = 1,TIMESTAMPDIFF(SECOND,RepeatedEvents.start,RepeatedEvents.end),0))',
          'ore_altro'=>'SUM(IF( CategoriesGroup.id != 1,TIMESTAMPDIFF(SECOND,RepeatedEvents.start,RepeatedEvents.end),0))',
          'tot_ore'=>'SUM(TIMESTAMPDIFF(SECOND,RepeatedEvents.start,RepeatedEvents.end))'];
        $opt['contain'] = ['Eventi'=>['Services'=>['CategoriesGroup' => function($q){
          return $q->select(['gruppo' => 'CategoriesGroup.id']);
        }]] ];
        $opt['conditions']['RepeatedEvents.start >= '] = $start;
        $opt['conditions']['RepeatedEvents.start <= '] = $end;
        $opt['conditions']['Eventi.id_contatto'] = $operatore;
        $opt['conditions']['Services.billable'] = 1;
        $opt['order'] = "RepeatedEvents.start ASC";

        return $this->find('all',$opt)->first();

    }



}
