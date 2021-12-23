<?php
namespace Progest\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProgestCategories Model
 *
 * @method \Progest\Model\Entity\ProgestCategory get($primaryKey, $options = [])
 * @method \Progest\Model\Entity\ProgestCategory newEntity($data = null, array $options = [])
 * @method \Progest\Model\Entity\ProgestCategory[] newEntities(array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestCategory|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Progest\Model\Entity\ProgestCategory patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestCategory[] patchEntities($entities, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestCategory findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CategoriesTable extends Table
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

        $this->setTable('progest_categories');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsToMany('Services', [
            'foreignKey' => 'id_category',
            'targetForeignKey' => 'id_service',
            'through' => 'Progest.CategoriesServices',
            'className' => 'Progest.Services'
        ]);

        $this->addBehavior('Timestamp');
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
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->integer('ordering')
            ->requirePresence('ordering', 'create')
            ->notEmpty('ordering');

        return $validator;
    }
}
