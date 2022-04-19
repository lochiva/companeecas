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

    public function getDataForReportGuestsEmergenzaUcraina($date = "")
    {
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $dateMinus17Years = date('Y-m-d', strtotime('-17 years', strtotime($date)));
        $dateMinus6Years = date('Y-m-d', strtotime('-6 years', strtotime($date)));

        $sedi = $this->find()
            ->select([
                'regione' => 'r.des_luo',
                'provincia' => 'p.des_luo',
                'comune' => 'c.des_luo',
                'tipo' => 'stm.name',
                'address' => 'CONCAT(Sedi.indirizzo, " ", Sedi.num_civico)', 
                'ente' => 'a.denominazione',
                'tot_guests' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                )",
                'tot_guests_male' => "(
                    SELECT COUNT(*) FROM guests g
                    LEFT JOIN guests_families gf ON gf.guest_id = g.id
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.sex = 'M' AND g.minor = 0 AND gf.id IS NULL
                )",
                'tot_guests_female' => "(
                    SELECT COUNT(*) FROM guests g
                    LEFT JOIN guests_families gf ON gf.guest_id = g.id
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.sex = 'F' AND g.minor = 0 AND gf.id IS NULL
                )",
                'tot_guests_family' => "(
                    SELECT COUNT(*) FROM guests g
                    LEFT JOIN guests_families gf ON gf.guest_id = g.id
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND gf.id IS NOT NULL
                )",
                'tot_guests_minor' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.minor = 1 AND g.minor_alone = 1
                )",
                'tot_guests_school' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.minor = 1 AND g.birthdate > '$dateMinus17Years' AND g.birthdate <= '$dateMinus6Years'
                )",
                'tot_guests_exited' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_out_date <= '$date' AND g.deleted = 0
                )",
                'Sedi.note'
            ])
            ->where([ 
                'a.id_tipo' => 2
                
            ])
            ->having([
                'tot_guests >' => 0 
            ])
            ->join([
                [
                    'table' => 'luoghi',
                    'alias' => 'p',
                    'type' => 'left',
                    'conditions' => 'p.c_luo = Sedi.provincia'
                ],
                [
                    'table' => 'luoghi',
                    'alias' => 'c',
                    'type' => 'left',
                    'conditions' => 'c.c_luo = Sedi.comune'
                ],
                [
                    'table' => 'luoghi',
                    'alias' => 'r',
                    'type' => 'left',
                    'conditions' => ['r.c_rgn = p.c_rgn', 'r.in_luo' => 2]
                ],
                [
                    'table' => 'sedi_tipi_ministero',
                    'alias' => 'stm',
                    'type' => 'left',
                    'conditions' => 'stm.id = Sedi.id_tipo_ministero'
                ],
                [
                    'table' => 'aziende',
                    'alias' => 'a',
                    'type' => 'left',
                    'conditions' => 'a.id = Sedi.id_azienda'
                ]
            ])
            ->order('a.denominazione ASC')
            ->toArray();

        $data[0] = [
            'REGIONE', 'PROVINCIA', 'COMUNE', 'TIPOLOGIA STRUTTURA', 'INDIRIZZO', 'ENTE GESTORE', 'CAPIENZA (POSTI RISERVATI PER UCRAINI)',
            'N. CITTADINI UCRAINI (I+J+K+L)', 'DI CUI UOMINI SINGOLI', 'DI CUI DONNE SINGOLE', 'DI CUI COMPONENTI NUCLEI FAMILIARI',
            'DI CUI MSNA', 'N. MINORI IN ETA\' SCOLARE (età compresa tra i 6 ed i 16 anni)', 'DISPONIBILITA\' DI POSTI REDISUA PER I CITTADINI UCRAINI',
            'N. ALLONTANATI', 'NOTE'
        ];

        foreach ($sedi as $sede) {
            $data[] = [
                $sede['regione'],
                $sede['provincia'],
                $sede['comune'],
                $sede['tipo'],
                $sede['address'],
                $sede['ente'],
                $sede['tot_guests'],
                $sede['tot_guests'],
                $sede['tot_guests_male'],
                $sede['tot_guests_female'],
                $sede['tot_guests_family'],
                $sede['tot_guests_minor'],
                $sede['tot_guests_school'],
                '0',
                $sede['tot_guests_exited'],
                $sede['note']
            ];
        }

        return $data;
    }
    
}
