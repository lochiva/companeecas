<?php
namespace Reports\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * Documenti Model
 *
 * @method \Reports\Model\Entity\Document get($primaryKey, $options = [])
 * @method \Reports\Model\Entity\Document newEntity($data = null, array $options = [])
 * @method \Reports\Model\Entity\Document[] newEntities(array $data, array $options = [])
 * @method \Reports\Model\Entity\Document|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Reports\Model\Entity\Document patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Reports\Model\Entity\Document[] patchEntities($entities, array $data, array $options = [])
 * @method \Reports\Model\Entity\Document findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class DocumentsTable extends AppTable
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

        $this->setTable('reports_documents');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->setEntityClass('Reports.Document');

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
            ->integer('report_id')
            ->requirePresence('report_id', 'create')
            ->allowEmpty('report_id', false);

        $validator
            ->scalar('file')
            ->maxLength('file', 255)
            ->requirePresence('file', 'create')
            ->allowEmptyString('file', false);

        $validator
            ->scalar('file_path')
            ->maxLength('file_path', 255)
            ->requirePresence('file_path', 'create')
            ->allowEmptyString('file_path', false);

        $validator
            ->scalar('title')
            ->maxLength('title', 255)
            ->allowEmptyString('title', true);

        $validator
            ->scalar('description')
            ->allowEmptyString('description', true);

        $validator
            ->boolean('deleted');

        return $validator;
    }

}
