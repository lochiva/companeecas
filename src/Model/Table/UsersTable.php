<?php
namespace App\Model\Table;

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
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('username', 'Lo username è obbligatorio')
            ->notEmpty('password', 'La password è obbligatoria')
            ->notEmpty('role', 'Il ruolo è obbligatorio')
            ->add('role', 'inList', [
                'rule' => ['inList', ['admin', 'area_iv', 'ragioneria', 'ente_ospiti', 'ente_contabile']],
                'message' => 'Inserire un ruolo valido'
            ]);
    }

    public function buildRules(RulesChecker $rules)
    {
        // Add a rule that is applied for create and update operations
        $rules->add($rules->isUnique(['username']));

        return $rules;
    }

	public function getUserAutocomplete($nome)
    {
        $users = $this->find('all')->select(['id' => 'id', 'text' => 'username'])
                  ->where(['username LIKE' => '%'.$nome.'%'])->order(['username' => 'ASC']);

        return $users->toArray();
    }


}
