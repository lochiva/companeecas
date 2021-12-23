<?php

namespace Progest\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class PeopleComponent extends Component
{
    protected $tablePeople;
    public $components = ['Excel'];

    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->tablePeople = TableRegistry::get('Progest.People');
        $this->tableExtension = TableRegistry::get('Progest.PeopleExtension');
        $this->tableFamiliari = TableRegistry::get('Progest.Familiari');
        $this->tableOrders = TableRegistry::get('Progest.Orders');
    }

    public function get($id = 0)
    {
        return $this->tablePeople->get($id,['contain'=>['PeopleExtension','Familiari']]);
    }

    public function autocomplete($q = '',$active=false)
    {
        return $this->tablePeople->autocomplete($q,$active);
    }

    public function save(array $data = array())
    {
        foreach($data as $key=>$val){
            if(strpos($key, 'date') !== false && !empty($val)){
              $data[$key] = Time::createFromFormat('d/m/Y',$val);
            }
        }
        $res = $this->tablePeople->savePerson($data);
        //debug($res);die;

        if(isset($data['extension']['address']) && isset($data['extension']['comune']) && isset($data['extension']['provincia'])){
    		  $fullAddress = $data['extension']['address'] . " " . $data['extension']['comune'] . " " . $data['extension']['provincia'];
    		  $coordinates = $this->getCoordinatesFromAddress($fullAddress);
        }else{
          $coordinates = [];
        }

    		if(!empty($coordinates)){
    			$data['extension']['address_lat'] = $coordinates['lat'];
    			$data['extension']['address_long'] = $coordinates['long'];
    		}else{
    			$data['extension']['address_lat'] = '';
    			$data['extension']['address_long'] = '';
    		}


        if($res && !empty($data['extension'])){
            $data['extension']['id_person'] = $res->id;
            $this->tableExtension->saveExtension($data['extension']);
        }

        return $res;
    }

    public function checkDelete($id = 0)
    {

        if($this->tableOrders->find()->where(['id_person'=>$id])->count() > 0){
            return "Errore durante la cancellazione, sono presenti dei buoni d'ordini relativi alla persona.";
        }else{
            return true;
        }

    }

    public function delete($id = 0)
    {
        $person = $this->get($id);
        return $this->tablePeople->softDelete($person);
    }

    public function getForTable($pass)
    {
        $opt = array();
        $toRet = array();

        $opt['contain'] = ['PeopleExtension','GroupActiveOrders' => ['GroupServices'=>['Services'] ]];
        $opt['group'] = 'People.id';
        $opt['order'] = ['People.surname' => 'ASC','People.name' => 'ASC' ];
        $columns = [
          0 => ['val' => 'People.surname', 'type' => 'text'],
          1 => ['val' => 'People.name', 'type' => 'text' ],
          2 => ['val' => 'PeopleExtension.comune', 'type' => 'text' ],
          3 => ['val' => 'PeopleExtension.address', 'type' => 'text' ],
          4 => ['val' => 'People.birthdate', 'type' => 'date'],
		  5 => ['val' => 'People.deceased', 'type' => 'text'],
          6 => ['val' => 'PeopleExtension.tel', 'type' => 'text'],
          7 => ['val' => 'PeopleExtension.cell', 'type' => 'text'],
          8 => ['val' => 'services', 'type' => 'text', 'having' => 1],
        ];

        $opt['fields'] = [
          'id' => 'People.id',
          'surname' => 'People.surname',
          'name' => 'People.name',
          'comune' => 'PeopleExtension.comune',
          'address' => 'PeopleExtension.address',
          'birth_date' => 'People.birthdate',
		  'deceased' => 'People.deceased',
          'tel' => 'PeopleExtension.tel',
          'cell' => 'PeopleExtension.cell',
          'services' => 'GROUP_CONCAT( DISTINCT Services.name ORDER BY Services.ordering ASC SEPARATOR ", ")',
        ];

        $toRet['res'] = $this->tablePeople->queryForTableSorter($columns,$opt,$pass);
        $toRet['tot'] = $this->tablePeople->queryForTableSorter($columns,$opt,$pass,true);

        return $toRet;

    }

    public function tableIndirizzario($pass = array(), $xls = false)
    {
          $pass['query']['size'] = 'all';
          $opt = array();

          $opt['order'] = ['People.surname' => 'ASC','People.name' => 'ASC' ];
          $opt['contain'] = ['PeopleExtension','Familiari'=>['GradoParentela','fields' =>
          ['Familiari.name','Familiari.id_person','Familiari.surname','GradoParentela.name','Familiari.tel','Familiari.cell']],
            'GroupActiveOrders' => ['GroupServices'=>['Services' =>
            ['fields' => ['services' => 'GROUP_CONCAT( DISTINCT Services.name SEPARATOR ", ")' ]]] ]];
          $opt['group'] = 'People.id';
          $columns = [
            0 => ['val' => 'People.surname', 'type' => 'text'],
            1 => ['val' => 'People.name', 'type' => 'text' ],
            2 => ['val' => 'PeopleExtension.comune', 'type' => 'text' ],
            3 => ['val' => 'PeopleExtension.address', 'type' => 'text' ],
            4 => ['val' => 'People.birthdate', 'type' => 'date'],
			5 => ['val' => 'People.deceased', 'type' => 'text'],
            6 => ['val' => 'PeopleExtension.tel', 'type' => 'text'],
            7 => ['val' => 'PeopleExtension.cell', 'type' => 'text'],
            8 => ['val' => 'services', 'type' => 'text', 'having' => 1],
          ];

          $res = $this->tablePeople->queryForTableSorter($columns,$opt,$pass);
          $data = array();
          $num = 0;

          foreach($res as $key =>  $person){
              $num++;
              $newData = [$num, $person['surname'], $person['name']];

              if(!empty($person['extension'])){
                  $fullAddress = $person['extension']['address'].' '.$person['extension']['cap']
                    .' '.$person['extension']['comune'].' '.$person['extension']['provincia'];
                  $extension = [trim($fullAddress), $person['extension']['tel'], $person['extension']['cell']];
              }else{
                  $extension = ['', '', ''];
              }
              $newData = array_merge($newData,$extension);
              $data[] = array_merge($newData,['','','','','']);
              foreach ($person['familiari'] as $familiare) {
                  $data[] = [
                    $num,'','','','','',
                    $familiare['grado_parentela']['name'],
                    $familiare['name'],
                    $familiare['surname'],
                    $familiare['tel'],
                    $familiare['cell']
                  ];
              }
          }
          $header = 'Report persone';
          if(!empty($pass['query']['filter'])){
              $first = true;
              $header.=' - ';
              foreach($pass['query']['filter'] as $filtro){
                  if(!$first){
                    $header.= ', ';
                  }
                  $header.=$filtro;
                  $first = false;
              }
          }
          $opt = array('title' => 'Report persone','filter'=> true, 'header'=>$header,//'landscape' => true,
            'columns' => [
              'N°'=>'num', 'Cognome'=>'string', 'Nome'=>'string','Indirizzo completo' => 'string',
              'Telefono'=>'string','Cellulare'=>'string', 'Parentela'=>'string', 'Cognome Familiare'=>'string',
              'Nome Familiare'=>'string', 'Telefono Familiare'=>'string', 'Cellulare Familiare'=>'string'
              ]);

          if($xls){
            $this->Excel->generateExcel($data,$opt);
            $this->Excel->download();
          }else{
            $this->Excel->printTable($data,$opt);
          }

    }

    public function getFamiliare($id = 0)
    {
        return $this->tableFamiliari->get($id);
    }

    public function saveFamiliare(array $data = array())
    {
        foreach($data as $key=>$val){
            if(strpos($key, 'date') !== false && !empty($val)){
              $data[$key] = Time::createFromFormat('d/m/Y',$val);
            }
        }
        $res = $this->tableFamiliari->saveFamiliare($data);

        return $res;
    }

    public function deleteFamiliare($id = 0)
    {
        $familiare = $this->getFamiliare($id);
        return $this->tableFamiliari->softDelete($familiare);
    }

    public function checkDeleteFamiliare($id)
    {
        if($this->tableOrders->find()->where(['id_richiedente'=>$id])->count() > 0){
            return "Errore durante la cancellazione, sono presenti dei buoni d'ordini che hanno come richiedente il familiare.";
        }else{
            return true;
        }
    }

    public function getOrders($id)
    {
        return $this->tableOrders->find()->contain(['Aziende', 'ServicesOrders'=>['Services'],'ContactsOrders'])
          ->where(['id_person' => $id, 'id_status' => 1])->toArray();
    }

	public function getCoordinatesFromAddress($address){

		$address = str_replace(" ", "+", $address);

		$json = file_get_contents("https://maps.googleapis.com/maps/api/geocode/json?address=" . $address);

		$json = json_decode($json);

		$coordinates = [];

		if(!empty($json->{'results'})){
			$coordinates['lat'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lat'};
			$coordinates['long'] = $json->{'results'}[0]->{'geometry'}->{'location'}->{'lng'};
		}

		return $coordinates;

	}
}
