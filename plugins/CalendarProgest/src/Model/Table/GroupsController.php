<?php
namespace App\Controller\Admin;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class GroupsController extends AppController
{

    public function isAuthorized($group)
    {
        // Admin can access every action
        if (isset($group['role']) && $group['role'] === 'admin') {
            return true;
        }

        // Default deny
        return false;
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        // Allow Groups to register and logout.
        // You should not add the "login" action to allow list. Doing so would
        // cause problems with normal functioning of AuthComponent.
        $this->Auth->allow(['logout']);
    }

    public function logout()
    {
        return $this->redirect($this->Auth->logout());
    }

    public function index()
    {
        $this->set('groups', $this->Groups->find('all'));
    }

    public function view($id)
    {
        $group = $this->Groups->get($id);
        $this->set(compact('group'));
    }

    public function add()
    {
        $group = $this->Groups->newEntity();
        if ($this->request->is('post')) {


            $group = $this->Groups->patchEntity($group, $this->request->data);
            if ($this->Groups->save($group)) {
                $this->Flash->success(__('Gruppo creato correttamente.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Impossibile creare il gruppo, si prega di riprovare.'));
        }
        $this->set('group', $group);
    }

    public function edit($id = 0)
    {

        if($id != 0){
            $group = $this->Groups->get($id,['contain'=>'Users']);
            $this->set('users',TableRegistry::get('Users')->find('all'));
            if ($this->request->is(['post', 'put'])) {


                $group = $this->Groups->patchEntity($group, $this->request->data);
                if ($this->Groups->save($group)) {
                    $this->Flash->success(__('Gruppo modificato correttamente.'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('Impossibile modificare il gruppo, si prega di riprovare.'));
            }

            $this->set(compact('group'));
        }else{
            $this->Flash->error(__('Id gruppo non valido, si prega di riprovare.'));
            return $this->redirect('/admin/groups');
        }

    }

    public function delete($id = 0)
    {

        if($id != 0){

            $this->request->allowMethod(['get','post', 'delete']);

            $group = $this->Groups->get($id);
            if ($this->Groups->delete($group)) {
                $this->Flash->success(__('Il gruppo id: {0} è stato correttamente cancellato.', h($id)));
                return $this->redirect(['action' => 'index']);
            }

        }else{
            $this->Flash->error(__('Id gruppo non valido, si prega di riprovare.'));
            return $this->redirect('/admin/groups');
        }

    }

    public function addUserToGoup()
    {
        $UsersGroups = TableRegistry::get('UsersToGroups');
        $userGroup = $UsersGroups->newEntity();
        if ($this->request->is('post')) {


            $userGroup = $UsersGroups->patchEntity($userGroup, $this->request->data);
            if ($UsersGroups->save($userGroup)) {
                $this->Flash->success(__('Utente aggiunto correttamente.'));
                return $this->redirect($this->referer());
            }
            $this->Flash->error(__('Impossibile aggiungere l\'utente, controllare che non sia già presente.'));
        }
        $this->redirect($this->referer());
        //$this->set('group', $userGroup);
    }

    public function deleteUserToGoup($id)
    {
      if($id != 0){

          $UsersGroups = TableRegistry::get('UsersToGroups');
          $this->request->allowMethod(['get','post', 'delete']);

          $userGroup = $UsersGroups->get($id);
          if ($UsersGroups->delete($userGroup)) {
              $this->Flash->success(__('L\'utente è stato rimosso fal gruppo.'));
              return $this->redirect($this->referer());
          }

      }else{
          $this->Flash->error(__('Id oggetto non valido, si prega di riprovare.'));
          return $this->redirect($this->referer());
      }

    }

}
