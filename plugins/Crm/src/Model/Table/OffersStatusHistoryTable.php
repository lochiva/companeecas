<?php

namespace Crm\Model\Table;

use Cake\ORM\Table;
use App\Model\Table\AppTable;

class OffersStatusHistoryTable extends Table
{
    public function initialize(array $config)
    {
        $this->table('offers_status_history');
        $this->primaryKey('id');
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
