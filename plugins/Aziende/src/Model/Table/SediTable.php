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
        $this->belongsTo('Aziende.PoliceStations',['foreignKey' => 'police_station_id', 'propertyName' => 'police_station']);

        $this->hasMany('PresenzeUpload', [
            'foreignKey' => 'sede_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.PresenzeUpload'
        ]);
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
            $entity->setError('code_centro', ['Il Codice Centro è già usato per un\'altra struttura.']);
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

    public function searchSedi($aziendaId, $search = "", $sedeId = "")
    {
        $where = [
            'Sedi.id_azienda' => $aziendaId,
            'Sedi.operativita' => 1
        ];

        if (!empty($sedeId)) {
            $where['Sedi.id !='] = $sedeId;
        }
        
        return $this->find()
            ->select([
                'Sedi.id',
                'label' => 'CONCAT(
                    Sedi.code_centro, 
                    " - ", 
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
            ->where($where)
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

    public function getSedeForSearch($sedeId)
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
                'Sedi.id' => $sedeId,
            ])
            ->join([
                [
                    'table' => 'luoghi',
                    'alias' => 'l',
                    'type' => 'left',
                    'conditions' => 'l.c_luo = Sedi.comune'
                ],
            ])
            ->first();
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
                'capienza_effettiva' => "Sedi.n_posti_effettivi", /* - (
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000243
                )",*/
                'presenze' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                )",
                /*    AND g.country_birth != 100000243
                )",*/
                'tipo_ospiti' => 'sto.name',
                'pec' => 'a.pec_commissione',
                'procedura' => 'spa.name',
                'data_stipula' => 'ag.date_agreement',
                'data_scadenza' => 'ag.date_agreement_expiration',
                'data_proroga' => 'ag.date_extension_expiration',
                'prezzo_giornaliero' => 'ag.guest_daily_price',
                'posti_struttura' => 'ats.capacity + ats.capacity_increment',
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
            $dispo = '0';
            if ($sede['operativita']) {
                $dispo = $sede['posti_struttura'] - $sede['presenze'];
                if ($dispo < 0) {
                    $dispo = 0;
                }
            }

            $dispoEffettiva = '0';
            if ($sede['operativita']) {
                $dispoEffettiva = $sede['capienza_effettiva'] - $sede['presenze'];
                if ($dispoEffettiva < 0) {
                    $dispoEffettiva = 0;
                }
            }

            $data[] = [
                $sede['code_centro'],
                $sede['regione'],
                $sede['provincia'],
                $sede['comune'],
                $sede['tipo_centro'],
                $sede['tipo_struttura'],
                $sede['ente'],
                $sede['address'],
                empty($sede['posti_struttura']) ? '0' : $sede['posti_struttura'],
                strval($sede['capienza_effettiva']),
                strval($sede['presenze']),
                strval($dispo),
                strval($dispoEffettiva),
                $sede['operativita'] ? 'ATTIVO' : 'CHIUSO',
                $sede['tipo_ospiti'],
                $sede['ente'],
                $sede['pec'],
                $sede['procedura'],
                empty($sede['data_stipula']) ? '' : implode('/', array_reverse(explode('-', $sede['data_stipula']))),
                empty($sede['data_scadenza']) ? '' : implode('/', array_reverse(explode('-', $sede['data_scadenza']))),
                empty($sede['data_proroga']) ? '' : implode('/', array_reverse(explode('-', $sede['data_proroga']))),
                $sede['prezzo_giornaliero'],
                strval($sede['guests_minori']),
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
                    AND g.country_birth = 100000243
                )",
                'tot_guests_male' => "(
                    SELECT COUNT(*) FROM guests g
                    LEFT JOIN guests_families gf ON gf.guest_id = g.id
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000243 AND g.sex = 'M' AND g.minor = 0 AND gf.id IS NULL
                )",
                'tot_guests_female' => "(
                    SELECT COUNT(*) FROM guests g
                    LEFT JOIN guests_families gf ON gf.guest_id = g.id
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000243 AND g.sex = 'F' AND g.minor = 0 AND gf.id IS NULL
                )",
                'tot_guests_family' => "(
                    SELECT COUNT(*) FROM guests g
                    LEFT JOIN guests_families gf ON gf.guest_id = g.id
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000243 AND gf.id IS NOT NULL
                )",
                'tot_guests_minor' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000243 AND g.minor = 1 AND g.minor_alone = 1
                )",
                'tot_guests_school' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_in_date <= '$date' AND (g.check_out_date > '$date' OR g.check_out_date IS NULL) AND g.deleted = 0
                    AND g.country_birth = 100000243 AND g.minor = 1 AND g.birthdate > '$dateMinus17Years' AND g.birthdate <= '$dateMinus6Years'
                )",
                'tot_guests_exited' => "(
                    SELECT COUNT(*) FROM guests g
                    WHERE g.sede_id = Sedi.id AND g.check_out_date <= '$date' AND g.deleted = 0 AND g.country_birth = 100000243
                )",
                'tot_guests_exited_sai' => "(
                    SELECT COUNT(*) FROM guests g
                    LEFT JOIN guests_histories h ON h.guest_id = g.id AND h.guest_status_id = 3 
                    LEFT JOIN guests_exit_types et ON et.id = h.exit_type_id 
                    WHERE g.sede_id = Sedi.id AND g.check_out_date <= '$date' AND g.status_id = 3 AND g.deleted = 0 
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

    public function getNextAziendaSede($sedeId)
    {
        return $this->find()
            ->where([
                'Sedi.id >' => $sedeId,
                'Sedi.id_azienda = (SELECT id_azienda FROM sedi WHERE id ='.$sedeId.')'
            ])
            ->order(['Sedi.id' => 'ASC'])
            ->first();
    }

    public function getDataForExportGuestsCasPresenze($aziendaId, $year, $month)
    {
        $res = $this->find()
            ->select($this)
            ->select([
                'a.denominazione', 'c.des_luo', 'g.id', 'g.vestanet_id', 'g.surname', 'g.name', 'g.birthdate', 'g.sex', 'n.des_luo', 
                'g.check_in_date', 'g.check_out_date', 
                'presenza_01' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-01" AND p.presente = 1)',
                'presenza_02' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-02" AND p.presente = 1)',
                'presenza_03' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-03" AND p.presente = 1)',
                'presenza_04' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-04" AND p.presente = 1)',
                'presenza_05' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-05" AND p.presente = 1)',
                'presenza_06' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-06" AND p.presente = 1)',
                'presenza_07' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-07" AND p.presente = 1)',
                'presenza_08' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-08" AND p.presente = 1)',
                'presenza_09' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-09" AND p.presente = 1)',
                'presenza_10' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-10" AND p.presente = 1)',
                'presenza_11' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-11" AND p.presente = 1)',
                'presenza_12' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-12" AND p.presente = 1)',
                'presenza_13' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-13" AND p.presente = 1)',
                'presenza_14' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-14" AND p.presente = 1)',
                'presenza_15' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-15" AND p.presente = 1)',
                'presenza_16' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-16" AND p.presente = 1)',
                'presenza_17' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-17" AND p.presente = 1)',
                'presenza_18' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-18" AND p.presente = 1)',
                'presenza_19' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-19" AND p.presente = 1)',
                'presenza_20' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-20" AND p.presente = 1)',
                'presenza_21' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-21" AND p.presente = 1)',
                'presenza_22' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-22" AND p.presente = 1)',
                'presenza_23' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-23" AND p.presente = 1)',
                'presenza_24' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-24" AND p.presente = 1)',
                'presenza_25' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-25" AND p.presente = 1)',
                'presenza_26' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-26" AND p.presente = 1)',
                'presenza_27' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-27" AND p.presente = 1)',
                'presenza_28' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-28" AND p.presente = 1)',
                'presenza_29' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-29" AND p.presente = 1)',
                'presenza_30' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-30" AND p.presente = 1)',
                'presenza_31' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date = "'.$year.'-'.$month.'-31" AND p.presente = 1)',
                'tot_presenze' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = g.id AND p.date LIKE "'.$year.'-'.$month.'%" AND p.presente = 1)',
            ])
            ->where([
                'a.id' => $aziendaId,
                'Sedi.deleted' => 0
            ])
            ->join([
                [
                    'table' => 'aziende',
                    'alias' => 'a',
                    'type' => 'left',
                    'conditions' => 'a.id = Sedi.id_azienda'
                ],
                [
                    'table' => 'luoghi',
                    'alias' => 'c',
                    'type' => 'left',
                    'conditions' => 'c.c_luo = Sedi.comune'
                ],
                [
                    'table' => 'guests',
                    'alias' => 'g',
                    'type' => 'left',
                    'conditions' => [
                        'g.sede_id = Sedi.id',
                        'g.check_in_date <=' => $year.'-'.$month.'-31',
                        'OR' => [
                            'g.check_out_date >=' => $year.'-'.$month.'-01',
                            'g.check_out_date IS NULL'
                        ],
                        'g.deleted' => 0
                    ]
                ],
                [
                    'table' => 'luoghi',
                    'alias' => 'n',
                    'type' => 'left',
                    'conditions' => 'n.c_luo = g.country_birth'
                ],
            ])
            ->order(['Sedi.id' => 'ASC', 'g.surname' => 'ASC'])
            ->toArray();

        $sedi = [];

        foreach ($res as $guest) {
            if (!isset($sedi[$guest['id']])) {
                $sedi[$guest['id']] = [
                    'ente' => $guest['a']['denominazione'],
                    'code_centro' => $guest['code_centro'],
                    'address' => $guest['indirizzo'].' '.$guest['num_civico'].' '.$guest['des_luo'],
                    'guests' => []
                ];
            }
            if (!empty($guest['g']['id'])) {
                $sedi[$guest['id']]['guests'][] = $guest;
            }
        }

        $monthLabels = [
            '01' => 'Gennaio',
            '02' => 'Febbraio',
            '03' => 'Marzo',
            '04' => 'Aprile',
            '05' => 'Maggio',
            '06' => 'Giugno',
            '07' => 'Luglio',
            '08' => 'Agosto',
            '09' => 'Settembre',
            '10' => 'Ottobre',
            '11' => 'Novembre',
            '12' => 'Dicembre',
        ];

        $data = [];

        foreach ($sedi as $sede) {
            $dataSede = [];
            $dataSede['name'] = $sede['code_centro'];

            $dataSede['data'][0] = [
                'ENTE','','','','',
                'STRUTTURA','','','','','','','','','','','','','','','','','',
                'MESE','','',$monthLabels[$month],'','','','','','',
                'ANNO','','',$year,'','','','',
            ];

            $dataSede['data'][1] = [
                $sede['ente'],'','','','',
                $sede['code_centro'].' - '.$sede['address'],'','','','','','','','','','','','','','','','','',
                '','','','','','','','','','',
                '','','','','','','','',
            ];

            $dataSede['data'][2] = [
                '','','','','','','','','',
                'PRESENZE GIORNALIERE   (1 = PRESENTE, 0 = ASSENTE [oltre le 72 ore])','','','','','','','','','','','','','','','','','','','','','','','','','','','','','','',''
            ];

            $dataSede['data'][3] = [
                'N.','ID VESTANET','COGNOME','NOME','DATA DI NASCITA','NAZIONALITA\'','SESSO','DATA CHECK-IN','DATA CHECK-OUT',
                '01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30','31',
                'TOT. PRESENZE MESE'
            ];

            if (!empty($sede['guests'])) {
                $tot01 = 0;
                $tot02 = 0;
                $tot03 = 0;
                $tot04 = 0;
                $tot05 = 0;
                $tot06 = 0;
                $tot07 = 0;
                $tot08 = 0;
                $tot09 = 0;
                $tot10 = 0;
                $tot11 = 0;
                $tot12 = 0;
                $tot13 = 0;
                $tot14 = 0;
                $tot15 = 0;
                $tot16 = 0;
                $tot17 = 0;
                $tot18 = 0;
                $tot19 = 0;
                $tot20 = 0;
                $tot21 = 0;
                $tot22 = 0;
                $tot23 = 0;
                $tot24 = 0;
                $tot25 = 0;
                $tot26 = 0;
                $tot27 = 0;
                $tot28 = 0;
                $tot29 = 0;
                $tot30 = 0;
                $tot31 = 0;
                $totTot = 0;

                foreach ($sede['guests'] as $i => $guest) {
                    $dataSede['data'][] = [
                        $i+1,
                        $guest['g']['vestanet_id'],
                        $guest['g']['surname'],
                        $guest['g']['name'],
                        empty($guest['g']['birthdate']) ? '' : implode('/', array_reverse(explode('-', $guest['g']['birthdate']))),
                        $guest['n']['des_luo'],
                        $guest['g']['sex'],
                        empty($guest['g']['check_in_date']) ? '' : implode('/', array_reverse(explode('-', $guest['g']['check_in_date']))),
                        empty($guest['g']['check_out_date']) ? '' : implode('/', array_reverse(explode('-', $guest['g']['check_out_date']))),
                        $guest['presenza_01'],
                        $guest['presenza_02'],
                        $guest['presenza_03'],
                        $guest['presenza_04'],
                        $guest['presenza_05'],
                        $guest['presenza_06'],
                        $guest['presenza_07'],
                        $guest['presenza_08'],
                        $guest['presenza_09'],
                        $guest['presenza_10'],
                        $guest['presenza_11'],
                        $guest['presenza_12'],
                        $guest['presenza_13'],
                        $guest['presenza_14'],
                        $guest['presenza_15'],
                        $guest['presenza_16'],
                        $guest['presenza_17'],
                        $guest['presenza_18'],
                        $guest['presenza_19'],
                        $guest['presenza_20'],
                        $guest['presenza_21'],
                        $guest['presenza_22'],
                        $guest['presenza_23'],
                        $guest['presenza_24'],
                        $guest['presenza_25'],
                        $guest['presenza_26'],
                        $guest['presenza_27'],
                        $guest['presenza_28'],
                        $guest['presenza_29'],
                        $guest['presenza_30'],
                        $guest['presenza_31'],
                        $guest['tot_presenze'],
                    ];

                    $tot01 += $guest['presenza_01'];
                    $tot02 += $guest['presenza_02'];
                    $tot03 += $guest['presenza_03'];
                    $tot04 += $guest['presenza_04'];
                    $tot05 += $guest['presenza_05'];
                    $tot06 += $guest['presenza_06'];
                    $tot07 += $guest['presenza_07'];
                    $tot08 += $guest['presenza_08'];
                    $tot09 += $guest['presenza_09'];
                    $tot10 += $guest['presenza_10'];
                    $tot11 += $guest['presenza_11'];
                    $tot12 += $guest['presenza_12'];
                    $tot13 += $guest['presenza_13'];
                    $tot14 += $guest['presenza_14'];
                    $tot15 += $guest['presenza_15'];
                    $tot16 += $guest['presenza_16'];
                    $tot17 += $guest['presenza_17'];
                    $tot18 += $guest['presenza_18'];
                    $tot19 += $guest['presenza_19'];
                    $tot20 += $guest['presenza_20'];
                    $tot21 += $guest['presenza_21'];
                    $tot22 += $guest['presenza_22'];
                    $tot23 += $guest['presenza_23'];
                    $tot24 += $guest['presenza_24'];
                    $tot25 += $guest['presenza_25'];
                    $tot26 += $guest['presenza_26'];
                    $tot27 += $guest['presenza_27'];
                    $tot28 += $guest['presenza_28'];
                    $tot29 += $guest['presenza_29'];
                    $tot30 += $guest['presenza_30'];
                    $tot31 += $guest['presenza_31'];
                    $totTot += $guest['tot_presenze'];
                }

                $dataSede['data'][] = [
                    '','','','','','','','','TOTALE',
                    strval($tot01), strval($tot02), strval($tot03), strval($tot04), strval($tot05), strval($tot06), strval($tot07), strval($tot08), strval($tot09), strval($tot10),
                    strval($tot11), strval($tot12), strval($tot13), strval($tot14), strval($tot15), strval($tot16), strval($tot17), strval($tot18), strval($tot19), strval($tot20),
                    strval($tot21), strval($tot22), strval($tot23), strval($tot24), strval($tot25), strval($tot26), strval($tot27), strval($tot28), strval($tot29), strval($tot30),
                    strval($tot31), strval($totTot)
                ];
            } else {
                $dataSede['data'][] = [
                    'Nessun ospite trovato','','','','','','','','','','','','','','','','','','','','','','',
                    '','','','','','','','','','','','','','','','','',''
                ];
            }

            $data[] = $dataSede;
        }

        return $data;
    }
    
}
