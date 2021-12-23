<?php
namespace Calendar\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class GroupsTable extends Table
{

    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->belongsToMany('Operatori', [
            'through' => 'Calendar.UsersToGroups',
            'targetForeignKey' => 'id_user',
            'foreignKey' => 'id_group',
            'className' => 'Users'
        ]);
        $this->hasMany('UsersToGroups',[
            'foreignKey' => 'id_group',
            'propertyName' => 'UsersToGroups',
            'className' => 'Calendar.UsersToGroups'
        ]);
        $this->hasOne('UsersGrouping',[
            'foreignKey' => 'id_group',
            'className' => 'Calendar.UsersToGroups'
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
