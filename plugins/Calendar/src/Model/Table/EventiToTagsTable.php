<?php
namespace Calendar\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class EventiToTagsTable extends Table
{

    public function initialize(array $config)
    {
        $this->table('calendar_events_to_tags');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('id_event', 'L\'utente è obbligatorio')
            ->notEmpty('id_tag', 'Il gruppo è obbligatoria');
    }

    public function buildRules(RulesChecker $rules)
    {
        // Add a rule that is applied for create and update operations
        $rules->add($rules->isUnique(
            ['id_event', 'id_tag'],
            'Questo tag è gia presente nell\'evento.'
        ));

        return $rules;
    }


}
