<?php
/**
* Reminder Manager is a plugin for manage attachment
*
* Companee :    Submissions Type Submissions Attributes  (https://www.companee.it)
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
namespace ReminderManager\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;

class SubmissionsTypeSubmissionsAttributesTable extends Table
{

    public function initialize(array $config)
    {
      $this->setTable('submissions_type_submissions_attributes');
      $this->setPrimaryKey('id');
      $this->addBehavior('Timestamp');
    }

    


}
