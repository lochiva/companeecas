<?php
namespace Aziende\Model\Entity;

use Cake\ORM\Entity;

/**
 * SurveysInterviewsPayment Entity
 *
 * @property int $id
 * @property int $interview_id
 * @property int $payment_id
 * @property \Cake\I18n\Time $created
 * @property \Cake\I18n\Time $modified
 * @property \Cake\I18n\Time|null $deleted
 *
 * @property \Aziende\Model\Entity\Interview $interview
 * @property \Aziende\Model\Entity\Payment $payment
 */
class SurveysInterviewsPayment extends Entity
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
        'payment_id' => true,
        'created' => true,
        'modified' => true,
        'deleted' => true,
        'interview' => true,
        'payment' => true
    ];
}
