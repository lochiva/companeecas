<?php
namespace Consulenza\Controller;

use Consulenza\Controller\AppController;
use Cake\I18n\Time;

/**
 * Home Controller
 *
 * @property \Consulenza\Model\Table\HomeTable $Home */
class PianificazioneController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Consulenza.User');
        $this->loadComponent('Consulenza.Order');
        $this->loadComponent('Consulenza.Job');
        $this->loadComponent('Consulenza.Phase');
        $this->loadComponent('Consulenza.Office');
        $this->loadComponent('Consulenza.Azienda');
        $this->loadComponent('Consulenza.Task');
        //$this->loadComponent('Csrf');
    }
   
    public function view()
    {
       


    }
     public function edit($id = "", $order = "")
    {

        ##################################################################################################################
        if($order == ""){
            $order = date('Y');
        }

        ##################################################################################################################
        //Carico i i dati del cliente

        $client = $this->Azienda->_get($id);

        ##################################################################################################################
        //Genero gli anni

        $now = date('Y');
        $max = $now + 1;
        $min = $now - 3;

        for ($i=$min; $i <= $max ; $i++) { 
            $years[] = $i;
        }

        $years = array_reverse($years);

        ##################################################################################################################
        //Carico i soci di riferimento e gli utenti

        $partners = $this->User->getPartners();
        $users = $this->User->getUsers();

        ##################################################################################################################
        //Carico gli studi

        $offices = $this->Office->getOffices();

        ##################################################################################################################
        //Carico i dati di questo order, se ci sono

        $params['azienda_id'] = $id;
        $params['year'] = $order;

        $dataOrder = $this->Order->getDataOrder($params);
        if(isset( $dataOrder[0])){
            $dataOrder = $dataOrder[0];

            //L'array di job mi serve con l'id == alla chiave
            $listJobs = array();
            foreach ($dataOrder->jobs as $key => $job) {

                //$job->_joinData->totalTime = $job->_joinData->totalTime / 60 / 60;

                $job->_joinData->totalTime = $this->Job->getStringTime($job->_joinData->totalTime);

                $job->_joinData->toBeAssigned = $this->Job->getStringTime($this->Job->checkTimetoBeAssigned($job->_joinData->id));

                $job->_joinData->tasksPlanned = $this->Task->getTaskPlanned($job->_joinData->job_id, $job->_joinData->order_id);

                $job->_joinData->tasksProgrammed = $this->Task->getTaskProgrammed($job->_joinData->job_id, $job->_joinData->order_id);

                $job->_joinData->tasksManual = $this->Task->getTaskManual($job->_joinData->job_id, $job->_joinData->order_id);

                $listJobs[$job->id] = $job;
            }

            $dataOrder->jobs = $listJobs;
        }
        
        //echo"<pre>"; print_r($dataOrder); echo "</pre>"; exit;

        ##################################################################################################################
        //Se non ci sono order per questo anno verifico se ce ne sono di precedenti e ne genero una lista

        $oldDataOrder = array();

        if(empty($dataOrder)){

            $params['azienda_id'] = $id;
            $params['year'] = $order;
            $params['previus'] = true;

            $res = $this->Order->getDataOrder($params);

            //echo"<pre>"; print_r($res); echo "</pre>"; exit;

            foreach ($res as $key => $value) {
                $oldDataOrder[$value->id]['id'] = $value->id;
                $oldDataOrder[$value->id]['year'] = $value->year;
            }

            //echo"<pre>"; print_r($oldDataOrder); echo "</pre>"; exit;

        }

        ##################################################################################################################
        //carico gli attributi dei jobs di tipo TYPEOFBUSINESS

        $typeOfBusiness = $this->Job->getAttributeByKey('TYPEOFBUSINESS');

        ##################################################################################################################
        //Carico tutti i job

        $jobs = $this->Job->getJobs();

        ##################################################################################################################
        //recupero i dati dello user loggato

        $user = $this->request->session()->read('Auth.User');

        ##################################################################################################################

        //Passo le variabili al view
        $this->set('client' , $client);
        $this->set('partners' , $partners);
        $this->set('users' , $users);
        $this->set('offices' , $offices);
        $this->set('years' , $years);
        $this->set('order' , $order);
        $this->set('dataOrder' , $dataOrder);
        $this->set('oldDataOrder' , $oldDataOrder);
        $this->set('typeOfBusiness' , $typeOfBusiness);
        $this->set('jobs' , $jobs);
        $this->set('user' , $user);

    }

    public function saveDataOrder(){

        $this->autoRender = false;

        $post = $this->request->data;

        //echo "<pre>"; print_r($post); echo "</pre>"; exit;

        if(!empty($post)){

            if($post['order_id'] != ""){
                $post['id'] = $post['order_id'];
            }
            unset($post['order_id']);

            $jobsOrder = array();
            if(isset($post['jobs'])){
                $jobsOrder = $post['jobs'];
                unset($post['jobs']);
            }

            $post['dataConsegnaBilancino'] = Time::parseDate($post['dataConsegnaBilancino']);
            
            //echo "<pre>"; print_r($post); echo "</pre>"; exit;

            if(!empty($jobsOrder)){
                //Posso salvare

                //Comincio a salvare l'ordine, il suo id mi servirà per fare la delete e l'insert in jobs_orders
                $order = $this->Order->_newEntity();
        
                $order = $this->Order->_patchEntity($order, $post);
            
                //echo "<pre>"; print_r($event); echo "</pre>";
                $save = $this->Order->_save($order);

                //echo "<pre>"; print_r($save); echo "</pre>";

                if($post['isLocked'] != 1){
                    //Ora posso calncellare eventuali vecchie relazioni

                    $this->Job->deleteAllJobsOrderByOrderId($save->id);

                    //Creo l'array di dati corretti da salvare
                    $ckLocked = false;
                    foreach ($jobsOrder as $key => $jobs) {

                        //Faccio dei controlli di sicurezza
                        if(!isset($jobs['user_id'])){
                            $jobs['user_id'] = 0;
                        }
                        if(!isset($jobs['process_id'])){
                            $jobs['process_id'] = 0;
                        }
                        if(!isset($jobs['job_id'])){
                            $jobs['job_id'] = 0;
                        }
                        if(!isset($jobs['totalTime'])){
                            $jobs['totalTime'] = "000:00";
                        }

                        //Il total time mi arriva come stringa ma lo devo salvare in secondi....
                        list($h,$m) = explode(":",$jobs['totalTime']);
                        $jobs['totalTime'] = ($h * 60 * 60) + ($m * 60);

                        $toSave[] = array(
                                'order_id' => $save->id,
                                'user_id' => $jobs['user_id'],
                                'process_id' => $jobs['process_id'],
                                'phase_id' => "",
                                'job_id' => $jobs['job_id'],
                                'totalTime' => $jobs['totalTime'],
                                'note' => ""
                            );

                        if($jobs['user_id'] != 0 || $jobs['process_id'] != 0 || $jobs['totalTime'] != 0){
                            $ckLocked = true;
                        }

                    }

                    $this->Job->insertJobsOrder($toSave);

                    if($ckLocked){
                        $this->Order->lockOrderById($save->id);
                    }

                }

                $this->redirect('/consulenza/pianificazione/edit/' . $post['azienda_id']);

            }else{
                //Caso strano....non ho job....che faccio?!?!?

                echo "Errore nel salvataggio, non ho job da salvare!";

            }
           

        }else{
            echo "Errore nel salvataggio, non ho dati da salvare!";
        }

    }

}