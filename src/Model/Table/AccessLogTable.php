<?php

namespace App\Model\Table;

use Cake\ORM\Table;

class AccessLogTable extends Table
{
    public function initialize(array $config)
    {
        $this->setTable('access_log');
        $this->setPrimaryKey('id');
        $this->belongsTo('Registration.Users', ['foreignKey' => 'id_user', 'propertyName' => 'user']);
    }

    public function getAccessHistory( $action ,$limit = 12)
    {

        $toRet = $this->find('all')->select(['AccessLog.id_user','date_access' => 'MAX(AccessLog.created)','user'=>'username'])
          ->contain('Users')->order(['date_access' => 'DESC'])->group('AccessLog.id_user')
          ->where(['action' => $action ])->limit($limit)->toArray();



        foreach($toRet as $key=>$val ){
            $date = date('Y-m-d',strtotime($val['date_access']));
            if( $date == date('Y-m-d') ){
                $toRet[$key]['date'] = 'Oggi';
            }else if( $date == date('Y-m-d', strtotime("-1 day"))){
                $toRet[$key]['date']  = 'Ieri';
            }else{
                $toRet[$key]['date']  = date('j M',strtotime($val['date_access']));
            }

        }
        //debug($toRet);die;
        return $toRet;
    }
}
