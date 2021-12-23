<?php
namespace Leads\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Core\Configure;

class InterviewComponent extends Component
{

    public function getInterviews($pass)
    {
        $interviews = TableRegistry::get('Leads.LeadsInterviews');
        $columns = [
            0 => ['val' => 'a.denominazione', 'type' => 'text'],
            1 => ['val' => 'CONCAT(c.cognome, " ", c.nome)', 'type' => 'text'],
            2 => ['val' => 'le.name', 'type' => 'text'],
            3 => ['val' => 'LeadsInterviews.name', 'type' => 'text'],
            4 => ['val' => 'LeadsInterviews.created', 'type' => 'date']
        ];
        $opt['fields'] = ['LeadsInterviews.id', 'LeadsInterviews.name', 'LeadsInterviews.created', 'a.denominazione', 'le.name', 
                            'contatto' => 'CONCAT(c.cognome, " ", c.nome)'];

        $opt['join'] = [
            [
                'table' => 'aziende',
                'alias' => 'a',
                'type' => 'LEFT',
                'conditions' => ['a.id = LeadsInterviews.id_azienda']
            ],
            [
                'table' => 'contatti',
                'alias' => 'c',
                'type' => 'LEFT',
                'conditions' => ['c.id = LeadsInterviews.id_contatto']
            ],
            [
                'table' => 'leads_ensembles',
                'alias' => 'le',
                'type' => 'LEFT',
                'conditions' => ['le.id = LeadsInterviews.id_ensemble']
            ]
        ];
   
        $opt['order'] = ['LeadsInterviews.created DESC'];
        
        $toRet['res'] = $interviews->queryForTableSorter($columns, $opt, $pass);
        $toRet['tot'] = $interviews->queryForTableSorter($columns, $opt, $pass, true);

        return $toRet;
    }

    public function verifyUsedEnsemble($idEnsemble)
    {
        $interviews = TableRegistry::get('Leads.LeadsInterviews')->find()
            ->where(['id_ensemble' => $idEnsemble, 'deleted' => 0])
            ->toArray();

        if($interviews){
            return true;
        }else{
            return false;
        }

    }

    public function getInterviewsByAzienda($idAzienda)
    {
        $interviews = TableRegistry::get('Leads.LeadsInterviews')->find()
            ->where(['id_azienda' => $idAzienda])
            ->contain('Ensemble')
            ->toArray();

        return $interviews;
    }

    public function uploadAnswerFile($file)
    {
        $basePath = ROOT.DS.Configure::read('dbconfig.leads.INTERVIEWS_UPLOAD_PATH');
        $folderPath = date('Y').DS.date('m');
        $fileName = uniqid().$file['name'];
        $res = $folderPath.DS.$fileName;

        if (!is_dir($basePath.$folderPath) && !mkdir($basePath.$folderPath, 0755, true)){
          return false;
        }

        if(!move_uploaded_file($file['tmp_name'],$basePath.$res) ){
          return false;
        }

        return $res;

    }

}