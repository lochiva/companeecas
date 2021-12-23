<?php
namespace Calendar\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use Cake\ORM\Behavior\TimestampBehavior;
use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class EventiFrozenTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->table('calendar_events_frozen');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->entityClass('Calendar.EventoFrozen');
        //$this->belongsTo('Document.Contacts',['foreignKey' => 'id_client', 'conditions' => ['Contacts.client' => 1], 'propertyName' => 'client']);
        //$this->belongsTo('Document.Projects',['foreignKey' => 'id_project']);
        $this->belongsTo('Aziende.Aziende',['foreignKey' => 'id_azienda','propertyName' => 'azienda']);
        $this->belongsToMany('Tags', [
            'through' => 'Calendar.EventiToTags',
            'targetForeignKey' => 'id_tag',
            'foreignKey' => 'id_event'
        ]);
        $this->belongsTo('Progest.Orders',['foreignKey' => 'id_order','propertyName' => 'ordine']);
        $this->belongsTo('Aziende.Contatti',['foreignKey' => 'id_contatto','propertyName' => 'contatto']);
        $this->belongsTo('Progest.Services',['foreignKey' => 'id_service','propertyName' => 'service']);
        $this->belongsTo('Calendar.Groups',['foreignKey' => 'id_group','propertyName' => 'group']);
    }

    public function getEvents(array $opt = array())
    {
      $opt['fields'] = array_merge([
        'id'=>'Eventi.id',
        'title'=>'Eventi.title',
        'id_user'=>'Eventi.id_user',
        'id_group'=>'Eventi.id_group',
        'id_azienda'=>'Eventi.id_azienda',
        'azienda_denominazione' => 'Aziende.denominazione',
        'id_sede'=>'Eventi.id_sede',
        'id_contatto'=>'Eventi.id_contatto',
        'id_order'=>'Eventi.id_order',
        'id_service'=>'Eventi.id_service',
        'start'=>'Eventi.start',
        'end'=>'Eventi.end',
        'allDay'=>'Eventi.allDay',
        'repeated'=>'Eventi.repeated',
        'backgroundColor'=>'Eventi.backGroundColor',
        'borderColor'=>'Eventi.borderColor',
        'note'=>'Eventi.note',
        'vobject' => 'Eventi.vobject',
        'nome_ordine' => 'Orders.name'
      ],$opt['fields']);

      return $this->find('all',$opt)->autoFields(true)->toArray();
    }

    public function getDetails($id)
    {
        $event =  $this->get($id,['contain'=>['Orders'=>['ServicesOrders'=>['Services'],
          'People','Aziende'],'Contatti','Groups'=>['UsersToGroups'] ] ]);

        $event['operatori'] = array();
        if(!empty($event['group']['UsersToGroups'])){
             foreach ($event['group']['UsersToGroups'] as $operatore) {
                 $event['operatori'][] = $operatore->id_user;
             }
        }else{
            $event['operatori'][] = $event->id_contatto;
        }
        return $event;
    }

    public function getEventsForClone($contactId,$start,$end, $oneclone=1)
    {
        $opt['conditions']['Eventi.start >= '] = $start;
        $opt['conditions']['Eventi.start <= '] = $end;
        $opt['conditions']['Eventi.repeated'] = 0;
        if($oneclone){
          $opt['conditions']['Eventi.cloned'] = 0; // se oneclone è definito allora prendi solo gli eventi mai clonati
        }
    //    debug($opt);die;
      $opt['conditions']['AND'][]['OR'] = ['Orders.id_status' => 1,'Eventi.id_order' => 0];

        $opt['conditions']['AND'][]['OR'] = ['Eventi.id_contatto' => $contactId, 'UsersGrouping.id_user'=>$contactId];

        $opt['fields'] = [
          'id' => 'Eventi.id',
          'title'=>'Eventi.title',
          'id_user'=>'Eventi.id_user',
          'id_azienda'=>'Eventi.id_azienda',
          'id_sede'=>'Eventi.id_sede',
          'id_contatto'=>'Eventi.id_contatto',
          'id_order'=>'Eventi.id_order',
          'id_service'=>'Eventi.id_service',
          'start'=>'Eventi.start',
          'end'=>'Eventi.end',
          'allDay'=>'Eventi.allDay',
          'repeated'=>'Eventi.repeated',
          'backgroundColor'=>'Eventi.backGroundColor',
          'borderColor'=>'Eventi.borderColor',
          'note'=>'Eventi.note',
          'id_parentEvent' => 'Eventi.id_parentEvent',
          'vobject' => 'Eventi.vobject'
        ];
        $opt['contain'] = ['Groups'=> function($q){
            return $q->select(['name','note','id'])->contain(['UsersGrouping','UsersToGroups' => function($q){
              return $q->select(['id_user','id_group']);
            }])->autoFields(false);
        },'Orders'];
        $opt['order'] = "Eventi.start ASC";
        $res = $this->find('all',$opt)->toArray();
        foreach ($res as $key => $event) {
            if(!empty($event['group']) && !empty($event['group']['UsersToGroups'])){
                foreach ($event['group']['UsersToGroups'] as $key2 => $value) {
                    unset($res[$key]['group']['UsersToGroups'][$key2]['id_group']);
                }
                unset($res[$key]['group']['id']);
            }
            $res[$key] = $res[$key]->toArray();
        }

        return $res;
    }
}
