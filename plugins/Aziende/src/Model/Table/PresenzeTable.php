<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Presenze Model
 *
 * @property \Aziende\Model\Table\GuestsTable&\Cake\ORM\Association\BelongsTo $Guests
 * @property \Aziende\Model\Table\SediTable&\Cake\ORM\Association\BelongsTo $Sedi
 *
 * @method \Aziende\Model\Entity\Presenze get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\Presenze newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\Presenze[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\Presenze|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\Presenze saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\Presenze patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\Presenze[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\Presenze findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PresenzeTable extends Table
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

        $this->setTable('presenze');
        $this->setDisplayField('guest_id');
        $this->setPrimaryKey(['guest_id', 'date']);

        $this->addBehavior('Timestamp');

        $this->belongsTo('Guests', [
            'foreignKey' => 'guest_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Guests'
        ]);
        $this->belongsTo('Sedi', [
            'foreignKey' => 'sede_id',
            'joinType' => 'INNER',
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
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmptyDateTime('date');

        $validator
            ->boolean('presente')
            ->notEmptyString('presente');

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
        $rules->add($rules->existsIn(['sede_id'], 'Sedi'));

        return $rules;
    }

    public function getPresenzeGuestPrecedentiCheckIn($guestId, $checkInDate)
    {
        $presenze = $this->find()
            ->where([
                'guest_id' => $guestId,
                'date <' => $checkInDate
            ])
            ->toArray();
            
        return $presenze;
    }

    public function getPresenzeSedeForDay($sedeId, $day)
    {
        $presenze = $this->find()
            ->where([
                'sede_id' => $sedeId,
                'date' => $day,
                'presente' => 1
            ])
            ->toArray();
            
        return $presenze;
    }

    public function getPresenzeSedeForMonth($sedeId, $month)
    {
        $presenze = $this->find()
            ->where([
                'sede_id' => $sedeId,
                'date LIKE' => $month.'%',
                'presente' => 1
            ])
            ->toArray();
            
        return $presenze;
    }

    public function getGuestLastPresenzaByDate($guestId, $date)
    {
        $presenza = $this->find()
            ->where([
                'guest_id' => $guestId,
                'presente' => 1,
                'date <=' => $date
            ])
            ->order(['date' => 'DESC'])
            ->first();
            
        return $presenza;
    }

    public function getGuestPresenzaByDate($guestId, $date)
    {
        $presenza = $this->find()
            ->where([
                'guest_id' => $guestId,
                'presente' => 1,
                'date' => $date
            ])
            ->first();
            
        return $presenza;
    }
}
