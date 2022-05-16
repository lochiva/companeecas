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
                'a.id_tipo' => 2,
                'a.deleted' => 0,
                'Sedi.deleted' => 0
                
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

    public function getDataForReportGuestsCas($date = "")
    {
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $sedi = $this->find()
            ->select($this)
            ->select([
                'regione' => 'r.des_luo',
                'provincia' => 'p.des_luo',
                'comune' => 'c.des_luo',
                'tipo_centro' => 'stc.name',
                'tipo_struttura' => 'stm.name',
                'ente' => 'a.denominazione',
                'address' => 'CONCAT(Sedi.indirizzo, " ", Sedi.num_civico)', 
                'capienza_effettiva' => "Sedi.n_posti_effettivi - (
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.electronic_residence_permit = 1 AND g.country_birth = 100000243
                )",
                'presenze' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND (g.electronic_residence_permit != 1 ||! g.country_birth = 100000243)
                )",
                'tipo_ospiti' => 'sto.name',
                'pec' => 'a.pec',
                'procedura' => 'spa.name',
                'data_stipula' => 'ag.date_agreement',
                'data_scadenza' => 'ag.date_agreement_expiration',
                'data_proroga' => 'ag.date_extension_expiration',
                'prezzo_giornaliero' => 'ag.guest_daily_price',
                'guests_minori' => "IF(
                    sto.id IN (1, 7, 8),
                    (SELECT COUNT(*) FROM guests g
                        WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                        AND g.minor = 1 AND g.minor_alone = 1
                    ),
                    ''
                )",
                'guests_siria' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000348
                )",
                'guests_iran' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000332
                )",
                'guests_iraq' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000333
                )",
                'guests_somalia' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000453
                )",
                'guests_eritrea' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000466
                )",
                'guests_bangladesh' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000305
                )",
                'guests_nigeria' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000443
                )",
                'guests_pakistan' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000344
                )",
                'guests_tunisia' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000460
                )",
                'guests_sudan' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000455
                )",
                'guests_altre' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth NOT IN (100000348, 100000332, 100000333, 100000453, 100000466, 100000305, 100000443, 100000344, 100000460, 100000455)
                )",
            ])
            ->where([ 
                'a.id_tipo' => 1,
                'a.deleted' => 0,
                'Sedi.deleted' => 0
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
                    'table' => 'sedi_tipologie_centro',
                    'alias' => 'stc',
                    'type' => 'left',
                    'conditions' => 'stc.id = Sedi.id_tipologia_centro'
                ],
                [
                    'table' => 'sedi_tipi_ministero',
                    'alias' => 'stm',
                    'type' => 'left',
                    'conditions' => 'stm.id = Sedi.id_tipo_ministero'
                ],
                [
                    'table' => 'sedi_tipologie_ospiti',
                    'alias' => 'sto',
                    'type' => 'left',
                    'conditions' => 'sto.id = Sedi.id_tipologia_ospiti'
                ],
                [
                    'table' => 'aziende',
                    'alias' => 'a',
                    'type' => 'left',
                    'conditions' => 'a.id = Sedi.id_azienda'
                ],
                [
                    'table' => 'agreements_to_sedi',
                    'alias' => 'ats',
                    'type' => 'left',
                    'conditions' => ['ats.sede_id = Sedi.id', 'ats.active' => 1]
                ],
                [
                    'table' => 'agreements',
                    'alias' => 'ag',
                    'type' => 'left',
                    'conditions' => ['ag.id = ats.agreement_id', 'ag.deleted' => 0]
                ],
                [
                    'table' => 'sedi_procedure_affidamento',
                    'alias' => 'spa',
                    'type' => 'left',
                    'conditions' => 'spa.id = ag.procedure_id'
                ]
            ])
            ->order('a.denominazione ASC')
            ->toArray();

        $data[0] = [
            'CODICE CENTRO', 'REGIONE', 'PROVINCIA', 'COMUNE', 'TIPOLOGIA CENTRO', 'TIPOLOGIA STRUTTURA', 'DENOMINAZIONE STRUTTURA', 'INDIRIZZO', 
            'CAPIENZA', 'CAPIENZA EFFETTIVA', 'PRESENZE TOTALI GIORNALIERE', 'DISPONIBILITÀ POSTI', 'DISPONIBILITA\' EFFETTIVA', 'OPERATIVITA\' DEL CENTRO',
            'TIPOLOGIA OSPITI', 'ENTE GESTORE', 'PEC DEL RESPONSABILE ENTE GESTORE A FINI NOTIFICA', 'PROCEDURA APERTA', 'DATA DI STIPULA DELLA CONVENZIONE',
            'DATA DI SCADENZA DELLA CONVENZIONE',  'DATA DI SCADENZA DELLA EVENTUALE PROROGA', 'PREZZO GIORNALIERO PER OSPITE (al lordo di IVA)',
            'ECCEZIONALI PRESENZE DI MSNA NEI CENTRI PER ADULTI', 'SIRIA', 'IRAN', 'IRAQ', 'SOMALIA', 'ERITREA', 'BANGLADESH', 'NIGERIA', 'PAKISTAN',
            'TUNISIA', 'SUDAN', 'ALTRE NAZIONALITA\'', 'NOTE'
        ];

        foreach ($sedi as $sede) {
            $data[] = [
                $sede['code_centro'],
                $sede['regione'],
                $sede['provincia'],
                $sede['comune'],
                $sede['tipo_centro'],
                $sede['tipo_struttura'],
                $sede['ente'],
                $sede['address'],
                $sede['n_posti_struttura'],
                $sede['capienza_effettiva'],
                $sede['presenze'],
                $sede['n_posti_struttura'] - $sede['presenze'],
                $sede['capienza_effettiva'] - $sede['presenze'],
                $sede['operativita'] ? 'ATTIVO' : 'CHIUSO',
                $sede['tipo_ospiti'],
                $sede['ente'],
                $sede['pec'],
                $sede['procedura'],
                empty($sede['data_stipula']) ? '' : implode('/', array_reverse(explode('-', $sede['data_stipula']))),
                empty($sede['data_scadenza']) ? '' : implode('/', array_reverse(explode('-', $sede['data_scadenza']))),
                empty($sede['data_proroga']) ? '' : implode('/', array_reverse(explode('-', $sede['data_proroga']))),
                $sede['prezzo_giornaliero'],
                $sede['guests_minori'],
                $sede['guests_siria'],
                $sede['guests_iran'],
                $sede['guests_iraq'],
                $sede['guests_somalia'],
                $sede['guests_eritrea'],
                $sede['guests_bangladesh'],
                $sede['guests_nigeria'],
                $sede['guests_pakistan'],
                $sede['guests_tunisia'],
                $sede['guests_sudan'],
                $sede['guests_altre'],
                $sede['note']
            ];
        }

        return $data;
    }

    public function getDataForDettaglioGuestsCas($date = "")
    {
        if (empty($date)) {
            $date = date('Y-m-d');
        }

        $dateMinus17Years = date('Y-m-d', strtotime('-17 years', strtotime($date)));
        $dateMinus6Years = date('Y-m-d', strtotime('-6 years', strtotime($date)));

        $sedi = $this->find()
            ->select($this)
            ->select([
                'regione' => 'r.des_luo',
                'provincia' => 'p.des_luo',
                'comune' => 'c.des_luo',
                'tipo_centro' => 'stc.name',
                'tipo_struttura' => 'stm.name',
                'address' => 'CONCAT(Sedi.indirizzo, " ", Sedi.num_civico)', 
                'ente' => 'a.denominazione',
                'tot_guests' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.electronic_residence_permit = 1 AND g.country_birth = 100000243
                )",
                'tot_guests_male' => "(
                    SELECT COUNT(*) FROM guests g
                    LEFT JOIN guests_families gf ON gf.guest_id = g.id
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.electronic_residence_permit = 1 AND g.country_birth = 100000243 AND g.sex = 'M' AND g.minor = 0 AND gf.id IS NULL
                )",
                'tot_guests_female' => "(
                    SELECT COUNT(*) FROM guests g
                    LEFT JOIN guests_families gf ON gf.guest_id = g.id
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.electronic_residence_permit = 1 AND g.country_birth = 100000243 AND g.sex = 'F' AND g.minor = 0 AND gf.id IS NULL
                )",
                'tot_guests_family' => "(
                    SELECT COUNT(*) FROM guests g
                    LEFT JOIN guests_families gf ON gf.guest_id = g.id
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.electronic_residence_permit = 1 AND g.country_birth = 100000243 AND gf.id IS NOT NULL
                )",
                'tot_guests_minor' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.electronic_residence_permit = 1 AND g.country_birth = 100000243 AND g.minor = 1 AND g.minor_alone = 1
                )",
                'tot_guests_school' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.electronic_residence_permit = 1 AND g.country_birth = 100000243 AND g.minor = 1 AND g.birthdate > '$dateMinus17Years' AND g.birthdate <= '$dateMinus6Years'
                )",
                'tot_guests_exited' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_out_date <= '$date' AND g.deleted = 0 AND g.electronic_residence_permit = 1 AND g.country_birth = 100000243
                )",
                'tot_guests_exited_sai' => "(
                    SELECT COUNT(*) FROM guests g
                    LEFT JOIN guests_histories h ON h.guest_id = g.id AND h.guest_status_id = 3 
                    LEFT JOIN guests_exit_types et ON et.id = h.exit_type_id 
                    WHERE g.sede_id = Sedi.id AND g.check_out_date <= '$date' AND g.status_id = 3 AND g.deleted = 0 AND g.electronic_residence_permit = 1 
                    AND g.country_birth = 100000243 AND et.toSAI = 1
                )"
            ])
            ->where([ 
                'a.id_tipo' => 1,
                'a.deleted' => 0,
                'Sedi.deleted' => 0
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
                    'table' => 'sedi_tipologie_centro',
                    'alias' => 'stc',
                    'type' => 'left',
                    'conditions' => 'stc.id = Sedi.id_tipologia_centro'
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
            'REGIONE', 'PROVINCIA', 'COMUNE', 'TIPOLOGIA CENTRO', 'TIPOLOGIA STRUTTURA', 'DENOMINAZIONE STRUTTURA', 'INDIRIZZO', 'OPERATIVITA\' DEL CENTRO',
            'ENTE GESTORE', 'STRUTTURA ATTIVATA EX DL 28.02.2022', 'CAPIENZA (SOLI POSTI RISERVATI PER UCRAINI)', 'N. CITTADINI UCRAINI (M+N+O+P)', 
            'DI CUI UOMINI SINGOLI', 'DI CUI DONNE SINGOLE', 'DI CUI COMPONENTI NUCLEI FAMILIARI', 'DI CUI MSNA', 
            'N. MINORI IN ETA\' SCOLARE (età compresa tra i 6 ed i 16 anni)', 'DISPONIBILITA\' DI POSTI REDISUA PER I CITTADINI UCRAINI', 'N. ALLONTANATI', 
            'N. TRASFERITI IN SAI', 'NOTE'
        ];

        foreach ($sedi as $sede) {
            $data[] = [
                $sede['regione'],
                $sede['provincia'],
                $sede['comune'],
                $sede['tipo_centro'],
                $sede['tipo_struttura'],
                $sede['ente'],
                $sede['address'],
                $sede['operativita'] ? 'ATTIVO' : 'CHIUSO',
                $sede['ente'],
                $sede['exdl_28022022'] ? 'SI' : 'NO',
                $sede['tot_guests'],
                $sede['tot_guests'],
                $sede['tot_guests_male'],
                $sede['tot_guests_female'],
                $sede['tot_guests_family'],
                $sede['tot_guests_minor'],
                $sede['tot_guests_school'],
                '0',
                $sede['tot_guests_exited'],
                $sede['tot_guests_exited_sai'],
                $sede['note']
            ];
        }

        return $data;
    }
    
}
