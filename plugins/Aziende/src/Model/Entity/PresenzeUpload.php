<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;
use Cake\Routing\Router;
use Cake\Core\Configure;

/**
 * PresenzeUpload Entity
 *
 * @property int $id
 * @property int $sede_id
 * @property \Cake\I18n\Date $date
 * @property string $file
 * @property string $filepath
 * @property bool $deleted
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 *
 * @property \Aziende\Model\Entity\Sede $sedi
 */
class PresenzeUpload extends Entity
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
        'sede_id' => true,
        'date' => true,
        'file' => true,
        'filepath' => true,
        'deleted' => true,
        'created' => true,
        'modified' => true,
        'sedi' => true
    ];

    protected function _getFullPath()
    {
        return Router::url(['plugin' => 'Aziende', 'controller' => 'Ws', 'action' => 'downloadFile', $this->id]);
    }
}
