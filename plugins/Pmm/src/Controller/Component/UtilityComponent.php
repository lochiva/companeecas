<?php

/**
* class UtilityComponent
*
* Contiene metodi generici
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

class UtilityComponent extends Component
{
	/**
	* @api
	* @var $_controller - contiene l'istanza del controller
	*/

	protected $_controller;

	public function startup(Event $event)
	{
		$this->_controller = $this->_registry->getController();
	}

	/**
	* metodo setFilterInSession
	*
	* Inserisce dei filtri in sessione con il prefisso indicato, se non c'è un prefisso restituisce false senza fare nulla
	*
	* @api
	* @author Sergio Frasca
	* @param string $prefix il prefiosso per il filtro
	* @param array $filters l'array dei filtri, nome_filtro => valore
	* @return boolean
	*/

	public function setFilterInSession($prefix = "",$filters = [])
	{
		try
		{
			if($prefix == "")
				throw new Exception("setFilterInSession, nessun prefisso ricevuto");
				
			foreach($filters as $filter => $value)
			{
				// se il filtro è vuoto lo elimino
				if($value == "")
				{
					if($this->request->session()->check($prefix . "." . $filter))
						$this->request->session()->delete($prefix . "." . $filter);
				}else
				{
					// scrivo il filtro in sessione
					$this->request->session()->write($prefix . "." . $filter,$value);
				}
			}

			return true;

		}catch(\Exception $e)
		{
			return false;
		}
	}

	/**
	* metodo isItalianDate
	*
	* restituisce true se la data è in formato italiano
	*
	* @api 
	* @author Sergio Frasca
	* @param string $date la data da controllare
	* @return boolean
	*/

	public function isItalianDate($date = "")
	{
		return preg_match('/[0-9]{1,2}\/[0-9]{1,2}\/[0-9]{2,4}/', $date);
	}
}