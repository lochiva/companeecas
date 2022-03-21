<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GuestsEducationalQualifications Model
 *
 * @method \Aziende\Model\Entity\GuestsEducationalQualification get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\GuestsEducationalQualification newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\GuestsEducationalQualification[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsEducationalQualification|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsEducationalQualification saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\GuestsEducationalQualification patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsEducationalQualification[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\GuestsEducationalQualification findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GuestsEducationalQualificationsTable extends Table
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

        $this->setTable('guests_educational_qualifications');
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

        $validator
            ->integer('parent')
            ->allowEmptyString('parent');

        $validator
            ->boolean('have_children');

        return $validator;
    }

    public function getByParent($parentId)
    {
        return $this->find()->where(['parent' => $parentId])->order(['ordering' => 'ASC'])->toArray();
    }
}
