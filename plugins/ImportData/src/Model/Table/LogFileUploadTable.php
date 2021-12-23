<?php
namespace ImportData\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * LogFileUpload Model
 */
class LogFileUploadTable extends AppTable
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

        $this->setTable('log_file_upload');
        $this->setDisplayField('id');
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
            ->allowEmpty('id', 'create');

        $validator
            ->integer('user')
            ->requirePresence('user', 'create')
            ->notEmpty('user');

        $validator
            ->requirePresence('table_name', 'create')
            ->notEmpty('table_name');

        $validator
            ->requirePresence('file', 'create')
            ->notEmpty('file');

        $validator
			->datetime('date')
            ->requirePresence('date', 'create')
            ->notEmpty('date');

        return $validator;
    }
}
