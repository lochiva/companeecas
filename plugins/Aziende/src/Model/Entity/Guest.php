<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * Guest Entity.
 */
class Guest extends Entity
{
    protected $_accessible = [
        '*' => true,
    ];

    public function cleanDirty($param)
    {
        if(is_array($param)){
          foreach($param as $val){
              unset($this->_dirty[$val]);
          }
        }else{
             unset($this->_dirty[$param]);
        }
    }

}