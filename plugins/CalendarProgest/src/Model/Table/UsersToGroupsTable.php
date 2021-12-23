<?php
namespace Calendar\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class UsersToGroupsTable extends Table
{

    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->hasOne('Operatore',[
            'foreignKey' => 'id_user',
            'className' => 'Aziende.Contatti'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('id_user', 'L\'utente è obbligatorio');
            //->notEmpty('id_group', 'Il gruppo è obbligatoria');
    }

    public function buildRules(RulesChecker $rules)
    {
        // Add a rule that is applied for create and update operations
        $rules->add($rules->isUnique(
            ['id_user', 'id_group'],
            'Questo utente è gia presente nel gruppo.'
        ));

        return $rules;
    }


}
