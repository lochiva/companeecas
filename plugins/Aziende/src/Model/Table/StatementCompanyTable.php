<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;
/**
 * StatementCompany Model
 *
 * @property \aziende\Model\Table\StatementsTable&\Cake\ORM\Association\BelongsTo $Statements
 * @property \Aziende\Model\Table\AgreementsCompaniesTable&\Cake\ORM\Association\BelongsTo $AgreementsCompanies
 * @property \aziende\Model\Table\StatusTable&\Cake\ORM\Association\BelongsTo $Status
 *
 * @method \aziende\Model\Entity\StatementCompany get($primaryKey, $options = [])
 * @method \aziende\Model\Entity\StatementCompany newEntity($data = null, array $options = [])
 * @method \aziende\Model\Entity\StatementCompany[] newEntities(array $data, array $options = [])
 * @method \aziende\Model\Entity\StatementCompany|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \aziende\Model\Entity\StatementCompany saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \aziende\Model\Entity\StatementCompany patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \aziende\Model\Entity\StatementCompany[] patchEntities($entities, array $data, array $options = [])
 * @method \aziende\Model\Entity\StatementCompany findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class StatementCompanyTable extends AppTable
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

        $this->setTable('statement_company');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Statements', [
            'foreignKey' => 'statement_id',
            'className' => 'Aziende.Statements',
            'propertyName' => 'statement'
        ]);

        $this->belongsTo('AgreementsCompanies', [
            'foreignKey' => 'company_id',
            'className' => 'Aziende.AgreementsCompanies',
            'propertyName' => 'company'
        ]);

        $this->belongsTo('Status', [
            'foreignKey' => 'status_id',
            'className' => 'Aziende.Status',
            'propertyName' => 'status'
        ]);

        $this->hasMany('Costs', [
            'foreignKey' => 'statement_company',
            'bindingKey' => 'id',
            'className' => 'Aziende.Costs',
            'propertyName' => 'costs'
        ]);

        $this->hasMany('History', [
            'foreignKey' => 'statement_company_id',
            'bindingKey' => 'id',
            'sort' => ['History.created' => 'ASC'],
            'className' => 'Aziende.StatementsStatusHistory',
            'propertyName' => 'history'
        ]);

        $this->hasMany('StatementsNotifications', [
            'foreignKey' => 'statement_company_id',
            'bindingKey' => 'id',
            'className' => 'aziende.StatementsNotifications',
            'propertyName' => 'notifications'
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

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {

        return $rules;
    }
}
