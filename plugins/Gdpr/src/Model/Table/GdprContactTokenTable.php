<?php
/**
* Gdpr is a plugin for manage attachment
*
* Companee :    Gdpr Contact Token  (https://www.companee.it)
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
namespace Gdpr\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * GdprContactToken Model
 *
 * @method \Gdpr\Model\Entity\GdprContactToken get($primaryKey, $options = [])
 * @method \Gdpr\Model\Entity\GdprContactToken newEntity($data = null, array $options = [])
 * @method \Gdpr\Model\Entity\GdprContactToken[] newEntities(array $data, array $options = [])
 * @method \Gdpr\Model\Entity\GdprContactToken|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Gdpr\Model\Entity\GdprContactToken|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \Gdpr\Model\Entity\GdprContactToken patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \Gdpr\Model\Entity\GdprContactToken[] patchEntities($entities, array $data, array $options = [])
 * @method \Gdpr\Model\Entity\GdprContactToken findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class GdprContactTokenTable extends Table
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

        $this->setTable('gdpr_contact_token');
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
            ->scalar('token')
            ->maxLength('token', 255)
            ->requirePresence('token', 'create')
            ->notEmpty('token');

        $validator
            ->email('email')
            ->maxLength('email', 100)
            ->requirePresence('email', 'create')
            ->notEmpty('email');

        $validator
            ->integer('used')
            ->requirePresence('used', 'create')
            ->notEmpty('used');

        return $validator;
    }

}
