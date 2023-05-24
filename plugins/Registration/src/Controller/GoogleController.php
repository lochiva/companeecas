<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    Google  (https://www.companee.it)
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

use \Cake\Network\Exception;
use Cake\Utility\Text;
use Google_Client;
use Google_Service_Oauth2;

class GoogleController extends AppController
{

	private $googleOauthClientId = '713787601734-nsdpv93lku7js2fkt9srkj5ona0qkbem.apps.googleusercontent.com';
	private $googleOauthClientSecret = 'xxxxxxxxxxxxxxxx';
	private $googleOauthRedirectUri = '';

	public function initialize(){
		parent::initialize();
		//$this->loadComponent('Calendar.WebApp');
	}

    public function beforeFilter(Event $event){

        parent::beforeFilter($event);
        $this->Auth->allow(['googlelogin', 'googleLoginRedirect']);
		$this->response->header('Access-Control-Allow-Origin','*');

		$this->googleOauthClientId = Configure::read('dbconfig.registration.GOOGLE_OAUTH_CLIENT_ID');
		$this->googleOauthClientSecret = Configure::read('dbconfig.registration.GOOGLE_OAUTH_CLIENT_SECRET');
		$this->googleOauthRedirectUri = Router::url('/registration/google/google_login_redirect', true);

    }

    public function googlelogin(){

		$client = new Google_Client();
		$client->setClientId($this->googleOauthClientId);
		$client->setClientSecret($this->googleOauthClientSecret);
		$client->setRedirectUri($this->googleOauthRedirectUri);

		$client->setScopes(array(
			"https://www.googleapis.com/auth/userinfo.profile",
			'https://www.googleapis.com/auth/userinfo.email'
		));

		$url = $client->createAuthUrl();

		$this->redirect($url);

	}

	public function googleLoginRedirect(){

		/* Creo il client Google */
		$client = new Google_Client();
		
		$client->setClientId($this->googleOauthClientId);
		$client->setClientSecret($this->googleOauthClientSecret);
		$client->setRedirectUri($this->googleOauthRedirectUri);

		$client->setScopes(array(
			"https://www.googleapis.com/auth/userinfo.profile",
			'https://www.googleapis.com/auth/userinfo.email'
		));

		$client->setApprovalPrompt('auto');

		/* Se l'url contiene il parametro 'code' */
		if (isset($this->request->query['code'])) {
			// Allora autentichiamo il client google con il codice ricevuto
			$res = $client->authenticate($this->request->query['code']);
			// e salviamo il token generato in sessione
			$this->request->Session()->write('access_token', $client->getAccessToken());
		}
 
		/* Se il token esiste già lo salviamo nel client google */
		if ($this->request->Session()->check('access_token') && ($this->request->Session()->read('access_token'))) {
			$client->setAccessToken($this->request->Session()->read('access_token'));
		}
		
		/* Se il client ha un token valido */
		if ($client->getAccessToken()) {
			
			$this->request->Session()->write('access_token', $client->getAccessToken());
			// Creiamo l'oggetto Oauth2 per leggere le info dell'utente
			$oauth2 = new Google_Service_Oauth2($client);
			// Recuperiamo le info dell'utente
			$user = $oauth2->userinfo->get();
			//debug($user); die();
			try {
				if (!empty($user)) {
					// Se l'utente esiste e ci arriva verifichiamo se esiste sul nostro sistema tramite la mail
					$this->loadModel('Users');
					$result = $this->Users->find('all')
						->where(['email' => $user['email']])
						->first();
					if ($result) {
						// Si la mail esiste quindi dichiariamo autorizzato l'utente su cakephp ma devo verificare se è attivo

						//debug($result->toArray()['auth_email']); die();

						if($result->toArray()['auth_email'] == 1){
							$this->Auth->setUser($result->toArray());
							// Reindirizziamo alla home
							$this->redirect('/');
						}else{
							$this->Flash->error('Non sei autorizzato all\'accesso');
							$this->redirect(['controller' => 'Home', 'action' => 'login']);
						}
						
					} else {

						// L'utente non esiste sul sistema, quindi devo verificare se è possibile crearlo oppure no
						$registrationEnable = Configure::read('dbconfig.registration.REGISTRATION_FRONTEND');

						if($registrationEnable){

							// La registrazione è ammessa quindi posso procedere

							$data = array();
							$data['email'] = $user['email'];
							$data['username'] = $user['email'];
							$data['nome'] = $user['givenName'];
							$data['cognome'] = $user['familyName'];
							//$data['social_id'] = $user['id'];
							//$data['avatar'] = $user['picture'];
							//$data['link'] = $user['link'];
							//$data['uuid'] = Text::uuid();

							$data['role'] = 'user';
							$data['level'] = 0;
							$data['auth_email'] = 1;
							$data['password'] ="fromgoogleapioauth2";

							$entity = $this->Users->newEntity($data);
							if ($this->Users->save($entity)) {
								
								$data['id'] = $entity->id;
								// Salviamo l'immagine dell'avatar per l'utente
								$imgPath = WWW_ROOT . 'img' . DS . 'user' . DS;
								if(!file_exists($imgPath)){
									mkdir($imgPath, 0777);
								}
								$img = file_get_contents($user['picture']);
								file_put_contents($imgPath . $data['id'] . '.jpg', $img);

								// dichiariamo autorizzato l'utente su cakephp
								$this->Auth->setUser($data);
								$this->redirect('/');
							} else {
								$this->Flash->error('Errore di connessione');
								$this->redirect(['controller' => 'Home',  'action' => 'login']);
							}
						
						}else{

							//La registrazione non è ammessa, lo notifico
							$this->Flash->error('L\'utente non esiste ancora sul sistema e non può essere creato automaticamente, si prega di contattare il gestore.' );
							$this->redirect(['controller' => 'Home',  'action' => 'login']);

						}
					}
				} else {
					$this->Flash->error('Erorre, le informazioni passate da Google non sono state trovate');
					$this->redirect(['controller' => 'Home',  'action' => 'login']);
				}
			} catch (\Exception $e) {
				$this->Flash->error('Errore su Google');
				return $this->redirect(['controller' => 'Home', 'action' => 'login']);
			}
		}else{
			$this->Flash->error('Errore di autenticazione, si prega di riprovare.');
			return $this->redirect(['controller' => 'Home', 'action' => 'login']);
		}
	}


}
