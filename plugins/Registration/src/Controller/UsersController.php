<?php

namespace Registration\Controller;

use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\ORM\TableRegistry;

class UsersController extends AppController
{
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        if (Configure::read('dbconfig.registration.REGISTRATION_FRONTEND')) {
            $this->Auth->allow(['register']);
        }
		if(Configure::read('dbconfig.registration.PASSWORD_RECOVERY_FRONTEND')){
			$this->Auth->allow(['recoveryPassword', 'newPassword']);
		}
        $this->loadComponent('Google');
        $this->loadComponent('Registration.User');
        //$this->Auth->allow(['add','recoveryPassword','newPassword','authEmailSended','authEmail']);

        $user = $this->Auth->user();

        if(isset($user['role']) && ($user['role'] == 'ente')){
            $this->Auth->allow(['view', 'unlikGoogle', 'edit']);
        }
    }

    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {

            //La registrazione da Front End crea solo utenti di livello user!!!
            $this->request->data['role'] = 'user';
            $this->request->data['level'] = 0;

            $authEmail = Configure::read('dbconfig.registration.AUTH_EMAIL');

            //Verifico se è richiesta la auth della mail o meno e ne setto il valore nel db
            if ($authEmail) {
                //E' necessaria l'autenticazione trami te procedura quindi per ora la segno come da autenticare
                $this->request->data['auth_email'] = 0;

                $authCode = uniqid();
                $this->request->data['auth_code'] = $authCode;
            } else {
                //Non è necessaria l'autenticazione della password pertanto segno il campo come == 1
                $this->request->data['auth_email'] = 1;
            }

            $user = $this->Users->patchEntity($user, $this->request->data);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('Utente creato correttamente.'));

                //Verifico se devo inviare la mail di autenticazione
                if ($authEmail) {
                    //Devo inviare la mail di autenticazione
                    $this->sendMailAuthEmail($this->request->data['email'], $authCode);

                    return $this->redirect(['action' => 'authEmailSended', $this->request->data['email']]);
                } else {
                    //Utente creato senza autenticazione reindirizzo
                    return $this->redirect('/');
                }
            }
            $errorMsg = '';
            foreach($user->errors() as $field => $errors){ 
				foreach($errors as $rule => $msg){ 
					$errorMsg .= ' '.$msg;
				}
			}  
            $this->Flash->error(__('Impossibile creare l\'utente.'.$errorMsg.' Si prega di riprovare.'));
        }
        $this->set('user', $user);
    }

    public function register()
    {
        $this->viewBuilder()->layout('login');
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            if (empty($this->request->data['username']) || empty($this->request->data['password']) ||
                empty($this->request->data['ck_password'])) {
                $this->Flash->error(__('Impossibile creare l\'utente, si prega di compilare tutti i campi.'));

                return $this->redirect(['action' => 'register']);
            }

            if ($this->request->data['ck_password'] !== $this->request->data['password']) {
                $this->Flash->error(__('Impossibile creare l\'utente, le due password non corrispondono.'));

                return $this->redirect(['action' => 'register']);
            }
            //La registrazione da Front End crea solo utenti di livello user!!!
            $this->request->data['role'] = 'user';
            $this->request->data['level'] = 0;
            $this->request->data['auth_email'] = 1;

            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Utente creato correttamente.'));

                return $this->redirect(['controller' => 'home', 'action' => 'login']);
            }
            $errorMsg = '';
            foreach($user->errors() as $field => $errors){ 
				foreach($errors as $rule => $msg){ 
					$errorMsg .= ' '.$msg;
				}
			}  
            $this->Flash->error(__('Impossibile creare l\'utente.'.$errorMsg.' Si prega di riprovare.'));
        }
        $this->set('user', $user);
    }

    private function sendMailAuthEmail($email = '', $authCode = '')
    {
        if ($email != '') {

            //Posso inviare la mail
            $from = Configure::read('dbconfig.registration.SENDER_EMAIL');
            $fromAlias = Configure::read('dbconfig.registration.SENDER_ALIAS');

            //echo "Invio la mail all'utente: " . $this->request->data['email'] . " con il code: " . $recoveryCode . "<br/>";
            //echo '<a href="' . Router::url('/registration/users/newPassword/' . $recoveryCode) . '">link</a>';
            $this->email
                ->template('Registration.auth_email', 'default')
                ->emailFormat('html')
                ->from([$from => $fromAlias])
                ->to($email)
                ->subject('Conferma email utenza')
                ->viewVars(['authCode' => $authCode])
                ->send();
        }
    }

    public function authEmailSended($email = '')
    {
        $this->set('emailSended', $email);
    }

    public function view($id,$tab = 'timeline')
    {
        if($this->request->session()->read('Auth.User.role') == 'admin' || $this->request->session()->read('Auth.User.id') == $id){
            $user = $this->Users->get($id);
            $authUser = $this->Auth->user();
            $info = $this->User->getUserViewData($id);
            $tabs = array('timeline'=>'','notifications'=>'','modify'=>'');
            if(!isset($tabs[$tab])){
            $tab = 'timeline';
            }
            $tabs[$tab] = 'active';
            //debug($info);die;
            //$timeline = TableRegistry::get('ActionLog')->getHistoryGeneral(10,$id);


            unset($user->password);
            $this->set('tabs', $tabs);
            $this->set('user', $user);
            $this->set('authUser',$authUser);
            $this->set('info',$info);
            $this->set('googleAuthLink', $this->Google->client()->createAuthUrl());
        }else{
            $this->Flash->error('Accesso negato. Non sei autorizzato.');
            $this->redirect('/');
        }
    }

    public function edit()
    {
            $u = $this->request->session()->read('Auth.User');

            $user = $this->Users->get($u['id']);

            unset($user->password);


        if ($this->request->is(['post', 'put'])) {
            $data = $this->request->data;
            // if we have a new password, create key `password` in data
            if (!empty($new_password = $data['new_password'])) {
                $data['password'] = $new_password;
            } else { // else, we remove the rules on password
                $this->Users->validator()->remove('password');
            }
            // Check if there is any uploaded file
            if (!empty($data['inputImage']['name'])) {
                try {
                    $this->_generateSaveUserImage($data['inputImage'], $user['id']);
                } catch (\Exception $e) {
                    $this->Flash->error(__($e->getMessage()));

                    return  $this->redirect($this->referer());
                }
            } elseif (isset($data['deleteImage'])) {
                if ($data['deleteImage']) {
                    if (file_exists(WWW_ROOT.'img'.DS.'user'.DS.$user['id'].'.jpg')) {
                        unlink(WWW_ROOT.'img'.DS.'user'.DS.$user['id'].'.jpg');
                    }
                }
            }

            if (!empty($data['googleAuth'])) {
                $googleTokens = $this->Google->generateGoogleToken($data['googleAuth']);
                if (!$googleTokens) {
                    $this->Flash->error(__('Errore durante il collegamento a google, assicurarsi di aver inserito un codice valido.'));

                    return  $this->redirect($this->referer());
                }
                $data = array_merge($data, $googleTokens);
            }
            /*
            if($this->request->data['email'] == ""){
                unset($this->request->data['email']);
            }
            */
            $user = $this->Users->patchEntity($user, $data);

            if ($this->Users->save($user)) {
                $this->Flash->success(__('Profilo modificato correttamente.'));
                $this->Auth->setUser($user);

                return  $this->redirect($this->referer());
            }
            $errorMsg = '';
            foreach($user->errors() as $field => $errors){ 
				foreach($errors as $rule => $msg){ 
					$errorMsg .= ' '.$msg;
				}
			}  
            $this->Flash->error(__('Impossibile modificare il profilo.'.$errorMsg.' Si prega di riprovare.'));
            return  $this->redirect($this->referer());
        }

        $this->set('googleAuthLink', $this->Google->client()->createAuthUrl());
        $this->set('user', $user);
    }

    public function recoveryPassword()
    {
        $this->viewBuilder()->layout('login');

        $user = $this->Users->newEntity();

        if ($this->request->is('post')) {

            //########################################################################################################
            //Recupero l'utente tramite mail
            $user = $this->Users->find('all')->where(['email' => $this->request->data['email']]);

            $res = $user->first();

            if (!empty($res)) {

                //Controllo se è già stato autenticato il profilo
                if ($res->auth_email == 1) {

                    //########################################################################################################
                    //genero il codice di recovery e lo salvo nel db

                    $recoveryCode = $this->_generatePasswordToken();

                    $res->recovery_code = $recoveryCode;

                    $this->Users->save($res);

                    //########################################################################################################
                    //Posso inviare la mail all'utente

                    $from = Configure::read('dbconfig.registration.SENDER_EMAIL');
                    $fromAlias = Configure::read('dbconfig.registration.SENDER_ALIAS');

                    //echo "Invio la mail all'utente: " . $this->request->data['email'] . " con il code: " . $recoveryCode . "<br/>";
                    //echo '<a href="' . Router::url('/registration/users/newPassword/' . $recoveryCode) . '">link</a>';
                    try {
                        $this->email
                            ->template('Registration.recovery_password', 'default')
                            ->emailFormat('html')
                            ->from([$from => $fromAlias])
                            ->to($res->email)
                            ->subject('Recupera password')
                            ->viewVars(['recoveryCode' => $recoveryCode, 'user' => $res->username])
                            ->send();

                        $this->set('emailSended', $res->email);
                        $this->render('recovery_password_sended');
                    } catch (\Exception $e) {
                        $this->Flash->error(__('Errore durante l\'invio dell\'email, si prega di riprovare.'));
                    }
                } else {
                    $this->Flash->warning(__('Il profilo a cui appartiene la mail inserita risulta ancora da confermare, si prega di completare la procedura di registrazione e conferma prima di procede alla modifica della password.'));
                }
            } else {
                $this->Flash->error(__('La mail inserita non risulta registrata a nessuna utenza, si prega di riprovare.'));
            }
        }

        $this->set('user', $user);
    }

    public function newPassword($recoveryCode = 'xxx')
    {
        $this->viewBuilder()->layout('login');

        $users = $this->Users->find('all')->where(['recovery_code' => $recoveryCode]);
        $user = $users->first();

        if (!empty($user) && !empty($recoveryCode)) {
            $time = substr($recoveryCode, strrpos($recoveryCode, '.') + 1);
            if ($time < time() - (60 * 60 * 24)) {
                $this->Flash->error(__('Codice di recupero scaduto, si prega di ripetere la procedura.'));

                return $this->redirect('/');
            }

            if ($this->request->is(['post', 'put'])) {
                if (empty($this->request->data['password'])) {
                    $this->Flash->error(__('Impossibile modificare la password, inserisci una password valida.'));

                    return $this->redirect($this->referer());
                }

                if ($this->request->data['ck_password'] !== $this->request->data['password']) {
                    $this->Flash->error(__('Impossibile modificare la password,, le due password non corrispondono.'));

                    return $this->redirect($this->referer());
                }

                $user->password = $this->request->data['password'];
                $user->recovery_code = '';

                if ($this->Users->save($user)) {
                    $this->Flash->success(__('Password modifcata con successo.'));

                    return $this->redirect('/');
                } else {
                    $this->Flash->error(__('Impossibile modificare la password, si prega di riprovare.'));
                }
            }

            $this->set('user', $user);
        } else {
            $this->Flash->error(__('Codice di recupero non valido, si prega di ripetere la procedura.'));

            return $this->redirect('/');
        }
    }

    public function authEmail($authCode = 'xxx')
    {
        $users = $this->Users->find('all')->where(['auth_code' => $authCode]);
        $user = $users->first();

        if (!empty($user)) {
            $user->auth_email = 1;
            $user->auth_code = 'auth_'.$user->auth_code;

            if ($this->Users->save($user)) {
                $this->Flash->success(__('Profilo confermato con successo.'));

                return $this->redirect('/');
            } else {
                $this->Flash->error(__('Impossibile autenticare il profilo, si prega di riprovare.'));
            }
        } else {
            $this->Flash->error(__('Codice di autenticazione non valido, si prega di ripetere la procedura o contattare l\'assistenza.'));

            return $this->redirect('/');
        }
    }

    public function unlikGoogle()
    {
        $u = $this->request->session()->read('Auth.User');

        $user = $this->Users->get($u['id']);
        unset($user->password);
        $user->googleAccessToken = '';
        if ($this->Users->save($user)) {
            $this->Flash->success(__('Account google scollegato con successo.'));
        }else{
            $this->Flash->error(__('Errore server.'));
        }
        return  $this->redirect($this->referer());
    }

/****************************************************** FUNZIONI PRIVATE *********************************************************************/

    /**
     * Generate a recovery token and set in the user entity.
     *
     * @param $user entity
     *
     * @return object
     */
    private function _generatePasswordToken()
    {
        $token = uniqid().bin2hex(openssl_random_pseudo_bytes(10));
        $token .= '.'.time();

        return $token;
    }

    /**
     * Generate and save user profile image on server.
     *
     * @param array $file
     * @param int   $id   user id
     *
     * @return bool
     */
    private function _generateSaveUserImage($file, $id)
    {
        $imgPath = WWW_ROOT.'img'.DS.'user'.DS;
        if (!is_array($file)) {
            throw new \Exception('Errore lettura file!');

            return false;
        }
        
        if (!is_dir($imgPath) && !mkdir($imgPath, 0755, true)){
            throw new \Exception('Impossibile salvare l\'immagine in sul server, errore nella creazione della cartella per il salvataggio! si prega di riprovare.');

            return false;
        }

        if (!is_writable($imgPath)) {
            throw new \Exception('Impossibile salvare l\'immagine in sul server, errore di scrittura! si prega di riprovare.');

            return false;
        }
        $ext = substr(strtolower(strrchr($file['name'], '.')), 1);
        $arr_ext = array('jpg', 'jpeg', 'png');
        $src_x = $this->request->data['x'];
        $src_y = $this->request->data['y'];
        $src_w = $this->request->data['width'];
        $src_h = $this->request->data['height'];

      // check if the extension is correct
      if (in_array($ext, $arr_ext) && !empty($src_w) && !empty($src_h)) {
          $fileName = $id.'.jpg';
          if ($ext == 'png') {
              $src = imagecreatefrompng($file['tmp_name']);
          } else {
              $src = imagecreatefromjpeg($file['tmp_name']);
          }
          $dest = imagecreatetruecolor(150, 150);

        //Save the file in the $dest resource with new resolution
        if (!imagecopyresampled($dest, $src, 0, 0, $src_x, $src_y, 150, 150, $src_w, $src_h)) {
            throw new \Exception('Impossibile salvare l\'immagine! Errore durante l\'elaborazione, si prega di riprovare o cambiare immagine.');

            return false;
        }

        // Delete previus image if exists
        if (file_exists($imgPath.$id.'.jpg')) {
            unlink($imgPath.$id.'.jpg');
        }
        //Save the file in the path img/user/ with the user id as name, final image resolution 150*150
        if (!imagejpeg($dest, $imgPath.$fileName)) {
            throw new \Exception('Impossibile salvare l\'immagine in sul server, errore di scrittura! si prega di riprovare.');

            return false;
        }
      } else {
          throw new \Exception('Non è stata selezionata un area dell\'immagine.', 1);

          return false;
      }

        return true;
    }
}
