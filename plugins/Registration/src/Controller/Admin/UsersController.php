<?php
namespace Registration\Controller\Admin;

use Registration\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Http\Client;
use Cake\ORM\TableRegistry;

class UsersController extends AppController
{

    public function isAuthorized($user)
    {
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {
            return true;
        }

        // Default deny
        return false;
    }

    public function index()
    {
        $role = $this->request->session()->read('Auth.User.role');
        $level = $this->request->session()->read('Auth.User.level');

        if($role == 'admin'){
            $users = $this->Users->find()
                ->where([
                    'OR' => [
                        ['role' => 'ente'],
                        'AND' => [
                            'role' => 'admin',
                            'level <=' => $level
                        ]
                    ]
                ])
                ->toArray();
        }elseif($role == 'ente'){
            $users = $this->Users->find()
            ->where(['role' => 'user', 'level <=' => $level])
            ->toArray();
        }

        $this->set('users', $users);
    }

    public function delete($id = 0)
    {

        if($id != 0){

            $this->request->allowMethod(['get','post', 'delete']);

            $user = $this->Users->get($id);
            if ($this->Users->delete($user)) {
                $this->Flash->success(__('L\'utente id: {0} è stato correttamente cancellato.', h($id)));
                return $this->redirect(['action' => 'index']);
            }

        }else{
            $this->Flash->error(__('Id utente non valido, si prega di riprovare.'));
            return $this->redirect(['action' => 'index']);
        }

    }

    public function add()
    {
        $user = $this->Users->newEntity();
        $users = $this->Users->find('list',['valueField' => 'cognome'])->where(['cognome !=' => ""])->order('cognome')->toArray();
        //echo "<pre>"; print_r($users); echo "</pre>"; exit;

        if ($this->request->is('post')) {


            $authEmail = Configure::read('dbconfig.registration.AUTH_EMAIL');

            if($authEmail && $this->request->data['auth_email'] == 0){

                $authCode = uniqid();
                $this->request->data['auth_code'] = $authCode;

                $sendMail = true;

            }else{
                //Non è necessaria l'autenticazione della mail
                $sendMail = false;
            }

            //echo "<pre>"; print_r($this->request->data); echo "</pre>"; exit;

            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {

                //Verifico se devo inviare la mail di autenticazione
                if($sendMail){
                    //Devo inviare la mail di autenticazione
                    $this->sendMailAuthEmail($this->request->data['email'],$authCode);
                    $this->Flash->success(__('Utente creato correttamente, è stata inviata la mail per l\'autenticazione della mail.'));
                }else{
                    //Utente creato senza autenticazione reindirizzo
                    $this->Flash->success(__('Utente creato correttamente.'));
                }

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Impossibile creare l\'utente, si prega di riprovare.'));
        }
        $this->set('user', $user);
        $this->set('users', $users);
    }

    private function sendMailAuthEmail($email = "", $authCode = ""){

        if($email != ""){

            //Posso inviare la mail
            $from = Configure::read('dbconfig.registration.SENDER_EMAIL');
            $fromAlias = Configure::read('dbconfig.registration.SENDER_ALIAS');

            //echo "Invio la mail all'utente: " . $this->request->data['email'] . " con il code: " . $recoveryCode . "<br/>";
            //echo '<a href="' . Router::url('/registration/users/newPassword/' . $recoveryCode) . '">link</a>';
            $this->email
                ->template('Registration.auth_email','default')
                ->emailFormat('html')
                ->from([$from => $fromAlias])
                ->to($email)
                ->subject('Conferma email utenza')
                ->viewVars(['authCode' => $authCode])
                ->send();


        }

    }

    public function edit($id = 0)
    {

        if($id != 0){
            $user = $this->Users->get($id,['contain'=>'Groups']);
            $users = $this->Users->find('list',['valueField' => 'cognome'])->where(['cognome !=' => ""])->order('cognome')->toArray();

            if ($this->request->is(['post', 'put'])) {

                if($this->request->data['password'] == ""){
                    unset($this->request->data['password']);
                }

                $user = $this->Users->patchEntity($user, $this->request->data);
                if ($this->Users->save($user)) {
                    $this->Flash->success(__('Utente modificato correttamente.'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('Impossibile modificare l\'utente, si prega di riprovare.'));
            }
            $this->set('users', $users);
            $this->set(compact('user'));
        }else{
            $this->Flash->error(__('Id utente non valido, si prega di riprovare.'));
            return $this->redirect('/admin/users');
        }

    }

    public function getTimetaskAnag()
    {
        $this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');

        $email = $this->request->data['email'];
        $id = $this->request->data['id'];

        $users = TableRegistry::get('Users');
        $user = $users->get($id);
        $timetaskToken = $user->timetask_token;

        $http = new Client();

        $url = 'https://api.myintervals.com/person/?email='.$email;

        $response = $http->get(
            $url, 
            [], 
            [
                'headers' => ['Authorization' => 'Basic '.base64_encode($timetaskToken.':X')],
                'type' => 'json'
            ]
        );

        $res = json_decode($response->body);

        if($res && $res->code == 200){
            if(!empty($res->person)){
                $user->anagrafica_timetask = json_encode($res->person[0]);
                $users->save($user);

                $result = array('response' => 'OK', 'msg' => "Anagrafica timetask recuperata con successo.");             
            }else{
                $result = array('response' => 'KO', 'msg' => "Nessuna anagrafica trovata per questo indirizzo email.");
            }   
        }else{
            $result = array('response' => 'KO', 'msg' => "Errore nella chiamata a timetask.");
        }        

        $this->set('result', json_encode($result));
    }

    public function changeUser($idUser){

        $user = $this->Users->get($idUser)->toArray();

        if($user){
            $this->Auth->setUser($user);
        }else{
            $this->Flash->error(__('Impossibile prendere l\'identità dell\'utente ('. $idUser .'), id non valido, si prega di riprovare.'));
        }
        
        return $this->redirect('/'); 

    }

}
