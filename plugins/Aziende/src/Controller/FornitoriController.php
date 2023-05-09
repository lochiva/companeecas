<?php
/**
* Controller is a plugin for manage attachment
*
* Companee :    Fornitori  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
namespace Aziende\Controller;

use Aziende\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;

/**
 * Fornitori Controller
 *
 * @property \Aziende\Model\Table\AziendeTable $Aziende
 */
class FornitoriController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        //$this->loadComponent('Document.Document');
        $this->loadComponent('Aziende.Fornitori');
        $this->loadComponent('Aziende.Sedi');
        $this->loadComponent('Aziende.Azienda');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['index']);
    }

    public function fatture($id = 0)
    {

        $purposes = $this->Fornitori->getInvoicePurposesTree();
        $paymentConditions = $this->Fornitori->getPaymentConditions();
        $payers = $this->Azienda->getAziendeInterne();
        $metodi = json_decode(Configure::read('dbconfig.aziende.FATTUREINCLOUD_METHODS'), true);

        if(!empty($id) && $id != 'all'){
            $fornitore = TableRegistry::get('Aziende.Aziende')->get($id);
            $this->set('fornitore',$fornitore);
        }


        $this->set('purposesPassive',$purposes);
        $this->set('paymentConditions',$paymentConditions);
        $this->set('idFornitore',$id);
        $this->set('payers',$payers);
        $this->set('lista_metodi', $metodi['lista_conti']);
        //debug($purposes);die;
    }

    public function getAttachment($year,$month,$fileName)
    {
      if(!empty($fileName)){
          $this->response->file('files'.DS.$year.DS.$month.DS.$fileName);
      }

      return $this->response;

    }
}
