<?php

namespace App\Model\Table;

use Cake\ORM\Table;
use Cake\Core\Configure;
use Cake\Routing\Router;

class ActionLogTable extends Table
{
    public $nammingEntity;
    public $actionVerbs;

    public function initialize(array $config)
    {
        $this->setTable('action_log');
        $this->setPrimaryKey('id');
        $this->belongsTo('Registration.Users', ['foreignKey' => 'id_user', 'propertyName' => 'user']);
        $this->nammingEntity = Configure::read('localconfig.nammingEntity');
        $this->actionVerbs = ['insert'=>'inserito','update'=>'aggiornato','delete' => 'cancellato'];
        $this->excludeEntity = Configure::read('localconfig.excludeEntity');
    }

    public function getRecordHistory($table, $recordId, $limit = 0)
    {
        $toRet = $this->find('all')->contain('Users')->where(['table_name' => $table, 'id_record' => $recordId])
                  ->order(['ActionLog.id' => 'DESC']);

        if (!empty($limit)) {
            $toRet = $toRet->limit($limit);
        }
        $toRet = $toRet->toArray();

        if (!empty($toRet)) {
            foreach ($toRet as $key => $val) {
                $toRet[$key]['entity'] = json_decode($val['entity'], true);
            }
        }

        return $toRet;
    }

    public function getHistoryGeneral($limit = 10 ,$user = 0)
    {
        $toRet = $this->find('all')->contain('Users')->order(['ActionLog.id' => 'DESC'])
                ->where(['table_name NOT IN'=>$this->excludeEntity])->limit($limit);
        if(!empty($user)){
          $toRet = $toRet->where(['id_user' => $user]);
        }
        $toRet = $toRet->toArray();

        if (!empty($toRet)) {
            foreach ($toRet as $key => $val) { 
                $toRet[$key]['label'] = array();
                if(empty($val['user']['nome'])){
                  $toRet[$key]['label']['user'] = $val['user']['username'];
                }else{
                  $toRet[$key]['label']['user'] = $val['user']['nome'].' '.$val['user']['cognome'];
                }
                $toRet[$key]['label']['action'] = 'ha '.$this->actionVerbs[$val['action']].' '.$this->nammingEntity[$val['table_name']];
                $toRet[$key]['label']['data'] = $val['created']->i18nFormat('HH:mm - dd/MM/yy');
                if($val['action'] == 'update' || $val['action'] == 'insert'){
                  switch ($val['table_name']) {
                    case 'aziende':
                      $toRet[$key]['label']['link'] = Router::url('/aziende/home/info/').$val['id_record'];
                      break;
                    case 'documents':
                      $toRet[$key]['label']['link'] = Router::url('/document/home/edit/').$val['id_record'];
                      break;
                  }
                }
            }
        }
        //debug($toRet);die;
        return $toRet;
    }
}
