<?php
/**
* Crm is a plugin for manage attachment
*
* Companee :    Offers Status History  (https://www.companee.it)
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
use App\Model\Table\AppTable;

class OffersStatusHistoryTable extends Table
{
    public function initialize(array $config)
    {
        $this->setTable('offers_status_history');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('OffersStatus', [
            'className' => 'Crm.OffersStatus',
            'foreignKey' => 'id_status'
        ]);
    }

    public function saveOfferChange($data)
    {
        $history = $this->newEntity();
        $history->id_status = $data['id_status'];
        $history->id_offer = $data['id'];

        return $this->save($history);
    }

    public function getStoricoStati($id)
    {
        $res = $this->find()
            ->where(['id_offer' => $id])
            ->contain(['OffersStatus'])
            ->order(['OffersStatusHistory.created DESC'])
            ->toArray();

        return $res;
    }

    public function deleteStatus($id)
    {
        $entity = $this->get($id);

        $res = $this->delete($entity);

        return $res;
    }
}
