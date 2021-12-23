<?php

namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;

class LuoghiComponent extends Component
{

    public function initialize(array $config)
    {
      parent::initialize($config);
    }

    public function getProvinceTable($pass)
    {
        $opt = array();
        $toRet = array();
        $tableProvince = TableRegistry::get('Province');

        $columns = [
          0 => ['val' => 'Province.id', 'type' => 'text'],
          1 => ['val' => 'Province.s_prv', 'type' => 'text' ],
          2 => ['val' => 'abilitato', 'type' => 'text' , 'having' => 1],

        ];

        $opt['fields'] = [
          'id',
          's_prv',
          'abilitato' => 'IF(enabled , "SI" , "NO")'
        ];

        $toRet['res'] = $tableProvince->queryForTableSorter($columns,$opt,$pass);
        $toRet['tot'] = $tableProvince->queryForTableSorter($columns,$opt,$pass,true);

        return $toRet;

    }
}
