<?php
namespace App\Controller\Admin;
################################################################################
#
# Companee :  Configurations (https://www.companee.it)
# Copyright (c) lochiva ,(http://www.lochiva.it)
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
use Cake\I18n\Time;

class ConfigurationsController extends AppController
{

    public function isAuthorized($user)
    {
        // Admin can access every action
        if (isset($user['role']) && $user['role'] === 'admin') {

            //Per aggiungere o cancellare configurazioni devi avere un livello superiore al 900
            if ($user['level'] < 900 && ($this->request->action == 'add' || $this->request->action == 'delete')) {
                return false;
            }

            return true;
        }

        // Default deny
        return false;
    }

    public function index()
    {

        $user = $this->request->session()->read('Auth.User');
        $configTypes = $this->Configurations->getConfigTypes($user['level']);
        $configs = $this->Configurations->getConfigPerType($configTypes,$user['level']);
        $this->set('configurations', $this->Configurations->find('all')->where(['level <=' => $user['level']]));
        $this->set('configTypes',$configTypes);
        $this->set('configs',$configs);
    }

    public function add()
    {
        $configuration = $this->Configurations->newEntity();
        if ($this->request->is('post')) {

            $this->request->data['key_conf'] = strtoupper($this->request->data['key_conf']);

            $configuration = $this->Configurations->patchEntity($configuration, $this->request->data);
            if ($this->Configurations->save($configuration)) {
                $this->Flash->success(__('Configurazione creata correttamente.'));
                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('Impossibile creare la configurazione, si prega di riprovare.'));
        }
        $this->set('configuration', $configuration);
    }

    public function delete($id = 0)
    {

        if($id != 0){

            $this->request->allowMethod(['get','post', 'delete']);

            $configuration = $this->Configurations->get($id);
            if ($this->Configurations->delete($configuration)) {
                $this->Flash->success(__('La configurazione id: {0} è stata correttamente cancellata.', h($id)));
                return $this->redirect(['action' => 'index']);
            }

        }else{
            $this->Flash->error(__('Id Configurazione non valido, si prega di riprovare.'));
            return $this->redirect(['action' => 'index']);
        }

    }

    public function edit($id = 0)
    {

        if($id != 0){
            $configuration = $this->Configurations->get($id);

            if ($this->request->is(['post', 'put'])) {
                $data = $this->request->data;

                if(isset($data['value']['year']) ){
                  $data['value'] = $data['value']['year'] .'-'.$data['value']['month'].'-'.$data['value']['day'];
                }

                $data['key_conf'] = strtoupper($data['key_conf']);

                $configuration = $this->Configurations->patchEntity($configuration, $data);
                if ($this->Configurations->save($configuration)) {
                    $this->Flash->success(__('Configurazione modificata correttamente.'));
                    return $this->redirect(['action' => 'index']);
                }
                $this->Flash->error(__('Impossibile modificare l\'utente, si prega di riprovare.'));
            }

            $this->set(compact('configuration'));
        }else{
            $this->Flash->error(__('Id Configurazione non valido, si prega di riprovare.'));
            return $this->redirect(['action' => 'index']);
        }

    }

}
