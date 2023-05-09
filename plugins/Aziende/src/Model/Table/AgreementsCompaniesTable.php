<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Agreements Companies  (https://www.companee.it)
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
 * AgreementsCompanies Model
 *
 * @property \Aziende\Model\Table\AgreementsTable&\Cake\ORM\Association\BelongsTo $Agreements
 * @property \Aziende\Model\Table\AziendeTable&\Cake\ORM\Association\BelongsTo $Aziende
 *
 * @method \Aziende\Model\Entity\AgreementsCompany get($primaryKey, $options = [])
 * @method \Aziende\Model\Entity\AgreementsCompany newEntity($data = null, array $options = [])
 * @method \Aziende\Model\Entity\AgreementsCompany[] newEntities(array $data, array $options = [])
 * @method \Aziende\Model\Entity\AgreementsCompany|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\AgreementsCompany saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Aziende\Model\Entity\AgreementsCompany patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Aziende\Model\Entity\AgreementsCompany[] patchEntities($entities, array $data, array $options = [])
 * @method \Aziende\Model\Entity\AgreementsCompany findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AgreementsCompaniesTable extends AppTable
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

        $this->setTable('agreements_companies');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');

        $this->belongsTo('Agreements', [
            'foreignKey' => 'agreement_id',
            'className' => 'Aziende.Agreements'
        ]);

        $this->hasMany('AgreementsToSedi', [
            'foreignKey' => 'agreement_company_id',
            'bindingKey' => 'id',
            'className' => 'Aziende.AgreementsToSedi',
            'propertyName' => 'sedi'
        ]);

        $this->hasMany('StatementCompany', [
            'foreignKey' => 'company_id',
            'bindingKey' => 'id',
            'className' => 'Aziende.StatementCompany',
            'propertyName' => 'statement'
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

        return $validator;
    }
}
