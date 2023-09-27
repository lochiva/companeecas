<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Agreements (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
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
use Cake\ORM\TableRegistry;

/**
 * Agreements Controller
 */
class AgreementsController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Aziende.Azienda');
    }

    public function isAuthorized($user)
    {
        if(
            $user['role'] == 'admin' || 
            $user['role'] == 'area_iv' || 
            $user['role'] == 'ragioneria' || 
            $user['role'] == 'questura' ||
            $user['role'] == 'ente_ospiti' ||
            $user['role'] == 'ente_contabile'
        ){
            return true;
        }
        
        return false;
    }

    /**
     * Index method
     *
     * @return \Cake\Network\Response|null
     */
    public function index($aziendaId)
    {
        $azienda = TableRegistry::get('Aziende.Aziende')->get($aziendaId);
        $sedi = TableRegistry::get('Aziende.Sedi')->find()->where(['id_azienda' => $aziendaId])->contain('Comuni')->toArray();
        $procedureAffidamento = TableRegistry::get('Aziende.SediProcedureAffidamento')->getList();

        $this->set('azienda', $azienda);
        $this->set('sedi', $sedi);
        $this->set('procedureAffidamento',$procedureAffidamento);
    }

}
