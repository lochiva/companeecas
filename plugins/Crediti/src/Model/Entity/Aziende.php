<?php
namespace Crediti\Model\Entity;

use Cake\ORM\Entity;

/**
 * Aziende Entity.
 */
class Aziende extends Entity
{

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * @var array
     */
    protected $_accessible = [
        'denominazione' => true,
        'cognome' => true,
        'nome' => true,
        'famiglia' => true,
        'cod_paese' => true,
        'piva' => true,
        'cf' => true,
        'cod_eori' => true,
        'cod_sispac' => true,
        'telefono' => true,
        'email_info' => true,
        'email_contabilita' => true,
        'email_solleciti' => true,
        'fax' => true,
        'cliente' => true,
        'fornitore' => true,
    ];
}
