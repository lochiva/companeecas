<?php
namespace Progest\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * ProgestServices Model
 *
 * @property \Cake\ORM\Association\BelongsToMany $Orders
 *
 * @method \Progest\Model\Entity\ProgestService get($primaryKey, $options = [])
 * @method \Progest\Model\Entity\ProgestService newEntity($data = null, array $options = [])
 * @method \Progest\Model\Entity\ProgestService[] newEntities(array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestService|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Progest\Model\Entity\ProgestService patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestService[] patchEntities($entities, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestService findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServicesTable extends AppTable
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

        $this->setTable('progest_services');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
        $this->belongsToMany('Skills', [
            'foreignKey' => 'id_service',
            'targetForeignKey' => 'id_skill',
            'joinTable' => 'progest_skills_services',
            'className' => 'Progest.Skills'
        ]);
        $this->HasOne('CategoriesGroup', [
            'foreignKey' => 'id_service',
            'className' => 'Progest.CategoriesServices'
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
            ->requirePresence('name', 'create')
            ->notEmpty('name');

        $validator
            ->integer('ordering')
            ->requirePresence('ordering', 'create')
            ->notEmpty('ordering');
        return $validator;
    }

    public function getListSorter($category = 0)
    {
        return $list = $this->find('list',[
                            'keyField' => 'name',
                            'valueField' => 'name'
                        ])->contain(['CategoriesGroup'=>['Categories']])
                        ->where(['Categories.id' => $category])->order(['Services.ordering' => 'ASC'])->toArray();
    }

    public function getListCat($category = 0)
    {
      return $this->find()->select(['id'=>'Services.id','text'=>'Services.name'])->contain(['CategoriesGroup'=>['Categories']])
        ->order(['Services.ordering' => 'ASC'])->where(['Categories.id' => $category])->toArray();
    }

    public function getContactsForService($id,$pass)
    {

        $query = $this->find('all',[ 'conditions' => ['Services.id' => $id,'contatti.deleted' => 0],
        'fields'=>['id'=>'contatti.id','text'=>'CONCAT(contatti.cognome,SPACE(1),contatti.nome)',
        'events'=>'(SELECT COUNT(*) FROM calendar_events LEFT JOIN users_to_groups ON calendar_events.id_group = users_to_groups.id_group
          WHERE (contatti.id = calendar_events.id_contatto OR contatti.id = users_to_groups.id_user )
            AND ( (calendar_events.start >= :start AND calendar_events.start < :end )
            OR (calendar_events.end > :start AND calendar_events.end <= :end )
            OR (calendar_events.start <= :start AND calendar_events.end >= :end )  ) )'],
        'join' => [
            [
              'table' => 'progest_skills_services',
              'type' => 'INNER',
              'conditions' => 'Services.id = progest_skills_services.id_service'
            ],
            [
              'table' => 'skills',
              'type' => 'INNER',
              'conditions' => 'progest_skills_services.id_skill = skills.id'
            ],
            [
              'table' => 'skills_contacts',
              'type' => 'INNER',
              'conditions' => 'skills.id = skills_contacts.id_skill '
            ],
            [
              'table' => 'contatti',
              'type' => 'INNER',
              'conditions' => 'skills_contacts.id_contatto = contatti.id'
            ],
            [
              'table' => 'aziende',
              'type' => 'INNER',
              'conditions' => 'contatti.id_azienda = aziende.id AND aziende.interno = 1'
            ],

          ]
        ])->bind(':start',$pass['start'])->bind(':end',$pass['end'])->group('contatti.id');
        $having['OR']['events'] = 0;

        if(!empty($pass['users'])){
          $having['OR']['contatti.id IN'] = $pass['users'];
        }

        return $query->having($having)->toArray();
    }

	public function getServiceById($id){

		$opt['conditions']= ['id' => $id];

		return $this->find('all', $opt)->toArray();
	}
}
