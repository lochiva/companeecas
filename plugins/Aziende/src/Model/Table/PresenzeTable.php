<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Presenze  (https://www.companee.it)
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
namespace Aziende\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use Cake\ORM\TableRegistry;
use Cake\Database\Expression\QueryExpression;
use Cake\I18n\Date;
use App\Model\Table\AppTable;

/**
 * Presenze Model
 *
 * @property \Aziende\Model\Table\GuestsTable&\Cake\ORM\Association\BelongsTo $Guests
 * @property \Aziende\Model\Table\SediTable&\Cake\ORM\Association\BelongsTo $Sedi
 *
 * @method \Aziende\Model\Entity\Presenze get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\Presenze newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\Presenze[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\Presenze|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\Presenze saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\Presenze patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\Presenze[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\Presenze findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class PresenzeTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('presenze');
        $this->setDisplayField('id');
        $this->setPrimaryKey(['id']);

        $this->addBehavior('Timestamp');

        $this->belongsTo('Guests', [
            'foreignKey' => 'guest_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Guests'
        ]);
        $this->belongsTo('Sedi', [
            'foreignKey' => 'sede_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Sedi'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->date('date')
            ->requirePresence('date', 'create')
            ->notEmptyDateTime('date');

        $validator
            ->boolean('presente')
            ->notEmptyString('presente');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['guest_id'], 'Guests'));
        $rules->add($rules->existsIn(['sede_id'], 'Sedi'));

        $rules->add($rules->isUnique(
            ['guest_id', 'date'],
            'Questo ospite è gia stato segnato per questa data.'
        ));

        return $rules;
    }

    public function getPresenzeGuestPrecedentiCheckIn($guestId, $checkInDate)
    {
        $presenze = $this->find()
            ->where([
                'guest_id' => $guestId,
                'date <' => $checkInDate
            ])
            ->toArray();
            
        return $presenze;
    }

    public function getPresenzeSedeForDay($sedeId, $day)
    {
        $presenze = $this->find()
            ->where([
                'Presenze.sede_id' => $sedeId,
                'Presenze.date' => $day,
                'Presenze.presente' => 1,
                'Guests.deleted' => 0
            ])
            ->contain(['Guests'])
            ->toArray();
            
        return $presenze;
    }

    public function getPresenzeSedeForMonth($sedeId, $month)
    {
        $presenze = $this->find()
            ->where([
                'Presenze.sede_id' => $sedeId,
                'Presenze.date LIKE' => $month.'%',
                'Presenze.presente' => 1,
                'Guests.deleted' => 0
            ])
            ->contain(['Guests'])
            ->toArray();
            
        return $presenze;
    }

    public function getGuestLastPresenzaByDate($guestId, $date)
    {
        $presenza = $this->find()
            ->where([
                'guest_id' => $guestId,
                'presente' => 1,
                'date <=' => $date
            ])
            ->order(['date' => 'DESC'])
            ->first();
            
        return $presenza;
    }

    public function getGuestPresenzaByDate($guestId, $date)
    {
        $presenza = $this->find()
            ->where([
                'guest_id' => $guestId,
                'presente' => 1,
                'date' => $date
            ])
            ->first();
            
        return $presenza;
    }

    public function getGuestPresenzeByDate($guestId, $date)
    {
        $presenza = $this->find()
            ->where([
                'guest_id' => $guestId,
                'presente' => 1,
                'date >=' => $date
            ])
            ->toArray();
            
        return $presenza;
    }

    public function countPresenze ($statement, $copmpany_id=null) {
        $guest_daily_price = $statement->agreement->guest_daily_price;
        if (isset($copmpany_id)) {
            $where = ['agreement_company_id' => $copmpany_id];
        } else {
            $where = [];
        }
        $sedi = TableRegistry::get('Aziende.AgreementsToSedi')->find('all')
        ->contain(['Sedi'])
        ->where(['agreement_id' => $statement->agreement_id, 'Sedi.deleted' => 0])
        ->where($where)
        ->extract('sede_id')
        ->toList();
    
        if (count($sedi)) {
            $presenzeQuery = TableRegistry::get('Aziende.Presenze')->find('all')
            ->contain(['Guests'])
            ->where(['Presenze.sede_id IN' => $sedi, 'Presenze.presente' => true])
            ->where(function (QueryExpression $exp, Query $q) use ($statement) {
                return $exp->between('Presenze.date', $statement->period_start_date, $statement->period_end_date);
            });

            $presenze = $presenzeQuery->count();

            $dateLimit = new Date($statement->period_end_date);
            $minors = $presenzeQuery
                ->select(['Presenze.guest_id'])
                ->distinct(['Presenze.guest_id'])
                ->where(['Guests.birthdate >=' => $dateLimit->modify('-30 months')])
                ->count();
        } else {
            $presenze = 0;
            $minors = 0;
        }
        return ['presenze' => $presenze, 'minori' => $minors, 'guest_daily_price' => $guest_daily_price];
    }
}
