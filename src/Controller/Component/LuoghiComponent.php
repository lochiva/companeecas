<?php
/** 
* Companee :    Luoghi (https://www.companee.it)
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
