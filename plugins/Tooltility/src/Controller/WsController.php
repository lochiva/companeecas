<?php
namespace Tooltility\Controller;

use Tooltility\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

/**
 * Tooltility Ws Controller
 */
class WsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
		$this->loadComponent('Tooltility.Authorization');
		$this->loadComponent('Tooltility.EmailVerification');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        
        $this->Auth->allow();

        $this->log("Request URL: ".$this->request->url, 'info', ['scope' => ['tooltility']]);
        $this->log("Client IP: ".$this->request->clientIp(), 'info', ['scope' => ['tooltility']]);

		//verifica ip
		if(!$this->Authorization->verifyIp()){

            $this->log($this->request->action.": autorizzazione negata.".PHP_EOL, 'error', ['scope' => ['tooltility']]);

			$this->response->statusCode(403);

			return $this->response;
		}

		$this->viewBuilder()->layout('ajax');
        $this->viewBuilder()->templatePath('Async');
        $this->viewBuilder()->template('default');
		$this->_result = array('response' => 'KO', 'data' => -1, 'msg' => "Errore");

    }

    public function beforeRender(Event $event) {
        parent::beforeFilter($event);
        $this->set('result', json_encode($this->_result));
    }

	public function emailExists($email)
    { 
        $this->log("emailExists: verifica dell'email ".$email.".", 'info', ['scope' => ['tooltility']]);

        // Check if email is valid and exists
        switch($this->EmailVerification->emailExists($email)){ 
            case 3:
                $this->log("emailExists: email ".$email." esiste.".PHP_EOL, 'info', ['scope' => ['tooltility']]);
                $this->_result = array('response' => 'OK', 'msg' => "Email ".$email." esiste.");
                break;
            case 2:
                $this->log("emailExists: il dominio dell'email ".$email." non è abilitato a ricevere posta.".PHP_EOL, 'info', ['scope' => ['tooltility']]);
                $this->_result = array('response' => 'KO', 'msg' => "Il dominio dell'email ".$email." non è abilitato a ricevere posta.");
                break;
            case 1:
                $this->log("emailExists: il dominio dell'email ".$email." non esiste.".PHP_EOL, 'info', ['scope' => ['tooltility']]);
                $this->_result = array('response' => 'KO', 'msg' => "Il dominio dell'email ".$email." non esiste.");
                break;
            case 0:
                $this->log("emailExists: email ".$email." non esiste.".PHP_EOL, 'info', ['scope' => ['tooltility']]);
                $this->_result = array('response' => 'KO', 'msg' => "Email ".$email." non esiste.");
                break;
            case -1:
                $this->log("emailExists: impossibile verificare l'esistenza dell'email ".$email.".".PHP_EOL, 'error', ['scope' => ['tooltility']]);
                $this->_result = array('response' => 'UNKNOWN', 'msg' => "Impossibile verificare l'esistenza dell'email ".$email.".");
                break;
        } 
        
    }

    public function getTextFromHtml()
    {
        $url = $this->request->query['url'];

        $content = file_get_contents($url);

        $content = str_replace("\n", '', $content);
        $content = preg_replace('@<script[^>]*?>.*?</script>@si', '', $content); 
        $content = preg_replace('@<style[^>]*?>.*?</style>@si', '', $content); 
        $content = str_replace('<br>', "\n", $content);
        $content = str_replace('<br/>', "\n", $content);
        $content = str_replace('<br />', "\n", $content);
        $content = strip_tags($content); 
        $content = trim($content); 
        $content = preg_replace('/[ ]{2,}/', ' ', $content);
        $content = preg_replace('/[\t]{2,}/', "\n", $content);

        $folderPath = ROOT . DS . 'files';

        if(!is_dir($folderPath)){
			mkdir($folderPath, 0775, true);
        }
        
        $filePath = $folderPath.DS."text_from_html_".time().".txt";

        $file = fopen($filePath, "w");
        
        fwrite($file, $content);

        fclose($file);

        $this->_result = array('response' => 'OK', 'msg' => "Operazione terminata");
    }

}
