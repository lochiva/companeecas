<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    Home  (https://www.companee.it)
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

namespace Registration\Controller;

use Cake\Event\Event;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\Http\Response;
use Cake\Routing\Router;

class HomeController extends AppController
{
	public function initialize(){
		parent::initialize();
		//$this->loadComponent('WebApp.WebApp');

	}

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        $this->Auth->allow(['login','logout', 'loginApp', 'logoutApp']);
		$this->response->header('Access-Control-Allow-Origin','*');
    }

    /*public function index()
    {
    }*/

    public function login()
    {
		##########################################################################
        // setto user in sessione se c'è cookie rememberme
        $isRemembered = $this->RememberMe->getRememberedData();
        if($isRemembered){
            $user = TableRegistry::get('Users')->find()->where(['email' => $isRemembered])->first();
            unset($user->password); 
            $user = json_decode(json_encode($user),true);
			$this->Auth->setUser($user);
			$this->request->session()->delete('Flash');
		}

        if($this->Auth->user()){
          return $this->redirect('/');
        }
        $this->viewBuilder()->layout('login');
        $this->set('enabledRegistration',Configure::read('dbconfig.registration.REGISTRATION_FRONTEND')  ?? 0 );
		$this->set('enabledPasswordRecovery',Configure::read('dbconfig.registration.PASSWORD_RECOVERY_FRONTEND') ?? 0  );
		$this->set('enabledGoogleLogin', Configure::read('dbconfig.registration.GOOGLE_OAUTH_ENABLE') ?? 0);
		$this->set('enabledVerifyData', Configure::read('dbconfig.gdpr.ENABLE_BTN_VERIFY_DATA') ?? 0);
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                if ($user['auth_email'] == 1) {
					$this->Auth->setUser($user);
					if($this->request->data['remember_me']){ 
						$this->RememberMe->rememberData($user['email']);
					}
                    if(Configure::read('dbconfig.registration.LOG_ACCESS') ?? 0 ){
                        $this->_accessLog('login');
                    }
                    //return $this->redirect($this->Auth->redirectUrl());
                    // storm deciso con marco di portarlo sempre in home se sei admin o programmazione se sei utente, in questo modo si evitano redirect a pagine rotte

                    return $this->redirect('/');
                } else {
                    $this->Flash->warning(__('L\'utenza risulta ancora da confermare, si prega di seguire la procedura di autenticazione ricevuta via email.'));
                }
            } else {
                $this->Flash->error(__('Username o password non valide, si prega di riprovare'));
            }
		}
		
		//carico immagine di sfondo
		$backgrounds = TableRegistry::get('AppearanceBackgrounds')->getList();

		if(count($backgrounds) > 0){
			$random = rand(0, count($backgrounds)-1);
			$background = Router::url('/').$backgrounds[$random]->path.$backgrounds[$random]->name;
		}else{
			$background = Router::url('/').'webroot/img/bg-login.jpg';
		}

		$this->set('background', $background);
    }

    public function logout()
    {
        if(Configure::read('dbconfig.registration.LOG_ACCESS')){
            $this->_accessLog('logout');
        }
		$this->request->session()->destroy();
		$this->RememberMe->removeRememberedData();
        $this->Auth->logout();

        return $this->redirect(['action' => 'login']);
    }

  /**
   * log in access_log table
   * @param  string $action
   */
    private function _accessLog($action)
    {
        $conn = ConnectionManager::get('default');
        $conn->execute('INSERT INTO `access_log` (`id_user`, `action`,`ip`, `created`)
				 	VALUES ( :id_user, :action, :ip, NOW() ) ',[
					':id_user' => $this->Auth->user('id'),
					':action' => $action,
					':ip' => $this->request->clientIp()
				]);
    }

	public function loginApp(){

		if ($this->request->is('post')) {
        	$user = $this->Auth->identify();
        	if ($user) {
				if ($user['auth_email'] == 1) {
						$this->Auth->setUser($user);
						if(Configure::read('dbconfig.registration.LOG_ACCESS')){
							$this->_accessLog('login');
						}
						
						$id_operatore = $user['id'];

						$token = $this->WebApp->getTokenById($id_operatore);

						$this->response->type('json');
						$this->response->body(json_encode([
							'status' => 'OK',
							'message'=> 'Utente autenticato.',
							'data' => [
								'id_operatore' => $id_operatore,
								'token_di_sicurezza' => $token
							]
						]));
				} else {
					$this->response->type('json');
					$this->response->body(json_encode([
						'status' => 'KO',
						'message'=> 'L\'utenza risulta ancora da confermare, si prega di seguire la procedura di autenticazione ricevuta via email.'
					]));
				}
        	} else {
				$this->response->type('json');
				$this->response->body(json_encode([
					'status' => 'KO',
					'message'=> 'Username o password non valide,  si prega di riprovare'
				]));
        	}
		}

		return $this->response;
    }

	public function logoutApp(){



	}
}
