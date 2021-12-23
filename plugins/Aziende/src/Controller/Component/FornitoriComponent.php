<?php
namespace Aziende\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Core\Configure;

class FornitoriComponent extends Component
{
    public function getInvoicePurposesTree()
    {
        $parentId = Configure::read('dbconfig.aziende.ROOT_PASSIVE');
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
        $table = TableRegistry::get('Aziende.Invoices');
        if(!empty($dati['id'])){
            $entity = $table->get($dati['id']);
        }else{
            $entity = $table->newEntity();
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
        $dati['passive'] = 1;
        $entity = $table->patchEntity($entity, $dati);

        return $table->save($entity);
    }

    public function deleteInvoice($id)
    {
        $table = TableRegistry::get('Aziende.Invoices');
        return $table->softDelete($table->get($id));
    }

    public function getFatture($pass = array(), $idOrAction = 0)
    {

        $ivoicesTable = TableRegistry::get('Aziende.Invoices');
        $opt = array();
        $toRet = array();
        $opt['contain'] = ['Payer','InvoicesPurposes', 'Issuer'];
        $columns = [
          0 => ['val' => 'Payer.denominazione', 'type' => 'text'],
          1 => ['val' => 'Invoices.num', 'type' => 'text'],
          2 => ['val' => 'Invoices.emission_date', 'type' => 'date'],
          3 => ['val' => 'Invoices.description', 'type' => 'text'],
          4 => ['val' => 'InvoicesPurposes.name', 'type' => 'text'],
          5 => ['val' => 'Invoices.amount_topay', 'type' => ''],
          6 => ['val' => 'Invoices.due_date', 'type' => 'date'],
          7 => ['val' => 'Invoices.attachment', 'type' => 'text'],
          8 => ['val' => 'is_paid', 'type' => 'text', 'having' => 1],

        ];

        $opt['fields'] = [
          'id' => 'Invoices.id',
          'is_paid' => 'IF(Invoices.paid = 0, "NO" , "SI" )',
          'due_date' => 'Invoices.due_date',
          'payer' => 'Payer.denominazione',
          'num' => 'Invoices.num',
          'emission_date' => 'Invoices.emission_date',
          'description' => 'Invoices.description',
          'purpose'=>'InvoicesPurposes.name',
          'amount_topay' => 'Invoices.amount_topay',
          'attachment' => 'Invoices.attachment',
          'id_fattureincloud' => 'Invoices.id_fattureincloud',
          'issuer_id_fattureincloud' => 'Issuer.id_fornitore_fattureincloud'
        ];

        if($idOrAction > 0 && $idOrAction !== 'all' ){
          $opt['conditions'] = ['Invoices.id_issuer' => $idOrAction];
        }else{
          $opt['fields']['issuer'] = 'Issuer.denominazione';
          array_unshift($columns, ['val' => 'Issuer.denominazione', 'type'=>'text']);
        }
        $opt['conditions']['AND'] = ['passive' => 1];
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

    public function getFattureFornitore($id)
    {
        $ivoicesTable = TableRegistry::get('Aziende.Invoices');
        return $ivoicesTable->find()->where(['id_issuer' => $id])->order(['emission_date DESC'])->toArray();
    }

    /**
     * Metodo che fa le query per trovare il numero di fatture per mese a ritroso dalla
     * data attuale.
     *
     * @param  integer $monthsNum numeri di mesi che si vuole visualizzare, default 6
     * @return array              dati formattati pronti per il chart
     */
      public function getFattureFornitoriChart($monthsNum = 6)
      {
          $ivoicesTable = TableRegistry::get('Aziende.Invoices');
          $monthsNames = array('01'=>'Gennaio','02'=>'Febbraio','03'=>'Marzo','04'=>'Aprile',
              '05'=>'Maggio','06'=>'Giugno','07'=>'Luglio','08'=>'Agosto','09'=>'Settembre',
              '10'=>'Ottobre','11'=>'Novembre','12'=>'Dicembre');
          $date = new \DateTime(date('Y-m-15'));
          $invoices = array();
          for( $i = 0; $i < $monthsNum; $i++ ){
              $invoices['labels'][] = $monthsNames[$date->format('m')];
              $invoices['data']['Totali']['color'] = '#c1c7d1';
              $invoices['data']['Pagate']['color'] = '#00a65a';

              $invoicesMonth = $ivoicesTable->find()->select([
                'total' => 'SUM(IF(`due_date` LIKE"'.$date->format('Y-m').'%" ,`amount_topay`,0 ))',
                'isPaid' => ' SUM(IF(`due_date` LIKE"'.$date->format('Y-m').'%" AND `paid`,`amount_topay`,0))'
                ])->where(['passive' => 1])->first();
              $invoices['data']['Totali']['data'][] = max(0,$invoicesMonth['total']);
              $invoices['data']['Pagate']['data'][] = max(0,$invoicesMonth['isPaid']);

              $date->modify(' -1 month');
          }

          $invoices['labels'] = array_reverse($invoices['labels']);
          foreach(  $invoices['data'] as $key => $data){
            $invoices['data'][$key]['data'] = array_reverse($data['data']);
          }


          return $invoices;
      }

      public function getFattureChartPerCausale($years = 1, $months = 0)
      {
          $ivoicesTable = TableRegistry::get('Aziende.Invoices');
          $date = new \DateTime();
          $date->modify(' -'.$years.' year');
          $date->modify(' -'.$months.' month');

          $data = $ivoicesTable->find()->select(['parentId' => 'InvoicesPurposes.parent_id','value'=>'SUM(`amount_topay`)',
            'label' => '(SELECT `name` FROM `invoices_purposes` WHERE `id` = parentId )',
            'color' => '(SELECT `color` FROM `invoices_purposes` WHERE `id` = parentId )'])
            ->where(['due_date >' => $date->format('Y-m-d')])
            ->matching('InvoicesPurposes')->group('InvoicesPurposes.parent_id')
            ->toArray();

          return $data;

      }

      public function getFatturePassiveAzienda($id, $limit='50')
      {
            $offersTable = TableRegistry::get('Aziende.Invoices');
            return $offersTable->find('all')->where(['Invoices.id_issuer' => $id, 'passive' => '1'])
            ->order(['Invoices.emission_date' => 'DESC'])->limit($limit)->contain('Payer')->toArray();
      }
}
