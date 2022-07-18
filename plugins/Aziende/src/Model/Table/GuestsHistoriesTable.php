<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GuestsHistories Model
 *
 * @property \Aziende\Model\Table\GuestsTable&\Cake\ORM\Association\BelongsTo $Guests
 * @property \Aziende\Model\Table\AziendeTable&\Cake\ORM\Association\BelongsTo $Aziende
 * @property \Aziende\Model\Table\SediTable&\Cake\ORM\Association\BelongsTo $Sedi
 * @property \Model\Table\UsersTable&\Cake\ORM\Association\BelongsTo $Operators
 * @property \Aziende\Model\Table\GuestsStatusesTable&\Cake\ORM\Association\BelongsTo $GuestsStatuses
 * @property \Aziende\Model\Table\GuestsExitTypesTable&\Cake\ORM\Association\BelongsTo $ExitTypes
 * @property \Aziende\Model\Table\GuestsTable&\Cake\ORM\Association\BelongsTo $ClonedGuests
 * @property \Aziende\Model\Table\SediTable&\Cake\ORM\Association\BelongsTo $Destinations
 * @property \Aziende\Model\Table\SediTable&\Cake\ORM\Association\BelongsTo $Provenances
 *
 * @method \Aziende\Model\Entity\GuestsHistory get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\GuestsHistory newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\GuestsHistory[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsHistory|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsHistory saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsHistory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsHistory[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsHistory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GuestsHistoriesTable extends Table
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

        $this->setTable('guests_histories');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Guests', [
            'foreignKey' => 'guest_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Guests'
        ]);
        $this->belongsTo('Aziende', [
            'foreignKey' => 'azienda_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Aziende'
        ]);
        $this->belongsTo('Sedi', [
            'foreignKey' => 'sede_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Sedi'
        ]);
        $this->belongsTo('Users', [
            'foreignKey' => 'operator_id',
            'joinType' => 'INNER',
            'className' => 'Users'
        ]);
        $this->belongsTo('GuestsStatuses', [
            'foreignKey' => 'guest_status_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.GuestsStatuses'
        ]);
        $this->belongsTo('ExitTypes', [
            'foreignKey' => 'exit_type_id',
            'className' => 'Aziende.GuestsExitTypes'
        ]);
        $this->belongsTo('ClonedGuests', [
            'foreignKey' => 'cloned_guest_id',
            'className' => 'Aziende.Guests'
        ]);
        $this->belongsTo('Destinations', [
            'foreignKey' => 'destination_id',
            'className' => 'Aziende.Sedi'
        ]);
        $this->belongsTo('Provenances', [
            'foreignKey' => 'provenance_id',
            'className' => 'Aziende.Sedi'
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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->date('operation_date')
            ->allowEmptyDate('operation_date');

        $validator
            ->date('exit_date')
            ->allowEmptyDate('exit_date');

        $validator
            ->scalar('file')
            ->allowEmptyString('file');

        $validator
            ->scalar('note')
            ->allowEmptyString('note');

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
        $rules->add($rules->existsIn(['guest_id'], 'Guests'));
        $rules->add($rules->existsIn(['azienda_id'], 'Aziende'));
        $rules->add($rules->existsIn(['sede_id'], 'Sedi'));
        $rules->add($rules->existsIn(['operator_id'], 'Users'));
        $rules->add($rules->existsIn(['guest_status_id'], 'GuestsStatuses'));
        $rules->add($rules->existsIn(['exit_type_id'], 'ExitTypes'));
        $rules->add($rules->existsIn(['cloned_guest_id'], 'ClonedGuests'));
        $rules->add($rules->existsIn(['destination_id'], 'Destinations'));
        $rules->add($rules->existsIn(['provenance_id'], 'Provenances'));

        return $rules;
    }

    public function getHistoryGuest($guestId)
    {
        return $this->find()
            ->select([
                'GuestsHistories.id', 
                'GuestsHistories.guest_id',
                'GuestsHistories.operation_date',
                'GuestsHistories.exit_type_id', 
                'GuestsHistories.destination_id', 
                'GuestsHistories.provenance_id',
                'GuestsHistories.guest_status_id',
                'GuestsHistories.file',
                'GuestsHistories.note', 
                'azienda' => 'a.denominazione',
                'sede' => 'CONCAT(s.indirizzo, " ", s.num_civico, " ", ls.des_luo, " (", ls.s_prv, ") [", s.code_centro, "]")',
                'status' => 'gs.name',
                'exit_type' => 'et.name',
                'destination' => 'CONCAT(ad.denominazione, " - ", d.indirizzo, " ", d.num_civico, " ", ld.des_luo, " (", ld.s_prv, ") [", d.code_centro, "]")',
                'provenance' => 'CONCAT(ap.denominazione, " - ", p.indirizzo, " ", p.num_civico, " ", lp.des_luo, " (", lp.s_prv, ") [", p.code_centro, "]")',
                'operator' => 'CONCAT(u.nome, " ", u.cognome)',
            ])
            ->where(['GuestsHistories.guest_id' => $guestId])
            ->join([
                [
                    'table' => 'aziende',
                    'alias' => 'a',
                    'type' => 'left',
                    'conditions' => 'a.id = GuestsHistories.azienda_id'
                ],
                [
                    'table' => 'sedi',
                    'alias' => 's',
                    'type' => 'left',
                    'conditions' => 's.id = GuestsHistories.sede_id'
                ],
                [
                    'table' => 'luoghi',
                    'alias' => 'ls',
                    'type' => 'left',
                    'conditions' => 'ls.c_luo = s.comune'
                ],
                [
                    'table' => 'guests_statuses',
                    'alias' => 'gs',
                    'type' => 'left',
                    'conditions' => 'gs.id = GuestsHistories.guest_status_id'
                ],
                [
                    'table' => 'guests_exit_types',
                    'alias' => 'et',
                    'type' => 'left',
                    'conditions' => 'et.id = GuestsHistories.exit_type_id'
                ],
                [
                    'table' => 'sedi',
                    'alias' => 'd',
                    'type' => 'left',
                    'conditions' => 'd.id = GuestsHistories.destination_id'
                ],
                [
                    'table' => 'aziende',
                    'alias' => 'ad',
                    'type' => 'left',
                    'conditions' => 'ad.id = d.id_azienda'
                ],
                [
                    'table' => 'luoghi',
                    'alias' => 'ld',
                    'type' => 'left',
                    'conditions' => 'ld.c_luo = d.comune'
                ],
                [
                    'table' => 'sedi',
                    'alias' => 'p',
                    'type' => 'left',
                    'conditions' => 'p.id = GuestsHistories.provenance_id'
                ],
                [
                    'table' => 'aziende',
                    'alias' => 'ap',
                    'type' => 'left',
                    'conditions' => 'ap.id = p.id_azienda'
                ],
                [
                    'table' => 'luoghi',
                    'alias' => 'lp',
                    'type' => 'left',
                    'conditions' => 'lp.c_luo = p.comune'
                ],
                [
                    'table' => 'users',
                    'alias' => 'u',
                    'type' => 'left',
                    'conditions' => 'u.id = GuestsHistories.operator_id'
                ]
            ])
            ->order(['GuestsHistories.created' => 'DESC', 'GuestsHistories.id' => 'DESC'])
            ->toArray();
    }

    public function getLastGuestHistoryByStatus($guestId, $statusId) {
        return $this->find()
            ->where(['guest_id' => $guestId, 'guest_status_id' => $statusId])
            ->order(['created' => 'DESC', 'id' => 'DESC'])
            ->first();
    }
}
