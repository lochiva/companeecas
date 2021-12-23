<?php
namespace Crediti\Model\Entity;

use Cake\ORM\Entity;

/**
 * Credit Entity.
 */
class Credit extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'azienda_id' => true,
        'cod_sispac' => true,
        'num_documento' => true,
        'data_emissione' => true,
        'data_scadenza' => true,
        'importo' => true,
        'azienda' => true,
    ];
}
