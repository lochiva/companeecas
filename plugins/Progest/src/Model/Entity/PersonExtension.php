<?php
namespace Progest\Model\Entity;

use Cake\ORM\Entity;

/**
 * ProgestPeopleExtension Entity
 *
 * @property int $id
 * @property int $id_person
 * @property bool $last
 * @property string $address
 * @property string $comune
 * @property string $cap
 * @property string $provincia
 * @property string $tel
 * @property string $cell
 * @property string $email
 * @property bool $deleted
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class PersonExtension extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        '*' => true,
        'id' => false
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
