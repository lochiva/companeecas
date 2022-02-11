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
 * @property \Diary\Model\Table\SedesTable|\Cake\ORM\Association\BelongsTo $Sedes
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
            ->requirePresence('name', 'create')
            ->allowEmptyString('name', false);

        $validator
            ->scalar('cui')
            ->maxLength('cui', 7)
            ->allowEmptyString('cui', true);

        $validator
            ->scalar('vestanet_id')
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
            ->allowEmptyString('birthdate', true);

        $validator
            ->integer('country_birth')
            ->allowEmptyString('country_birth', true);

        $validator
            ->scalar('sex')
            ->maxLength('sex', 1)
            ->allowEmptyString('sex', true);

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

        return $rules;
    }

}