<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Agreements   (https://www.companee.it)
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
use App\Model\Table\AppTable;

/**
 * Agreements Model
 *
 * @property \Aziende\Model\Table\SediProcedureAffidamentoTable&\Cake\ORM\Association\BelongsTo $Procedures
 * @property \Aziende\Model\Table\AgreementsToSediTable&\Cake\ORM\Association\HasMany $AgreementsToSedi
 *
 * @method \Aziende\Model\Entity\Agreement get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\Agreement newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\Agreement[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\Agreement|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\Agreement saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\Agreement patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\Agreement[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\Agreement findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AgreementsTable extends AppTable
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

        $this->setTable('agreements');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Aziende', [
            'foreignKey' => 'azienda_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Aziende'
        ]);
        $this->belongsTo('Procedures', [
            'foreignKey' => 'procedure_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.SediProcedureAffidamento'
        ]);
        $this->hasMany('AgreementsToSedi', [
            'foreignKey' => 'agreement_id',
            'className' => 'Aziende.AgreementsToSedi'
        ]);

        $this->hasMany('AgreementsCompanies', [
            'foreignKey' => 'agreement_id',
            'className' => 'Aziende.AgreementsCompanies',
            'dependent' => true,
            'propertyName' => 'companies',
            'saveStrategy' => 'replace'
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
            ->integer('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->date('date_agreement')
            ->requirePresence('date_agreement', 'create')
            ->notEmptyDateTime('date_agreement');

        $validator
            ->date('date_agreement_expiration')
            ->requirePresence('date_agreement_expiration', 'create')
            ->notEmptyDateTime('date_agreement_expiration');

        $validator
            ->date('date_extension_expiration')
            ->allowEmptyDateTime('date_extension_expiration');

        $validator
            ->decimal('guest_daily_price')
            ->notEmptyString('guest_daily_price');

        $validator
            ->scalar('cig')
            ->maxLength('cig', 10)
            ->minLength('cig', 10)
            ->allowEmptyString('cig');

        $validator->add('cig', 'custom', [
            'rule' => 'validateCig',
            'provider' => 'table',
            'message' => 'Il CIG inserito non è valido.'
        ]);

        $validator
            ->integer('capacity_increment');

        $validator
            ->boolean('approved');

        $validator
            ->boolean('deleted');

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
        $rules->add($rules->existsIn(['azienda_id'], 'Aziende'));
        $rules->add($rules->existsIn(['procedure_id'], 'Procedures'));

        return $rules;
    }

    public function getFieldLabelsList() {
        return [
            'id' => 'ID',
            'azienda_id' => 'ID ente',
            'procedure_id' => 'ID procedura di affidamento',
            'date_agreement' => 'Data stipula convenzione',
            'date_agreement_expiration' => 'Data scadenza convenzione',
            'date_extension_expiration' => 'Data scadenza proroga',
            'guest_daily_ptice' => 'Prezzo giornaliero ospiti',
            'cig' => 'CIG',
            'capacity_increment' => 'Incremento posti',
            'approved' => 'Approvato',
            'deleted' => 'Cancellato',
            'created' => 'Data creazione',
            'modified' => 'Data modifica'
        ];
    }

    public function validateCig($value, array $context)
    {
        $regex = '/[0-9]{7}[0-9A-F]{3}|[V-Z]{1}[0-9A-F]{9}|[A-U]{1}[0-9A-F]{9}/';
        if (preg_match($regex, $value)) {
            return true;
        }

        return false;
    }
}
