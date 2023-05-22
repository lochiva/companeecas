<?php
/** 
* Companee :    Groups (https://www.companee.it)
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

class GroupsTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Users', [
            'through' => 'UsersToGroups',
            'targetForeignKey' => 'id_user',
            'foreignKey' => 'id_group'
        ]);
        $this->hasMany('UsersToGroups',[
            'foreignKey' => 'id_group',
            'propertyName' => 'UsersToGroups'
        ]);
        $this->hasOne('UsersGrouping',[
            'foreignKey' => 'id_group',
            'className' => 'UsersToGroups'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('name', 'Il nome del gruppo è obbligatorio.');

    }

    public function buildRules(RulesChecker $rules)
    {
        // Add a rule that is applied for create and update operations
        //$rules->add($rules->isUnique(['name']));

        return $rules;
    }

}
