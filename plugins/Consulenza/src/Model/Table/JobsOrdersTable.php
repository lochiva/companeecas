<?php
namespace Consulenza\Model\Table;

use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\ORM\Rule\IsUnique;
use Cake\Validation\Validator;
use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;

class JobsOrdersTable extends AppTable
{

    public function initialize(array $config)
    {
        $this->table('jobs_orders');
        $this->primaryKey('id');
        $this->addBehavior('Timestamp');
        //$this->entityClass('Consulenza.Typeofbusiness');
        /*
        $this->belongsToMany('Consulenza.Jobs', [
            'joinTable' => 'jobs_jobsattributes',
        ]);
        */
        $this->belongsTo('Consulenza.Orders',['foreignKey' => 'order_id', 'propertyName' => 'order']);
        $this->belongsTo('Consulenza.Jobs',['foreignKey' => 'job_id', 'propertyName' => 'job']);
        $this->belongsTo('Consulenza.Phases',['foreignKey' => 'phase_id', 'propertyName' => 'phase']);
        $this->belongsTo('Consulenza.Users',['foreignKey' => 'user_id', 'propertyName' => 'user']);
        $this->belongsTo('Consulenza.Processes',['foreignKey' => 'process_id', 'propertyName' => 'process']);
        $this->belongsTo('Consulenza.Jobsattributes',['foreignKey' => 'job_id', 'propertyName' => 'jobsAttribute']);
        //$this->belongsTo('Document.Projects',['foreignKey' => 'id_project']);

		$this->belongsTo('Consulenza.Phases',[
			'foreignKey' => 'phase_id',
			'propertyName' => 'phase'
		]);

        // metto jobs_orders in join con se stessa in base a order_id
        $this->belongsTo('Consulenza.JobsOrdersContabilita',[
            'foreignKey' => false,
            'joinType' => 'LEFT',
            'propertyName' => 'JobsOrdersContabilita',
            'conditions' => array(
                'JobsOrders.order_id = JobsOrdersContabilita.order_id AND JobsOrdersContabilita.user_id > 0 AND JobsOrdersContabilita.job_id in(select job_id from jobs_jobsattributes where jobsattribute_id = 7)'
            )
        ]);

        // metto jobs_orders in join con se stessa in base a order_id per avere lo stato del bilancio
        $this->belongsTo('Consulenza.JobsOrdersBilancio',[
            'foreignKey' => false,
            'joinType' => 'LEFT',
            'propertyName' => 'JobsOrdersBilancio',
            'conditions' => array(
                'JobsOrders.order_id = JobsOrdersBilancio.order_id AND JobsOrdersBilancio.user_id > 0 AND JobsOrdersBilancio.job_id in(select job_id from jobs_jobsattributes where jobsattribute_id = 11)'
            )
        ]);

        // metto jobs_orders in join con se stessa in base a order_id per avere lo stato dell'irap ENC
        $this->belongsTo('Consulenza.JobsOrdersIrapEnc',[
            'foreignKey' => false,
            'joinType' => 'LEFT',
            'propertyName' => 'JobsOrdersIrapEnc',
            'conditions' => array(
                'JobsOrders.order_id = JobsOrdersIrapEnc.order_id AND JobsOrdersIrapEnc.user_id > 0 AND JobsOrdersIrapEnc.job_id in(select job_id from jobs_jobsattributes where jobsattribute_id = 14)'
            )
        ]);

    }

    public function buildRules(RulesChecker $rules){

        $rules->add($rules->isUnique(['order_id','job_id']));
        return $rules;

    }

    public function retrieveReportDichiarativi($causaleId, $year, $office,$pass=array(),$count=false,$xls=false) {


        $opz = array();
        $opt = array();

        $col[0] = "denominazione";
        $col[1] = "UsersPartner.cognome";
        $col[2] = "Users.cognome";
        $col[3] = "milestone";
        $col[5] = "JobsOrders.notes";

        ######################################################################################################
        //Gestione paginazione

        if(isset($pass['size']) && isset($pass['page'])){
            $size = $pass['size'];
            $page = $pass['page'] + 1;
        }else{
            $size = 50;
            $page = 1;
        }

        //echo $size . "|" . $page;


        if($size != "all"){
           $opt['limit'] = $size;
           $opt['page'] = $page;
        }



        ######################################################################################################

        ######################################################################################################
        //Gestione ordinamento

        //echo "<pre>"; print_r($pass['query']); echo "</pre>";

        $order = "";
        $separatore = "";

        if($size != "all"){

            $opt['order'] = "Aziende.denominazione ASC";

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


        ######################################################################################################
        // gestione filtri
        $opz['job_id'] = $causaleId;
        $opz['user_id >'] = 0;
        $opz['year'] = $year;

        //controllo se $office è una lista di uffici e lo trasformo in un array
        if(preg_match("/,/",$office) || $office != -1)
        {
            $office = explode(",",$office);
            $opz['Orders.office_id IN'] = $office;
        }

        // storm task #5117
        // nel report dichiarativi se si seleziona la causale "Dichiarazioni IVA" (id 13) mostrare i clienti che hanno il campo "IVA autonoma si"
        if($causaleId=='13')
         $opz['Orders.isIVAAutonoma'] = '1';

            if(isset($pass['filter']) && !empty($pass['filter']) && is_array($pass['filter'])){

                foreach ($pass['filter'] as $key => $value) {

                    switch ($key) {

                        case '1':
                            //$opz['OR']['UsersPartner.cognome LIKE'] = "%" . $value . "%";
                            //$opz['OR']['UsersPartner.nome LIKE'] = "%" . $value . "%";
                            $opz['CONCAT(UsersPartner.cognome,SPACE(1),UsersPartner.nome) LIKE']  = "%" . $value . "%";
                        break;

                        case '0':
                            $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                        break;
                        case '2':
                            //$opz['OR']['Users.cognome LIKE'] = "%" . $value . "%";
                            //$opz['OR']['Users.nome LIKE'] = "%" . $value . "%";
                            $opz['CONCAT(Users.cognome,SPACE(1),Users.nome) LIKE']  = "%" . $value . "%";
                        break;
                        case '3':
                          $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                        break;
                        case '5':
                          $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                        break;

                        default:

                        break;
                    }


                }
            }

        if($count){ // restuisce il count per i totali per la paginazione
            return $this->find()
                ->contain(['Orders'=>['Aziende','UsersPartner'], 'Jobs', 'Phases', 'Users'])
                ->where($opz)->order($opt['order'])->count()
                ;
        } else {
        // storm se voglio un xls a che serve la paginazione?

        if(!$xls){
            return $this->find('all')
                ->contain(['Orders'=>['Aziende','UsersPartner'], 'Jobs', 'Phases', 'Users'])
                ->where($opz)->order($opt['order'])->limit($opt['limit'])->page($opt['page'])->toArray()
                ;
            } else {
            return $this->find('all')
                ->contain(['Orders'=>['Aziende','UsersPartner'], 'Jobs', 'Phases', 'Users'])
                ->where($opz)->order($opt['order'])->toArray()
                ;

            }
        }
    }

    public function retrieveReportDichiarativiUNICO($causaleId, $year, $office,$pass=array(),$count=false,$xls=false) {


        $opz = array();
        $opt = array();

        $col[0] = "denominazione";
        $col[1] = "UsersPartner.cognome";
        $col[2] = "Orders.dataConsegnaBilancino";
        $col[3] = "Orders.hasPIVA";
        $col[4] = "UsersContabilita.cognome";
        $col[5] = "Users.cognome";
        $col[6] = "PhasesContabilita.milestone";
        $col[7] = "Phases.milestone";
        $col[10] = "JobsOrders.notes";

        ######################################################################################################
        //Gestione paginazione

        if(isset($pass['size']) && isset($pass['page'])){
            $size = $pass['size'];
            $page = $pass['page'] + 1;
        }else{
            $size = 50;
            $page = 1;
        }

        //echo $size . "|" . $page;


        if($size != "all"){
           $opt['limit'] = $size;
           $opt['page'] = $page;
        }



        ######################################################################################################

        ######################################################################################################
        //Gestione ordinamento

        //echo "<pre>"; print_r($pass['query']); echo "</pre>";

        $order = "";
        $separatore = "";

        if($size != "all"){

            $opt['order'] = "Aziende.denominazione ASC";

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

        ######################################################################################################
        // gestione filtri
        $opz['JobsOrders.job_id'] = $causaleId;
        $opz['JobsOrders.user_id >'] = 0;
        $opz['year'] = $year;

        //controllo se $office è una lista di uffici e lo trasformo in un array
        if(preg_match("/,/",$office) || $office != -1)
        {
            $office = explode(",",$office);
            $opz['Orders.office_id IN'] = $office;
        }

            if(isset($pass['filter']) && !empty($pass['filter']) && is_array($pass['filter'])){

                foreach ($pass['filter'] as $key => $value) {

                    switch ($key) {

                        case '0':
                          $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                        break;
                        case '5':
                          //$opz['OR']['Users.cognome LIKE'] = "%" . $value . "%";
                          //$opz['OR']['Users.nome LIKE'] = "%" . $value . "%";
                          $opz['CONCAT(Users.cognome,SPACE(1),Users.nome) LIKE']  = "%" . $value . "%";
                        break;
                        case '4':
                          //$opz['OR']['UsersContabilita.cognome LIKE'] = "%" . $value . "%";
                          //$opz['OR']['UsersContabilita.nome LIKE'] = "%" . $value . "%";
                          $opz['CONCAT(UsersContabilita.cognome,SPACE(1),UsersContabilita.nome) LIKE']  = "%" . $value . "%";
                        break;
                        case '6':
                            $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                        break;
                        case '1':
                          $opz['CONCAT(UsersPartner.cognome,SPACE(1),UsersPartner.nome) LIKE']  = "%" . $value . "%";
                          //$opz['OR']['UsersPartner.cognome LIKE'] = "%" . $value . "%";
                          //$opz['OR']['UsersPartner.nome LIKE'] = "%" . $value . "%";
                         break;

                        case '2':
                            if(isset($value)){
                                $new_value = array();
                                $new_value = explode('/', $value);
                                if(isset($new_value[2])){
                                    $value = $new_value[2].'%-%'.$new_value[1].'%-%'.$new_value[0];
                                } else if(isset($new_value[1])) {
                                    $value = $new_value[1].'%-%'.$new_value[0];
                                } else {
                                    $value = $new_value[0];
                                }

                               $opz['OR']['Orders.dataConsegnaBilancino LIKE'] = "%" . $value . "%";
                            }
                         break;

                        case '3':
                          if(strtolower($value)=='s' || strtolower($value)=='si') {
                              $value = '1';
                          } else if(strtolower($value)=='n' || strtolower($value)=='no') {
                              $value = '0';
                          }
                          $opz['OR']['Orders.hasPIVA LIKE'] = "%" . $value . "%";
                        break;
                        case '10':
                          $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                        break;

                        default:

                        break;
                    }


                }
            }

        // lista dei model per le join
        $contain_opt = ['Orders'=>['Aziende','UsersPartner'], 'Jobs', 'Phases', 'Users','JobsOrdersContabilita' => ['PhasesContabilita','UsersContabilita']];

        if($count){ // restuisce il count per i totali per la paginazione
            return $this->find('all')
                ->contain($contain_opt)
                ->where($opz)->order($opt['order'])->count()
                ;
        } else {
        // storm se voglio un xls a che serve la paginazione?

        if(!$xls){
            return $this->find('all')
                ->contain($contain_opt)
                ->where($opz)->order($opt['order'])->limit($opt['limit'])->page($opt['page'])->toArray()
                ;

            } else {
            return $this->find('all')
                ->contain($contain_opt)
                ->where($opz)->order($opt['order'])->toArray()
                ;

            }
        }
    }

    public function retrieveReportDichiarativiUNICOSC($causaleId, $year, $office,$pass=array(),$count=false,$xls=false) {


        $opz = array();
        $opt = array();

        $col[0] = "denominazione";
        $col[1] = "UsersPartner.cognome";
        $col[2] = "Orders.dataConsegnaBilancino";
        $col[3] = "Orders.hasPIVA";
        $col[4] = "UsersContabilita.cognome";
        $col[5] = "Users.cognome";
        $col[6] = "PhasesBilancio.milestone";
        $col[7] = "PhasesContabilita.milestone";
        $col[8] = "Phases.milestone";
        $col[12] = "JobsOrders.notes";

        ######################################################################################################
        //Gestione paginazione

        if(isset($pass['size']) && isset($pass['page'])){
            $size = $pass['size'];
            $page = $pass['page'] + 1;
        }else{
            $size = 50;
            $page = 1;
        }

        //echo $size . "|" . $page;


        if($size != "all"){
           $opt['limit'] = $size;
           $opt['page'] = $page;
        }



        ######################################################################################################

        ######################################################################################################
        //Gestione ordinamento

        //echo "<pre>"; print_r($pass['query']); echo "</pre>";

        $order = "";
        $separatore = "";

        if($size != "all"){

            $opt['order'] = "Aziende.denominazione ASC";

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

        ######################################################################################################
        // gestione filtri
        $opz['JobsOrders.job_id'] = $causaleId;
        $opz['JobsOrders.user_id >'] = 0;
        $opz['year'] = $year;

        //controllo se $office è una lista di uffici e lo trasformo in un array
        if(preg_match("/,/",$office) || $office != -1)
        {
            $office = explode(",",$office);
            $opz['Orders.office_id IN'] = $office;
        }

            if(isset($pass['filter']) && !empty($pass['filter']) && is_array($pass['filter'])){

                foreach ($pass['filter'] as $key => $value) {

                    switch ($key) {

                        case '0':
                          $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                        break;
                        case '4':
                          //$opz['OR']['UsersContabilita.cognome LIKE'] = "%" . $value . "%";
                          //$opz['OR']['UsersContabilita.nome LIKE'] = "%" . $value . "%";
                          $opz['CONCAT(UsersContabilita.cognome,SPACE(1),UsersContabilita.nome) LIKE']  = "%" . $value . "%";
                        break;
                        case '5':
                          //$opz['OR']['Users.cognome LIKE'] = "%" . $value . "%";
                          //$opz['OR']['Users.nome LIKE'] = "%" . $value . "%";
                          $opz['CONCAT(Users.cognome,SPACE(1),Users.nome) LIKE']  = "%" . $value . "%";
                          break;
                        case '6':
                        case '7':

                        break;
                        case '8':
                          $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                        break;
                        case '1':
                          //$opz['OR']['UsersPartner.cognome LIKE'] = "%" . $value . "%";
                          //$opz['OR']['UsersPartner.nome LIKE'] = "%" . $value . "%";
                          $opz['CONCAT(UsersPartner.cognome,SPACE(1),UsersPartner.nome) LIKE']  = "%" . $value . "%";
                         break;

                        case '2':
                            if(isset($value)){
                                $new_value = array();
                                $new_value = explode('/', $value);
                                if(isset($new_value[2])){
                                    $value = $new_value[2].'%-%'.$new_value[1].'%-%'.$new_value[0];
                                } else if(isset($new_value[1])) {
                                    $value = $new_value[1].'%-%'.$new_value[0];
                                } else {
                                    $value = $new_value[0];
                                }

                               $opz['OR']['Orders.dataConsegnaBilancino LIKE'] = "%" . $value . "%";
                            }
                         break;
                        case '3':
                            if(strtolower($value)=='s' || strtolower($value)=='si') {
                                $value = '1';
                            } else if(strtolower($value)=='n' || strtolower($value)=='no') {
                                $value = '0';
                            }
                            $opz['OR']['Orders.hasPIVA LIKE'] = "%" . $value . "%";
                         break;
                        case '12':
                           $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                        break;



                        default:

                        break;
                    }


                }
            }

        // lista dei model per le join
        $contain_opt = ['Orders'=>['Aziende','UsersPartner'], 'Jobs', 'Phases', 'Users','JobsOrdersContabilita' => ['PhasesContabilita','UsersContabilita'],'JobsOrdersBilancio' => ['PhasesBilancio']];


        if($count){ // restuisce il count per i totali per la paginazione
            return $this->find('all')
                ->contain($contain_opt)
                ->where($opz)->order($opt['order'])->count()
                ;
        } else {
        // storm se voglio un xls a che serve la paginazione?

        if(!$xls){
            return $this->find('all')
                ->contain($contain_opt)
                ->where($opz)->order($opt['order'])->limit($opt['limit'])->page($opt['page'])->toArray()
                ;

            } else {
            return $this->find('all')
                ->contain($contain_opt)
                ->where($opz)->order($opt['order'])->toArray()
                ;

            }
        }
    }

    public function retrieveReportDichiarativiUNICOENC($causaleId, $year, $office,$pass=array(),$count=false,$xls=false)
    {
        $opz = array();
        $opt = array();

        $col[0] = "denominazione";
        $col[1] = "UsersPartner.cognome";
        $col[2] = "Orders.dataConsegnaBilancino";
        $col[3] = "Orders.hasPIVA";
        $col[4] = "UsersContabilita.cognome";
        $col[5] = "Users.cognome";
        $col[6] = "PhasesContabilita.milestone";
        $col[7] = "Phases.milestone";
        $col[8] = "PhasesIrapEnc.milestone";
        $col[11] = "JobsOrders.notes";

        ######################################################################################################
        //Gestione paginazione

        if(isset($pass['size']) && isset($pass['page'])){
            $size = $pass['size'];
            $page = $pass['page'] + 1;
        }else{
            $size = 50;
            $page = 1;
        }

        //echo $size . "|" . $page;


        if($size != "all"){
           $opt['limit'] = $size;
           $opt['page'] = $page;
        }



        ######################################################################################################

        ######################################################################################################
        //Gestione ordinamento

        //echo "<pre>"; print_r($pass['query']); echo "</pre>";

        $order = "";
        $separatore = "";

        if($size != "all"){

            $opt['order'] = "Aziende.denominazione ASC";

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

        ######################################################################################################
        // gestione filtri
        $opz['JobsOrders.job_id'] = $causaleId;
        $opz['JobsOrders.user_id >'] = 0;
        $opz['OR']['JobsOrdersIrapEnc.user_id >'] = 0;
        $opz['year'] = $year;

        //controllo se $office è una lista di uffici e lo trasformo in un array
        if(preg_match("/,/",$office) || $office != -1)
        {
            $office = explode(",",$office);
            $opz['Orders.office_id IN'] = $office;
        }

            if(isset($pass['filter']) && !empty($pass['filter']) && is_array($pass['filter'])){

                foreach ($pass['filter'] as $key => $value) {

                    switch ($key) {

                        case '0':
                            $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                          break;
                        case '4':
                          //$opz['OR']['UsersContabilita.cognome LIKE'] = "%" . $value . "%";
                          //$opz['OR']['UsersContabilita.nome LIKE'] = "%" . $value . "%";
                          $opz['CONCAT(UsersContabilita.cognome,SPACE(1),UsersContabilita.nome) LIKE']  = "%" . $value . "%";
                        break;
                        case '5':
                          //$opz['OR']['Users.cognome LIKE'] = "%" . $value . "%";
                          //$opz['OR']['Users.nome LIKE'] = "%" . $value . "%";
                          $opz['CONCAT(Users.cognome,SPACE(1),Users.nome) LIKE']  = "%" . $value . "%";
                        break;
                        case '6':
                        break;
                        case '8':
                            $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                        break;
                        case '1':
                          //$opz['OR']['UsersPartner.cognome LIKE'] = "%" . $value . "%";
                          //$opz['OR']['UsersPartner.nome LIKE'] = "%" . $value . "%";
                          $opz['CONCAT(UsersPartner.cognome,SPACE(1),UsersPartner.nome) LIKE']  = "%" . $value . "%";
                        break;
                        case '2':
                          if(isset($value)){
                              $new_value = array();
                              $new_value = explode('/', $value);
                              if(isset($new_value[2])){
                                  $value = $new_value[2].'%-%'.$new_value[1].'%-%'.$new_value[0];
                              } else if(isset($new_value[1])) {
                                  $value = $new_value[1].'%-%'.$new_value[0];
                              } else {
                                  $value = $new_value[0];
                              }

                             $opz['OR']['Orders.dataConsegnaBilancino LIKE'] = "%" . $value . "%";
                          }
                        break;
                        case '3':
                          if(strtolower($value)=='s' || strtolower($value)=='si') {
                              $value = '1';
                          } else if(strtolower($value)=='n' || strtolower($value)=='no') {
                              $value = '0';
                          }
                          $opz['OR']['Orders.hasPIVA LIKE'] = "%" . $value . "%";
                        break;
                        case '11':
                          $opz[$col[$key] . ' LIKE'] = "%" . $value . "%";
                        break;
                        default:

                        break;
                    }


                }
            }

        // lista dei model per le join
        $contain_opt = ['Orders'=>['Aziende','UsersPartner'], 'Jobs', 'Phases', 'Users','JobsOrdersContabilita' => ['PhasesContabilita','UsersContabilita'],'JobsOrdersIrapEnc' => ['PhasesIrapEnc']];
//echo "<pre>";print_r($opz);die;

        if($count){ // restuisce il count per i totali per la paginazione
            return $this->find('all')
                ->contain($contain_opt)
                ->where($opz)->order($opt['order'])->count()
                ;
        } else {
        // storm se voglio un xls a che serve la paginazione?

        if(!$xls){
            return $this->find('all')
                ->contain($contain_opt)
                ->where($opz)->order($opt['order'])->limit($opt['limit'])->page($opt['page'])->toArray()
                ;

            } else {
            return $this->find('all')
                ->contain($contain_opt)
                ->where($opz)->order($opt['order'])->toArray()
                ;

            }
        }
    }

	public function getCount($jobList, $year, $user) {
		$this->jobList=$jobList;

		// get only jobIds
		$this->jobIds = array_map(function($job) {
			return $job['id'];
		}, $this->jobList);

		$query = $this->find('all');
		$query->select([
			'job_id',
			'count'=>$query->func()->count('*')

		])
		->contain(['Orders', 'Jobs'])
		->where([
			'Orders.year' => $year,
			'job_id IN' => $this->jobIds,
			'user_id' => $user['id']
		])
		->group('job_id');
		return $query;
	}

	public function getRigheContabili($jobList, $year, $user) {
		$this->jobList=$jobList;

		// get only jobIds
		$this->jobIds = array_map(function($job) {
			return $job['id'];
		}, $this->jobList);

		$query = $this->find('all');
		$query->select([
		'user_id',
		'tot'=>$query->func()->sum('righeContabili')
		])
		->contain(['Orders', 'Jobs'])
		->where([
			'Orders.year' => $year,
			'job_id IN' => $this->jobIds,
			'user_id' => $user['id']
		]);
//		->group('job_id');
	//	debug($query);exit;
		return $query;
	}

}
