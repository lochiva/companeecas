<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * Costs Model
 *
 * @property \aziende\Model\Table\CostsCategoriesTable&\Cake\ORM\Association\BelongsTo $CostsCategories
 *
 * @method \aziende\Model\Entity\Cost get($primaryKey, $options = [])
 * @method \aziende\Model\Entity\Cost newEntity($data = null, array $options = [])
 * @method \aziende\Model\Entity\Cost[] newEntities(array $data, array $options = [])
 * @method \aziende\Model\Entity\Cost|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \aziende\Model\Entity\Cost saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \aziende\Model\Entity\Cost patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \aziende\Model\Entity\Cost[] patchEntities($entities, array $data, array $options = [])
 * @method \aziende\Model\Entity\Cost findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CostsTable extends AppTable
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

        $this->setTable('costs');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('CostsCategories', [
            'foreignKey' => 'category_id',
            'className' => 'Aziende.CostsCategories',
            'propertyName' => 'category'
        ]);

        $this->belongsTo('StatementCompany', [
            'foreignKey' => 'statement_company_id',
            'className' => 'Aziende.StatementCompany',
            'propertyName' => 'company'
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
            ->integer('statement_company')
            ->requirePresence('statement_company', 'create')
            ->notEmptyString('statement_company');

        $validator
            ->decimal('amount')
            ->requirePresence('amount', 'create')
            ->notEmptyString('amount');

        $validator
            ->decimal('share')
            ->requirePresence('share', 'create')
            ->notEmptyString('share');

        $validator
            ->scalar('attachment')
            ->maxLength('attachment', 255)
            ->notEmptyString('attachment');

        $validator
            ->scalar('supplier')
            ->maxLength('supplier', 255)
            ->requirePresence('supplier', 'create')
            ->notEmptyString('supplier');

        $validator
            ->scalar('description')
            ->maxLength('description', 255)
            ->allowEmptyString('description', true)
            ->requirePresence('description', false);

        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmptyDate('date');

        $validator
            ->scalar('notes')
            ->maxLength('notes', 255)
            ->allowEmptyString('notes', true)
            ->requirePresence('notes', false);

        $validator
            ->scalar('number')
            ->maxLength('number', 255)
            ->requirePresence('number', 'create')
            ->notEmptyString('number');

        $validator
            ->boolean('deleted');

        $validator
            ->scalar('filename')
            ->maxLength('filename', 255)
            ->notEmptyFile('filename');

        return $validator;
    }

}
