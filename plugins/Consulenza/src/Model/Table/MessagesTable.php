<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class MessagesTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->setTable('messages');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
     	$this->setEntityClass('Consulenza.Messages');

        $this->belongsTo('UserSources',[
        	'className'=>'Users',
        	'foreignKey' => 'userSource_id', 
        	'propertyName' => 'users_source'
        	]);

        $this->belongsTo('UserDests',[
        	'className'=>'Users',
        	'foreignKey' => 'userDest_id', 
        	'propertyName' => 'users_dest'
        	]);        

    }
    
    public function afterSave($event,$entity,$options){

        parent::afterSave($event,$entity,$options);

        //debug($entity); exit;
        
        //Tutti i messaggi che vengono inviati a Marta Leone (25) devono essere inviati anche a Paola Grinza (31)
        if($entity->userDest_id == 25){

            if(!isset($entity->noAfterSave) || $entity->noAfterSave == 0){
                unset($entity->id);         //Tolgo l'id per evitare che faccia un update sullo stesso record
                $entity->noAfterSave = 1;   //Blocco la ricorsività di questo aftersave
                $entity->userDest_id = 31;  //Metto il nuovo destinatario
                $this->save($entity);       //Salvo il nuovo record

            }

        }

    }
    
}