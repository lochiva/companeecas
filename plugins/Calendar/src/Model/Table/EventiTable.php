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
        $this->belongsTo('Aziende.Orders',['foreignKey' => 'id_order','propertyName' => 'ordine']);
        $this->belongsTo('Users',['foreignKey' => 'id_user','propertyName' => 'user']);
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
      $opt['fields'] = [
        'id'=>'Eventi.id',
        'title'=>'Eventi.title',
        'id_user'=>'Eventi.id_user',
        'id_group'=>'Eventi.id_group',
        'id_azienda'=>'Eventi.id_azienda',
        'azienda_denominazione' => 'Aziende.denominazione',
        'id_sede'=>'Eventi.id_sede',
        'id_contatto'=>'Eventi.id_contatto',
        'id_order'=>'Eventi.id_order',
        'start'=>'Eventi.start',
        'end'=>'Eventi.end',
        'allDay'=>'Eventi.allDay',
        'repeated'=>'Eventi.repeated',
        'backgroundColor'=>'Eventi.backGroundColor',
        'borderColor'=>'Eventi.borderColor',
        'note'=>'Eventi.note',
        'vobject' => 'Eventi.vobject',
        'nome_ordine' => 'Orders.name',
        'id_timetask' => 'Eventi.id_timetask',
        'number_timetask' => 'Eventi.number_timetask',
        'client_timetask' => 'Eventi.client_timetask',
        'project_timetask' => 'Eventi.project_timetask',
        'id_time_timetask' => 'Eventi.id_time_timetask'
      ];

      return $this->find('all',$opt)->autoFields(true)->toArray();
    }

    public function getEventsDataByInterval($dateStart, $dateEnd, $id_operator){

		$opt['conditions']['AND'] = [
			'Eventi.start >=' => $dateStart,
			'Eventi.start <=' => $dateEnd,
		];
		$opt['conditions']['AND']['OR'] = [
			'Eventi.id_user' => $id_operator,
		];
		$opt['conditions']['AND']['OR']['AND'] = [
			'Eventi.id_group <> 0',
			'utg.id_user' => $id_operator
		];
		$opt['join'] = [
			[
				'table' => 'users_to_groups',
		        'alias' => 'utg',
		        'type' => 'LEFT',
				'conditions' => ['utg.id_group = Eventi.id_group', 'utg.id_user' => $id_operator]
			],
		];

		$opt['order'] = ['Eventi.start' => 'ASC'];

		return $this->find('all', $opt)->toArray();
	}

    public function getDetails($id)
    {
        $event =  $this->get($id, ['contain'=>['Orders' => ['Aziende'],'Users','Groups'=>['UsersToGroups']]]);

        $event['operatori'] = array();
        $event['operatoriShared'] = array();
        if(!empty($event['group']['UsersToGroups'])){
            $event['condiviso'] = 1;

             foreach ($event['group']['UsersToGroups'] as $operatore) {
                 $operatoriShared = TableRegistry::get('Contatti')->find()->where(['id' => $operatore->id_user])->toArray();
                 $event['operatori'][] = $operatore->id_user;
                 $event['operatoriShared'][] = ['id' => $operatore->id_user,
                                                'nome' => $operatoriShared[0]['nome'],
                                                'cognome' => $operatoriShared[0]['cognome']];
             }
        }else{
            $event['operatori'][] = $event->id_contatto;
        }
        return $event;
    }

}
