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
        $this->setTable('sedi');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Aziende.Sede');
        $this->belongsTo('Aziende.SediTipiMinistero',['foreignKey' => 'id_tipo_ministero', 'propertyName' => 'tipoSedeMinistero']);
        $this->belongsTo('Aziende.SediTipiCapitolato',['foreignKey' => 'id_tipo_capitolato', 'propertyName' => 'tipoSedeCapitolato']);
        $this->belongsTo('Aziende.Aziende',['foreignKey' => 'id_azienda', 'propertyName' => 'azienda']);
        $this->belongsTo('Comuni',['className' => 'Luoghi','foreignKey' => 'comune', 'propertyName' => 'comune']);
        $this->belongsTo('Province',['className' => 'Luoghi','foreignKey' => 'provincia', 'propertyName' => 'provincia']);
        $this->belongsTo('Aziende.SediTipologieCentro',['foreignKey' => 'id_tipologia_centro', 'propertyName' => 'tipologiaCentro']);
        $this->belongsTo('Aziende.SediTipologieOspiti',['foreignKey' => 'id_tipologia_ospiti', 'propertyName' => 'tipologiaOspiti']);
        $this->belongsTo('Aziende.SediProcedureAffidamento',['foreignKey' => 'id_procedura_affidamento', 'propertyName' => 'proceduraAffidamento']);
    }

    public function beforeSave($event, $entity, $options)
    {
        // Controllo unicità codice centro
        $where = [
            'code_centro' => $entity->code_centro
        ];
        if (!empty($entity->id)) {
            $where['id !='] = $entity->id;
        }
        $sede = $this->find()->where($where)->first();
        if (!empty($sede)) {
            $entity->setError('code_centro', ['Il Codice Centro deve essere univoco.']);
            return false;
        }
        return true;
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

        if (!empty($data['comune'])) {
            $entity->comune = $data['comune'];
        }
        if (!empty($data['provincia'])) {
            $entity->provincia = $data['provincia'];
        }

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

    public function searchTransferSedi($sedeId, $aziendaId, $search)
    {
        return $this->find()
            ->select([
                'Sedi.id',
                'label' => 'CONCAT(
                    Sedi.indirizzo, 
                    " ", 
                    Sedi.num_civico, 
                    ", ", 
                    l.des_luo, 
                    " (", 
                    l.s_prv, 
                    ")"
                )'
            ])
            ->where([ 
                'Sedi.id !=' => $sedeId,
                'Sedi.id_azienda' => $aziendaId
            ])
            ->join([
                [
                    'table' => 'luoghi',
                    'alias' => 'l',
                    'type' => 'left',
                    'conditions' => 'l.c_luo = Sedi.comune'
                ],
            ])
            ->having(['label LIKE' => '%'.$search.'%'])
            ->order('label ASC')
            ->toArray();
    }
    
}
