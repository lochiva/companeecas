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

namespace Crm\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use App\Model\Table\AppTable;

class OffersTable extends AppTable
{
    public function initialize(array $config)
    {
        $this->setTable('offers');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Crm.Offer');
        $this->belongsTo('Status', [
          'foreignKey' => 'id_status',
          'className' => 'Crm.OffersStatus',
          'propertyName' => 'status', ]);
        $this->belongsTo('AziendaEmit', [
          'foreignKey' => 'id_azienda_emit',
          'className' => 'Aziende.Aziende',
          'propertyName' => 'emittente', ]);
        $this->belongsTo('ContattoEmit', [
          'foreignKey' => 'id_contatto_emit',
          'className' => 'Aziende.Contatti',
          'propertyName' => 'contatto_emit', ]);
        $this->belongsTo('AziendaDest', [
            'foreignKey' => 'id_azienda_dest',
            'className' => 'Aziende.Aziende',
            'propertyName' => 'azienda_dest', ]);
        $this->belongsTo('ContattoDest', [
            'foreignKey' => 'id_contatto_dest',
            'className' => 'Aziende.Contatti',
            'propertyName' => 'contatto_dest', ]);
        $this->belongsTo('Sedi', [
            'foreignKey' => 'id_sede_dest',
            'className' => 'Aziende.Sedi',
            'propertyName' => 'sede_dest', ]);
        $this->belongsTo('Stati', [
            'foreignKey' => 'id_sede_dest',
            'className' => 'Aziende.Sedi',
            'propertyName' => 'sede_dest', ]);

        $this->hasMany('Crm.OffersStatusHistory', [
            'className' => 'OffersStatusHistory',
            'foreignKey' => 'id_offer'
        ]);
    }

    public function getValoreOfferte($range = 2, $status = 1){

      $offersHistory = TableRegistry::get('Crm.OffersStatusHistory');



      //echo "<pre>"; print_r($a); echo "</pre>";

      $subQuery = $this->find('all')
        ->select(['idOffer' => 'Offers.id', 'importo' => 'Offers.amount', 'idHistory' => 'OffersStatusHistory.id', 'dataInvio' => 'OffersStatusHistory.created',
          'meseInvio' => 'MONTH(OffersStatusHistory.created)','actualStatus' => 'Offers.id_status',
          'annoMeseInvio' => 'CONCAT(YEAR(OffersStatusHistory.created) , MONTH(OffersStatusHistory.created))',
          'annoInvio' => 'YEAR(OffersStatusHistory.created)'])
        ->matching('OffersStatusHistory', function($q) use ($status){
          return $q->where(['OffersStatusHistory.id_status' => $status]);
        })
        ->where([
          'OffersStatusHistory.created' => $offersHistory->find()->select(['dataInvio' => 'MAX(OffersStatusHistory.created)'])->where(['OffersStatusHistory.id_status = ' . $status . ' AND OffersStatusHistory.id_offer = Offers.id']),
          'OffersStatusHistory.created >= "' . date('Y-m-d H:s:i',mktime(date('H'),date('i'),date('s'),date('m') - $range, date('d'),date('Y'))) . '"'
          ])
        ->order(['OffersStatusHistory.created ASC'])
        ;

        $res= $subQuery->select(['totMese' => 'SUM(Offers.amount)'])->group(['annoMeseInvio','actualStatus'])->toArray()
        ;

      return $res;

    }

}
