<?php
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;


/**
 * PresenzeUpload Model
 *
 * @property \Aziende\Model\Table\SediTable&\Cake\ORM\Association\BelongsTo $Sedi
 *
 * @method \Aziende\Model\Entity\PresenzeUpload get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\PresenzeUpload newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\PresenzeUpload[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\PresenzeUpload|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\PresenzeUpload saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\PresenzeUpload patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\PresenzeUpload[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\PresenzeUpload findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PresenzeUploadTable extends AppTable
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

        $this->setTable('presenze_upload');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Sedi', [
            'foreignKey' => 'sede_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Sedi'
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
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmptyDate('date');

        $validator
            ->scalar('file')
            ->maxLength('file', 255)
            ->requirePresence('file', 'create')
            ->notEmptyFile('file');

        $validator
            ->scalar('filepath')
            ->maxLength('filepath', 255)
            ->requirePresence('filepath', 'create')
            ->notEmptyFile('filepath');

        $validator
            ->boolean('deleted')
            ->allowEmptyString('deleted', null, 'create');

        return $validator;
    }



}