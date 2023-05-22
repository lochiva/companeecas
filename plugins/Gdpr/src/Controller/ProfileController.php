<?php
/**
* Gdpr is a plugin for manage attachment
*
* Companee :    Profile  (https://www.companee.it)
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
namespace Gdpr\Controller;

use Gdpr\Controller\AppController;
use Cake\ORM\TableRegistry;
use Cake\Event\Event;

/**
 * Profile Controller
 */
class profileController extends AppController
{

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->Auth->allow(['check', 'checkSuccess']);

    }

    public function check($token)
    {
        $this->viewBuilder()->layout('Gdpr.default');

        $contactTokens = TableRegistry::get('Gdpr.GdprContactToken');
        $now = date('Y-m-d H:i:s');
        $res = $contactTokens->find()->where(['token' => $token, 'used' => 0, 'DATE_ADD(created, INTERVAL 1 DAY) >' => $now])->first();
        if($res){
            //setto il token come usato  //commentato per facilità dev
            $entity = $contactTokens->get($res['id']);
            $entity->used = 1;
            $contactTokens->save($entity);

            //prendo i dati per quell'email
            $contacts = TableRegistry::get('Aziende.Contatti');
            $contact = $contacts->find()->where(['email' => $res['email']])->first();

            $this->set(['contact' => $contact]);
        }

        $ruoli = TableRegistry::get('Aziende.ContattiRuoli')->find('all')->order(['ordering'=>'ASC'])->toArray();

        $this->set('ruoli', $ruoli);
    }

    public function checkSuccess()
    {

    }

}
