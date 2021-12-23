<?php
namespace Gdpr\Model\Entity;

use Cake\ORM\Entity;

/**
 * GdprContactToken Entity
 *
 * @property int $id
 * @property string $token
 * @property string $email
 * @property int $used
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class GdprContactToken extends Entity
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
        'token' => true,
        'email' => true,
        'used' => true,
        'created' => true,
        'modified' => true
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'token'
    ];
}
