<?php
namespace Reports\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * Reports Model
 *
 * @method \Reports\Model\Entity\Report get($primaryKey, $options = [])
 * @method \Reports\Model\Entity\Report newEntity($data = null, array $options = [])
 * @method \Reports\Model\Entity\Report[] newEntities(array $data, array $options = [])
 * @method \Reports\Model\Entity\Report|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Reports\Model\Entity\Report|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Reports\Model\Entity\Report patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Reports\Model\Entity\Report[] patchEntities($entities, array $data, array $options = [])
 * @method \Reports\Model\Entity\Report findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ReportsTable extends AppTable
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

        $this->setTable('reports');
        $this->setPrimaryKey('id');
        $this->setEntityClass('Reports.Report');

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
            ->scalar('code')
            ->maxLength('code', 5)
            ->requirePresence('code')
            ->allowEmpty('code', false);

        $validator
            ->scalar('province_code')
            ->maxLength('province_code', 5)
            ->requirePresence('province_code')
            ->allowEmpty('province_code', false);

        $validator
            ->scalar('type_reporter')
            ->maxLength('type_reporter', 16)
            ->requirePresence('type_reporter')
            ->allowEmptyString('type_reporter', false);

        $validator
            ->integer('victim_id')
            ->allowEmpty('victim_id', true);

        $validator
            ->integer('witness_id')
            ->allowEmpty('witness_id', true);

        $validator
            ->integer('interview_id')
            ->allowEmpty('interview_id', true);

        $validator
            ->integer('node_id')
            ->allowEmpty('node_id', true);

        $validator
            ->integer('user_create_id')
            ->requirePresence('user_create_id')
            ->allowEmpty('user_create_id', false);

        $validator
            ->integer('user_update_id')
            ->requirePresence('user_update_id')
            ->allowEmpty('user_update_id', false);

        $validator
            ->scalar('status')
            ->maxLength('status', 255)
            ->requirePresence('status')
            ->allowEmptyString('status', false);

        $validator
            ->date('opening_date')
            ->allowEmptyDate('opening_date', true);

        $validator
            ->date('closing_date')
            ->allowEmptyDate('closing_date', true);

        $validator
            ->integer('closing_outcome_id')
            ->allowEmpty('closing_outcome_id', true);

        $validator
            ->date('transfer_date')
            ->allowEmptyDate('transfer_date', true);

        return $validator;
    }

    public function beforeSave($event, $entity, $options) {
        
        //report code
        if ($entity->isNew()) {
            if (empty($entity->code)) {
                $lastCode = $this->find('all', [
                    'fields' => ['code'],
                    'conditions' => ['province_code' => $entity->province_code],
                    'order' => ['code' => 'DESC'],
                    'retrieveDeleted' => true
                ])->first();
                if ($lastCode) {
                    $code = str_pad((int) $lastCode['code'] + 1 , 5 , "0", STR_PAD_LEFT);
                } else {
                    $code = '00001';
                }
                $entity->code = $code;
            }
        }

    }

}
