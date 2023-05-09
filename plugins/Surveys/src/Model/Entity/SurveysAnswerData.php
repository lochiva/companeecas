<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Surveys Answer Data  (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
namespace Surveys\Model\Entity;

use Cake\ORM\Entity;

/**
 * SurveysAnswerData Entity
 *
 * @property int $id
 * @property int $interview_id
 * @property int $question_id
 * @property string $value
 * @property string $options
 * @property string $type
 * @property string $final_value
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 */
class SurveysAnswerData extends Entity
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
        'interview_id' => true,
        'question_id' => true,
        'value' => true,
        'options' => true,
        'type' => true,
        'final_value' => true,
        'created' => true,
        'modified' => true
    ];
}
