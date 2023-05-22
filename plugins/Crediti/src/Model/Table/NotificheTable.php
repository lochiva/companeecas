<?php
/**
* Crediti is a plugin for manage attachment
*
* Companee :    Notifiche  (https://www.companee.it)
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
namespace Crediti\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

/**
 * Credits Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Aziende
 * @property \Cake\ORM\Association\BelongsTo $CredtisTotals
 *
 *
 */
class NotificheTable extends AppTable
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->setTable('notifiche');
        $this->displayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Crediti.Aziende', [
            'foreignKey' => 'azienda_id',
            'joinType' => 'INNER'
        ]);
        $this->belongsTo('Crediti.CreditsTotals',[
          'foreignKey' => 'credits_totals_id',
          'joinType' => 'INNER'
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
            ->add('id', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('id', 'create');

        $validator
            ->requirePresence('azienda_id', 'valid',['rule' => 'numeric'])
            ->notEmpty('azienda_id');

        $validator
            ->requirePresence('credits_totals_id', 'valid',['rule' => 'numeric'])
            ->notEmpty('credits_totals_id');
        $validator
            ->requirePresence('testo', 'create')
            ->notEmpty('testo');
        $validator
            ->requirePresence('type', 'create')
            ->notEmpty('type');
        return $validator;
    }

    public function saveNotifica($data)
    {
      $new = $this->newEntity();

      $new->azienda_id = $data['azienda_id'];
      $new->credits_totals_id = $data['credits_totals_id'];
      $new->testo = $data['testo'];
      $new->type = $data['type'];
      $new->author_id = $data['author_id'];

      return $this->save($new);
    }


}
