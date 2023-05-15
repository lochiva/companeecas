<?php
/**
* Crm is a plugin for manage attachment
*
* Companee :    Offers    (https://www.companee.it)
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
namespace Crm\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class OffersComponent extends Component
{


    public function getOffersTable($pass = array(), $idOrAction = 0)
    {

      if(isset($pass['query']['filter'][10])){
        $str = substr($pass['query']['filter'][10], 2, -3);
        $arr = explode('|', $str);
        $pass['query']['filter'][10] = $arr;
      }

      $offersTable = TableRegistry::get('Crm.Offers');
      $opt = array();
      $toRet = array();
      $opt['contain'] = ['Status','AziendaEmit','ContattoEmit','AziendaDest','Sedi' => ['Comuni']];
      $columns = [
        0 => ['val' => 'Offers.id', 'type' => ''],
        1 => ['val' => 'Offers.emission_date', 'type' => 'date'],
        2 => ['val' => 'AziendaEmit.denominazione', 'type' => 'text'],
        3 => ['val' => 'contatto_emittente', 'type' => 'text', 'having' => 1],
        4 => ['val' => 'Offers.name', 'type' => 'text'],
        5 => ['val' => 'AziendaDest.denominazione', 'type' => 'text'],
        6 => ['val' => 'Comuni.des_luo', 'type' => 'text'],
        7 => ['val' => 'Offers.budget', 'type' => 'currency'],
        8 => ['val' => 'Offers.amount', 'type' => 'currency'],
        9 => ['val' => 'Offers.attachment', 'type' => 'text'],
        10 => ['val' => 'Status.name', 'type' => 'array'],
      ];

      $opt['fields'] = [
        'id' => 'Offers.id',
        'date' => 'Offers.emission_date',
        'emit' => 'AziendaEmit.denominazione',
        'contatto_emittente' => 'CONCAT(ContattoEmit.nome,SPACE(1),ContattoEmit.cognome)',
        'name' => 'Offers.name',
        'dest' => 'AziendaDest.denominazione',
        'comune_dest' => 'Comuni.des_luo',
        'budgetFormat' => ' FORMAT(Offers.budget,2, "de_DE")',
        'amountFormat' => 'FORMAT(Offers.amount,2, "de_DE")',
        'attachment' => 'Offers.attachment',
        'status' => 'Status.name',
      ];

      if($idOrAction > 0){
        $opt['conditions'] = ['AziendaDest.id' => $idOrAction];
      }

      $toRet['res'] = $offersTable->queryForTableSorter($columns,$opt,$pass);
      $toRet['tot'] = $offersTable->queryForTableSorter($columns,$opt,$pass,true);

      return $toRet;

    }

    public function _newEntity(){
        $table = TableRegistry::get('Crm.Offers');
        return $table->newEntity();
    }

    public function _patchEntity($doc,$request){
        $table = TableRegistry::get('Crm.Offers');
        return $table->patchEntity($doc,$request);
    }

    public function _save($doc){
        $table = TableRegistry::get('Crm.Offers');
        return $table->save($doc);
    }

    public function _get($id){
        $table = TableRegistry::get('Crm.Offers');
        return $table->triggerBeforeFind(false)->get($id,
            ['contain' => ['Status','AziendaEmit','ContattoEmit','AziendaDest','ContattoDest','Sedi']]);

    }

    public function _delete($id){
        $table = TableRegistry::get('Crm.Offers');
        $entity = $table->get($id);
        return $table->softDelete($entity);
    }

    public function getStatusList()
    {
        return TableRegistry::get('Crm.OffersStatus')->find()->order(['ordering'=>'ASC'])->toArray();
    }

    public function saveOffer($dati)
    {
        $offersTable = TableRegistry::get('Crm.Offers');
        if(!empty($dati['id'])){
            $offer = $offersTable->get($dati['id']);
        }else{
            $offer = $offersTable->newEntity();
        }
        foreach($dati as $key=>$val){
            if(strpos($key, 'date') !== false && !empty($val)){
              $dati[$key] = Time::createFromFormat('d/m/Y',$val);
            }
        }
        if($offer->id_status != $dati['id_status']){
            $statusUpdate = $dati['id_status'];
        }
        $offer = $offersTable->patchEntity($offer, $dati);
        $res = $offersTable->save($offer);

        if($res && !empty($statusUpdate)){
           TableRegistry::get('Crm.OffersStatusHistory')->saveOfferChange($res);
        }

        return $res;

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

    public function getOffersPieChart($years = 1, $months = 0)
    {
        $offersTable = TableRegistry::get('Crm.Offers');
        $date = new \DateTime();
        $date->modify(' -'.$years.' year');
        $date->modify(' -'.$months.' month');

        $data = $offersTable->find()->select(['value'=>'COUNT(Offers.id)',
          'label' => 'Status.name',
          'color' => 'Status.color'])
          ->where(['emission_date >' => $date->format('Y-m-d')])
          ->matching('Status')->group('Offers.id_status')
          ->toArray();

        return $data;

    }

    /**
     * Metodo che fa le query per trovare il numero di offerte per mese a ritroso dalla
     * data attuale.
     *
     * @param  integer $monthsNum numeri di mesi che si vuole visualizzare, default 6
     * @return array              dati formattati pronti per il chart
     */
      public function getOffersLineChart($monthsNum = 6)
      {
          $offersTable = TableRegistry::get('Aziende.Offers');
          $monthsNames = array('01'=>'Gennaio','02'=>'Febbraio','03'=>'Marzo','04'=>'Aprile',
              '05'=>'Maggio','06'=>'Giugno','07'=>'Luglio','08'=>'Agosto','09'=>'Settembre',
              '10'=>'Ottobre','11'=>'Novembre','12'=>'Dicembre');
          $date = new \DateTime(date('Y-m-15'));
          $offers = array();
          for( $i = 0; $i < $monthsNum; $i++ ){
              $offers['labels'][] = $monthsNames[$date->format('m')];
              $offers['data']['Emesse']['color'] = '#3b8bba';

              $offersMonth = $offersTable->find()->select(['emesse'=>'SUM(IF(`emission_date` LIKE "'.$date->format('Y-m').'%",1,0))',
                ])->first();
              $offers['data']['Emesse']['data'][] = $offersMonth['emesse'];

              $date->modify(' -1 month');
          }

          $offers['labels'] = array_reverse($offers['labels']);
          foreach(  $offers['data'] as $key => $data){
            $offers['data'][$key]['data'] = array_reverse($data['data']);
          }

          return $offers;
      }

      public function getValoreOfferteChart(){

        $ret['labels'] = array();
        $ret['data'] = array();

        $monthsNames = array('1'=>'Gennaio','2'=>'Febbraio','3'=>'Marzo','4'=>'Aprile',
              '5'=>'Maggio','6'=>'Giugno','7'=>'Luglio','8'=>'Agosto','9'=>'Settembre',
              '10'=>'Ottobre','11'=>'Novembre','12'=>'Dicembre');

        $offers = TableRegistry::get('Crm.Offers');


        $off = $offers->getValoreOfferte(15);

        //echo "<pre>"; print_r($off); echo "</pre>";

        foreach ($off as $key => $value) {
          $ret['labels'][] = $monthsNames[$value->meseInvio];
          $ret['data']['Inviate']['color'] = '#d81b60';
          $ret['data']['Inviate']['data'][] = $value->totMese;
        }

        return $ret;

      }

      public function getValoreOfferteChart2(){

        $ret['labels'] = array();
        //$ret['data'] = array();

        $monthsNames = array('1'=>'Gen','2'=>'Feb','3'=>'Mar','4'=>'Apr',
              '5'=>'Mag','6'=>'Giu','7'=>'Lug','8'=>'Ago','9'=>'Set',
              '10'=>'Ott','11'=>'Nov','12'=>'Dic');

        $offers = TableRegistry::get('Crm.Offers');
        $offersStatus = TableRegistry::get('Crm.OffersStatus');


        $off = $offers->getValoreOfferte(15,1);
        $offAccettate = $offers->getValoreOfferte(15,5);
        $offStatus = $offersStatus->getAll();
        $offStatusOrderFrom = $offersStatus->getOrderingStatus(5);

        //debug($offStatusOrderFrom);
        //debug($offStatus);
        //debug($off);

        $labels = [];
        $datasets = [];
        $cont = 0;
        $countStatus = count($offStatus);
        foreach ($offStatus as $key => $status) {

          foreach ($off as $key2 => $offer) {

            if(!in_array($monthsNames[$offer['meseInvio']] . " '" . substr($offer['annoInvio'],2,2),$labels)){
              $labels[] = $monthsNames[$offer['meseInvio']]  . " '" . substr($offer['annoInvio'],2,2);
            }

            $datasets[$cont + $countStatus]['label'] = $status['name'].'**';
            $datasets[$cont + $countStatus]['stack'] = 'Stack 0';
            $datasets[$cont + $countStatus]['backgroundColor'] = $status['color'];
            if($offer['actualStatus'] == $status['id']){
              $datasets[$cont + $countStatus]['dataOrig'][$offer['annoMeseInvio']] = $offer['totMese'];
            }else{
              if(!isset($datasets[$cont + $countStatus]['dataOrig'][$offer['annoMeseInvio']])){
                $datasets[$cont + $countStatus]['dataOrig'][$offer['annoMeseInvio']] = 0;
              }
            }

            // Inizializzo la struttura dati per le offerte accettate in modo da avere gli stessi mesi di quelle inviate
            if($status['ordering'] >= $offStatusOrderFrom[0]['ordering']){
              $datasets[$cont]['label'] = $status['name'].'*';
              $datasets[$cont]['stack'] = 'Stack 1';
              $datasets[$cont]['backgroundColor'] = $status['color'];
              $datasets[$cont]['dataOrig'][$offer['annoMeseInvio']] = 0;
            }

          }

          foreach ($offAccettate as $key3 => $offerAcc) {

            if($status['ordering'] >= $offStatusOrderFrom[0]['ordering']){
              // Per i mesi di cui ho dati ora scrivo il vero valore
              if($offerAcc['actualStatus'] == $status['id']){
                $datasets[$cont]['dataOrig'][$offerAcc['annoMeseInvio']] = $offerAcc['totMese'];
              }
            }

          }
          $cont ++;

        }
        //echo "<pre>"; print_r($datasets); echo "</pre>";
        foreach ($datasets as $key => $value) {

          foreach ($value['dataOrig'] as $key2 => $value2) {

            $datasets[$key]['data'][] = $value2;

          }

          unset($datasets[$key]['dataOrig']);

        }

        //echo "<pre>"; print_r($labels); echo "</pre>";
        //echo "<pre>"; print_r($datasets); echo "</pre>";

        //$datasets = array_reverse($datasets);

        $ret = array(
          'labels' => $labels,
          'datasets' => $datasets
        );

        return $ret;

      }

      public function getStoricoStati($id)
      {
        $storicoStati = TableRegistry::get('Crm.OffersStatusHistory');

        $res = $storicoStati->getStoricoStati($id);
        
        $stati = [];
        foreach($res as $stato){
          $stati[] = [
            'id' => $stato['id'],
            'nome' => $stato['offers_status']['name'],
            'data' => $stato['created']->format('d/m/Y H:i:m')
          ];
        }

        return $stati;

      }

      public function deleteStatus($id)
      {
        $storicoStati = TableRegistry::get('Crm.OffersStatusHistory');

        $res = $storicoStati->deleteStatus($id);

        return $res;
      }

      public function getOffersAzienda($id, $limit = 50)
      {
        $offersTable = TableRegistry::get('Crm.Offers');
        return $offersTable->find('all')->where(['Offers.id_azienda_dest' => $id])
          ->order(['Offers.emission_date' => 'DESC'])->limit($limit)->contain('AziendaEmit')->toArray();
      }
}
