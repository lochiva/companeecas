<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * Guests Model
 *
 * @property \Diary\Model\Table\SediTable|\Cake\ORM\Association\BelongsTo $Sedi
 * @property \Diary\Model\Table\GuestTypesTable|\Cake\ORM\Association\BelongsTo $GuestTypes
 * @property \Diary\Model\Table\ServiceTypesTable|\Cake\ORM\Association\BelongsTo $ServiceTypes
 *
 * @method \Diary\Model\Entity\Guests get($primaryKey, $options = [])
 * @method \Diary\Model\Entity\Guests newEntity($data = null, array $options = [])
 * @method \Diary\Model\Entity\Guests[] newEntities(array $data, array $options = [])
 * @method \Diary\Model\Entity\Guests|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Diary\Model\Entity\Guests|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Diary\Model\Entity\Guests patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Diary\Model\Entity\Guests[] patchEntities($entities, array $data, array $options = [])
 * @method \Diary\Model\Entity\Guests findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GuestsTable extends AppTable
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('guests');
        $this->setDisplayField('cui');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Sedi', [
            'foreignKey' => 'sede_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Sedi'
        ]);

        $this->belongsTo('FamilyGuests', [
            'foreignKey' => 'family_guest_id',
            'joinType' => 'LEFT',
            'className' => 'Aziende.Guests'
        ]);

        $this->belongsTo('Countries', [
            'foreignKey' => 'country_birth',
            'joinType' => 'LEFT',
            'className' => 'Luoghi'
        ]);

        $this->belongsTo('GuestsStatuses', [
            'foreignKey' => 'status_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.GuestsStatuses'
        ]);

        $this->belongsTo('OriginalGuests', [
            'foreignKey' => 'original_guest_id',
            'joinType' => 'LEFT',
            'className' => 'Aziende.Guests'
        ]);

        $this->belongsTo('EducationalQualifications', [
            'foreignKey' => 'educational_qualification_id',
            'joinType' => 'LEFT',
            'className' => 'Aziende.GuestsEducationalQualifications'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('id')
            ->allowEmptyString('id', 'create');

        $validator
            ->integer('sede_id')
            ->requirePresence('sede_id', 'create')
            ->allowEmptyString('sede_id', false);

        $validator
            ->scalar('cui')
            ->minLength('cui', 7)
            ->maxLength('cui', 7)
            ->allowEmptyString('cui', true);

        $validator
            ->scalar('vestanet_id')
            ->minLength('vestanet_id', 9)
            ->maxLength('vestanet_id', 10)
            ->allowEmptyString('vestanet_id', true);

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('surname')
            ->maxLength('surname', 255)
            ->requirePresence('surname', 'create')
            ->allowEmptyString('surname', false);

        $validator
            ->date('birthdate')
            ->allowEmptyString('birthdate', false);

        $validator
            ->integer('country_birth')
            ->allowEmptyString('country_birth', false);

        $validator
            ->scalar('sex')
            ->maxLength('sex', 1)
            ->allowEmptyString('sex', false);

        $validator
            ->boolean('minor');

        $validator
            ->boolean('minor_alone');

        $validator
            ->scalar('minor_note')
            ->allowEmptyString('minor_note', true);

        $validator
            ->boolean('electronic_residence_permit');

        $validator
            ->boolean('suspended');

        $validator
            ->boolean('draft');

        $validator
            ->date('draft_expiration')
            ->allowEmptyString('draft_expiration', true);

        $validator
            ->date('check_in_date')
            ->allowEmptyString('check_in_date', true);

        $validator
            ->date('check_out_date')
            ->allowEmptyString('check_out_date', true);

        $validator
            ->boolean('deleted');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['sede_id'], 'Sedi'));
        $rules->add($rules->existsIn(['country_birth'], 'Countries'));
        $rules->add($rules->existsIn(['status_id'], 'GuestsStatuses'));
        $rules->add($rules->existsIn(['original_guest_id'], 'OriginalGuests'));
        $rules->add($rules->existsIn(['educational_qualification_id'], 'EducationalQualifications'));

        return $rules;
    }

    public function getFieldLabelsList() {
        return [
            'id' => 'ID',
            'sede_id' => 'ID struttura',
            'check_in_date' => 'Check-in',
            'cui' => 'CUI',
            'vestanet_id' => 'ID Vestanet',
            'name' => 'Nome',
            'surname' => 'Cognome',
            'birthdate' => 'Data di nascita',
            'country_birth' => 'Paese di nascita',
            'sex' => 'Sesso',
            'minor' => 'Minore',
            'minor_alone' => 'Minore solo',
            'minor_note' => 'Note minore',
            'educational_qualification_id' => 'ID titolo di studio',
            'electronic_residence_permit' => 'Permesso di soggiorno elettronico',
            'suspended' => 'Sospeso',
            'draft' => 'Stato anagrafica in bozza',
            'draft_expiration' => 'Scadenza stato bozza',
            'check_in_date' => 'Data di check-in',
            'check_out_date' => 'Data di check-out',
            'status_id' => 'Stato',
            'original_guest_id' => 'ID ospite originale',
            'deleted' => 'Cancellato',
            'created' => 'Data creazione',
            'modified' => 'Data modifica'
        ];
    }

    public function beforeSave($event, $entity, $options)
    {
        // Se presente controllo unicità CUI e ID Vestanet (se non minore)
        if (!empty($entity->cui) || !empty($entity->vestanet_id)) {
            // Controllo CUI
            if (!empty($entity->cui)) {
                $where = [
                    'cui' => $entity->cui,
                    'status_id IN' => [1, 2, 5]
                ];
                if (!empty($entity->id)) {
                    $where[] = ['id !=' => $entity->id];
                    $where[] = ['original_guest_id !=' => $entity->id];
                }
                if (!empty($entity->original_guest_id)) {
                    $where[] = ['original_guest_id !=' => $entity->original_guest_id];
                    $where[] = ['id !=' => $entity->original_guest_id];
                }
                $guest = $this->find()->where($where)->first();
                if (!empty($guest)) {
                    $entity->setError('cui', ['Il CUI deve essere univoco.']);
                    return false;
                }
            }
            //Controllo ID Vestanet
            if (!empty($entity->vestanet_id) && !$entity->minor) {
                $where = [
                    'vestanet_id' => $entity->vestanet_id,
                    'minor' => 0,
                    'status_id IN' => [1, 2, 5]
                ];
                if (!empty($entity->id)) {
                    $where[] = ['id !=' => $entity->id];
                    $where[] = ['original_guest_id !=' => $entity->id];
                }
                if (!empty($entity->original_guest_id)) {
                    $where[] = ['original_guest_id !=' => $entity->original_guest_id];
                    $where[] = ['id !=' => $entity->original_guest_id];
                }
                $guest = $this->find()->where($where)->first();
                if (!empty($guest)) {
                    $entity->setError('vestanet_id', ['L\'ID Vestanet deve essere univoco.']);
                    return false;
                }
            }
        } else {
            // Altrimenti controllo unicità combinazione nome, cognome, data di nascita, paese di nascita, sesso
            $where = [
                'name' => $entity->name,
                'surname' => $entity->surname,
                'birthdate' => $entity->birthdate,
                'country_birth' => $entity->country_birth,
                'sex' => $entity->sex,
                'status_id IN' => [1, 2, 5]
            ];
            if (!empty($entity->id)) {
                $where[] = ['id !=' => $entity->id];
                $where[] = ['original_guest_id !=' => $entity->id];
            }
            if (!empty($entity->original_guest_id)) {
                $where[] = ['original_guest_id !=' => $entity->original_guest_id];
                $where[] = ['id !=' => $entity->original_guest_id];
            }
            $guest = $this->find()->where($where)->first();
            if (!empty($guest)) {
                $entity->setError('cui', ['In mancanza di un CUI o un ID Vestanet, non possono esistere due ospiti con nome, cognome, data di nascita, paese di nascita e sesso uguali.']);
                return false;
            }
        }
        return true;
    }

    public function getGuestsForPresenze($sedeId, $date)
    {
        $guests = $this->find()
            ->select($this)
            ->select(['presente' => 'p.presente', 'note' => 'p.note', 'country_birth_name' => 'l.des_luo'])
            ->where([
                'Guests.sede_id' => $sedeId, 
                'Guests.check_in_date <=' => $date,
                'Guests.check_in_date IS NOT NULL',
                'OR' => [
                    'Guests.check_out_date >' => $date,
                    'Guests.check_out_date IS NULL'
                ]
            ])
            ->join([
                [
                    'table' => 'presenze',
                    'alias' => 'p',
                    'type' => 'LEFT',
                    'conditions' => ['Guests.id = p.guest_id', 'p.date' => $date, 'p.sede_id' => $sedeId]
                ],
                [
                    'table' => 'luoghi',
                    'alias' => 'l',
                    'type' => 'LEFT',
                    'conditions' => ['l.c_luo = Guests.country_birth']
                ]
            ])
            ->toArray();

        return $guests;
    }

    public function countGuestsForSede($sedeId)
    {
        $guests = $this->find()
            ->where([
                'Guests.sede_id' => $sedeId,
                'gs.visibility' => 1
            ])
            ->join([
                [
                    'table' => 'guests_statuses',
                    'alias' => 'gs',
                    'type' => 'LEFT',
                    'conditions' => 'Guests.status_id = gs.id'
                ]
            ])
            ->count();

        return $guests;
    }

    public function searchGuestsForFamily($sedeId, $search, $guestId = "", $guestHasFamily)
    {
        $where = [
            'Guests.sede_id' => $sedeId, 
            'Guests.status_id' => 1,
            'Guests.minor_alone' => 0,
             'OR'=>[
               'Guests.surname LIKE ' => '%'.$search.'%',
               'Guests.name LIKE ' => '%'.$search.'%',
               'Guests.cui LIKE ' => '%'.$search.'%'
             
            ]
        ];

        $join = [];

        if(!empty($guestId)){
            $where['Guests.id !='] = $guestId;
        }

        if($guestHasFamily){
            $where[]= 'gf.id IS NULL';
        }

        return $this->find()
            ->select(['Guests.id', 'Guests.name', 'Guests.surname', 'Guests.minor',
                    'label' => 'CONCAT(Guests.cui,  " - ", Guests.name, " ", Guests.surname)', 'family_id' => 'gf.family_id'
            ])
            ->where($where)
            ->join([
                [
                    'table' => 'guests_families',
                    'alias' => 'gf',
                    'type' => 'left',
                    'conditions' => 'gf.guest_id = Guests.id'
                ]
            ])
            ->order('CONCAT(Guests.cui,  " - ", Guests.name, " ", Guests.surname) ASC')
            ->toArray();
    }

    public function countTitoliStudioEmergenzaUcraina($date, $titoloStudio)
    {
        return $this->find()
            ->where([
                'a.id_tipo' => 2,
                'a.deleted' => 0,
                's.deleted' => 0,
                'Guests.deleted' => 0,
                'Guests.check_in_date <=' => $date,
                [
                    'OR' => [
                        ['Guests.check_out_date >' => $date],
                        ['Guests.check_out_date IS NULL']
                    ]
                ],
                [
                    'OR' => [
                        ['geq.id' => $titoloStudio],
                        ['geq.parent' => $titoloStudio]
                    ]
                ],
            ])
            ->join([
                [
                    'table' => 'sedi',
                    'alias' => 's',
                    'type' => 'left',
                    'conditions' => 's.id = Guests.sede_id'
                ],
                [
                    'table' => 'aziende',
                    'alias' => 'a',
                    'type' => 'left',
                    'conditions' => 'a.id = s.id_azienda'
                ],
                [
                    'table' => 'guests_educational_qualifications',
                    'alias' => 'geq',
                    'type' => 'left',
                    'conditions' => 'geq.id = Guests.educational_qualification_id'
                ]
            ])
            ->count();
    }

    public function getDataForExportGuestsEmergenzaUcraina($aziendaId = '')
    {
        $where = [
            'a.id_tipo' => 2,
            'a.deleted' => 0,
            's.deleted' => 0
        ];

        if (!empty($aziendaId)) {
            $where['a.id'] = $aziendaId;
        }
        
        $guests = $this->find()
            ->select($this)
            ->select([
                'a.denominazione',
                's.code_centro',
                's.indirizzo',
                's.num_civico', 
                'c.des_luo',
                'c.s_prv',
                'n.des_luo',
                'geq.name',
                'geq_parent.name',
                'gs.name'
            ])
            ->where([$where])
            ->join([
                [
                    'table' => 'sedi',
                    'alias' => 's',
                    'type' => 'left',
                    'conditions' => 's.id = Guests.sede_id'
                ],
                [
                    'table' => 'luoghi',
                    'alias' => 'c',
                    'type' => 'left',
                    'conditions' => 'c.c_luo = s.comune'
                ],
                [
                    'table' => 'aziende',
                    'alias' => 'a',
                    'type' => 'left',
                    'conditions' => 'a.id = s.id_azienda'
                ],
                [
                    'table' => 'luoghi',
                    'alias' => 'n',
                    'type' => 'left',
                    'conditions' => 'n.c_luo = Guests.country_birth'
                ],
                [
                    'table' => 'guests_educational_qualifications',
                    'alias' => 'geq',
                    'type' => 'left',
                    'conditions' => 'geq.id = Guests.educational_qualification_id'
                ],
                [
                    'table' => 'guests_educational_qualifications',
                    'alias' => 'geq_parent',
                    'type' => 'left',
                    'conditions' => 'geq_parent.id = geq.parent'
                ],
                [
                    'table' => 'guests_statuses',
                    'alias' => 'gs',
                    'type' => 'left',
                    'conditions' => 'gs.id = Guests.status_id'
                ]
            ])
            ->order('a.denominazione ASC')
            ->toArray();

        $data[0] = [
            'ENTE', 'CODICE CENTRO', 'INDIRIZZO CENTRO', 'CHECK-IN', 'CHECK-OUT', 'NOME', 'COGNOME', 'DATA DI NASCITA', 'SESSO', 
            'MINORE', 'MINORE SOLO', 'NOTE', 'NAZIONALITA\'', 'TITOLO DI STUDIO', 'DETTAGLIO TITOLO DI STUDIO', 'STATO'
        ];

        foreach ($guests as $guest) {
            if (empty($guest['geq_parent']['name'])) {
                $educationaQualification = $guest['geq']['name'];
                $educationaQualificationDetail = '';
            } else {
                $educationaQualification = $guest['geq_parent']['name'];
                $educationaQualificationDetail = $guest['geq']['name'];
            }
            $data[] = [
                $guest['a']['denominazione'],
                $guest['s']['code_centro'],
                $guest['s']['indirizzo'].' '.$guest['s']['num_civico'].', '.$guest['c']['des_luo'].' ('.$guest['c']['s_prv'].')',
                empty($guest['check_in_date']) ? '' : $guest['check_in_date']->format('d/m/Y'),
                empty($guest['check_out_date']) ? '' : $guest['check_out_date']->format('d/m/Y'),
                $guest['name'],
                $guest['surname'],
                empty($guest['birthdate']) ? '' : $guest['birthdate']->format('d/m/Y'),
                $guest['sex'],
                $guest['minor'] ? 'Sì' : 'No',
                $guest['minor_alone'] ? 'Sì' : 'No',
                $guest['minor_note'],
                $guest['n']['des_luo'],
                $educationaQualification,
                $educationaQualificationDetail,
                $guest['gs']['name']
            ];
        }

        return $data;
    }

    public function getDataForExportGuestsCas($aziendaId, $year, $month)
    {
        $res = $this->find()
            ->select($this)
            ->select([
                'a.denominazione', 's.id', 's.code_centro', 's.indirizzo', 's.num_civico', 'c.des_luo', 'n.des_luo',
                'presenza_01' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-01" AND p.presente = 1)',
                'presenza_02' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-02" AND p.presente = 1)',
                'presenza_03' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-03" AND p.presente = 1)',
                'presenza_04' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-04" AND p.presente = 1)',
                'presenza_05' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-05" AND p.presente = 1)',
                'presenza_06' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-06" AND p.presente = 1)',
                'presenza_07' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-07" AND p.presente = 1)',
                'presenza_08' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-08" AND p.presente = 1)',
                'presenza_09' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-09" AND p.presente = 1)',
                'presenza_10' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-10" AND p.presente = 1)',
                'presenza_11' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-11" AND p.presente = 1)',
                'presenza_12' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-12" AND p.presente = 1)',
                'presenza_13' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-13" AND p.presente = 1)',
                'presenza_14' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-14" AND p.presente = 1)',
                'presenza_15' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-15" AND p.presente = 1)',
                'presenza_16' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-16" AND p.presente = 1)',
                'presenza_17' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-17" AND p.presente = 1)',
                'presenza_18' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-18" AND p.presente = 1)',
                'presenza_19' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-19" AND p.presente = 1)',
                'presenza_20' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-20" AND p.presente = 1)',
                'presenza_21' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-21" AND p.presente = 1)',
                'presenza_22' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-22" AND p.presente = 1)',
                'presenza_23' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-23" AND p.presente = 1)',
                'presenza_24' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-24" AND p.presente = 1)',
                'presenza_25' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-25" AND p.presente = 1)',
                'presenza_26' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-26" AND p.presente = 1)',
                'presenza_27' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-27" AND p.presente = 1)',
                'presenza_28' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-28" AND p.presente = 1)',
                'presenza_29' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-29" AND p.presente = 1)',
                'presenza_30' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-30" AND p.presente = 1)',
                'presenza_31' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date = "'.$year.'-'.$month.'-31" AND p.presente = 1)',
                'tot_presenze' => '(SELECT COUNT(*) FROM presenze p WHERE p.guest_id = Guests.id AND p.date LIKE "'.$year.'-'.$month.'%" AND p.presente = 1)',
            ])
            ->where([
                'a.id' => $aziendaId,
                's.deleted' => 0,
                'Guests.check_in_date <=' => $year.'-'.$month.'-31',
                'OR' => [
                    'Guests.check_out_date >' => $year.'-'.$month.'-01',
                    'Guests.check_out_date IS NULL'
                ]
            ])
            ->join([
                [
                    'table' => 'sedi',
                    'alias' => 's',
                    'type' => 'left',
                    'conditions' => 's.id = Guests.sede_id'
                ],
                [
                    'table' => 'aziende',
                    'alias' => 'a',
                    'type' => 'left',
                    'conditions' => 'a.id = s.id_azienda'
                ],
                [
                    'table' => 'luoghi',
                    'alias' => 'c',
                    'type' => 'left',
                    'conditions' => 'c.c_luo = s.comune'
                ],
                [
                    'table' => 'luoghi',
                    'alias' => 'n',
                    'type' => 'left',
                    'conditions' => 'n.c_luo = Guests.country_birth'
                ],
            ])
            ->order(['s.id' => 'ASC', 'Guests.surname' => 'ASC'])
            ->toArray();

        $sedi = [];

        foreach ($res as $guest) {
            if (!isset($sedi[$guest['s']['id']])) {
                $sedi[$guest['s']['id']] = [
                    'ente' => $guest['a']['denominazione'],
                    'code_centro' => $guest['s']['code_centro'],
                    'address' => $guest['s']['indirizzo'].' '.$guest['s']['num_civico'].' '.$guest['c']['des_luo'],
                    'guests' => []
                ];
            }
            $sedi[$guest['s']['id']]['guests'][] = $guest;
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
                '01','02','03','04','05','06','07','08','09','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','20','30','31',
                'TOT. PRESENZE MESE'
            ];

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
                    $guest['vestanet_id'],
                    $guest['surname'],
                    $guest['name'],
                    empty($guest['birthdate']) ? '' : $guest['birthdate']->format('d/m/Y'),
                    $guest['n']['des_luo'],
                    $guest['sex'],
                    empty($guest['check_in_date']) ? '' : $guest['check_in_date']->format('d/m/Y'),
                    empty($guest['check_out_date']) ? '' : $guest['check_out_date']->format('d/m/Y'),
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

            $data[] = $dataSede;
        }

        return $data;
    }

}