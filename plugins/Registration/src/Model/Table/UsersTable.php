<?php
namespace Registration\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class UsersTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Groups', [
            'through' => 'UsersToGroups',
            'targetForeignKey' => 'id_group',
            'foreignKey' => 'id_user'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            //->notEmpty('email', 'La email è obbligatoria')
            ->notEmpty('username', 'Lo username è obbligatorio')
            ->notEmpty('password', 'La password è obbligatoria');
    }

    public function buildRules(RulesChecker $rules)
    {
        // Add a rule that is applied for create and update operations
        $rules->add($rules->isUnique(['username']));

        // Controllo unicità email
	    $rules->add(function ($entity, $options) {
                return $this->isEmailUnique($entity);
            },
            'uniqueEmail',
            [
                'errorField' => 'email',
                'message' => 'La mail è già presente nel sistema.'
            ]
        );

        return $rules;
    }

    public function isEmailUnique($entity)
    {
        $email = $this->find()
            ->where([
                'email' => $entity->email, 
                'id !=' => $entity->id
            ])
            ->toArray();

        if (empty($email)) {
            return true;
        } else {
            return false;
        }
    }

}
