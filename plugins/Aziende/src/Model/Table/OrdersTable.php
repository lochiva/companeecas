<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class OrdersTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->table('orders');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->entityClass('Aziende.Order');
        $this->belongsTo('Aziende.Aziende',['foreignKey' => 'id_azienda', 'propertyName' => 'azienda']);
        $this->belongsTo('Aziende.Contatti',['foreignKey' => 'id_contatto', 'propertyName' => 'contatto']);
        $this->belongsTo('Aziende.OrdersStatus',['foreignKey' => 'id_status', 'propertyName' => 'stato']);
        $this->hasMany('Aziende.OrdersHistory',['foreignKey' => 'id_order', 'propertyName' => 'history']);
    }



}
