<?php

/**
* class ProfileComponent
*
* Gestisce i metodi relativi a Profiles
*
* @api
* @category Component
*/

namespace Pmm\Controller\Component;

use Cake\Controller\Component;
use Cake\I18n\Time;
use Cake\Utility\Text;
use Cake\Controller\ComponentRegistry;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

class ProfileComponent extends Component
{
	/**
	* @api
	* @var $_controller - contiene l'istanza del controller
	*/

	protected $_controller;

	public function startup(Event $event)
	{
		$this->_controller = $this->_registry->getController();
		$this->_profiles = TableRegistry::get('Pmm.Profiles');
	}

	/**
	* metodo getPosList
	*
	* restitusce la lista dei pos in formato id => nome
	*
	* @api
	* @author Sergio Frasca
	* @return array
	*
	*/

	public function getPosList()
	{
		return $this->_profiles->getPosList();
	}
}

