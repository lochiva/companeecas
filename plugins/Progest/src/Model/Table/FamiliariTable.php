<?php
namespace Progest\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class FamiliariTable extends AppTable
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

        $this->setTable('progest_familiari');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->belongsTo('Progest.GradoParentela',['foreignKey'=>'id_grado_parentela']);

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
            ->integer('id_person')
            ->requirePresence('id_person', 'create')
            ->notEmpty('id_person');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->requirePresence('surname', 'create')
            ->notEmpty('surname');

        $validator
            ->requirePresence('id_grado_parentela', 'create')
            ->notEmpty('id_grado_parentela');

        return $validator;
    }

    public function saveFamiliare($data)
    {

        if(!empty($data['id'])){
            $entity = $this->get($data['id']);
        }else{
            $entity = $this->newEntity();
        }
        $entity = $this->patchEntity($entity, $data);
        if(!$entity = $this->save($entity)){
            return $entity;
        }

        return $this->find()->where(['id_person' => $entity->id_person])->toArray();

    }


}
