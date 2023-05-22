<?php
/**
* Cespiti is a plugin for manage attachment
*
* Companee :    Cespiti  (https://www.companee.it)
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
namespace Cespiti\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;

/**
 * Cespiti Model
 *
 * @method \Cespiti\Model\Entity\Cespiti get($primaryKey, $options = [])
 * @method \Cespiti\Model\Entity\Cespiti newEntity($data = null, array $options = [])
 * @method \Cespiti\Model\Entity\Cespiti[] newEntities(array $data, array $options = [])
 * @method \Cespiti\Model\Entity\Cespiti|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Cespiti\Model\Entity\Cespiti patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Cespiti\Model\Entity\Cespiti[] patchEntities($entities, array $data, array $options = [])
 * @method \Cespiti\Model\Entity\Cespiti findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class CespitiTable extends AppTable
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

        $this->setTable('cespiti');
        $this->setDisplayField('id');
        $this->setPrimaryKey('id');

        $this->addBehavior('Timestamp');
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
            ->allowEmpty('id', 'create');

        $validator
            ->integer('id_azienda')
            ->requirePresence('id_azienda', 'create')
            ->notEmpty('id_azienda');

        $validator
            ->integer('id_fattura_passiva')
            ->requirePresence('id_fattura_passiva', 'create')
            ->notEmpty('id_fattura_passiva');

        $validator
            ->requirePresence('num', 'create')
            ->notEmpty('num');

        $validator
            ->requirePresence('descrizione', 'create')
            ->notEmpty('descrizione');

        $validator
            ->boolean('stato')
            ->requirePresence('stato', 'create')
            ->notEmpty('stato');

        $validator
            ->boolean('cancellato');

        return $validator;
    }
}
