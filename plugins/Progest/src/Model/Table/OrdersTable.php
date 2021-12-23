<?php
namespace Progest\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Aziende\Model\Table\OrdersTable as OrdersBaseTable;


class OrdersTable extends OrdersBaseTable
{
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->belongsTo('Progest.People',['foreignKey' => 'id_person', 'propertyName' => 'persona']);
        $this->belongsTo('Progest.InvoiceTypes',['foreignKey' => 'id_invoice_type', 'propertyName' => 'invoice_type']);
        $this->hasMany('Progest.ServicesOrders',['bindingKey' => 'id','foreignKey' => 'id_order','propertyName' => 'ServicesOrders']);
        $this->hasOne('GroupServices',[
          'className' =>'Progest.ServicesOrders',
          'foreignKey' => 'id_order',
          'propertyName' => 'services']);
        $this->hasMany('Progest.ContactsOrders',['bindingKey' => 'id','foreignKey' => 'id_order','propertyName' => 'ContactsOrders']);
    }

    public function getOrdersWithEvents($start='',$end='')
    { /* questa funzione non controlla gli eventi del gruppo torino #7931 controllo del pianificato   */
        $subQuery = " FROM calendar_events Eventi WHERE Eventi.id_order = Orders.id AND Eventi.start >= :start AND
         Eventi.start <= :end AND Eventi.repeated = 0 GROUP BY Eventi.id_order)";
        return $this->find('all')->contain(['GroupServices'=>['Services'],'People','Aziende'=>['GroupGruppi']])
          ->select(['id'=>'Orders.id', 'status'=>'Orders.id_status',
          'id_person' => 'Orders.id_person',
          'oggetto' => 'Orders.name',
          'note'=>'GROUP_CONCAT(CONCAT(Services.name," ( ",GroupServices.dettaglio," )") SEPARATOR ", ")',
          'person' => 'CONCAT(People.surname,SPACE(1),People.name)',
          'not_weekly' => 'IF(AVG(GroupServices.frequenza) = 1,0,1)',
          'week_houres' => 'SUM(GroupServices.ore_num)',
          'week_passages' => 'SUM(GroupServices.passaggi_settimanali)',
          'week_houres_events' => '(SELECT FORMAT(SUM(TIMESTAMPDIFF(MINUTE,Eventi.start,Eventi.end)/60),2)'.$subQuery,
          'week_passages_events' => '(SELECT COUNT(Eventi.id)'.$subQuery])
          ->group('Orders.id')->where(['Orders.id_status' => 1 , 'GroupServices.id_service IN'=>[1,5],
            'GroupGruppi.id_gruppo NOT IN'=>[3]])
          ->bind(':start',$start)->bind(':end',$end)->toArray();
    }

    public function getActiveOrdersWithNotes()
    {
        return $this->find('all')->contain(['GroupServices'=>['Services'],'People','Aziende'=>['GroupGruppi']])
          ->select(['id'=>'Orders.id', 'status'=>'Orders.id_status',
		  'ignora_controllo' => 'Orders.ignora_controllo',
		  'ignora_note' => 'Orders.ignora_note',
          'id_person' => 'Orders.id_person',
          'oggetto' => 'Orders.name',
          'note'=>'GROUP_CONCAT(CONCAT(Services.name," ( ",GroupServices.dettaglio," )") SEPARATOR ", ")',
          'person' => 'CONCAT(People.surname,SPACE(1),People.name)' ])
          ->group('Orders.id')->where(['Orders.id_status' => 1 , 'GroupServices.id_service IN'=>[1,5],
            'OR'=>['GroupGruppi.id_gruppo !='=>3,'GroupServices.id_service !='=>5]  ])->toArray();
    }

	public function getPersonId($id){

		$opt['conditions'] = ['id' => $id];
		$opt['fields'] = ['id_person'];

		return $this->find('all', $opt)->toArray();
	}
}
