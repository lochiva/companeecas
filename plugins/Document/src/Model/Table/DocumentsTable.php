<?php
namespace Document\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;
use Cake\ORM\Behavior\TimestampBehavior;

class DocumentsTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Document.Document');
        $this->belongsTo('Aziende.Aziende',['foreignKey' => 'id_azienda','propertyName' => 'azienda']);
        $this->belongsTo('Aziende.Orders',['foreignKey' => 'id_order','propertyName' => 'ordine']);
        $this->belongsToMany('Tags', [
            'through' => 'Document.DocumentsToTags',
            'targetForeignKey' => 'id_tag',
            'foreignKey' => 'id_document'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('title', 'Il titolo è obbligatorio')
            ->notEmpty('id_azienda', 'Il Cliente è obbligatorio')
            ->notEmpty('id_order', 'Il Progetto è obbligatorio');
    }

}
