<?php
namespace Crediti\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;
use Crediti\Model\Entity\CreditsTotal;
use Cake\ORM\TableRegistry;

/**
 * CreditsTotals Model
 *
 * @property \Cake\ORM\Association\BelongsTo $Aziendas
 */
class CreditsTotalsTable extends AppTable
{

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        $this->table('credits_totals');
        $this->displayField('id');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        $this->belongsTo('Crediti.Aziende', [
            'foreignKey' => 'azienda_id',
            'joinType' => 'INNER',
            'propertyName' => 'Aziende'
        ]);
        $this->hasMany('Crediti.Notifiche', [
            'foreignKey' => 'credits_totals_id',
            'joinType' => 'INNER',
            'propertyName' => 'Notifiche'
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
            ->add('total', 'valid', ['rule' => 'decimal'])
            ->requirePresence('total', 'create')
            ->notEmpty('total');

        $validator
            ->add('total_scaduti', 'valid', ['rule' => 'decimal'])
            ->requirePresence('total_scaduti', 'create')
            ->notEmpty('total_scaduti');

        $validator
            ->add('data_conto', 'valid', ['rule' => 'date'])
            ->requirePresence('data_conto', 'create')
            ->notEmpty('data_conto');

        $validator
            ->requirePresence('rating', 'create')
            ->notEmpty('rating');

        $validator
            ->add('num_importazione', 'valid', ['rule' => 'numeric'])
            ->requirePresence('num_importazione', 'create')
            ->notEmpty('num_importazione');

        $validator
            ->add('lavorato', 'valid', ['rule' => 'numeric'])
            ->allowEmpty('lavorato');

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
        $rules->add($rules->existsIn(['azienda_id'], 'Aziende'));
        return $rules;
    }*/
    public function saveFromCredits($data,$date,$num_importazione)
    {
      // Cerco il dato precedente con azienda_id uguale e current a 1, e imposto il current a 0
      $current = $this->find()->select(['total_scaduti','rating'])->where(['azienda_id' => $data['azienda_id'],'current' => 1])
      ->first();
      // Aggiorno tutti i campi dati in totalsCredits riferiti all'azienda mettendo current a 0
      $this->updateAll(['current'=>0],['azienda_id'=>$data['azienda_id']]);

      if($current != null && !empty($current)){
        $rating = $this->calcolaRating($data['crediti_scaduti'], $current['rating'], $current['total_scaduti']);
      }else{
        if($data['crediti_scaduti'] == 0)
          $rating = 'OK1';
        else
          $rating = 'NOTICE2';
      }
      /*if($update != null){
        $update->current = 0;
        $this->save($update);
      }*/

      $new = $this->newEntity();

      $new->azienda_id = $data['azienda_id'];
      $new->total = $data['crediti'];
      $new->total_scaduti = $data['crediti_scaduti'];
      $new->data_conto = $date;
      $new->rating = $rating;
      $new->num_importazione = $num_importazione;
      $new->current = 1;



      return $this->save($new);
    }

    public function retrieveCurrentCredits($pass=array(), $count=false, $xls=false)
    {

            $opz = array();
            $opt = array();

            $col[0] = "data_conto";
            $col[1] = "user_partner";
            $col[2] = "famiglia";
            $col[3] = "cod_sispac";
            $col[4] = "denominazione";
            $col[5] = "total";
            $col[6] = "total_scaduti";
            $col[7] = "rating";
            $col[8] = "lavorato";

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



            //controllo se $office è una lista di uffici e lo trasformo in un array
            /*if(preg_match("/,/",$office) || $office != -1)
            {
                $office = explode(",",$office);
                $opz['Orders.office_id IN'] = $office;
            }*/

            ######################################################################################################
            // gestione filtri




            // Filtri per campi
            if(isset($pass['filter']) && !empty($pass['filter']) && is_array($pass['filter'])){

                foreach ($pass['filter'] as $key => $value){

                    switch ($key) {
                      case '1':
                        $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                      break;
                      case '2':
                        $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                      break;
                      case '3':
                        $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                      break;
                      case '4':
                        $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                      break;
                      case '6':
                        $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                      break;
                      case '7';
                        $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                      break;
                      // Considero il fatto che nel db c'è un intero per il campo lavorato
                      case '8':
                        if(stripos('si',$value) !== false)
                          $opz[$col[$key]] = 1;
                        elseif (stripos('no',$value) !== false)
                          $opz[$col[$key]] = 0;
                      break;
                    }
                }
              }


            $opz['current']=1;
            // Costruisco subquery per trovare il user partner di riferimento
            $aziendeOrders = TableRegistry::get('Consulenza.Orders');
            $subquery = $aziendeOrders->find('all')->select(['partner'=>'CONCAT(UsersPartner.nome," ",UsersPartner.cognome)'])
            ->contain('UsersPartner')->where(['Orders.azienda_id = aziendaId'])->order(['Orders.year'=>'DESC'])->limit(1);
            // Costruisco select
            $select = ['data_conto'=>'CreditsTotals.data_conto','famiglia'=>'Aziende.famiglia','cod_sispac'=>'Aziende.cod_sispac',
                      'denominazione'=>'Aziende.denominazione','aziendaId'=>'Aziende.id','total'=>'CreditsTotals.total',
                      'total_scaduti'=>'CreditsTotals.total_scaduti','rating'=>'CreditsTotals.rating',
                      'lavorato'=>'CreditsTotals.lavorato','user_partner'=>$subquery,'current'=>'CreditsTotals.current'];

            if($count){

              return $this->find()->select($select)->contain('Aziende')->having($opz)->order($opt['order'])
              ->group('CreditsTotals.id')->count();

            }else{

              if($xls){

                return $this->find()->select($select)->contain('Aziende')->having($opz)->order($opt['order'])
                ->group('CreditsTotals.id')->toArray();


              }else{

                return $this->find()->select($select)->contain('Aziende')->having($opz)->order($opt['order'])
                ->group('CreditsTotals.id')->limit($opt['limit'])->page($opt['page'])->toArray();
              }
            }



    }

    public function getCreditsTotalsAziendaById($id)
    {
      return $this->find()->where(['azienda_id' => $id])->contain('Notifiche')->order(['data_conto'=>'ASC'])->toArray();
    }

    public function getCurrentCreditsTotalsAzienda($id)
    {
      return $this->find()->where(['azienda_id' => $id, 'current' => 1])->first();
    }

    public function calcolaRating($s,$r_db = '',$s_db = 0)
    {
      // $ds > 0 Aumentati i crediti scaduti || $ds < 0 Diminuiti i crediti scaduti || $ds == 0 invariati
      $ds = $s - $s_db;

      switch ($r_db) {

        case 'OK1':
          if($s == 0)
            $rating = 'OK1';
          elseif($s > 0)
            $rating = 'NOTICE2';
          break;
        case 'OK2':
          if($s == 0)
            $rating = 'OK1';
          elseif($s > 0)
            $rating = 'WARNING';
          break;
        case 'NOTICE1':
          if($s == 0)
            $rating = 'OK1';
          elseif($ds >= 0)
            $rating = 'NOTICE2';
          elseif($ds < 0)
            $rating = 'NOTICE1';
          break;
        case 'NOTICE2':
          if($s == 0)
            $rating = 'OK1';
          elseif($ds == 0)
            $rating = 'NOTICE3';
          elseif($ds < 0)
            $rating = 'NOTICE1';
          elseif($ds > 0)
            $rating = 'WARNING';
          break;
        case 'NOTICE3':
          if($s == 0)
            $rating = 'OK1';
          elseif($ds == 0)
            $rating = 'NOTICE3';
          elseif($ds < 0)
            $rating = 'NOTICE2';
          elseif($ds > 0)
            $rating = 'WARNING';
          break;
        case 'WARNING':
          if($s == 0)
            $rating = 'OK2';
          elseif($ds == 0)
            $rating = 'WARNING';
          elseif($ds < 0)
            $rating = 'NOTICE2';
          elseif($ds > 0)
            $rating = 'CRIT';
          break;
        case 'CRIT':
          if($s == 0)
            $rating = 'OK2';
          elseif($ds >= 0)
            $rating = 'CRIT';
          elseif($ds < 0)
            $rating = 'WARNING';
          break;
        default:
          $rating = 'NOTICE2';
          break;

      }

      return $rating;

    }

    public function calcolaIndicatore()
    {
      $res = $this->find()->select(['indicatore' =>
        '100*( SUM( IF(rating LIKE "NOTICE%" ,1,0) + IF(rating = "WARNING" ,3, 0) + IF(rating = "CRIT" ,6, 0) ) )/COUNT(*)/6 '])
        ->where(['current' => 1])->first()->toArray();

      return $res['indicatore'];
    }

    public function getNumImportazione($prefix = '')
    {
      $res = $this->find()->select(['prefix' => 'SUBSTRING(Aziende.cod_sispac , 1 ,3)','num_importazione'])->contain('Aziende')
        ->having(['prefix' => $prefix])->order(['num_importazione'=> 'DESC'])->first();

      return $res;
    }
}
