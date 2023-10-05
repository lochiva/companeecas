<?php
/** 
* Companee :    Home (https://www.companee.it)
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

        if(
          isset($user['role']) && (
            $user['role'] == 'admin' || 
            $user['role'] == 'area_iv' || 
            $user['role'] == 'ragioneria' || 
            $user['role'] == 'ragioneria_adm' ||
            $user['role'] == 'questura' ||
            $user['role'] == 'ente_ospiti' ||
            $user['role'] == 'ente_contabile'
          )
        ){
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

      $guestsNotifications = TableRegistry::get('Aziende.GuestsNotifications');
      $statementsNotifications = TableRegistry::get('Aziende.StatementsNotifications');
      // Notifiche
      $notificationsCount = $guestsNotifications->countGuestsNotifications(1);
      $notifications = $guestsNotifications->getGuestsNotificationsForHome(1);
      
      for ($i = 0; $i < count($notifications); $i++) {
        if ($notifications[$i]['type_count'] > 0) {
          $notifications[$i]['message'] = str_replace('{N}', $notifications[$i]['type_count'], $notifications[$i]['message']);
        }
      }
      // Notifiche emergenza ucraina
      $notificationsUkraineCount = $guestsNotifications->countGuestsNotifications(2);
      $notificationsUkraine = $guestsNotifications->getGuestsNotificationsForHome(2);
      
      for ($i = 0; $i < count($notificationsUkraine); $i++) {
        if ($notificationsUkraine[$i]['type_count'] > 0) {
          $notificationsUkraine[$i]['message'] = str_replace('{N}', $notificationsUkraine[$i]['type_count'], $notificationsUkraine[$i]['message']);
        }
      }

      // Notifiche Rendiconti
      $statementsNotificationsCount = $statementsNotifications->find('all')->where(['done' => false])->count();
      
      $this->set(compact('notifications', 'notificationsCount', 'notificationsUkraine', 'notificationsUkraineCount', 'statementsNotificationsCount'));
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
