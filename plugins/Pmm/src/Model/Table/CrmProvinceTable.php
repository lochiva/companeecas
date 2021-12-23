<?php

namespace Pmm\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use Cake\ORM\Behavior\TimestampBehavior;
use Cake\Network\Session;
use Cake\ORM\TableRegistry;
use Cake\ORM\Entity;
use Cake\I18n\Time;
use Cake\Core\Exception\Exception;

class CrmProvinceTable extends Table
{
	public function initialize(array $config)
	{
		$this->table('crm_province');
		$this->primaryKey('provincia_id');

		$this->belongsTo('Pmm.CrmRegioni',[
				'className' => 'Pmm.CrmRegioni',
				'foreignKey' => 'provincia_fk_regione',
				'propertyName' => 'Regione'
		]);
	}
}
