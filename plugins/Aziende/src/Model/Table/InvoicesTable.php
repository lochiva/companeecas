<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Invoices  (https://www.companee.it)
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
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;

class InvoicesTable extends AppTable
{
    public function initialize(array $config)
    {
        $this->setTable('invoices');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Aziende.Invoice');
        $this->belongsTo('Issuer',['foreignKey' => 'id_issuer','className' => 'Aziende.Aziende', 'propertyName' => 'issuer']);
        $this->belongsTo('Payer',['foreignKey' => 'id_payer','className' => 'Aziende.Aziende', 'propertyName' => 'payer']);
        $this->belongsTo('Aziende.Orders',['foreignKey' => 'id_order', 'propertyName' => 'order']);
        $this->belongsTo('Aziende.PaymentConditions',['foreignKey' => 'id_payment_condition', 'propertyName' => 'payment_condition']);
        $this->belongsTo('Aziende.InvoicesPurposes',['foreignKey' => 'id_purpose', 'propertyName' => 'order']);
        $this->hasmany('Aziende.InvoicesArticles', ['foreignKey' => 'id_invoice']);
    }

    public function afterSave($event, $entity, $options)
    {
        parent::afterSave($event, $entity, $options);
        // Se è un nuova fattura o non ha un id_scadenza la creo
        $st = TableRegistry::get('Scadenzario.Scadenzario');
        if($entity->isNew() || empty($entity->id_scadenza)){

            $scadenza = $st->newEntity();
        }else{
            $scadenza = $st->triggerBeforeFind(false)->find()
              ->where(['id' => $entity->id_scadenza])->first();
            if(empty($scadenza)){
                $scadenza = $st->newEntity();
            }
        }
        $data = [
          'descrizione'=>'Fattura num° '.$entity->num." da pagare",
          'data' => $entity->due_date,
          'note' => $entity->note,
          'data_eseguito' => $entity->paid_date,
        ];
        $scadenza = $st->patchEntity($scadenza, $data);
        $scadenza = $st->save($scadenza);
        // salvo l'id della scadenza nella fattura
        if(empty($entity->id_scadenza)){
          $this->updateAll(['id_scadenza'=>$scadenza->id],['id' => $entity->id ]);
        }

        return;
    }

    public function afterDelete($event, $entity, $options)
    {
        parent::afterDelete($event, $entity, $options);
        if(!empty($entity->id_scadenza)){
            $st = TableRegistry::get('Scadenzario.Scadenzario');
            $scadenza = $st->updateAll(['deleted'=>1],['id' => $entity->id_scadenza ]);
        }

    }

}
