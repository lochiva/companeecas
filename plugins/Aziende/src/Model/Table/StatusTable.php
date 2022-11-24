<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * Status Model
 *
 * @property \Aziende\Model\Table\GuestsTable&\Cake\ORM\Association\HasMany $Guests
 * @property \Aziende\Model\Table\ReportComnapyTable&\Cake\ORM\Association\HasMany $ReportComnapy
 * @property \Aziende\Model\Table\ReportCompanyTable&\Cake\ORM\Association\HasMany $ReportCompany
 * @property \aziende\Model\Table\StatementCompanyTable&\Cake\ORM\Association\HasMany $StatementCompany
 * @property \Aziende\Model\Table\GuestsTable&\Cake\ORM\Association\BelongsToMany $Guests
 * @property \Aziende\Model\Table\OffersTable&\Cake\ORM\Association\BelongsToMany $Offers
 * @property \Aziende\Model\Table\OrdersTable&\Cake\ORM\Association\BelongsToMany $Orders
 * @property \Aziende\Model\Table\SurveysInterviewsTable&\Cake\ORM\Association\BelongsToMany $SurveysInterviews
 * @property \Aziende\Model\Table\SurveysTable&\Cake\ORM\Association\BelongsToMany $Surveys
 *
 * @method \Aziende\Model\Entity\Status get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\Status newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\Status[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\Status|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\Status saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\Status patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\Status[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\Status findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StatusTable extends AppTable
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

        $this->setTable('status');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->hasMany('StatementCompany', [
            'foreignKey' => 'status_id',
            'className' => 'Aziende.StatementCompany'
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
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        return $validator;
    }
}
