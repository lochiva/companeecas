<?php
namespace Progest\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * ProgestPeople Model
 *
 * @method \Progest\Model\Entity\ProgestPerson get($primaryKey, $options = [])
 * @method \Progest\Model\Entity\ProgestPerson newEntity($data = null, array $options = [])
 * @method \Progest\Model\Entity\ProgestPerson[] newEntities(array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestPerson|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Progest\Model\Entity\ProgestPerson patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestPerson[] patchEntities($entities, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestPerson findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PeopleTable extends AppTable
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

        $this->setTable('progest_people');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');
        $this->hasOne('Progest.PeopleExtension',[
          'foreignKey' => 'id_person',
           'propertyName' => 'extension',
           'conditions' => ['PeopleExtension.last' => 1]
         ]);
        $this->hasMany('Progest.Familiari',[
            'foreignKey' => 'id_person',
            'propertyName' => 'familiari'
          ]);
        $this->hasOne('GroupActiveOrders',[
            'foreignKey' => 'id_person',
            'propertyName' => 'buono',
            'className' => 'Progest.Orders',
            'conditions' => ['GroupActiveOrders.id_status' => 1]
          ]);

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
            ->requirePresence('surname', 'create')
            ->notEmpty('surname');

        $validator
            ->requirePresence('name', 'create')
            ->notEmpty('name');


        return $validator;
    }

    public function autocomplete($q,$active=false)
    {
        $having = array();
        $q = explode(' ', $q);
        $matching = '';

        foreach($q as $word){
            $having[] = ['text LIKE' => '%'.$word.'%'];
        }
        $query =  $this->find()->select(['id'=>'People.id',
          'text'=>'CONCAT(People.surname,SPACE(1),People.name," nato/a il ",DATE_FORMAT(People.birthdate,"%d/%m/%Y"))'])
            ->having($having);
        if($active){
            $query->matching('GroupActiveOrders');
        }

        return $query->toArray();
    }

    public function savePerson($data)
    {

        if(!empty($data['id'])){
            $entity = $this->get($data['id']);
        }else{
            $entity = $this->newEntity();
        }
        unset($data['extension']);
        $entity = $this->patchEntity($entity, $data);

        return $this->save($entity);
    }

    public function birthdays($month = 0, $gruppo = 0)
    {
        $query =  $this->find()->select(['People.name','People.surname','People.birthdate'])
          ->contain(['GroupActiveOrders' => ['Aziende' => ['GroupGruppi']]])->group('People.id')
          ->where(['MONTH(People.birthdate)' => $month])->order(['DAY(People.birthdate)' => 'ASC']);
        if(!empty($gruppo)){
            $query = $query->where(['GroupGruppi.id_gruppo' => $gruppo]);
        } else {
       // devo imporre esplicitamente che il buono sia attivo.. se invece c'era il gruppo non serve.. bo? rufus
        $query = $query->where(['GroupActiveOrders.id_status' => 1]);

      }
        return $query->toArray();
    }

    public function reportAgeGender($opt = array())
    {
      return $this->find()->select([
        'maschi' => 'SUM(IF(gender = "m", 1, 0))',
        'femmine' => 'SUM(IF(gender = "f", 1, 0))',
        'totale' => 'COUNT(*)',
        'eta' => 'FLOOR(DATEDIFF(NOW(),People.birthdate)/365)',
        '0-17' => 'SUM(IF( FLOOR(DATEDIFF(NOW(),People.birthdate)/365) BETWEEN 0 AND 17, 1, 0))',
        '18-27' => 'SUM(IF( FLOOR(DATEDIFF(NOW(),People.birthdate)/365) BETWEEN 18 AND 27, 1, 0))',
        '28-37' => 'SUM(IF( FLOOR(DATEDIFF(NOW(),People.birthdate)/365) BETWEEN 28 AND 37, 1, 0))',
        '38-47' => 'SUM(IF( FLOOR(DATEDIFF(NOW(),People.birthdate)/365) BETWEEN 38 AND 47, 1, 0))',
        '48-57' => 'SUM(IF( FLOOR(DATEDIFF(NOW(),People.birthdate)/365) BETWEEN 48 AND 57, 1, 0))',
        '58-65' => 'SUM(IF( FLOOR(DATEDIFF(NOW(),People.birthdate)/365) BETWEEN 58 AND 65, 1, 0))',
        '66-75' => 'SUM(IF( FLOOR(DATEDIFF(NOW(),People.birthdate)/365) BETWEEN 66 AND 75, 1, 0))',
        '76-85' => 'SUM(IF( FLOOR(DATEDIFF(NOW(),People.birthdate)/365) BETWEEN 76 AND 85, 1, 0))',
        'oltre 85' => 'SUM(IF( FLOOR(DATEDIFF(NOW(),People.birthdate)/365) > 85, 1, 0))'
      ])->where($opt)->toArray();
    }

    public function reportIndirizzario($gruppo=0,$servizio=0)
    {

        $query =  $this->find()->select(['People.surname','People.name','PeopleExtension.comune','PeopleExtension.address',
          'People.id', 'People.birthdate','PeopleExtension.tel','PeopleExtension.cell'])
          ->contain(['PeopleExtension','GroupActiveOrders' => ['Aziende' => ['GroupGruppi'],'GroupServices'],
          'Familiari'=>['GradoParentela','fields' =>
            ['Familiari.name','Familiari.id_person','Familiari.surname','GradoParentela.name','Familiari.tel','Familiari.cell']] ])
          ->where(['GroupGruppi.id_gruppo' => $gruppo, 'People.deceased' => 'no'])->group('People.id')
          ->order(['PeopleExtension.comune' => 'ASC','People.surname'=>'ASC','People.name'=>'ASC']);
        if(!empty($servizio)){
            $query = $query->where(['GroupServices.id_service' => $servizio]);
        }
        return $query->toArray();

    }

    public function listActive()
    {
        return $this->find()->select(['id'=>'People.id',
          'text'=>'CONCAT(People.surname,SPACE(1),People.name," nato/a il ",DATE_FORMAT(People.birthdate,"%d/%m/%Y"))'])
          ->matching('GroupActiveOrders')->order(['People.surname' => 'ASC', 'People.name'=>'ASC'])->group('People.id')->toArray();
    }

	public function getPersonById($id){
		$opt['conditions'] = ['id' => $id];
		$opt['fields'] = ['surname', 'name'];

		return $this->find('all', $opt)->toArray();
	}
}
