<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * SkillsContact Entity
 *
 * @property int $id
 * @property int $id_contatto
 * @property int $id_skill
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class SkillsContact extends Entity
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
        'id' => true
    ];
}
