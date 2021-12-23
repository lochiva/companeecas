<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Log\Log;

class GoogleComponent extends Component
{
  /**
   * List of components to include.
   * @var array
   */
   public $components = array('Auth');

   /**
    * Google client object
    * @var Google_Client
    */
   protected $client;

   /**
    * Google calendar service object
    * @var Google_Service_Calendar
    */
   protected $calendarService;

   /**
    * [initialize description]
    * @param  array  $config [description]
    * @return [type]         [description]
    */
   public function initialize(array $config)
   {
      $this->client = new \Google_Client();
      $this->client->setApplicationName(Configure::read('dbconfig.generico.APP_NAME'));
      $this->client->setScopes(implode(' ', array(\Google_Service_Calendar::CALENDAR)));
      $this->client->setAuthConfig(ROOT.DS.'config'.DS.'client_secret.json');
      $this->client->setAccessType('offline');

   }

   /**
    * [client description]
    * @return [type] [description]
    */
   public function client()
   {
     return $this->client;
   }

   /**
    * generate google access token
    *
    * @param string $authCode google auth code
    *
    * @return array|bool false on error or array of results
    */
   public function generateGoogleToken($authCode)
   {
       try {
           $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
           $this->client->setAccessToken($accessToken);
       } catch (\Exception $e) {
           Log::error($e->getMessage(),'google');
           return false;
       }

       return array('googleAccessToken' => json_encode($accessToken));
   }

   /**
    * [accessUser description]
    * @return [type] [description]
    */
   public function accessUser(array $userData = array())
   {
     if(empty($userData)){
       $user =  $this->Auth->user();
     }else{
       $user = $userData;
     }

     if(empty($user['googleAccessToken'])){
       throw new \Exception("Il token d'accesso non è presente, assicurati di averlo inserito nel profilo.");
       return false;
     }

     $token = json_decode($user['googleAccessToken'],true);
     $refreshToken = $token['refresh_token'];

     $this->client->setAccessToken($token);

     if ($this->client->isAccessTokenExpired()) {
         if(empty($refreshToken)){
           throw new \Exception("Il token d'accesso è scaduto e non è possibile rigenerarlo, devi reinserirlo nel profilo.");
           return false;
         }
         $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
         $user['googleAccessToken'] = json_encode($this->client->getAccessToken());

         $userTable = TableRegistry::get('Registration.Users');
         $userTable->validator()->remove('password');
         $user = $userTable->patchEntity($userTable->get($user['id']), (array)$user);
         $user = $userTable->save($user);
         if($user && empty($userData)){
           $this->Auth->setUser($user->toArray());
         }

     }

     return true;

   }

  /**
   * [calendarService description]
   * @return [type] [description]
   */
   public function calendarService()
   {
      if(empty($this->calendarService)){
        $this->calendarService = new \Google_Service_Calendar($this->client);
      }
      return $this->calendarService;
   }

}
