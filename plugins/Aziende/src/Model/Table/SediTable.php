<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class SediTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->table('sedi');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->entityClass('Aziende.Sede');
        $this->belongsTo('Aziende.SediTipi',['foreignKey' => 'id_tipo', 'propertyName' => 'tipoSede']);
        $this->belongsTo('Aziende.Aziende',['foreignKey' => 'id_azienda', 'propertyName' => 'azienda']);
        $this->belongsTo('Comuni',['className' => 'Luoghi','foreignKey' => 'comune', 'propertyName' => 'comune']);
        $this->belongsTo('Province',['className' => 'Luoghi','foreignKey' => 'provincia', 'propertyName' => 'provincia']);
        $this->belongsTo('Aziende.SediTipologieCentro',['foreignKey' => 'id_tipologia_centro', 'propertyName' => 'tipologiaCentro']);
        $this->belongsTo('Aziende.SediTipologieOspiti',['foreignKey' => 'id_tipologia_ospiti', 'propertyName' => 'tipologiaOspiti']);
        $this->belongsTo('Aziende.SediProcedureAffidamento',['foreignKey' => 'id_procedura_affidamento', 'propertyName' => 'proceduraAffidamento']);
    }

    public function saveSede($data)
    {
        if(!empty($data['id']) && is_int($data['id'])){
            $entity = $this->get($data['id']);
        }else{
            $entity = $this->newEntity();

            $lastSede = $this->find()
                ->where(['id_azienda' => $data['id_azienda'], 'deleted' => '0'])
                ->order(['ordering DESC'])
                ->first();
            
            if($lastSede){
                $entity->ordering = $lastSede->ordering + 1;
            }
        }

        $entity = $this->patchEntity($entity, $data);

        $entity->comune = $data['comune'];
        $entity->provincia = $data['provincia'];

        $entity->cleanDirty(['created','modified']);
        if($entity->dirty()){
            return $this->save($entity);
        }
        return $entity;
    }

	public function getSedeFatturaincloud($idAzienda){
        return $this->find()
            ->select(['Sedi.indirizzo', 'Sedi.num_civico', 'Sedi.cap', 'comune' => 'c.des_luo', 'provincia' => 'p.des_luo'])
            ->where(['id_azienda' => $idAzienda])
            ->join([
                [
                    'table' => 'luoghi',
                    'alias' => 'c',
                    'type' => 'LEFT',
                    'conditions' => 'c.c_luo = Sedi.comune'
                ],
                [
                    'table' => 'luoghi',
                    'alias' => 'p',
                    'type' => 'LEFT',
                    'conditions' => 'p.c_luo = Sedi.provincia'
                ]
            ])
            ->order(['id_tipo ASC'])
            ->first();
	}

    public function getSediSurveyPartner($idPartner)
    {
        return $this->find()
					->select(['id', 'comune', 'indirizzo'])
					->where(['id_azienda' => $idPartner, 'deleted' => 0])
					->order(['comune ASC', 'indirizzo ASC'])
					->toArray();

    }
    
}
