<?php
namespace Progest\Model\Table;

use Cake\ORM\Table;
use App\Model\Table\AppTable;

class ActivitiesTable extends AppTable
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

        $this->setTable('progest_activities');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

    }

	public function getActivitiesByService($id_service){
		return $this->find()->where(['id_service' => $id_service])
							->order(['`order_value`' => 'ASC'])
							->toArray();
	}

}
