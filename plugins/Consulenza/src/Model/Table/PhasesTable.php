<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use App\Model\Table\AppTable;

class PhasesTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->table('phases');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->entityClass('Consulenza.Phase');
        $this->belongsTo('Document.Processes',['foreignKey' => 'process_id']);
    }
    
    
    public function getStep($data){

    	$steps = $this->find('all')->where(['isStep' => 1, 'process_id' => $data['idProcess']])->order('ordering ASC')->toArray();

        $currentStep = $this->getCurrentStep($data['idOrder'], $data['idJob']);
        
        foreach ($steps as $key => $step) {

            if(isset($currentStep->ordering) && $currentStep->ordering != ""){
                if($currentStep->ordering >= $step->ordering){
                    $steps[$key]['selected'] = 1;
                }else{
                    $steps[$key]['selected'] = 0;
                }
            }else{
                $steps[$key]['selected'] = 0;
            }
            
        }


        return $steps;

    }

    public function getCurrentStep($idOrder, $idJob){

        $jobsOrders = TableRegistry::get('Consulenza.JobsOrders');

        $opt['order_id'] = $idOrder;
        $opt['job_id'] = $idJob;

        $jobOrder = $jobsOrders->find('all')->where($opt)->contain(['Phases'])->first();

        //debug($jobOrder);

        if(is_object($jobOrder)){
            return $jobOrder->phase;
        }else{
            return array();
        }

        
    }

}