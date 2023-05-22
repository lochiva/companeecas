<?php
/**
* Crediti is a plugin for manage attachment
*
* Companee :    Aziende  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
namespace Crediti\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class AziendeTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->setTable('aziende');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        //$this->setEntityClass('Crediti.Azienda');
        //$this->belongsTo('Document.Contacts',['foreignKey' => 'id_client', 'conditions' => ['Contacts.client' => 1], 'propertyName' => 'client']);
        //$this->belongsTo('Document.Projects',['foreignKey' => 'id_project']);
        $this->hasMany('Consulenza.Orders',[
        	'foreignKey' => 'azienda_id',
        	'propertyName' => 'Orders'
        	]);
        $this->hasMany('Crediti.Credits',[
          'foreignKey' => 'azienda_id',
          'propertyName' => 'Credits'
        ]);
        $this->hasMany('Crediti.CreditsTotals',[
          'foreignKey' => 'azienda_id',
          'propertyName' => 'CreditsTotals'
        ]);
    }

    public function retrieveAziendaBeforeDelete($id=0)
    {
      $contain_opt = ['Orders'=>['Tasks' => function ($q) {
        return $q->where(['Tasks.start >' => '0000-00-00 00:00:00']);
      },'Frozentasks']];

      $opz['Aziende.id'] = $id;

      return $this->find()->contain($contain_opt)->where($opz)->first()->toArray();

    }

    public function buildRules(RulesChecker $rules)
    {
        // Add a rule that is applied for create and update operations
        $rules->add($rules->isUnique(['cod_sispac']));

        return $rules;
    }

    public function retireveAzinedaEmail($id = 0)
    {
      $res = $this->get($id);
      
      return $res['email_solleciti'];
    }



}
