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
            'educational_qualification_id' => 'ID titolo di studio',
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
                    'cui' => $entity->cui
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
                'sex' => $entity->sex
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
            ->select(['presente' => 'p.presente', 'note' => 'p.note'])
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
            'OR' => [
                'CONCAT(Guests.cui, " - ", Guests.name, " ", Guests.surname) LIKE' => '%'.$search.'%',
                'CONCAT(Guests.cui, " ", Guests.name, " ", Guests.surname) LIKE' => '%'.$search.'%',
                'CONCAT(Guests.cui, " ", Guests.surname, " ", Guests.name) LIKE' => '%'.$search.'%',
                'CONCAT(Guests.name, " ", Guests.cui, " ", Guests.surname) LIKE' => '%'.$search.'%',
                'CONCAT(Guests.name, " ", Guests.surname, " ", Guests.cui) LIKE' => '%'.$search.'%',
                'CONCAT(Guests.surname, " ", Guests.cui, " ", Guests.name) LIKE' => '%'.$search.'%',
                'CONCAT(Guests.surname, " ", Guests.name, " ", Guests.cui) LIKE' => '%'.$search.'%'
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
            ->select(['Guests.id', 'Guests.name', 'Guests.surname', 
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

}