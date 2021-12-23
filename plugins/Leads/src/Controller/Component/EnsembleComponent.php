<?php
namespace Leads\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Core\Configure;

class EnsembleComponent extends Component
{

    public function getEnsembles($pass)
    {
        $ensembles = TableRegistry::get('Leads.LeadsEnsembles');
        $columns = [
			0 => ['val' => 'LeadsEnsembles.name', 'type' => 'text'],
			1 => ['val' => 'LeadsEnsembles.description', 'type' => 'text'],
            2 => ['val' => 'LeadsEnsembles.active', 'type' => '']
        ];
        $opt['fields'] = ['LeadsEnsembles.id', 'LeadsEnsembles.name', 'LeadsEnsembles.description', 'LeadsEnsembles.active',
                            'total' => 'COUNT(lq.id)'];

        $opt['join'] = [
            [
                'table' => 'leads_questions',
                'alias' => 'lq',
                'type' => 'LEFT',
                'conditions' => ['lq.id_ensemble = LeadsEnsembles.id', 'lq.deleted = 0']
            ]
        ];

        $opt['group'] = ['LeadsEnsembles.id'];

        if(!empty($pass['query']['filter'][3])){
            $opt['having'] = ['total' => $pass['query']['filter'][3]];
        }

        $opt['order'] = ['LeadsEnsembles.active DESC', 'LeadsEnsembles.name ASC'];
   
        $toRet['res'] = $ensembles->queryForTableSorter($columns, $opt, $pass); 
        $toRet['tot'] = $ensembles->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;
    }

    public function getQuestionTypeAutocomplete($text)
    {
        $types = TableRegistry::get('Leads.LeadsQuestionTypes');
        $result = $types->find('all')
                    ->select(['id' => 'id','text' => 'label'])
                    ->where(['label LIKE' =>'%'.$text.'%'])
                    ->order(['label'=>'ASC'])
                    ->toArray();
        return $result;
    }

    public function getEnsembleAutocomplete($text)
    {
        $types = TableRegistry::get('Leads.LeadsEnsembles');
        $result = $types->find('all')
                    ->select(['id' => 'id','text' => 'name'])
                    ->where(['name LIKE' =>'%'.$text.'%', 'active' => 1])
                    ->order(['name'=>'ASC'])
                    ->toArray();
        return $result;
    }

}