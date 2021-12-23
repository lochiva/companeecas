<?php

namespace Gdpr\Controller;

use Gdpr\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Routing\Router;
use Cake\Network\Email\Email;

class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();

        //$this->loadComponent('');

    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);

        $this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');
        $this->_result = ['response' => 'KO', 'data' => null, 'msg' => null];

        $this->Auth->allow(['verifyEmail', 'getLuoghiContatto', 'saveContact', 'sendPrivacyText']);

    }

    public function beforeRender(Event $event) {
        parent::beforeFilter($event);
        $this->set('result', json_encode($this->_result));
    }

    public function verifyEmail()
    {
        $emailAddress = $this->request->data['email'];

        $contatti = TableRegistry::get('Aziende.Contatti');

        $res = $contatti->find()->where(['email' => $emailAddress])->toArray();

        if(count($res) == 1){

            $token = md5(uniqid(rand(), true));

            $contactTokens = Tableregistry::get('Gdpr.GdprContactToken');

            $entity = $contactTokens->newEntity();
            $entity->token = $token;
            $entity->email = $emailAddress;
            $entity->used = '0';

            $res = $contactTokens->save($entity);

            if($res){
                //invio email con link
                $sender = Configure::read('dbconfig.registration.SENDER_EMAIL');

                $url = Router::url([
                    'plugin' => 'gdpr',
                    'controller' => 'profile',
                    'action' => 'check',
                    $token,
                    '_full' => true,
                    '_ssl' => Configure::read('localconfig.HttpsEnabled')
                ]);
                
                $this->email->template('Gdpr.verify_data');
                $this->email->emailFormat('html');
                $this->email->from([$sender]);
                $this->email->to($emailAddress);
                $this->email->subject('Verifica dei dati in '.Configure::read('dbconfig.generico.APP_NAME'));
                $this->email->viewVars(['url' => $url]);
         
                if($this->email->send()){
                    $this->_result = ['response' => 'OK', 'data' => null, 'msg' => 'Abbiamo cercato nel sistema la mail inserita. Se la mail è stata trovata, abbiamo inviato la mail per verificare i dati.'];
                }else{
                    $this->_result = ['response' => 'KO', 'data' => null, 'msg' => 'Si è verificato un errore nell\'invio della mail.'];
                }                
            }else{
                $this->_result = ['response' => 'KO', 'data' => null, 'msg' => 'Si è verificato un errore nell\'invio della mail.'];
            }

            $this->_result = ['response' => 'OK', 'data' => null, 'msg' => 'Abbiamo cercato nel sistema la mail inserita. Se la mail è stata trovata, abbiamo inviato la mail per verificare i dati.'];
        }elseif(count($res) < 1){

            $this->_result = ['response' => 'OK', 'data' => null, 'msg' => 'Abbiamo cercato nel sistema la mail inserita. Se la mail è stata trovata, abbiamo inviato la mail per verificare i dati.'];
        }elseif(count($res > 1)){

            $this->_result = ['response' => 'OK', 'data' => null, 'msg' => 'Abbiamo cercato nel sistema la mail inserita. Se la mail è stata trovata, abbiamo inviato la mail per verificare i dati.'];
        }

    }

    public function saveContact()
    {
        $data = $this->request->data;

        $contatti = TableRegistry::get('Aziende.Contatti');
        $contatto = $contatti->find()->where(['email' => $data['email']])->first();

        isset($data['read_privacy']) ? $data['read_privacy'] = 1 : $data['read_privacy'] = 0;

        isset($data['accepted_privacy']) ? $data['accepted_privacy'] = 1 : $data['accepted_privacy'] = 0;

        isset($data['marketing_privacy']) ? $data['marketing_privacy'] = 1 : $data['marketing_privacy'] = 0;

        isset($data['third_party_privacy']) ? $data['third_party_privacy'] = 1 : $data['third_party_privacy'] = 0;

        isset($data['profiling_privacy']) ? $data['profiling_privacy'] = 1 : $data['profiling_privacy'] = 0;

        isset($data['spread_privacy']) ? $data['spread_privacy'] = 1 : $data['spread_privacy'] = 0;

        isset($data['notify_privacy']) ? $data['notify_privacy'] = 1 : $data['notify_privacy'] = 0;

        $contatti->patchEntity($contatto, $data);
        $res = $contatti->save($contatto);

        if($res){
            //invio email con riepilogo dati
            $ruoli = TableRegistry::get('Aziende.ContattiRuoli');
            $ruolo = $ruoli->find()->where(['id' => $data['id_ruolo']])->first();
            $data['ruolo'] = $ruolo['ruolo'];
            
            $sender = Configure::read('dbconfig.registration.SENDER_EMAIL');
            
            $this->email->template('Gdpr.contact_data');
            $this->email->emailFormat('html');
            $this->email->from([$sender]);
            $this->email->to($data['email']);
            $this->email->subject('Riepilogo dati contatto '.$data['nome'].' '.$data['cognome'].' in '.Configure::read('dbconfig.generico.APP_NAME'));
            $this->email->viewVars(['data' => $data]);
     
            if($this->email->send()){
                $this->_result = ['response' => 'OK', 'data' => null, 'msg' => 'Dati salvati correttamente e invio mail riepilogativa riuscito.'];
            }else{
                $this->_result = ['response' => 'KO', 'data' => null, 'msg' => 'Errore nell\'invio della mail riepilogativa.'];
            }                
        }else{
            $this->_result = ['response' => 'KO', 'data' => null, 'msg' => 'Errore nel salvataggio dei dati del contatto.'];
        }
    }

    public function getLuoghiContatto()
    {
        $email = $this->request->data['email'];

        $contatti = TableRegistry::get('Aziende.Contatti');

        $res = $contatti->find()->select(['provincia', 'comune', 'cap'])->where(['email' => $email])->first();

        if($res){
            $this->_result = ['response' => 'OK', 'data' => $res, 'msg' => ''];
        }
    }

    public function sendPrivacyText()
    {
        $email = $this->request->data['email'];
        $text = $this->request->data['privacyText'];

        $sender = Configure::read('dbconfig.registration.SENDER_EMAIL');

        $this->email->template('Gdpr.privacy_policy');
        $this->email->emailFormat('html');
        $this->email->from([$sender]);
        $this->email->to($email);
        $this->email->subject('Invio informativa privacy '.Configure::read('dbconfig.generico.APP_NAME'));
        $this->email->viewVars(['text' => $text]);

        if($this->email->send()){
            $this->_result = ['response' => 'OK', 'data' => null, 'msg' => 'Informativa privacy inviata all\'indirizzo '.$email.'.'];
        }else{
            $this->_result = ['response' => 'KO', 'data' => null, 'msg' => 'Si è verificato un errore nell\'invio della mail.'];
        }       
    }
}