<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * Statements Model
 *
 * @property \Aziende\Model\Table\AgreementsTable&\Cake\ORM\Association\BelongsTo $Agreements
 * @property \aziende\Model\Table\PeriodsTable&\Cake\ORM\Association\BelongsTo $Periods
 * @property \aziende\Model\Table\StatementCompanyTable&\Cake\ORM\Association\HasMany $StatementCompany
 *
 * @method \aziende\Model\Entity\Statement get($primaryKey, $options = [])
 * @method \aziende\Model\Entity\Statement newEntity($data = null, array $options = [])
 * @method \aziende\Model\Entity\Statement[] newEntities(array $data, array $options = [])
 * @method \aziende\Model\Entity\Statement|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \aziende\Model\Entity\Statement saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \aziende\Model\Entity\Statement patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \aziende\Model\Entity\Statement[] patchEntities($entities, array $data, array $options = [])
 * @method \aziende\Model\Entity\Statement findOrCreate($search, callable $callback = null, $options = [])
 */
class StatementsTable extends AppTable
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

        $this->setTable('statements');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->belongsTo('Agreements', [
            'foreignKey' => 'agreement_id',
            'className' => 'Aziende.Agreements',
            'propertyName' => 'agreement'
        ]);
        $this->belongsTo('Periods', [
            'bindingKey' => 'id',
            'foreignKey' => 'period_id',
            'className' => 'Aziende.Periods',
            'propertyName' => 'period'
        ]);
        $this->hasMany('StatementCompany', [
            'foreignKey' => 'statement_id',
            'className' => 'Aziende.StatementCompany',
            'propertyName' => 'companies'
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

    public function getStatementsByAgreement($agreementId)
    {
        return $this->find()->where(['agreement_id' => $agreementId])->order(['period_start_date' => 'ASC'])->toArray();
    }

}
