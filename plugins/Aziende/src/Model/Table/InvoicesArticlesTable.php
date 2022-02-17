<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class InvoicesArticlesTable extends AppTable
{
    public function initialize(array $config)
    {
        $this->setTable('invoices_articles');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Invoices',['foreignKey' => 'id_invoice','className' => 'Aziende.Invoices', 'propertyName' => 'invoice']);
    }

}
