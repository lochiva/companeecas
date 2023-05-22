<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Sedi Tipi Capitolato  (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class SediTipiCapitolatoTable extends AppTable
{
    
    public function initialize(array $config)
    {
        $this->setTable('sedi_tipi_capitolato');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
    }
    
    
    
}