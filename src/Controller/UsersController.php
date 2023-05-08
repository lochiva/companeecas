<?php
namespace App\Controller;
/** 
* Companee :    Users (https://www.companee.it)
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

use App\Controller\AppController;
use Cake\Event\Event;

class UsersController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        //$this->Auth->allow(['login', 'logout']);
    }

    public function login()
    {
        $this->viewBuilder()->layout('login');
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {

                if($user['auth_email'] == 1){
                    $this->Auth->setUser($user);
                    //return $this->redirect($this->Auth->redirectUrl());
                    // storm deciso con marco di portarlo sempre in home se sei admin o programmazione se sei utente, in questo modo si evitano redirect a pagine rotte

                        return $this->redirect('/');

                }else{
                    $this->Flash->error(__('L\'utenza risulta ancora da confermare, si prega di seguire la procedura di autenticazione ricevuta via email.'));
                }

            }else{
                $this->Flash->error(__('Username o password non valide, si prega di riprovare'));
            }

        }
    }

    public function logout()
    {
        $this->request->session()->destroy();
        $this->Auth->logout();
        return $this->redirect(['action' => 'login']);
    }

     public function index()
     {
        $this->set('users', $this->Users->find('all'));
    }

    public function view($id)
    {
        $user = $this->Users->get($id);
        $this->set(compact('user'));
    }

}
