<?php
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
