<?php

namespace Pmm\Controller\Component;

use Cake\Controller\Component;
use Cake\I18n\Time;
use Cake\Utility\Text;
use Cake\Controller\ComponentRegistry;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
* classe POSComponent
* Gestisce i metodi relativi ai POS
*
* @api
* @category Component
*/

class POSComponent extends Component
{

	/**
	* @api
	* @var $_controller - contiene l'istanza del controller
	*/

	protected $_controller;

	public function startup(Event $event)
	{
		$this->_controller = $this->_registry->getController();
		$this->_users = TableRegistry::get('Pmm.Users');

	}

	/**
	* metodo getPOSForTable
	*
	* restituisce un array dei POS nel formato richiesto da tablesorter
	*
	* @api
	* @author Rafael Esposito
	* @param array $params filtri e ordinamento
	* @return array
	*/

	public function getPOSForTable($params = [])
	{
		try
		{
			return $this->_users->retrievePOSForTable($params);

		}catch(Exception $e)
		{
			return [];
		}
	}

	/******************************************************* FUNZIONI PRIVATE *******************************************************************************/


}
