<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * GuestsNotifications Model
 *
 * @property \Aziende\Model\Table\TypesTable&\Cake\ORM\Association\BelongsTo $Types
 * @property \Aziende\Model\Table\AziendasTable&\Cake\ORM\Association\BelongsTo $Aziendas
 * @property \Aziende\Model\Table\SedesTable&\Cake\ORM\Association\BelongsTo $Sedes
 * @property \Aziende\Model\Table\GuestsTable&\Cake\ORM\Association\BelongsTo $Guests
 * @property \Aziende\Model\Table\UserMakersTable&\Cake\ORM\Association\BelongsTo $UserMakers
 * @property \Aziende\Model\Table\UserDonesTable&\Cake\ORM\Association\BelongsTo $UserDones
 *
 * @method \Aziende\Model\Entity\GuestsNotification get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\GuestsNotification newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\GuestsNotification[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsNotification|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsNotification saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsNotification patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsNotification[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsNotification findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GuestsNotificationsTable extends AppTable
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

        $this->setTable('guests_notifications');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Types', [
            'foreignKey' => 'type_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.GuestsNotificationsTypes'
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
        $this->belongsTo('Guests', [
            'foreignKey' => 'guest_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Guests'
        ]);
        $this->belongsTo('UsersMakers', [
            'foreignKey' => 'user_maker_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Users'
        ]);
        $this->belongsTo('UsersDones', [
            'foreignKey' => 'user_done_id',
            'className' => 'Aziende.Users'
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
            ->scalar('text');

        $validator
            ->boolean('done');

        $validator
            ->date('done_date')
            ->allowEmptyDate('done_date');

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
        $rules->add($rules->existsIn(['type_id'], 'Types'));
        $rules->add($rules->existsIn(['azienda_id'], 'Aziende'));
        $rules->add($rules->existsIn(['sede_id'], 'Sedi'));
        $rules->add($rules->existsIn(['user_maker_id'], 'UsersMakers'));
        $rules->add($rules->existsIn(['user_done_id'], 'UsersDones'));

        return $rules;
    }

    public function countGuestsNotifications($all = false)
    {
        $where = [];
        if (!$all) {
            $where['done'] = 0;
        }
        return $this->find()->where($where)->count();
    }

    public function getGuestsNotificationsForHome()
    {
        $messages = $this->find()
            ->select([
                'type_count' => 'COUNT(GuestsNotifications.id)',
                'message' => 'IF(COUNT(GuestsNotifications.id) > 1, Types.msg_plural, Types.msg_singular)'
            ])
            ->where(['GuestsNotifications.done' => 0])
            ->contain(['Types'])
            ->group(['GuestsNotifications.type_id'])
            ->toArray();

        return $messages;
    }
}
