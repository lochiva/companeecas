<?php
namespace Leads\Model\Entity;

use Cake\ORM\Entity;

/**
 * LeadsQuestion Entity
 *
 * @property int $id
 * @property int $id_ensemble
 * @property string $name
 * @property int $id_type
 * @property string $info
 * @property int $ordering
 * @property bool $deleted
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class LeadsQuestion extends Entity
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
        'id_ensemble' => true,
        'name' => true,
        'id_type' => true,
        'info' => true,
        'options' => true,
        'ordering' => true,
        'deleted' => true,
        'created' => true,
        'modified' => true
    ];
}
