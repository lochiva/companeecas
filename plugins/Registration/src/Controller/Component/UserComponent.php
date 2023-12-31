<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    User  (https://www.companee.it)
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
namespace Registration\Controller\Component;

use Cake\Controller\Component;
use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

class UserComponent extends Component
{
    public function getUserViewData($id,$limitNotice = 10)
    {
        $timeline = TableRegistry::get('ActionLog')->getHistoryGeneral(10,$id);
        $tot = array(
			'actions' => TableRegistry::get('ActionLog')->find()->where(['id_user' => $id])->count(),
			//'tasks' => TableRegistry::get('Calendar.Eventi')->find()->where(['id_user' => $id])->count(),
			'accessi' => TableRegistry::get('AccessLog')->find()->where(['id_user' => $id])->count(),
        );
        $notifications = array();
        /*$notice = TableRegistry::get('Notifications')->getUserNewNotice($id);
        foreach ($notice as  $value) {
          	$notifications[$value['id']] = $value;
        }
        if(count($notice) < $limitNotice){
			$notice = TableRegistry::get('Notifications')->getNotifications($id,$limitNotice-count($notice));
			foreach ($notice as  $value) {
				$notifications[$value['id']] = $value;
			}
        }*/
        $notifications = TableRegistry::get('Notifications')->getNotifications($id,$limitNotice);


        return array('timeline' => $timeline, 'tot' =>$tot, 'notifications' => $notifications);
    }

    public function getDataForExport()
    {
		$usersTable = TableRegistry::get('Registration.Users');
		$role = $this->request->session()->read('Auth.User.role');
		$level = $this->request->session()->read('Auth.User.level');
		$registrationType = Configure::read('dbconfig.registration.REGISTRATION_TYPE');

		$users = $usersTable->find()
			->where([
				'OR' => [
					['role IN' => ['area_iv', 'ragioneria', 'questura', 'ente_ospiti', 'ente_contabile']],
					'AND' => [
						'role' => 'admin',
						'level <=' => $level
					]
				]
			])
			->toArray();

		if ($registrationType == '1') {
			$data[0] = ['Username', 'Email', 'Nome', 'Cognome', 'Ruolo', 'Livello', 'Autenticato'];
		} else {
			$data[0] = ['Username', 'Email', 'Ruolo', 'Livello', 'Autenticato'];
		}

		foreach ($users as $user) {
			if ($registrationType == '1') {
				$data[] = [
					$user->username,
					$user->email,
					$user->nome,
					$user->cognome,
					$user->role,
					$user->level ? $user->level : '0',
					$user->auth_email ? "Si" : "No"
				];
			} else {
				$data[] = [
					$user->username,
					$user->email,
					$user->role,
					$user->level ? $user->level : '0',
					$user->auth_email ? "Si" : "No"
				];
			}
		}

		return $data;
    }
}
