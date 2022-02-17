<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class InvoicesPurposesTable extends AppTable
{
    public function initialize(array $config)
    {
        $this->setTable('invoices_purposes');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        //$this->setEntityClass('Aziende.Contatto');
    }

}
