<?php
/**
* Crediti is a plugin for manage attachment
*
* Companee :    Credits  (https://www.companee.it)
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
use Crediti\Model\Entity\Credit;
use Cake\ORM\TableRegistry;

/**
 * Credits Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Aziendas
 *
 * Estendo Table invece che AppTable per non fare il log delle query
 */
class CreditsTable extends Table
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->setTable('credits');
        $this->displayField('id');
        $this->setPrimaryKey('id');
        $this->addBehavior('Timestamp');

        $this->belongsTo('Crediti.Aziende', [
            'foreignKey' => 'azienda_id',
            'joinType' => 'INNER',
            'propertyName' => 'Aziende'
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
            ->requirePresence('cod_sispac', 'create')
            ->notEmpty('cod_sispac');

        $validator
            ->requirePresence('num_documento', 'create')
            ->notEmpty('num_documento');

        $validator
            ->add('data_emissione', 'valid', ['rule' => 'date'])
            ->requirePresence('data_emissione', 'create')
            ->notEmpty('data_emissione');

        $validator
            ->add('data_scadenza', 'valid', ['rule' => 'date'])
            ->requirePresence('data_scadenza', 'create')
            ->notEmpty('data_scadenza');

        $validator
            ->add('importo', 'valid', ['rule' => 'decimal'])
            ->requirePresence('importo', 'create')
            ->notEmpty('importo');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    /*public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['azienda_id'], 'Crediti.Aziende'));
        return $rules;
    }*/

    public function saveFromCsv($val)
    {

      $new = $this->newEntity();
      $new->cod_sispac = $val[0];
      $new->num_documento = $val[1];
      $new->data_emissione = $val[2];
      $new->importo = $val[3];
      $new->data_scadenza = $val[4];
      $new->azienda_id = $val[5];

      return $this->save($new);
    }

    public function retrieveCreditsGroupAzienda($date,$prefix)
    {
      // Eseguo il bind della data, per precauzione

      return $this->find()->select(['azienda_id'=>'azienda_id','cod_sispac'=>'cod_sispac',
      'crediti_scaduti'=>'SUM(importo * IF( data_scadenza < :data ,1, 0 ))',
      'crediti'=>'SUM(importo)'])->where(['cod_sispac LIKE'=> $prefix.'%'])->group('azienda_id')->bind(':data',$date,'date')->toArray();


    }
    public function retrieveCreditsGroupAziendaById($date='',$id='')
    {
      if(!empty($date) && !empty($id)){
        return $this->find()->select(['azienda_id'=>'azienda_id','cod_sispac'=>'cod_sispac',
        'crediti_scaduti'=>'SUM(importo * IF( data_scadenza < :data ,1, 0 ))',
        'crediti'=>'SUM(importo)'])->where(['azienda_id '=> $id ])->group('azienda_id')->bind(':data',$date,'date')->toArray();
      }
    }

    public function getCreditsAziendaById($id)
    {
      return $this->find()->where(['azienda_id' => $id])->order(['data_emissione'=>'ASC'])->toArray();
    }

    public function getCreditsAziendaSumPartner($id)
    {
      $aziendeOrders = TableRegistry::get('Consulenza.Orders');
      $today = date("Y-m-d");

      $subquery = $aziendeOrders->find('all')->select(['UsersPartner.id'])
      ->contain('UsersPartner')->where(['Orders.azienda_id'=> $id])->order(['Orders.year'=>'DESC'])->limit(1);

      return $this->find()->select(['somma' => 'SUM( IF(data_scadenza < :data ,importo,0) )','partnerId'=>$subquery])
      ->where(['azienda_id' => $id])->bind(':data',$today,'date')->first();
    }

    public function getCreditsSum()
    {
      return $this->find()->select(['somma' => 'SUM(importo)'])->first();
    }

    public function retrieveCredits($pass=array(), $count=false, $xls=false)
    {

      $opz = array();
      $opt = array();

      $col[0] = "Aziende.famiglia";
      $col[1] = "Aziende.cod_sispac";
      $col[2] = "Aziende.denominazione";
      $col[3] = "Credits.num_documento";
      $col[4] = "Credits.data_emissione";
      $col[5] = "Credits.data_scadenza";
      $col[6] = "Credits.importo";

      ######################################################################################################
      //Gestione paginazione

      if(isset($pass['size']) && isset($pass['page'])){
          $size = $pass['size'];
          $page = $pass['page'] + 1;
      }else{
          $size = 50;
          $page = 1;
      }

      if($size != "all"){
         $opt['limit'] = $size;
         $opt['page'] = $page;
      }

      ######################################################################################################
      //Gestione ordinamento


      $order = "";
      $separatore = "";

      if($size != "all"){

          $opt['order'] = "Aziende.cod_sispac ASC";

          if(isset($pass['column']) && !empty($pass['column']) && is_array($pass['column'])){

              foreach ($pass['column'] as $key => $value) {

                 // if($key==0) continue; // gestione particolare per denominazione

                  if(isset($col[$key])){

                      $order .= $separatore . $col[$key];
                      $separatore = ", ";

                      if($value == 1){
                          $order .= " DESC";
                      }else{
                          $order .= " ASC";
                      }

                  }
              }

              $opt['order'] = $order;


          }

      }

      // Filtri per campi
      if(isset($pass['filter']) && !empty($pass['filter']) && is_array($pass['filter'])){

          foreach ($pass['filter'] as $key => $value){

              switch ($key) {
                case '0':
                  $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                break;
                case '1':
                  $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                break;
                case '2':
                  $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                break;
                case '3':
                  $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                break;
              }
          }
      }






      if($count){

        return $this->find()->contain('Aziende')->where($opz)->order($opt['order'])
        ->limit($opt['limit'])->page($opt['page'])->count();

      }else{

        if($xls){

          return $this->find()->contain('Aziende')->where($opz)->order($opt['order'])->toArray();


        }else{

          return $this->find()->contain('Aziende')->where($opz)->order($opt['order'])
          ->limit($opt['limit'])->page($opt['page'])->toArray();
        }
      }

    }
}
