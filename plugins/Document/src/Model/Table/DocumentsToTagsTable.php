<?php
/**
* Document is a plugin for manage attachment
*
* Companee :    Documents To Tags  (https://www.companee.it)
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
namespace Document\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;
use Cake\ORM\Behavior\TimestampBehavior;

class DocumentsToTagsTable extends Table
{

    public function initialize(array $config)
    {
        $this->setTable('documents_to_tags');
        $this->addBehavior('Timestamp');
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('id_document', 'Il documento è obbligatorio')
            ->notEmpty('id_tag', 'Il tag è obbligatiorio');
    }

    public function buildRules(RulesChecker $rules)
    {
        // Add a rule that is applied for create and update operations
        $rules->add($rules->isUnique(
            ['id_document', 'id_tag'],
            'Questo tag è gia presente nel documento.'
        ));

        return $rules;
    }


}
