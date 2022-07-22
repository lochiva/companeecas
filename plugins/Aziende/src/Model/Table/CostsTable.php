<?php
namespace aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

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
class CostsTable extends Table
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

        return $validator;
    }

}
