<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GuestsFamilies Model
 *
 * @property \Aziende\Model\Table\GuestsTable&\Cake\ORM\Association\BelongsTo $Guests
 *
 * @method \Aziende\Model\Entity\GuestsFamily get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\GuestsFamily newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\GuestsFamily[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsFamily|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsFamily saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsFamily patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsFamily[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsFamily findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GuestsFamiliesTable extends Table
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

        $this->setTable('guests_families');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Guests', [
            'foreignKey' => 'guest_id',
            'joinType' => 'LEFT',
            'className' => 'Aziende.Guests'
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
            ->integer('family_id')
            ->allowEmptyString('family_id', false);

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

        return $rules;
    }

    public function getGuestsByFamily($familyId = "", $guestId = "")
    {
        $where['GuestsFamilies.family_id'] = $familyId;
        if(!empty($guestId)){
            $where['GuestsFamilies.guest_id !='] = $guestId;
        }
        $where['g.deleted'] = '0';

        return $this->find()
            ->select([
                'id' => 'g.id', 
                'cui' => 'g.cui', 
                'name' => 'g.name', 
                'surname' => 'g.surname',
                'status_id' => 'g.status_id'
            ])
            ->where($where)
            ->join([
                [
                    'table' => 'guests',
                    'alias' => 'g',
                    'type' => 'left',
                    'conditions' => 'g.id = GuestsFamilies.guest_id'
                ]
            ])
            ->toArray();
    }
}
