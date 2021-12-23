<?php
namespace Progest\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProgestCategoriesServices Model
 *
 * @method \Progest\Model\Entity\ProgestCategoriesService get($primaryKey, $options = [])
 * @method \Progest\Model\Entity\ProgestCategoriesService newEntity($data = null, array $options = [])
 * @method \Progest\Model\Entity\ProgestCategoriesService[] newEntities(array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestCategoriesService|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Progest\Model\Entity\ProgestCategoriesService patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestCategoriesService[] patchEntities($entities, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestCategoriesService findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CategoriesServicesTable extends Table
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

        $this->setTable('progest_categories_services');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->belongsTo('Categories', [
            'foreignKey' => 'id_category',
            'className' => 'Progest.Categories'
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
            ->integer('id_service')
            ->requirePresence('id_service', 'create')
            ->notEmpty('id_service');

        $validator
            ->integer('id_category')
            ->requirePresence('id_category', 'create')
            ->notEmpty('id_category');

        return $validator;
    }
}
