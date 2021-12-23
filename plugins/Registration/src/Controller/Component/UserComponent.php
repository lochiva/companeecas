<?php
namespace Registration\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class UserComponent extends Component
{
    public function getUserViewData($id,$limitNotice = 10)
    {
        $timeline = TableRegistry::get('ActionLog')->getHistoryGeneral(10,$id);
        $tot = array(
          'actions' => TableRegistry::get('ActionLog')->find()->where(['id_user' => $id])->count(),
          //'tasks' => TableRegistry::get('Calendar.Eventi')->find()->where(['id_user' => $id])->count(),
          'accessi' => TableRegistry::get('AccessLog')->find()->where(['id_user' => $id])->count(),
        );
        $notifications = array();
        /*$notice = TableRegistry::get('Notifications')->getUserNewNotice($id);
        foreach ($notice as  $value) {
          $notifications[$value['id']] = $value;
        }
        if(count($notice) < $limitNotice){
          $notice = TableRegistry::get('Notifications')->getNotifications($id,$limitNotice-count($notice));
          foreach ($notice as  $value) {
            $notifications[$value['id']] = $value;
          }
        }*/
        $notifications = TableRegistry::get('Notifications')->getNotifications($id,$limitNotice);


        return array('timeline' => $timeline, 'tot' =>$tot, 'notifications' => $notifications);
    }
}
