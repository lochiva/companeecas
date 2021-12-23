<?php
namespace Leads\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * LeadsInterviews Model
 *
 * @method \Leads\Model\Entity\LeadsInterviews get($primaryKey, $options = [])
 * @method \Leads\Model\Entity\LeadsInterviews newEntity($data = null, array $options = [])
 * @method \Leads\Model\Entity\LeadsInterviews[] newEntities(array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsInterviews|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Leads\Model\Entity\LeadsInterviews|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Leads\Model\Entity\LeadsInterviews patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsInterviews[] patchEntities($entities, array $data, array $options = [])
 * @method \Leads\Model\Entity\LeadsInterviews findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class LeadsInterviewsTable extends AppTable
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

        $this->setTable('leads_interviews');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Azienda', [
            'className' => 'Aziende.Aziende',
            'foreignKey' => 'id_azienda'
        ]);

        $this->belongsTo('Contatti', [
            'className' => 'Aziende.Contatti',
            'foreignKey' => 'id_contatto'
        ]);

        $this->belongsTo('Ensemble', [
            'className' => 'Leads.LeadsEnsembles',
            'foreignKey' => 'id_ensemble'
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
            ->integer('id_azienda')
            ->requirePresence('id_azienda', 'create')
            ->notEmpty('id_azienda');

        $validator
            ->integer('id_contatto')
            ->requirePresence('id_contatto', 'create')
            ->notEmpty('id_contatto');

        $validator
            ->integer('id_ensemble')
            ->requirePresence('id_ensemble', 'create')
            ->notEmpty('id_ensemble');

        $validator
            ->scalar('name')
            ->maxLength('info', 255)
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->boolean('deleted');

        return $validator;
    }
}
