<?php
namespace Aziende\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;

class ContattiTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->setTable('contatti');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');
        $this->setEntityClass('Aziende.Contatto');
        $this->belongsTo('Aziende.ContattiRuoli',['foreignKey' => 'id_ruolo', 'propertyName' => 'ruolo']);
        $this->belongsTo('Aziende.Sedi',['foreignKey' => 'id_sede', 'propertyName' => 'sede']);
        $this->belongsTo('Aziende.Aziende',['foreignKey' => 'id_azienda', 'propertyName' => 'azienda']);
        $this->belongsTo('Users', ['foreignKey' => 'id_user', 'propertyName' => 'users']);
        $this->hasMany('Aziende.SkillsContacts',['foreignKey' => 'id_contatto', 'propertyName' => 'skills']);
        $this->belongsToMany('Skills', [
            'foreignKey' => 'id_contatto',
            'targetForeignKey' => 'id_skill',
            'through' => 'Aziende.SkillsContacts',
            'className' => 'Aziende.Skills',
            'propertyName' => 'Skills'
        ]);
        $this->belongsTo('Users',['foreignKey' => 'id_user', 'propertyName' => 'user']);
        $this->hasOne('SkillsGroup', [
            'foreignKey' => 'id_contatto',
            'className' => 'Aziende.SkillsContacts',
            'propertyName' => 'skills'
        ]);
    }

    /**
     * Salva il contatto solo se ci sono state delle modifiche o è nuovo.
     * Il controllo sulla variazione delle skill è più complesso, essendo una relazione
     * molti a molti, e cake in automatico non riesce a trovare le variazioni.
     *
     * @param  array $data array dei dati
     * @return bool risultato dell'operazione
     */
    public function saveContatto($data)
    {
        if(!empty($data['id']) && is_int($data['id'])){
            $entity = $this->get($data['id'],['contain' => 'Skills']);
        }else{
            $entity = $this->newEntity();

            $lastContatto = $this->find()
                ->where(['id_azienda' => $data['id_azienda'], 'deleted' => '0'])
                ->order(['ordering DESC'])
                ->first();
            
            if($lastContatto){
                $entity->ordering = $lastContatto->ordering + 1;
            }
        }
        $sameSkills = true;
        $countSkills = empty($entity['Skills']) ? 0 : count($entity['Skills']);
        if(count($data['skills']) !== $countSkills){
            $sameSkills = false;
        }
        if(!empty($data['skills'])){
          // Sistema che controlla nel caso che le skill siano cambiate
          foreach ($data['skills'] as $skill) {
              $data['Skills'][] = array('id' => $skill);
              if($sameSkills){
                $found = false;
                foreach ($entity['Skills'] as $skillEntity) {
                    if($skillEntity->id == $skill){
                      $found = true;
                    }
                }
                if(!$found){
                  $sameSkills = false;
                }
              }
          }
          // se le skill non variano le unsetto, mi serve per evitare che mi consideri l'entity dirty
          // ovvero variato e lo salvi, andando a fare operazioni inutili sul DB.
          if($sameSkills){
            unset($data['Skills']);
          }
        }else{
            if(!$sameSkills){
                $data['Skills'] = array();
            }
        }
        // unsetto anche la variabile skills che me rende l'entity dirty, ovvero variata.
        unset($data['skills']);
        unset($data['user']);
        $entity = $this->patchEntity($entity, $data);
        // pulisco la dirty dal created e modified che variano, e controllo se ci sono
        // variazione dell'entity, nel caso salvo
        $entity->cleanDirty(['created','modified']);
        if($entity->isDirty()){
            //debug($entity);die;
            return $this->save($entity);
        }
        return $entity;
    }

    public function listInternal()
    {
        return $this->find('all')->contain(['Aziende','Users'])
          ->order(['Contatti.cognome' => 'ASC'])->where(['Aziende.interno'=>1,'Contatti.deleted' => 0])->toArray();
    }

    public function getProvinciaContattoByUser($userId = null) 
    {
        $provincia = null;

        if($userId) {
            $res = $this->find()
                ->select(['provincia' => 'l.des_luo'])
                ->where(['id_user' => $userId])
                ->join([
                    [
                        'table' => 'luoghi',
                        'alias' => 'l',
                        'type' => 'LEFT',
                        'conditions' => 'l.c_luo = Contatti.provincia'
                    ]
                ])
                ->first();

            $provincia = empty($res) ? null : $res['provincia'];
        }

        return $provincia;
    }

    public function getContattoByUser($userId = null) 
    {
        $contatto = null;

        if($userId) {
            $contatto = $this->find()
                ->where(['id_user' => $userId])
                ->first();
        }

        return $contatto;
    }

    public function isValidEnte($userId = null) 
    {
        if ($userId) {
            $contatto = $this->find()
                ->where(['id_user' => $userId, 'id_azienda != 0'])
                ->toArray();

            return (bool) $contatto;
        }

        return false;
    }

}
