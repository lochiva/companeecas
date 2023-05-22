<?php
/**
* Attachment manager is a plugin for manage attachment
*
* Companee :    Attachment   (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/


namespace AttachmentManager\Model\Entity;

use Cake\ORM\Entity;

/**
 * Attachment Entity
 *
 * @property int $id
 * @property string $context
 * @property int $id_item
 * @property string $file
 * @property string $file_path
 * @property string $file_type
 * @property float $file_size
 * @property \Cake\I18n\Date $upload_date
 * @property bool $deleted
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class Attachment extends Entity
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
        'context' => true,
        'id_item' => true,
        'file' => true,
        'file_path' => true,
        'file_type' => true,
        'file_size' => true,
        'upload_date' => true,
        'deleted' => true,
        'created' => true,
        'modified' => true
    ];
}
