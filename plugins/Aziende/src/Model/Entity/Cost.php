<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * Cost Entity
 *
 * @property int $id
 * @property int $statement_company
 * @property int $category_id
 * @property float $amount
 * @property float $share
 * @property string $attachment
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \aziende\Model\Entity\CostsCategory $costs_category
 */
class Cost extends Entity
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
        'statement_company' => true,
        'category_id' => true,
        'amount' => true,
        'share' => true,
        'attachment' => true,
        'filename' => true,
        'created' => true,
        'modified' => true,
        'costs_category' => true,
        'deleted' => true,
        'description' => true,
        'supplier' => true,
        'number' => true,
        'date' => true,
        'notes' => true,
    ];
}
