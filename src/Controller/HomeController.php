<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Network\Exception\NotFoundException;
use Cake\View\Exception\MissingTemplateException;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;


class HomeController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $user = $this->Auth->user();

        if(isset($user['role']) && ($user['role'] == 'admin' || $user['role'] == 'ente')){
            $this->Auth->allow(['index', 'checkPathFiles']);
        }
    }

    public function index()
    {
      $user = $this->Auth->user();
      if($user['role'] == 'nodo'){
        $provincia = TableRegistry::get('Aziende.Contatti')->getProvinciaContattoByUser($user['id']);

        $provincia = ucfirst(strtolower($provincia));

        $this->set('provincia', $provincia);
      }

      // Notifiche
      $guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');
      $notificationsCount = $guestsNotifications->countGuestsNotifications();
      $notifications = $guestsNotifications->getGuestsNotificationsForHome();
      
      for ($i = 0; $i < count($notifications); $i++) {
        if ($notifications[$i]['type_count'] > 0) {
          $notifications[$i]['message'] = str_replace('{N}', $notifications[$i]['type_count'], $notifications[$i]['message']);
        }
      }
      
      $this->set('notifications', $notifications);
      $this->set('notificationsCount', $notificationsCount);
    }

    /*public function index2()
    {

    }*/

    public function checkPathFiles()
    {
      $this->layout='login';
      $path = Configure::read('dbconfig.PATH_FILES');
      if(is_readable($path)){
          if(is_writable($path)){
            $this->Flash->success(__('Il path: "'.$path.'" ha permessi di SCRITTURA e LETTURA.'));
          }else{
            $this->Flash->success(__('Il path: "'.$path.'" ha permessi di LETTURA.'));
            $this->Flash->error(__('Il path: "'.$path.'" non ha permessi di SCRITTURA.'));
          }
      }else{
        $this->Flash->error(__('Il path: "'.$path.'" non ha permessi di LETTURA.'));
      }

    }

}
