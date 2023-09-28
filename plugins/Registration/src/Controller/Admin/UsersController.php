<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    Users   (https://www.companee.it)
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
namespace Registration\Controller\Admin;

use Registration\Controller\AppController;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\Routing\Router;
use Cake\Http\Client;
use Cake\ORM\TableRegistry;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

class UsersController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->loadComponent('Registration.User');
    }

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

        $users = $this->Users->find()
            ->where([
                'OR' => [
                    ['role IN' => ['area_iv','ro_area-iv','ragioneria', 'ente_ospiti', 'questura', 'ente_contabile']],
                    'AND' => [
                        'role' => 'admin',
                        'level <=' => $level
                    ]
                ]
            ])
            ->toArray();

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
            $msg = 'Impossibile creare l\'utente. ';
            foreach ($user->errors() as $field => $error) {
                foreach ($error as $type => $message) {
                    $msg .= $message.". ";
                }
            }
            $msg .= 'Si prega di riprovare.';
            $this->Flash->error(__($msg));
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
                $msg = 'Impossibile modificare l\'utente. ';
                foreach ($user->errors() as $field => $error) {
                    foreach ($error as $type => $message) {
                        $msg .= $message.". ";
                    }
                }
                $msg .= 'Si prega di riprovare.';
                $this->Flash->error(__($msg));
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

    public function export()
    {
		$spreadsheet = new Spreadsheet();

        $users = $this->User->getDataForExport();
		
		//ultima colonna
		$c = 'A';
		for($i = 1; $i < count($users[0]); $i++){
			++$c;
		}

		//filtri riga intestazione
        $spreadsheet->getActiveSheet()->setAutoFilter('A1:'.$c.'1');

		//grassetto riga intestazione
		$spreadsheet->getActiveSheet()->getStyle('A1:'.$c.'1')
			->getFont()->setBold(true);


		//dimensione automatica delle celle
		$i = 'A';
		foreach($users[0] as $col){
			$spreadsheet->getActiveSheet()->getColumnDimension($i)->setAutoSize(true);
			++$i;
		}

		$spreadsheet->getActiveSheet()->fromArray($users, NULL);
		
		$spreadsheet->getActiveSheet()->freezePane('A2');

		$spreadsheet->setActiveSheetIndex(0);

        $filename = "Utenti";

		setcookie('downloadStarted', '1', false, '/');

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
		header('Cache-Control: max-age=0');

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');

		$writer->save('php://output');

		exit;
    }

}
