<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Clienti   (https://www.companee.it)
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
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Core\Configure;

class ClientiComponent extends Component
{
    public function getInvoicePurposesTree()
    {
        $parentId = Configure::read('dbconfig.aziende.ROOT_ATTIVE');
        $table = TableRegistry::get('Aziende.InvoicesPurposes');
        $purposes = $table->find()->where(['parent_id' => $parentId])->toArray();
        foreach ($purposes as $key => $value) {
            $purposes[$key]['children'] = $table->find()->where(['parent_id' => $value['id']])->toArray();
        }

        return $purposes;
    }

    public function getPaymentConditions()
    {
        return TableRegistry::get('Aziende.PaymentConditions')->find()->order('PaymentConditions.ordering')->toArray();
    }

    public function saveInvoice($dati)
    {
        $invoices = TableRegistry::get('Aziende.Invoices');
        $articles = TableRegistry::get('Aziende.InvoicesArticles');

        if(!empty($dati['id'])){
            $entity = $invoices->get($dati['id']);
        }else{
            $entity = $invoices->newEntity();
        }

        if(!empty($dati['paid_date'])){
            $entity->paid = 1;
        }else{
            $entity->paid = 0;
        }
        foreach($dati as $key=>$val){
            if(strpos($key, 'date') !== false && !empty($val)){
              $dati[$key] = Time::createFromFormat('d/m/Y',$val);
            }
        }
        $dati['passive'] = 0;

        $entity = $invoices->patchEntity($entity, $dati);

        if($invoice = $invoices->save($entity)){
            if(!empty($data['id'])){
                foreach($dati['articoli'] as $articolo){
                    $articolo['id_invoice'] = $dati['id'];
                    if(!empty($articolo['id'])){
                        $articleEntity = $articles->get($articolo['id']);
                        $articleEntity = $articles>patchEntity($articleEntity, $articolo);
                    }else{
                        $articleEntity = $articles->newEntity($articolo);
                    } 
                    $articles->save($articleEntity);
                }
            }else{
                foreach($dati['articoli'] as $articolo){
                    $articolo['id_invoice'] = $invoice->id;
                    $articleEntity = $articles->newEntity($articolo);
                    $articles->save($articleEntity);
                }
            }
            return $invoice;
        }else{
            return false;
        }
    }

    public function deleteInvoice($id)
    {
        $invoices = TableRegistry::get('Aziende.Invoices');
        $articles = TableRegistry::get('Aziende.InvoicesArticles'); 
        if($articles->updateAll(['deleted' => 1], ['id_invoice' => $id])){ 
            return $invoices->softDelete($invoices->get($id));
        }else{
            return false;
        }
    }

    public function getFatture($pass = array(), $idOrAction = 0)
    {

        $ivoicesTable = TableRegistry::get('Aziende.Invoices');
        $opt = array();
        $toRet = array();
        $opt['contain'] = ['Payer', 'Issuer'];
        $columns = [
          0 => ['val' => 'Issuer.denominazione', 'type' => 'text'],
          1 => ['val' => 'Invoices.num', 'type' => 'text'],
          2 => ['val' => 'Invoices.emission_date', 'type' => 'date'],
          3 => ['val' => 'Invoices.amount_topay', 'type' => ''],
          4 => ['val' => 'Invoices.due_date', 'type' => 'date'],
          5 => ['val' => 'Invoices.attachment', 'type' => 'text'],
          6 => ['val' => 'is_paid', 'type' => 'text', 'having' => 1],

        ];

        $opt['fields'] = [
          'id' => 'Invoices.id',
          'is_paid' => 'IF(Invoices.paid = 0, "NO" , "SI" )',
          'due_date' => 'Invoices.due_date',
          'issuer' => 'Issuer.denominazione',
          'num' => 'Invoices.num',
          'emission_date' => 'Invoices.emission_date',
          'amount_topay' => 'Invoices.amount_topay',
          'attachment' => 'Invoices.attachment',
          'id_fattureincloud' => 'Invoices.id_fattureincloud',
          'payer_id_fattureincloud' => 'Payer.id_cliente_fattureincloud'
        ];

        if($idOrAction > 0 && $idOrAction !== 'all' ){
          $opt['conditions'] = ['Invoices.id_payer' => $idOrAction];
        }else{
          $opt['fields']['payer'] = 'Payer.denominazione';
          array_splice($columns, 1, 0, [1 =>['val' => 'Payer.denominazione', 'type'=>'text']]);
        } 
        $opt['conditions']['AND'] = ['passive' => 0];
        $toRet['res'] = $ivoicesTable->queryForTableSorter($columns,$opt,$pass);
        $toRet['tot'] = $ivoicesTable->queryForTableSorter($columns,$opt,$pass,true);

        return $toRet;

    }

    public function uploadAttachment($file)
    {
        $uploadPath = ROOT.DS.'src'.DS.'files'.DS.date('Y').DS.date('m');
        $fileName = uniqid().$file['name'];
        $res = date('Y').DS.date('m').DS.$fileName;

        if (!is_dir($uploadPath) && !mkdir($uploadPath, 0755, true)){
          return false;
        }

        if(!move_uploaded_file($file['tmp_name'],$uploadPath.DS.$fileName) ){
          return false;
        }

        return $res;

    }

    public function getFattureAttiveAzienda($id, $limit='50')
    {
        $offersTable = TableRegistry::get('Aziende.Invoices');
        return $offersTable->find('all')->where(['Invoices.id_payer' => $id, 'passive' => '0'])
        ->order(['Invoices.emission_date' => 'DESC'])->limit($limit)->contain('Issuer')->toArray();
    }

}
