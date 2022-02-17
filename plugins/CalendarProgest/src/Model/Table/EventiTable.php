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

class EventiTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->setTable('calendar_events');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Calendar.Evento');
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

    public function afterSave($event, $entity, $options)
    {
        parent::afterSave($event, $entity, $options);

        if($entity->isNew() && !$entity->repeated ){
            if(!empty($entity['tags']) && is_array($entity['tags'] )){
                $tagScadenzario = Configure::read('dbconfig.scadenzario.TAG');
                foreach ($entity['tags'] as $value) {
                    if($value['id'] == $tagScadenzario){
                        $az = TableRegistry::get('Scadenzario.Scadenzario');
                        $scadenzario = $az->newEntity();
                        $data = [
                          'id_event'=>$entity->id,
                          'descrizione'=>$entity->title,
                          'note'=>$entity->note,
                          'data'=>$entity->start,
                        ];
                        $scadenzario = $az->patchEntity($scadenzario, $data);
                        $az->save($scadenzario);
                        return;
                    }
                }
            }
        }else{
            $az = TableRegistry::get('Scadenzario.Scadenzario');
            $az->updateAll(['descrizione'=>$entity->title,'note'=>$entity->note,'data'=>$entity->start],['id_event'=>$entity->id]);
        }

    }

    public function afterDelete($event, $entity, $options)
    {
        parent::afterDelete($event, $entity, $options);

        $az = TableRegistry::get('Scadenzario.Scadenzario');
        $az->updateAll(['id_event'=>$entity->id],['deleted' => 1]);
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

    public function getEventsStampe(array $opt = array())
    {
        $opt['fields'] = [
          'id'=>'Eventi.id',
          'title'=>'Eventi.title',
          'id_user'=>'Eventi.id_user',
          'id_group'=>'Eventi.id_group',
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
          'id_parentEvent' => 'Eventi.id_parentEvent'
        ];
        $opt['contain'] = array_merge($opt['contain'],['Groups'=>['UsersGrouping','Operatori']]);
        $opt['group'] = 'Eventi.id';

        return $this->find('all',$opt)->autoFields(true)->toArray();
    }

    public function getEventsMonteOre($operatore,$start,$end)
    {
        $opt['fields'] = ['interventi' => 'SUM(IF( CategoriesGroup.id = 1,1,0))',
          'ore_utenti'=>'SUM(IF( CategoriesGroup.id = 1,TIMESTAMPDIFF(SECOND,Eventi.start,Eventi.end),0))',
          'ore_altro'=>'SUM(IF( CategoriesGroup.id != 1,TIMESTAMPDIFF(SECOND,Eventi.start,Eventi.end),0))',
          'tot_ore'=>'SUM(TIMESTAMPDIFF(SECOND,Eventi.start,Eventi.end))'];
        $opt['contain'] = ['Services'=>['CategoriesGroup'=> function($q){
          return $q->select(['gruppo' => 'CategoriesGroup.id']);
        }],'Groups' => ['UsersGrouping']] ;
        $opt['conditions']['Eventi.start >= '] = $start;
        $opt['conditions']['Eventi.start <= '] = $end;
        $opt['conditions']['Eventi.repeated'] = 0;
        $opt['conditions']['Eventi.allDay'] = 0;
        $opt['conditions']['Services.billable'] = 1;
        $opt['conditions']['AND']['OR']['Eventi.id_contatto'] = $operatore;
        $opt['conditions']['AND']['OR']['UsersGrouping.id_user'] = $operatore;
        $opt['order'] = "Eventi.start ASC";

        return $this->find('all',$opt)->first();
    }

    public function getEventsForFroze($start,$end)
    {
        $opt['conditions']['Eventi.start >= '] = $start;
        $opt['conditions']['Eventi.start <= '] = $end;
        $opt['conditions']['Eventi.repeated'] = 0;
        $opt['fields'] = [
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
            return $q->select(['name','note','id'])->contain(['UsersToGroups' => function($q){
              return $q->select(['id_user','id_group']);
            }])->autoFields(false);
        }];
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

    public function getPeriodOperatore($start,$end,$contattoId,$eventId=0)
    {
        $opt['conditions']['OR'] = ['Eventi.id_contatto' => $contattoId,'UsersGrouping.id_user' => $contattoId];
        $opt['contain'] = ['Groups'=>['UsersGrouping']];
        $opt['group'] = 'Eventi.id';
        $opt['conditions']['AND']['OR'][] = ['Eventi.start >= ' => $start, 'Eventi.start <' => $end];
        $opt['conditions']['AND']['OR'][] = ['Eventi.end >' => $start, 'Eventi.end <= ' => $end];
        $opt['conditions']['AND']['OR'][] = ['Eventi.start <=' => $start, 'Eventi.end  >=' => $end];
        $opt['conditions']['Eventi.id !='] = $eventId;
        //$opt['fields'] = array();

        return $this->find('all',$opt)->toArray();
    }


	public function getEventsData($date, $id_operator){

		$opt['conditions']['AND'] = [
			'Eventi.start LIKE' => $date . '%',
			'Eventi.id_contatto' => $id_operator,
		];

		return $this->find('all', $opt)->toArray();
	}

	public function getEventsDataByInterval($dateStart, $dateEnd, $id_operator){

		$opt['conditions']['AND'] = [
			'Eventi.start >=' => $dateStart,
			'Eventi.start <=' => $dateEnd,
			'Eventi.id_contatto' => $id_operator,
		];

		$opt['order'] = ['Eventi.start' => 'ASC'];

		return $this->find('all', $opt)->toArray();
	}

}
