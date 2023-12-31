<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Aziende  (https://www.companee.it)
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
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class AziendeTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->setTable('aziende');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Aziende.Azienda');
        $this->hasMany('Aziende.Sedi',['foreignKey' => 'id_azienda']);
        $this->hasMany('Aziende.Contatti',['foreignKey' => 'id_azienda']);
        //$this->belongsTo('Document.Projects',['foreignKey' => 'id_project']);
        $this->belongsToMany('Gruppi', [
            'foreignKey' => 'id_azienda',
            'targetForeignKey' => 'id_gruppo',
            'through' => 'Aziende.AziendeToGruppi',
            'className' => 'Aziende.AziendeGruppi',
            'propertyName' => 'gruppi'
        ]);
        $this->hasOne('GroupGruppi',[
          'foreignKey' => 'id_azienda',
          'className' => 'Aziende.AziendeToGruppi',
        ]);
        $this->belongsTo('Tipi', [
          'foreignKey' => 'id_tipo',
          'className' => 'Aziende.AziendeTipi',
          'propertyName' => 'tipo'
        ]);
        $this->hasOne('SedeLegale',[
          'className' => 'Aziende.Contatti',
          'foreignKey' => 'id_azienda',
          'bindingKey' => 'id',
          'conditions' => ['SedeLegale.id_ruolo' => 18],
          'propertyName' => 'sede_legale'
        ]);
    }

    public function saveAzienda($data, $user)
    {
        if(isset($data['id'])){
          $entity = $this->get($data['id']/*,['contain' => 'Gruppi']*/);
        }else{
            $entity = $this->newEntity();
        }
        /*
        $sameGruppi = true;
        $gruppi = array();
        if(count($data['gruppi']) !== count($entity['gruppi'])){
            $sameGruppi = false;
        }
        if(!empty($data['gruppi'])){
          // Sistema che controlla nel caso che i gruppi siano cambiati
          foreach ($data['gruppi'] as $gruppo) {
              $gruppi[] = array('id' => $gruppo);
              if($sameGruppi){
                $found = false;
                foreach ($entity['gruppi'] as $gruppoEntity) {
                    if($gruppoEntity->id == $gruppo){
                      $found = true;
                    }
                }
                if(!$found){
                  $sameGruppi = false;
                }
              }
          }
          // se i gruppi non variano le unsetto, mi serve per evitare che mi consideri l'entity dirty
          // ovvero variato e lo salvi, andando a fare operazioni inutili sul DB.
          if($sameGruppi){
            unset($data['gruppi']);
          }else{
            $data['gruppi'] = $gruppi;
          }

        }
        // unsetto anche la variabile gruppi se uguali che me rende l'entity dirty, ovvero variata.
        if($sameGruppi){
            unset($data['gruppi']);
        }
        */

        if (isset($data['pa_codice'])) {
          $data['pa_codice'] = strtoupper($data['pa_codice']);
        }

        $entity = $this->patchEntity($entity, $data);

        if ($entity->isDirty('id_tipo')) {
          if ($user['role'] !== 'admin' && $user['role'] !== 'area_iv') {
            $entity->id_tipo = $entity->getOriginal('id_tipo');
            $entity->clean('id_tipo');
          }
        }
        //$entity->cleanDirty(['created','modified']);
        if($entity->isDirty() || $entity->isNew()){
            return $this->save($entity);
        }
        return $entity;
    }

    public function getAziendeRecipient($offices,$partners){

        $where = [];
        $whereOffice = [];
        $wherePartner = [];
  
        if(is_array($offices)){
          foreach ($offices as $key => $value) {
            $whereOffice['OR'][] = [
              'o1.office_id' => $value
            ];
          }
        }
  
        if(!empty($whereOffice)){
          $where[] = $whereOffice;
        }
  
        if(is_array($partners)){
          foreach ($partners as $key => $value) {
            $wherePartner['OR'][] = [
              'Aziende.userPartner_id' => $value
            ];
          }
        }
  
        if(!empty($wherePartner)){
          $where[] = $wherePartner;
        }
  
  
        $ret = $this->find()
          ->select(['Aziende.id','Aziende.denominazione','Aziende.email_info','Aziende.email_contabilita','Aziende.email_solleciti'])
          ->where($where)
          ->order('Aziende.denominazione')
          ->toArray();
  
        return $ret;
  
      }

      public function getNodoLogo($userId = null) 
      {
          if ($userId) {
              $azienda = $this->find()
                  ->where(['c.id_user' => $userId])
                  ->join([
                    [
                      'table' => 'contatti',
                      'alias' => 'c',
                      'type' => 'left',
                      'conditions' => 'c.id_azienda = Aziende.id'
                    ]
                  ])
                  ->first();
  
              return empty($azienda) ? '' : $azienda->logo;
          }
  
          return false;
      }

      public function searchAziende($search)
      {
          return $this->find()
              ->select([
                'Aziende.id',
                'label' => 'Aziende.denominazione'
              ])
              ->where([
                'Aziende.denominazione LIKE' => '%'.$search.'%'
              ])
              ->order('Aziende.denominazione ASC')
              ->toArray();
      }

      public function getAziendaBySede($sedeId)
      {
          return $this->find()
              ->select([
                'Aziende.id',
                'label' => 'Aziende.denominazione'
              ])
              ->where([
                's.id' => $sedeId
              ])
              ->join([
                [
                  'table' => 'sedi',
                  'alias' => 's',
                  'type' => 'INNER',
                  'conditions' => 's.id_azienda = Aziende.id'
                ]
              ])
              ->first();
      }

      public function getAziendaByUser($userId)
      {
        return $this->find()
              ->where([
                'u.id' => $userId
              ])
              ->join([
                [
                  'table' => 'contatti',
                  'alias' => 'c',
                  'type' => 'INNER',
                  'conditions' => 'c.id_azienda = Aziende.id'
                ],
                [
                  'table' => 'users',
                  'alias' => 'u',
                  'type' => 'INNER',
                  'conditions' => 'u.id = c.id_user'
                ]
              ])
              ->first();
      }

}
