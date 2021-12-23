<?php
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
