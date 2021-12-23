<?php
namespace Leads\Model\Entity;

use Cake\ORM\Entity;

/**
 * LeadsInterview Entity
 *
 * @property int $id
 * @property string $id_azienda
 * @property string $id_ensemble
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class LeadsInterview extends Entity
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
        'id_azienda' => true,
        'id_contatto' => true,
        'id_ensemble' => true,
        'name' => true,
        'created' => true,
        'modified' => true
    ];
}
