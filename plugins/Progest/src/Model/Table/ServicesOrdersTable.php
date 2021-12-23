<?php
namespace Progest\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProgestServicesOrders Model
 *
 * @method \Progest\Model\Entity\ProgestServicesOrder get($primaryKey, $options = [])
 * @method \Progest\Model\Entity\ProgestServicesOrder newEntity($data = null, array $options = [])
 * @method \Progest\Model\Entity\ProgestServicesOrder[] newEntities(array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestServicesOrder|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Progest\Model\Entity\ProgestServicesOrder patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestServicesOrder[] patchEntities($entities, array $data, array $options = [])
 * @method \Progest\Model\Entity\ProgestServicesOrder findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class ServicesOrdersTable extends Table
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

        $this->setTable('progest_services_orders');
        $this->setPrimaryKey('id');
        $this->belongsTo('Progest.Orders',['foreignKey' => 'id_order']);
        $this->belongsTo('Progest.Services',['foreignKey' => 'id_service']);
        $this->entityClass('Progest.ServicesOrder');
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
            ->integer('id_order')
            ->requirePresence('id_order', 'create')
            ->notEmpty('id_order');

        $validator
            ->integer('id_service')
            ->requirePresence('id_service', 'create')
            ->notEmpty('id_service');

        return $validator;
    }

    /**
     * Esegue una query che dato l'id_order e la settimana trova i servizi con
     * associati i conteggi delle ore degli eventi e dei passaggi nei tre vari tipi
     * di frequenza, ovvero settimanale, ogni 2 settimane, e mensile.
     * La query è composta da varie subquery, che fanno le somme delle ore e dei passaggi, considerando
     * anche il caso della compresenza in un evento, ovvero moltiplicando le ore per il
     * numero di operatori.
     *
     * @param  int    $idOrder id dell'ordine da controllare
     * @param  string $start   data inizio della settimana da controllare
     * @param  string $end     data fine della settimana da controllare
     * @return array          array dei risultati della query
     */
    public function getServicesEvents($idOrder,$start,$end)
    {
        $starts = array();
        $ends = array();
        $subQuery = array();
        $starts[0] = $start;
        $starts[1] = new \DateTime($start);
        $starts[1] = $starts[1]->modify('-7 day')->format('Y-m-d');
        $starts[2] = new \DateTime($start);
        $starts[2] = $starts[2]->format('Y-m-').'01';

        $ends[0] = $end;
        $ends[1] = $end;
        $ends[2] = new \DateTime($start);
        $ends[2] = $ends[2]->format('Y-m-').'31';
        $query = $this->find();

        $subQuery2 = '(SELECT IF(COUNT(*) = 0,1,COUNT(*)) FROM users_to_groups WHERE users_to_groups.id_group = Eventi.id_group)';
        for ($i=0; $i < 3; $i++) {
           $subQuery[$i] = " FROM calendar_events Eventi WHERE Eventi.id_order = :idOrder AND Eventi.start >= :start".$i." AND
             Eventi.start <= :end".$i." AND Eventi.repeated = 0 AND Eventi.id_service = ServicesOrders.id_service GROUP BY
             Eventi.id_order,Eventi.id_service)";
           $query->bind(':start'.$i,$starts[$i]);
           $query->bind(':end'.$i,$ends[$i]);
        }
       
        $query->bind(':idOrder',$idOrder);
        $query->select([
            'ServicesOrders.id',
            'ServicesOrders.id_service',
            'ServicesOrders.id_order',
            'ServicesOrders.frequenza',
            'service_houres'=>'FORMAT(ServicesOrders.ore_num,2)',
            'service_houres_weekend'=>'FORMAT(ServicesOrders.ore_festive,2)',
            'service_passages'=>'ServicesOrders.passaggi_settimanali',
            'service' => 'Services.name',
            'week_houres' => '(SELECT FORMAT(SUM((TIMESTAMPDIFF(MINUTE,Eventi.start,Eventi.end)/60*'.$subQuery2.')),2) '.$subQuery[0],
            'week_passages' => '(SELECT COUNT(Eventi.id)'.$subQuery[0],
            'two_week_houres' => '(SELECT FORMAT(SUM((TIMESTAMPDIFF(MINUTE,Eventi.start,Eventi.end)/60*'.$subQuery2.')),2) '.$subQuery[1],
            'two_week_passages' => '(SELECT COUNT(Eventi.id)'.$subQuery[1],
            'month_houres' => '(SELECT FORMAT(SUM((TIMESTAMPDIFF(MINUTE,Eventi.start,Eventi.end)/60*'.$subQuery2.')),2) '.$subQuery[2],
            'month_passages' => '(SELECT COUNT(Eventi.id)'.$subQuery[2],
            'week_houres_weekend' => '(SELECT FORMAT(SUM(IF(WEEKDAY(Eventi.start)=6,(TIMESTAMPDIFF(MINUTE,Eventi.start,Eventi.end)/60*'.$subQuery2.'),0 ) ),2) '.$subQuery[0],
            'two_week_houres_weekend' => '(SELECT FORMAT(SUM(IF(WEEKDAY(Eventi.start)=6,(TIMESTAMPDIFF(MINUTE,Eventi.start,Eventi.end)/60*'.$subQuery2.'),0 ) ),2) '.$subQuery[1],
            'month_houres_weekend' => '(SELECT FORMAT(SUM(IF(WEEKDAY(Eventi.start)=6,(TIMESTAMPDIFF(MINUTE,Eventi.start,Eventi.end)/60*'.$subQuery2.'),0 ) ),2) '.$subQuery[2],
            'errore_compresenza' => '(SELECT SUM( IF(Eventi.id_group > 0 AND '.$subQuery2.' <= 1,1,0)) '.$subQuery[0],
          ])->contain(['Services','Orders'=>['Aziende'=>['GroupGruppi']]])->where(['ServicesOrders.id_order' => $idOrder,
          'ServicesOrders.id_service IN' => [1,5],'OR'=>['GroupGruppi.id_gruppo !='=>3,'ServicesOrders.id_service !='=>5]])->group(['ServicesOrders.id']);

        return $query->toArray();
    }
}
