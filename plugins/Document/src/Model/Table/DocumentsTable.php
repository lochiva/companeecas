<?php
/**
* Document is a plugin for manage attachment
*
* Companee :    Documents  (https://www.companee.it)
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

class DocumentsTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Document.Document');
        $this->belongsTo('Aziende.Aziende',['foreignKey' => 'id_azienda','propertyName' => 'azienda']);
        $this->belongsTo('Aziende.Orders',['foreignKey' => 'id_order','propertyName' => 'ordine']);
        $this->belongsToMany('Tags', [
            'through' => 'Document.DocumentsToTags',
            'targetForeignKey' => 'id_tag',
            'foreignKey' => 'id_document'
        ]);
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('title', 'Il titolo è obbligatorio')
            ->notEmpty('id_azienda', 'Il Cliente è obbligatorio')
            ->notEmpty('id_order', 'Il Progetto è obbligatorio');
    }

}
