<?php
namespace Crediti\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Crediti\Model\Entity\Kpi;
use Cake\I18n\Time;
use App\Model\Table\AppTable;

/**
 * Kpi Model
 *
 */
class KpiTable extends appTable
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->setTable('kpi');
        $this->displayField('id');
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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('nome', 'create')
            ->notEmpty('nome');

        $validator
            ->add('giorno', 'valid', ['rule' => 'date'])
            ->requirePresence('giorno', 'create')
            ->notEmpty('giorno');

        $validator
            ->add('valore', 'valid', ['rule' => 'numeric'])
            ->requirePresence('valore', 'create')
            ->notEmpty('valore');

        return $validator;
    }

    public function saveIndicatoreCrediti($res='',$date='')
    {
      if(!empty($res)){
        $new = $this->newEntity();

        $new->nome = 'Crediti';
        if(!empty($date)){
          $new->giorno = $date;
        }else{
          $new->giorno = Time::now();
        }
        $new->valore = $res;

        return $this->save($new);
      }else{
        return false;
      }
    }

    public function getIndicatoreCrediti()
    {
      return $this->find()->where(['nome' => 'Crediti'])->order(['giorno'=>'DESC' , 'id'=>'DESC'])->limit(12)->toArray();
    }
}
