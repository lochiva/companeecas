<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Guests   (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
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

        $this->belongsTo('GuestsExitRequestStatuses', [
            'foreignKey' => 'exit_request_status_id',
            'joinType' => 'LEFT',
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
            ->scalar('temporary_id')
            ->minLength('temporary_id', 20)
            ->maxLength('temporary_id', 20)
            ->allowEmptyString('temporary_id', true);

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
        $rules->add($rules->existsIn(['exit_request_status_id'], 'GuestsExitRequestStatuses'));
        $rules->add($rules->existsIn(['educational_qualification_id'], 'EducationalQualifications'));
        $rules->add(
            function ($entity, $options) {
                if (!empty($entity['original_guest_id'])) {
                    $originalGuest = $this->triggerBeforeFind(false)->find()->where(['id' => $entity['original_guest_id']])->first();
                    return !empty($originalGuest);
                }
                return true;
            },
            ['errorField' => 'original_guest_id', 'message' => 'Questo valore non esiste']
        );
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
            'exit_request_status_id' => 'Stato richiesta di uscita',
            'original_guest_id' => 'ID ospite originale',
            'deleted' => 'Cancellato',
            'created' => 'Data creazione',
            'modified' => 'Data modifica'
        ];
    }

    public function beforeSave($event, $entity, $options)
    {
        $this->triggerBeforeFind(true);

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
                    $where[] = [
                        'OR' => [
                            'original_guest_id IS NULL',
                            'original_guest_id !=' => $entity->id
                        ]
                    ];
                }
                if (!empty($entity->original_guest_id)) {
                    $where[] = ['id !=' => $entity->original_guest_id];
                    $where[] = [
                        'OR' => [
                            'original_guest_id IS NULL',
                            'original_guest_id !=' => $entity->original_guest_id
                        ]
                    ];
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
                    $where[] = [
                        'OR' => [
                            'original_guest_id IS NULL',
                            'original_guest_id !=' => $entity->id
                        ]
                    ];
                }
                if (!empty($entity->original_guest_id)) {
                    $where[] = ['id !=' => $entity->original_guest_id];
                    $where[] = [
                        'OR' => [
                            'original_guest_id IS NULL',
                            'original_guest_id !=' => $entity->original_guest_id
                        ]
                    ];
                }
                $guest = $this->find()->where($where)->first();
                if (!empty($guest)) {
                    $entity->setError('vestanet_id', ['L\'ID Vestanet deve essere univoco.']);
                    return false;
                }
            }

            // Controllo combinazione nome, cognome, data di nascita, paese di nascita, sesso rispetto agli ospiti senza cui e vestanet
            $where = [
                'cui' => '',
                'name' => $entity->name,
                'surname' => $entity->surname,
                'birthdate' => $entity->birthdate,
                'country_birth' => $entity->country_birth,
                'sex' => $entity->sex,
                'status_id IN' => [1, 2, 5]
            ];
            if ($entity->minor) {
                $where['OR'] = [
                    ['vestanet_id' => ''],
                    ['vestanet_id' => $entity->vestanet_id],
                ];
            } else {
                $where['OR'] = [
                    ['vestanet_id' => ''],
                    ['vestanet_id' => $entity->vestanet_id, 'minor' => 1],
                ];
            }
            if (!empty($entity->id)) {
                $where[] = ['id !=' => $entity->id];
                $where[] = [
                    'OR' => [
                        'original_guest_id IS NULL',
                        'original_guest_id !=' => $entity->id
                    ]
                ];
            }
            if (!empty($entity->original_guest_id)) {
                $where[] = ['id !=' => $entity->original_guest_id];
                $where[] = [
                    'OR' => [
                        'original_guest_id IS NULL',
                        'original_guest_id !=' => $entity->original_guest_id
                    ]
                ];
            }
            $guest = $this->find()->where($where)->first();
            if (!empty($guest)) {
                $entity->setError('cui', ['Non possono esistere due ospiti con nome, cognome, data di nascita, nazionalità e sesso uguali senza che entrambi abbiano un CUI e/o un ID Vestanet.']);
                return false;
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
                $where[] = [
                    'OR' => [
                        'original_guest_id IS NULL',
                        'original_guest_id !=' => $entity->id
                    ]
                ];
            }
            if (!empty($entity->original_guest_id)) {
                $where[] = ['id !=' => $entity->original_guest_id];
                $where[] = [
                    'OR' => [
                        'original_guest_id IS NULL',
                        'original_guest_id !=' => $entity->original_guest_id
                    ]
                ];
            }
            $guest = $this->find()->where($where)->first();
            if (!empty($guest)) {
                $entity->setError('cui', ['In mancanza di un CUI o un ID Vestanet, non possono esistere due ospiti con nome, cognome, data di nascita, nazionalità e sesso uguali.']);
                return false;
            }
        }

        return true;
    }

    public function getGuestsForPresenze($sedeId, $date)
    {
        $guests = $this->find()
            ->select($this)
            ->select(['id_presenza' => 'p.id', 'presente' => 'p.presente', 'note' => 'p.note', 'country_birth_name' => 'l.des_luo'])
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
            'Guests.exit_request_status_id IS NULL',
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

    public function getDataForExportGuests($type, $aziendaId = '')
    {
        $where = [
            'a.id_tipo' => $type,
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
            'MINORE', 'MINORE SOLO', 'NOTE', 'NAZIONALITA\'', 'TITOLO DI STUDIO', 'DETTAGLIO TITOLO DI STUDIO', 'STATO', 'CUI', 'VESTANET'
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
                $guest['gs']['name'],
                $guest['cui'],
                $guest['vestanet_id']
            ];
        }

        return $data;
    }

    public function checkIfExistsFutureGuest($guest)
    {
        $where['created >'] = $guest->created;
        if (!empty($guest->original_guest_id)) {
            $where['original_guest_id'] = $guest->original_guest_id;
        } else {
            $where['original_guest_id'] = $guest->id;
        }

        $guest = $this->find()
            ->where($where)
            ->first();

        return empty($guest) ? false : true;
    }

}