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
namespace AttachmentManager\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Attachments Model
 *
 * @method \AttachmentManager\Model\Entity\Attachment get($primaryKey, $options = [])
 * @method \AttachmentManager\Model\Entity\Attachment newEntity($data = null, array $options = [])
 * @method \AttachmentManager\Model\Entity\Attachment[] newEntities(array $data, array $options = [])
 * @method \AttachmentManager\Model\Entity\Attachment|bool save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \AttachmentManager\Model\Entity\Attachment|bool saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \AttachmentManager\Model\Entity\Attachment patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \AttachmentManager\Model\Entity\Attachment[] patchEntities($entities, array $data, array $options = [])
 * @method \AttachmentManager\Model\Entity\Attachment findOrCreate($search, callable $callback = null, $options = [])
 *
 * @mixin \Cake\ORM\Behavior\TimestampBehavior
 */
class AttachmentsTable extends Table
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

        $this->setTable('attachments');
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
            ->scalar('context')
            ->maxLength('context', 255)
            ->requirePresence('context', 'create')
            ->notEmpty('context');

        $validator
            ->integer('id_item')
            ->requirePresence('id_item', 'create')
            ->notEmpty('id_item');

        $validator
            ->scalar('file')
            ->maxLength('file', 255)
            ->requirePresence('file', 'create')
            ->notEmpty('file');

        $validator
            ->scalar('file_path')
            ->maxLength('file_path', 255)
            ->requirePresence('file_path', 'create')
            ->notEmpty('file_path');

        $validator
            ->scalar('file_type')
            ->maxLength('file_type', 255)
            ->requirePresence('file_type', 'create')
            ->notEmpty('file_type');

        $validator
            ->numeric('file_size')
            ->requirePresence('file_size', 'create')
            ->notEmpty('file_size');

        $validator
            ->date('upload_date')
            ->requirePresence('upload_date', 'create')
            ->notEmpty('upload_date');

        $validator
            ->boolean('deleted');

        return $validator;
    }

    public function getAttachmentsNumber($context, $idItem)
    {
        $where = [
            'Attachments.context' => $context, 
            'Attachments.id_item' => $idItem, 
            'Attachments.deleted' => '0'
        ];

        $res = $this->find()
			->where($where)
            ->toArray();
            
        return $res;
    }
}
