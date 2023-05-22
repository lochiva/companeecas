<?php
/**
* Crm is a plugin for manage attachment
*
* Companee :    Offers  (https://www.companee.it)
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
namespace Crm\Controller;

use Crm\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * Ws Offers Controller
 *
 * @property \Scadenzario\Model\Table\ScadenzarioTable $Scadenzario
 */
class OffersController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Csrf');
        $this->loadComponent('Crm.Offers');
        $this->loadComponent('Aziende.Azienda');

        $this->set('title', 'offerte');
    }

    public function index($idAzienda = 0)
    {
        $statusList = $this->Offers->getStatusList();
        $aziendeList = $this->Azienda->getAziendeInterne();

        if($idAzienda > 0){
            $azienda = $this->Azienda->_get($idAzienda);
        }else{
            $azienda['denominazione'] = '';
        }

        $this->set('statusList',$statusList);
        $this->set('aziendeList',$aziendeList);
        $this->set('idAzienda',$idAzienda);
        $this->set('nomeAzienda', $azienda['denominazione']);
    }

    public function getAttachment($year,$month,$fileName)
    {
      if(!empty($fileName)){
          $this->response->file('files'.DS.$year.DS.$month.DS.$fileName);
      }

      return $this->response;

    }


}
