<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Contatti Ruoli  (https://www.companee.it)
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
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;

class ContattiRuoliTable extends Table
{

    public function initialize(array $config)
    {
        $this->setTable('contatti_ruoli');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Aziende.Ruolo');
        //$this->belongsTo('Document.Contacts',['foreignKey' => 'id_client', 'conditions' => ['Contacts.client' => 1], 'propertyName' => 'client']);
        //$this->belongsTo('Document.Projects',['foreignKey' => 'id_project']);
        $this->hasOne('Aziende.Contatti',['foreignKey' => 'id_ruolo', 'propertyName' => 'contatti']);
    }

    public function validationDefault(Validator $validator)
    {
        return $validator
            ->notEmpty('ruolo', 'Il nome del ruolo è obbligatorio.')
            ->notEmpty('color', 'Il colore del ruolo è obbligatorio.')
            ->notEmpty('order', 'l\'ordinamento del ruolo è obbligatorio.');

    }

    public function getList($conditions = array())
  	{
  			return $this->find()->select(['id'=>'id','text'=>'ruolo'])->order(['ordering' => 'ASC'])
  				->where($conditions)->toArray();
  	}



}
