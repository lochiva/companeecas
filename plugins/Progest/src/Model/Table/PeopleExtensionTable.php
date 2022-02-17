<?php
namespace Progest\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;


class PeopleExtensionTable extends Table
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

        $this->setTable('progest_people_extension');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');
        $this->setEntityClass('Progest.PersonExtension');
        $this->addBehavior('Timestamp');
		$this->belongsTo('Progest.People',['foreignKey' => 'id_person']);
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

        return $validator;
    }

    public function saveExtension($data)
    {
        $entity = $this->find()->where(['id_person' => $data['id_person'], 'last' => 1])->first();
        if(empty($entity)){
            $entity = $this->newEntity();
        }
        $entity = $this->patchEntity($entity, $data);
        $entity->cleanDirty(['created','modified' ,'last']);
        if($entity->dirty()){
            $this->updateAll(['last' => 0 ],['id_person' => $data['id_person'] ]);
            $entity = $this->newEntity();
            $entity = $this->patchEntity($entity, $data);
            return $this->save($entity);
        }
        return $entity;
    }

	public function getExtensionByPersonId($id){
		$opt['conditions']['AND'] = [
			'id_person' => $id,
			'last' => '1',
			'deleted' => '0'
		];
		$opt['fields'] = ['address', 'comune', 'provincia', 'tel', 'cell'];

		return $this->find('all', $opt)->toArray();
	}

}
