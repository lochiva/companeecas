<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class JobsJobsAttributesTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->setTable('jobs_jobsattributes');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        

        
        //$this->belongsTo('Document.Contacts',['foreignKey' => 'id_client', 'conditions' => ['Contacts.client' => 1], 'propertyName' => 'client']);
        //$this->belongsTo('Document.Projects',['foreignKey' => 'id_project']);

        $this->belongsTo('Consulenza.Jobs',['foreignKey' => 'job_id']);
        $this->belongsTo('Consulenza.Jobsattributes',['foreingKey' => 'jobsattribute_id']);

    }

    /*
    * metodo getAttributeFromJobId
	*
	* Dato un job_id restituisce il nome del relativo attributo
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
    			

    		return $this->find('all')
    			->contain(['Jobsattributes'])
    			->select(['Jobsattributes.key_attribute'])
    			->where(['job_id' => $id])
                ->toArray();

    	}catch(Exception $e)
    	{
    		return false;
    	}
    }

}