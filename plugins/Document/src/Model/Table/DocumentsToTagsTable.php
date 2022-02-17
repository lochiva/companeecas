<?php
namespace Document\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;
use Cake\ORM\Behavior\TimestampBehavior;

class DocumentsToTagsTable extends Table
{

    public function initialize(array $config)
    {
        $this->setTable('documents_to_tags');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('id_document', 'Il documento è obbligatorio')
            ->notEmpty('id_tag', 'Il tag è obbligatiorio');
    }

    public function buildRules(RulesChecker $rules)
    {
        // Add a rule that is applied for create and update operations
        $rules->add($rules->isUnique(
            ['id_document', 'id_tag'],
            'Questo tag è gia presente nel documento.'
        ));

        return $rules;
    }


}
