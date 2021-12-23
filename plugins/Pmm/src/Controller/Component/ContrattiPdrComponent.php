<?php

/**
* class ContrattiPdrComponent
*
* Gestisce i metodi relativi a contratti_pdr
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

class ContrattiPdrComponent extends Component
{
	/**
	* @api
	* @var $_controller - contiene l'istanza del controller
	*/

	protected $_controller;

	public function startup(Event $event)
	{
		$this->_controller = $this->_registry->getController();
		$this->_contratti_pdr = TableRegistry::get('Pmm.ContrattiPdr');
	}

	/**
	* metodo getPdrList
	*
	* restitusce la lista dei pdr in formato id => nome
	*
	* @api
	* @author Sergio Frasca
	* @return array
	*
	*/

	public function getPdrList()
	{
		return $this->_contratti_pdr->getPdrList();
	}
}