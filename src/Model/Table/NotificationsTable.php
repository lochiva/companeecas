<?php
/** 
* Companee :    Notifications (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
namespace App\Model\Table;


use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\I18n\Time;

class NotificationsTable extends Table
{

    public function initialize(array $config)
    {
        $this->setTable('notifications');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Dest', [
            'className' => 'Users',
            'foreignKey' => 'id_dest',
            'propertyName' => 'Dest'
        ]);
        $this->belongsTo('Creator', [
            'className' => 'Users',
            'foreignKey' => 'id_creator',
            'propertyName' => 'Creator'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('id_dest', 'Il destinatario è obbligatorio')
            ->notEmpty('message', 'Il messaggio non può essere vuoto');
    }

    public function getUserNewNotice($id)
    {
        $toRet =  $this->find()->where(['Notifications.id_dest' => $id, 'Notifications.readed LIKE' => '%0000-00-00 00:00%'])
            ->contain('Creator')->order(['Notifications.created' => 'DESC'])->toArray();
        foreach($toRet as $key => $val){
            $toRet[$key]['created'] = $val['created']->i18nFormat('HH:mm - dd/MM/yy');

            if(!empty($val['Creator']['nome'])){
                $toRet[$key]['creator'] = $val['Creator']['nome'].' '.$val['Creator']['cognome'];
            }else{
                $toRet[$key]['creator'] = (!empty($val['Creator']['username'])?$val['Creator']['username']:'');
            }
        }
        //debug($toRet);die;
        return $toRet;
    }

    public function getNotifications($id,$limit = 10)
    {
        $toRet =  $this->find()->where(['Notifications.id_dest' => $id])
            ->contain('Creator')->order(['Notifications.created' => 'DESC'])->limit($limit)->toArray();
        foreach($toRet as $key => $val){
            $toRet[$key]['created'] = $val['created']->i18nFormat('HH:mm - dd/MM/yy');
            if(!empty($val['readed'])){
              $toRet[$key]['readed'] = $val['readed']->i18nFormat('HH:mm - dd/MM/yy');
            }
            if(!empty($val['Creator']['nome'])){
                $toRet[$key]['creator'] = $val['Creator']['nome'].' '.$val['Creator']['cognome'];
            }else{
                $toRet[$key]['creator'] = (!empty($val['Creator']['username'])?$val['Creator']['username']:'');
            }
        }
        //debug($toRet);die;
        return $toRet;
    }

    public function readNotice($id)
    {
        $notice = $this->get($id);
        if(empty($notice)){
          return false;
        }
        $notice->readed = Time::now();

        return $this->save($notice);
    }

    public function notifyCalendarEvent($data,$id_creator)
    {
        $entity = $this->newEntity();
        $entity->message = 'Inserimento/Modifica evento in calendario dal titolo "'.$data['title'].
            '" del giorno '.$data['start']->i18nFormat('dd/MM/yy');
        $entity->id_dest = $data['id_user'];
        $entity->id_creator = $id_creator;

        $this->save($entity);

    }

    public function sendNotice($data)
    {
        $entity = $this->newEntity();
        $entity = $this->patchEntity($entity,$data);

        return $this->save($entity);

    }


}
