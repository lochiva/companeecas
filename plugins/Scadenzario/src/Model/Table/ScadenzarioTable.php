<?php
namespace Scadenzario\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

class ScadenzarioTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->setTable('scadenzario');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Scadenzario.Scadenzario');
        //$this->belongsTo('Document.Contacts',['foreignKey' => 'id_client', 'conditions' => ['Contacts.client' => 1], 'propertyName' => 'client']);
        //$this->belongsTo('Document.Projects',['foreignKey' => 'id_project']);
    }

    public function afterSave($event, $entity, $options)
    {
        parent::afterSave($event, $entity, $options);
        // Se è un nuovo evento ed è vuoto il campo id_event lo salvo negli eventi
        if($entity->isNew() && empty($entity->id_event)){

          $ca = TableRegistry::get('Calendar.Eventi');
          $tagToCa = TableRegistry::get('Calendar.EventiToTags');
          $evento = $ca->newEntity();
          $data = [
            'title'=>$entity->descrizione,
            'note'=>$entity->note,
            'start' => $entity->data,
            'end' => $entity->data,
            'allDay'=> 1,
            'backgroundColor' => '#3a87ad',
            'borderColor' => '#3a87ad',
          ];
          if(!empty($_SESSION['Auth']['User']['id']) ){
            $data['id_user'] = $_SESSION['Auth']['User']['id'];
          }
          $evento = $ca->patchEntity($evento, $data);
          $evento = $ca->save($evento);
          // salvo l'id dell'evento nello scadenzario
          $entity->id_event = $evento->id;
          $this->save($entity);
          // aggiungo il tag scadenzario all'evento
          $tag = $tagToCa->newEntity();
          $tag->id_event = $evento->id;
          $tag->id_tag = Configure::read('dbconfig.scadenzario.TAG');
          $tagToCa->save($tag);

        }else{
          $ca = TableRegistry::get('Calendar.Eventi');
          $ca->updateAll(['title'=>$entity->descrizione,'note'=>$entity->note,'start' => $entity->data,'end' => $entity->data,
            'allDay' => 1]
                  ,['id'=>$entity->id_event]);
        }

        return;
    }

    public function afterDelete($event, $entity, $options)
    {
        parent::afterDelete($event, $entity, $options);

        $ca = TableRegistry::get('Calendar.Eventi');
        $ca->deleteAll(['id'=>$entity->id_event]);
    }



}
