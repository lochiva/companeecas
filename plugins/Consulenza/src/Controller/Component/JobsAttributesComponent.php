<?php

namespace Consulenza\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class JobsAttributesComponent extends Component
{

	/*
    * metodo getAttributeFromJobId
	*
	* Dato un job_id restituisce l'array con i relativi attributi
	*
	* @api 
	* @author Sergio Frasca
	* @param integer $id il job_id
	* @return mixed
	* @throws Exception
	*
    */

	public function getAttributeFromJobId($id = 0)
	{
		try
		{
			if($id == 0)
				throw new Exception();
				

			$jja = TableRegistry::get('Consulenza.JobsJobsAttributes');

			$attributes = $jja->getAttributeFromJobId($id);

			$toRet = array();

			foreach($attributes as $attribute)
			{
				if(!in_array($attribute->Jobsattributes->key_attribute, $toRet))
					$toRet[] = $attribute->Jobsattributes->key_attribute;
			}

			return $toRet;

		}catch(Exception $e)
		{
			return false;
		}
	}
}