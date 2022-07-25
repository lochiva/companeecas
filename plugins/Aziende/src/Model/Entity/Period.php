<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * Period Entity
 *
 * @property int $id
 * @property string $label
 * @property string $start_date
 * @property string $end_date
 * @property bool $visible
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Period extends Entity
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
        'label' => true,
        'start_date' => true,
        'end_date' => true,
        'visible' => true,
        'created' => true,
        'modified' => true
    ];
}
