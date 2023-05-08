<?php
namespace App\Controller\Admin;
################################################################################
#
# Companee :   Users (https://www.companee.it)
# Copyright (c) lochiva , (http://www.lochiva.it)
#
# Licensed under The GPL  License
# For full copyright and license information, please see the LICENSE.txt
# Redistributions of files must retain the above copyright notice.
#
# @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
# @link          https://www.companee.it Companee project
# @since         1.2.0
# @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
#
################################################################################

use App\Controller\AppController;
use Cake\Event\Event;

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
    
    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow users to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['logout']);
    }
    
    public function login()
    {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                
                if($user['auth_email'] == 1){
                    $this->Auth->setUser($user);
                    return $this->redirect($this->Auth->redirectUrl());
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
        return $this->redirect($this->Auth->logout());
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

    public function add()
    {
        $user = $this->Users->newEntity();
        if ($this->request->is('post')) {
            
            $this->request->data['auth_email'] = 1;
            
            $user = $this->Users->patchEntity($user, $this->request->data);
            if ($this->Users->save($user)) {
                $this->Flash->success(__('Utente creato correttamente.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Impossibile creare l\'utente, si prega di riprovare.'));
        }
        $this->set('user', $user);
    }
    
    public function edit($id = 0)
    {
        
        if($id != 0){
            $user = $this->Users->get($id);
            
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
            
            $this->set(compact('user'));
        }else{
            $this->Flash->error(__('Id utente non valido, si prega di riprovare.'));
            return $this->redirect('/admin/users');
        }
        
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
            return $this->redirect('/admin/users');
        }
        
    }
    
}