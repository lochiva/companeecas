<?php
namespace Crediti\Model\Entity;

use Cake\ORM\Entity;

/**
 * Kpi Entity.
 */
class Kpi extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'nome' => true,
        'giorno' => true,
        'valore' => true,
    ];
}
