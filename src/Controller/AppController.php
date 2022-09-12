<?php
/**
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link      http://cakephp.org CakePHP(tm) Project
 * @since     0.2.9
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 */
namespace App\Controller;

use Cake\Controller\Controller;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Network\Email\Email;
use Cake\I18n\Time;
use Cake\Network\Response;
use Cake\Routing\Router;

Time::$defaultLocale = 'it_IT';
Time::setToStringFormat('dd/MM/YYYY HH:mm:ss');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @link http://book.cakephp.org/3.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller
{

    public $email;
    public $customViews = array();

    /**
     * Initialization hook method.
     *
     * Use this method to add common initialization code like loading components.
     *
     * @return void
     */

    public function initialize()
    {
        $this->loadComponent('Flash');

        $this->loadComponent('RememberMe.RememberMe',[
            'cypherKey' => "16485937564892755682043369192734583655920936",  
            'cookieName' => "rememberme", 
            'period' => '30 Days'
        ]);

        $this->loadComponent('Auth', [
            'authorize' => ['Controller'],
            'loginRedirect' => [
                'prefix' => false,
                'controller' => 'Home',
                'action' => 'index'
            ],
            'logoutRedirect' => [
                'controller' => 'Home',
                'action' => 'index',
                'prefix' => false
            ],
            'loginAction' => [
                'prefix' => false,
                'controller' => 'Home',
                'action' => 'login',
                'plugin' => 'Registration'
            ],
            'unauthorizedRedirect' => false

        ]);
        $this->customViews = Configure::read('custom.views');
    }

    public function isAuthorized($user)
    {
        if($user['role'] == 'admin'){
            return true;
        }else{
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
            return true;
        }
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        ##########################################################################
        //Gestione del layout da usare per admin o meno
        if(isset($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin'){
            $this->viewBuilder()->layout('admin');
        }

        ##########################################################################
        //Leggo le configurazioni da db
        $configurations = TableRegistry::get('Configurations');
        $result = $configurations->find('all');
        foreach ($result as $conf) {
            Configure::write('dbconfig.' .$conf->plugin .'.'. $conf->key_conf,$conf->value);
        }

        ##########################################################################
        //Inizializzo la classe del mailer
        $this->email = new Email('default');

        ##########################################################################
        //Rimozione spazi bianchi iniziali e finali nei dati ricevuti in POST     
        array_walk_recursive($this->request->data, function (&$value, $key) {
            if (is_string($value)) {
                $value = trim($value);    
            }
        });     
    }

    /**
     * Override render method of controller, search for custom views setted.
     * @param  [type] $view   [description]
     * @param  [type] $layout [description]
     * @return [type]         [description]
     */
    public function render($view = null, $layout = null)
    {
        if(!empty($this->customViews)){
            $params = $this->request->params;

            $customView = $params['controller'].'.'.$params['action'];
            if(!empty($params['plugin'])){
                $customView = $params['plugin'].'.'.$customView;
            }
            if(!empty($this->customViews[$customView])){
                return parent::render($this->customViews[$customView]);
            }
        }
        return parent::render($view, $layout);
    }

    protected function trimByReference(&$value)
    {
         $value = trim($value);
    }

    protected function filterRecursive($array)
    {

        foreach($array as $key => $value){
            if(is_array($value)){
                $array[$key] = $this->filterRecursive($value);
            }else{
                if($value === ''){
                    unset($array[$key]);
                }
            }
        }
        return $array;
    }

    /**
     * Redirects to given $url, after turning off $this->autoRender.
     *
     * @param string|array $url A string or array-based URL pointing to another location within the app,
     *     or an absolute URL
     * @param int $status HTTP status code (eg: 301)
     * @return \Cake\Network\Response|null
     * @link http://book.cakephp.org/3.0/en/controllers.html#Controller::redirect
     */
    public function redirect($url, $status = 302)
    {
        $this->autoRender = false;

        $response = $this->response;
        if ($status) {
            $response->statusCode($status);
        }

        $event = $this->dispatchEvent('Controller.beforeRedirect', [$url, $response]);
        if ($event->result instanceof Response) {
            return $event->result;
        }
        if ($event->isStopped()) {
            return null;
        }

        if (!$response->location()) {
            $response->location(Router::url($url));
        }

        return $response;
    }

}
