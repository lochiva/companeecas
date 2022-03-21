<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AziendeTipi Model
 *
 * @method \Aziende\Model\Entity\AziendeTipi get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\AziendeTipi newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\AziendeTipi[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\AziendeTipi|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\AziendeTipi saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\AziendeTipi patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\AziendeTipi[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\AziendeTipi findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AziendeTipiTable extends Table
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

        $this->setTable('aziende_tipi');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

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
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->requirePresence('name', 'create')
            ->notEmptyString('name');

        $validator
            ->integer('ordering')
            ->notEmptyString('ordering');

        return $validator;
    }

    public function getList($conditions = array())
  	{
        return $this->find()
            ->order(['ordering' => 'ASC'])
            ->where($conditions)
            ->toArray();
  	}
}
