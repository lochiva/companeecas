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
use Cake\Core\Configure;


class CrmCoopconsorziTable extends Table
{

	public function initialize(array $config)
	{
		$this->table('crm_coopconsorzi');
		$this->primaryKey('coop_id');
    $this->hasMany('Pmm.Users',[
			'className' => 'Pmm.Users',
			'foreignKey' => 'coopconsorzi_id',
			'propertyName' => 'User'
		]);

  }

}
