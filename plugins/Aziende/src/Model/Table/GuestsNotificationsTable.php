<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Guests Notifications  (https://www.companee.it)
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

    public function countGuestsNotifications($enteType = 1, $all = false)
    {
        $where = [
            'gnt.ente_type' => $enteType
        ];
        if (!$all) {
            $where['GuestsNotifications.done'] = 0;
        }
        $joins = [
            [
                'table' => 'guests_notifications_types',
                'alias' => 'gnt',
                'type' => 'LEFT',
                'conditions' => 'gnt.id = GuestsNotifications.type_id'
            ]
        ];
        return $this->find()->where($where)->join($joins)->count();
    }

    public function getGuestsNotificationsForHome($enteType = 1)
    {
        $messages = $this->find()
            ->select([
                'type_count' => 'COUNT(GuestsNotifications.id)',
                'message' => 'IF(COUNT(GuestsNotifications.id) > 1, Types.msg_plural, Types.msg_singular)'
            ])
            ->where(['Types.ente_type' => $enteType, 'GuestsNotifications.done' => 0])
            ->contain(['Types'])
            ->group(['GuestsNotifications.type_id'])
            ->toArray();

        return $messages;
    }

    public function getGuestsNotificationsByEnteType($enteType)
    {
        $where = [
            'gnt.ente_type' => $enteType,
            'GuestsNotifications.done' => 0
        ];
        $joins = [
            [
                'table' => 'guests_notifications_types',
                'alias' => 'gnt',
                'type' => 'LEFT',
                'conditions' => 'gnt.id = GuestsNotifications.type_id'
            ]
        ];
        return $this->find()->where($where)->join($joins)->toArray();
    }
}
