<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Agreements To Sedi  (https://www.companee.it)
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
 * AgreementsToSedi Model
 *
 * @property \Aziende\Model\Table\AgreementsTable&\Cake\ORM\Association\BelongsTo $Agreements
 * @property \Aziende\Model\Table\SedesTable&\Cake\ORM\Association\BelongsTo $Sedes
 *
 * @method \Aziende\Model\Entity\AgreementsToSedi get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\AgreementsToSedi newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\AgreementsToSedi[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\AgreementsToSedi|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\AgreementsToSedi saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\AgreementsToSedi patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\AgreementsToSedi[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\AgreementsToSedi findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AgreementsToSediTable extends AppTable
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

        $this->setTable('agreements_to_sedi');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Agreements', [
            'foreignKey' => 'agreement_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Agreements'
        ]);
        $this->belongsTo('Sedi', [
            'foreignKey' => 'sede_id',
            'joinType' => 'INNER',
            'className' => 'Aziende.Sedi'
        ]);
        $this->belongsTo('AgreementsCompanies', [
            'foreignKey' => 'agreement_company_id',
            'bindingKey' => 'id',
            'className' => 'Aziende.AgreementsCompanies',
            'propertyName' => 'agreement_company_data'
        ]);
        $this->belongsTo('Guests', [
            'foreignKey' => 'guest_id',
            'className' => 'Aziende.Guests',
            'propertyName' => 'guests'
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
            ->integer('capacity')
            ->notEmptyString('capacity');

        $validator
            ->integer('capacity_increment')
            ->allowEmptyString('capacity_increment');

        $validator
            ->boolean('active')
            ->notEmptyString('active');

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
        $rules->add($rules->existsIn(['agreement_id'], 'Agreements'));
        $rules->add($rules->existsIn(['sede_id'], 'Sedi'));

        return $rules;
    }

    public function countActiveSediForAgreement($id)
    {
        return $this->find()
            ->where([
                'agreement_id' => $id,
                'active' => 1
            ])
            ->count();
    }
}
