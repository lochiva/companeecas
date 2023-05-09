<?php
/** 
* Companee :    Users (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
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
