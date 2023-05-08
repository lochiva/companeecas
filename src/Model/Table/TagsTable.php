<?php
namespace App\Model\Table;
################################################################################
#
# Companee :   Tags (https://www.companee.it)
# Copyright (c) lochiva , (http://www.lochiva.it)
#
# Licensed under The GPL  License
# For full copyright and license information, please see the LICENSE.txt
# Redistributions of files must retain the above copyright notice.
#
# @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
# @link          https://www.companee.it Companee project
# @since         1.2.0
# @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
#
################################################################################

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use Cake\ORM\Behavior\TimestampBehavior;

class TagsTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->setTable('tags');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Tag');
        $this->belongsToMany('Calendar.Eventi', [
            'through' => 'Calendar.EventiToTags',
            'targetForeignKey' => 'id_event',
            'foreignKey' => 'id_tag'
        ]);
        $this->belongsToMany('Document.Documents', [
            'through' => 'Document.DocumentsToTags',
            'targetForeignKey' => 'id_document',
            'foreignKey' => 'id_tag'
        ]);
    }

    public function buildRules(RulesChecker $rules)
    {
        // Add a rule that is applied for create and update operations
        $rules->add($rules->isUnique(['name'],
            'Questo tag esiste già.'
        ));

        return $rules;
    }

    public function getAutocomplete($nome)
    {
      return $this->find('all')->select(['id' => 'id','text' => 'name'])
                ->where(['name LIKE' =>'%'.$nome.'%' ])->toArray();
    }

}
