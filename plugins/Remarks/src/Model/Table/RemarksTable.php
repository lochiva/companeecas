<?php
namespace Remarks\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Remarks Model
 *
 * @method \Remarks\Model\Entity\Remark get($primaryKey, $options = [])
 * @method \Remarks\Model\Entity\Remark newEntity($data = null, array $options = [])
 * @method \Remarks\Model\Entity\Remark[] newEntities(array $data, array $options = [])
 * @method \Remarks\Model\Entity\Remark|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Remarks\Model\Entity\Remark|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Remarks\Model\Entity\Remark patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Remarks\Model\Entity\Remark[] patchEntities($entities, array $data, array $options = [])
 * @method \Remarks\Model\Entity\Remark findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class RemarksTable extends Table
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

        $this->setTable('remarks');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Users', [
            'className' => 'Users',
            'foreignKey' => 'user_id',
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
            ->allowEmpty('id', 'create');

        $validator
            ->scalar('reference')
            ->maxLength('reference', 255)
            ->requirePresence('reference', 'create')
            ->notEmpty('reference');

        $validator
            ->scalar('remark')
            ->requirePresence('remark', 'create')
            ->notEmpty('remark');

        $validator
            ->integer('rating')
            ->allowEmpty('rating');

        $validator
            ->integer('visibility')
            ->allowEmpty('visibility');

        $validator
            ->scalar('attachment')
            ->maxLength('refereattachmentnce', 255)
            ->allowEmpty('attachment');

        return $validator;
    }

    public function getRemarksByRef($reference, $userId, $showDeleted)
    {
        $where = [
            'Remarks.reference' => $reference, 
            'OR' => [
                'Remarks.visibility' => '0', 
                'AND' => [
                    'Remarks.user_id' => $userId, 
                    'Remarks.visibility' => '1'
                ]
            ]
        ];
        
        if($showDeleted != 'true'){
            $where[] = ['deleted !=' => 1];
        }

        $res = $this->find()
            ->select(['Remarks.id', 'Remarks.user_id', 'Remarks.reference', 'Remarks.reference_id',
                    'Remarks.remark', 'Remarks.rating', 'Remarks.visibility', 'Remarks.attachment', 'Remarks.deleted', 'Remarks.created', 
                    'Users.nome', 'Users.cognome'
            ])
			->where($where)
			->order(['Remarks.created DESC'])
            ->contain(['Users'])
			->toArray();

        return $res;
    }

    public function getRemarksByRefId($reference, $referenceId, $userId, $showDeleted)
    {
        $where = [
            'Remarks.reference' => $reference, 
            'Remarks.reference_id' => $referenceId, 
            'OR' => [
                'Remarks.visibility' => '0', 
                'AND' => [
                    'Remarks.user_id' => $userId, 
                    'Remarks.visibility' => '1'
                ]
            ]
        ];
        
        if($showDeleted != 'true'){
            $where[] = ['deleted !=' => 1];
        }

        $res = $this->find()
            ->select(['Remarks.id', 'Remarks.user_id', 'Remarks.reference', 'Remarks.reference_id',
                    'Remarks.remark', 'Remarks.rating', 'Remarks.visibility', 'Remarks.attachment', 'Remarks.deleted', 'Remarks.created', 
                    'Users.nome', 'Users.cognome'
            ])
			->where($where)
			->order(['Remarks.created ASC'])
            ->contain(['Users'])
			->toArray();

        return $res;
    }

    public function deleteRemark($remarkId)
    {
        $remark = $this->get($remarkId);

        $remark->deleted = 1;

		$res = $this->save($remark);

		return $res;
    }

    public function getRemarksNumber($reference, $referenceId, $userId)
    {
        $where = [
            'Remarks.reference' => $reference, 
            'Remarks.reference_id' => $referenceId, 
            'OR' => [
                'Remarks.visibility' => '0', 
                'AND' => [
                    'Remarks.user_id' => $userId, 
                    'Remarks.visibility' => '1'
                ]
            ],
            'deleted !=' => '1'
        ];

        $res = $this->find()
			->where($where)
            ->toArray();
            
        return $res;
    }

    public function getRemarkedUsers($reference, $reference_id)
    {
        $res = $this->find()
            ->select(['user_id'])
            ->distinct(['user_id'])
            ->where(['reference' => $reference, 'reference_id' => $reference_id, 'visibility' => 0])
            ->toArray();

        return $res;
    }

}
