<?php
namespace Crediti\Model\Entity;

use Cake\ORM\Entity;

/**
 * CreditsTotal Entity.
 */
class CreditsTotal extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'azienda_id' => true,
        'total' => true,
        'total_scaduti' => true,
        'data_conto' => true,
        'rating' => true,
        'num_importazione' => true,
        'lavorato' => true,
        'azienda' => true,
    ];
}
