<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Statements Notifications  (https://www.companee.it)
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
namespace aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * StatementsNotifications Model
 *
 * @property \Aziende\Model\Table\StatementsTable&\Cake\ORM\Association\BelongsTo $Statements
 * @property \aziende\Model\Table\StatementCompaniesTable&\Cake\ORM\Association\BelongsTo $StatementsNotifications
 * @property \aziende\Model\Table\UserMakersTable&\Cake\ORM\Association\BelongsTo $UserMakers
 * @property \aziende\Model\Table\UserDonesTable&\Cake\ORM\Association\BelongsTo $UserDones
 *
 * @method \aziende\Model\Entity\StatementsNotification get($primaryKey, $options = [])
 * @method \aziende\Model\Entity\StatementsNotification newEntity($data = null, array $options = [])
 * @method \aziende\Model\Entity\StatementsNotification[] newEntities(array $data, array $options = [])
 * @method \aziende\Model\Entity\StatementsNotification|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \aziende\Model\Entity\StatementsNotification saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \aziende\Model\Entity\StatementsNotification patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \aziende\Model\Entity\StatementsNotification[] patchEntities($entities, array $data, array $options = [])
 * @method \aziende\Model\Entity\StatementsNotification findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StatementsNotificationsTable extends AppTable
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

        $this->setTable('statements_notifications');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Statements', [
            'foreignKey' => 'statement_id',
            'joinType' => 'LEFT',
            'className' => 'aziende.Statements'
        ]);
        $this->belongsTo('StatementCompany', [
            'foreignKey' => 'statement_company_id',
            'joinType' => 'LEFT',
            'className' => 'aziende.StatementCompany'
        ]);
        $this->belongsTo('UsersMakers', [
            'foreignKey' => 'user_maker_id',
            'joinType' => 'LEFT',
            'className' => 'Aziende.Users'
        ]);
        $this->belongsTo('UsersDones', [
            'foreignKey' => 'user_done_id',
            'joinType' => 'LEFT',
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

        return $validator;
    }

}
