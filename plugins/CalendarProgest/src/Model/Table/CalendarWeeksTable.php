<?php
namespace Calendar\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\I18n\Time;

/**
 * CalendarWeeks Model
 *
 * @method \Calendar\Model\Entity\CalendarWeek get($primaryKey, $options = [])
 * @method \Calendar\Model\Entity\CalendarWeek newEntity($data = null, array $options = [])
 * @method \Calendar\Model\Entity\CalendarWeek[] newEntities(array $data, array $options = [])
 * @method \Calendar\Model\Entity\CalendarWeek|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Calendar\Model\Entity\CalendarWeek patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Calendar\Model\Entity\CalendarWeek[] patchEntities($entities, array $data, array $options = [])
 * @method \Calendar\Model\Entity\CalendarWeek findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CalendarWeeksTable extends Table
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

        $this->setTable('calendar_weeks');
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
            ->integer('id_user')
            ->requirePresence('id_user', 'create')
            ->notEmpty('id_user');

        $validator
            ->dateTime('start')
            ->requirePresence('start', 'create')
            ->notEmpty('start');

        $validator
            ->dateTime('end')
            ->requirePresence('end', 'create')
            ->notEmpty('end');

        $validator
            ->dateTime('frozen_date')
            ->requirePresence('frozen_date', 'create')
            ->notEmpty('frozen_date');

        return $validator;
    }

    public function saveFrozeWeek($start,$end)
    {
        $frozenWeek = $this->newEntity();
        $frozenWeek->start = $start;
        $frozenWeek->end = $end;
        $frozenWeek->frozen_date = new Time();
        if(!empty($_SESSION['Auth']['User']['id'])){
					     $frozenWeek->id_user = $_SESSION['Auth']['User']['id'];
				}else{
            return false;
        }

        return $this->save($frozenWeek);
    }
}
