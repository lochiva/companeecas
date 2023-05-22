<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    Users  (https://www.companee.it)
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
            ->notEmpty('password', 'La password è obbligatoria')
            ->notEmpty('email', 'L\'email è obbligatoria');
    }

    public function buildRules(RulesChecker $rules)
    {
        // Add a rule that is applied for create and update operations
        $rules->add($rules->isUnique(['username'], 'Lo username inserito è già presente a sistema'));
        $rules->add($rules->isUnique(['email'], 'L\'email inserita è già presente a sistema'));

        return $rules;
    }

}
