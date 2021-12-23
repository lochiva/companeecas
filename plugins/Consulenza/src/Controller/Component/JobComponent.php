<?php
namespace Consulenza\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\I18n\Time;
use Cake\Utility\Text;


class JobComponent extends Component
{

    // The other component your component uses
    public $components = array('Order','Consulenza.Office','Consulenza.JobsAttributes');

    public function autocomplete($term){

        $out = array();

        if($term != ""){

            $jobs = TableRegistry::get('Consulenza.Jobs');

            $opt['OR'] = array('code LIKE' => '%' . $term . '%', 'name LIKE' => '%' . $term . '%');

            $res = $jobs->find('all')->where($opt)->order('code ASC')->toArray();

            //echo "<pre>"; print_r($res); echo "</pre>";

            foreach ($res as $key => $job) {

                $out[] = array('id' => $job->id, 'label' => $job->code . " - " . $job->name, 'name' => $job->name, 'code' => $job->code, 'borderColor' => $job->borderColor);

            }

            //echo "<pre>"; print_r($aziende); echo "</pre>";

        }

        return $out;

    }

    public function getProcessByIdJob($id = "", $order = ""){

        $out = array();

        if($id != ""){

            if($order == ""){

                $jobs = TableRegistry::get('Consulenza.Jobs');

                $opt['id'] = $id;

                $res = $jobs->find('all')->where($opt)->contain(['Processes'])->toArray();

                //echo "<pre>"; print_r($res); echo "</pre>";

                if(isset($res[0]['processes']) && !empty($res[0]['processes'])){

                    foreach ($res[0]['processes'] as $key => $process) {
                        $out[] = array('id' => $process->id, 'name' => $process->name, 'toConsume' => $process->toConsume);
                    }

                }

            }else{

                $jobsOrders = TableRegistry::get('Consulenza.JobsOrders');

                $opt['job_id'] = $id;
                $opt['order_id'] = $order;

                $res = $jobsOrders->find('all')->where($opt)->contain(['Processes'])->toArray();

                //echo "<pre>"; print_r($res); echo "</pre>";

                if(isset($res[0]['process']) && !empty($res[0]['process'])){

                    $out[] = array('id' => $res[0]['process']->id, 'name' => $res[0]['process']->name, 'toConsume' => (int)$res[0]['process']->toConsume);

                }

            }

        }

        return $out;
    }

    public function getAttributeByKey($key = ""){

        $res = array();

        if($key != ""){

            $jobsAttr = TableRegistry::get('Consulenza.Jobsattributes');

            $res = $jobsAttr->find('all')->where(['key_attribute' => $key])->order('ordering ASC')->toArray();

        }

        return $res;

    }

    public function getJobs(){

        $jobs = TableRegistry::get('Consulenza.Jobs');

        $res = $jobs->find('all')->where(['isPlannable' => 1])->contain(['Processes','Jobsattributes'])->order('code ASC')->toArray();

        //echo "<pre>"; print_r($res); echo "</pre>"; exit;

        return $res;
    }

    public function deleteAllJobsOrderByOrderId($orderId){

        $jobsOrders = TableRegistry::get('Consulenza.JobsOrders');

        $jobsOrders->deleteAll(['order_id' => $orderId]);

    }

    public function insertJobsOrder($toSave){

        $jobsOrders = TableRegistry::get('Consulenza.JobsOrders');

        $jobs = $jobsOrders->newEntities($toSave);

        foreach ($jobs as $key => $job) {
            $jobsOrders->save($job);
        }

    }

    public function updateJobsOrderData($toSave){

        //debug($toSave); exit;

        if(isset($toSave['id'])){

            $jobsOrders = TableRegistry::get('Consulenza.JobsOrders');

            if(isset($toSave['totalTime'])){
                //Questo valore dal view mi arriva in ore (00:00) ma nel db lo salviamo in secondi quindi....
                list($h,$m) = explode(":",$toSave['totalTime']);

                $toSave['totalTime'] = ($h * 60 * 60) + ($m * 60);
            }

            if($toSave['id'] != 0){
                $jobsOrder = $jobsOrders->get($toSave['id']);

                $jobsOrder = $jobsOrders->patchEntity($jobsOrder,$toSave);
            }else{

                unset($toSave['id']);

                //Verifico ancora se la coppia order_id e job_id esistono già....nel caso di chiamate lente che non hanno aggiornato il dom prima del secondo invio
                $jobsOrder = $jobsOrders->find('all')->where(['order_id' => $toSave['order_id'], 'job_id' => $toSave['job_id']])->order('id')->first();

                if($jobsOrder){

                    $jobsOrder = $jobsOrders->patchEntity($jobsOrder,$toSave);

                }else{

                    if(!isset($toSave['user_id'])){
                        $toSave['user_id'] = 0;
                    }
                    if(!isset($toSave['process_id'])){
                        $toSave['process_id'] = 0;
                    }
                    if(!isset($toSave['totalTime'])){
                        $toSave['totalTime'] = 0;
                    }

                    $jobsOrder = $jobsOrders->newEntity($toSave);
                }
            }

            //debug($jobsOrder); exit;

            $res = $jobsOrders->save($jobsOrder);

        }else{

            $res = false;

        }

        return $res;

    }

    public function lockJobOrderById($id = ""){

        if($id != ""){

            $jobsOrders = TableRegistry::get('Consulenza.JobsOrders');

            $toSave['isLocked'] = 1;

            $jobsOrder = $jobsOrders->get($id);

            $jobsOrder = $jobsOrders->patchEntity($jobsOrder,$toSave);

            $jobsOrders->save($jobsOrder);

        }

    }

    public function checkTimetoBeAssigned($idJobOrder = ""){

        $out = 0;

        if($idJobOrder != ""){

            //Recupero i dati di jobs order
            $jobsOrders = TableRegistry::get('Consulenza.JobsOrders');
            $jobOrder = $jobsOrders->get($idJobOrder);

            //debug($jobOrder);

            $out = $jobOrder->totalTime;

            //Recupero i task assegnati a questo
            $tasks = TableRegistry::get('Consulenza.Tasks');

            $opt['order_id'] = $jobOrder->order_id;
            $opt['job_id'] = $jobOrder->job_id;
            $opt['start'] = "0000-00-00 00:00:00";
            $opt['end'] = "0000-00-00 00:00:00";

            $res = $tasks->find('all')->where($opt)->toArray();

            //debug($res); exit;

            $set = 0;
            if(!empty($res)){

                foreach ($res as $key => $task) {

                    if($task->start != null){
                        $secondStart = $task->start->toUnixString();
                        $secondEnd = $task->end->toUnixString();
                        $set += ($secondEnd - $secondStart);
                    }else if($task->byPlanning == 1){
                        $set += $task->plannedLenght;
                    }


                }

            }

            $out -= $set;
        }

        return $out;
    }


    public function getDataOrderByJobsOrderId($idJobOrder = ""){

        //Recupero i dati di jobs order
        $jobsOrders = TableRegistry::get('Consulenza.JobsOrders');
        $jobOrder = $jobsOrders->get($idJobOrder);

        //echo "<pre>"; print_r($jobOrder); echo "</pre>";

        $order = $this->Order->_get($jobOrder->order_id);

        //echo "<pre>"; print_r($order); echo "</pre>";

        return $order;

    }

    public function getStringTime($time){

        //echo "Time: " . $time . "</br>";

        //verifico se ho un time negativo
        $negativo = false;
        if($time < 0){
            $negativo = true;
            $time = $time * -1;
        }

        $time = number_format(($time / 60 / 60),2);

        //echo "Time H: " . $time . "</br>";

        $t = explode(".",$time);

        $t[0] = str_pad($t[0], 3, "0", STR_PAD_LEFT);

        if(isset($t[1])){
            $t[1] = str_pad($t[1], 2, "0");
            $t[1] = round(($t[1] * 60) / 100);
            $t[1] = str_pad($t[1], 2, "0", STR_PAD_LEFT);
        }else{
            $t[1] = "00";
        }

        //echo "H: " . $t[0] . " M: " . $t[1] . "</br>";

        if($negativo){
            $toRet = "- " . $t[0] . ":" . $t[1];
        }else{
            $toRet = $t[0] . ":" . $t[1];
        }

        return $toRet;
    }

    public function _newEntity(){
        $jobs = TableRegistry::get('Consulenza.Jobs');
        return $jobs->newEntity();
    }

    public function _patchEntity($doc,$request){
        $jobs = TableRegistry::get('Consulenza.Jobs');
        return $jobs->patchEntity($doc,$request);
    }

    public function _save($doc){
        $jobs = TableRegistry::get('Consulenza.Jobs');
        return $jobs->save($doc);
    }

    public function _get($id){
        $jobs = TableRegistry::get('Consulenza.Jobs');
        return $jobs->get($id);

    }

    public function _delete($doc){
        $jobs = TableRegistry::get('Consulenza.Jobs');
        return $jobs->delete($doc);
    }

    public function jobInviiCausali($causaleId = -1, $year = -1,$office = -1,$xls=false){

       $Jobsattributes = TableRegistry::get('Consulenza.Jobsattributes');
        $Orders = TableRegistry::get('Consulenza.Orders');
        $JobsOrders = TableRegistry::get('Consulenza.JobsOrders');

        $jobs = $Jobsattributes->getJobsFiltered('ACCOUNTING')->toArray();
        $jobs = $jobs[0]['jobs'];
        $years = array_keys($Orders->getAllYears()->toArray());
		//$offices = $this->Office->getOffices();

        if($causaleId == -1) {
            $causaleId = $jobs[0]['id'];
        }

        if($year == -1) {
            $year = $years[0];
        }

        // Sergio 07/04/2016 task #5360, se non ho un ufficio li cerco tutti
		/*if($office == -1){
			$office = $offices[0]->id;
		}*/

        $pass =  $this->request->query;
        $out = array();

        if($jobs != "" && $causaleId != 0 && $year != null && $office != null){

            $res = $JobsOrders->retrieveReportDichiarativi($causaleId, $year,$office,$pass,false,$xls);
            $totals = $JobsOrders->retrieveReportDichiarativi($causaleId, $year,$office,$pass,true);
           /*echo "<pre>";
           print_r($res);exit;*/

            $rows = array();

            foreach ($res as $azienda) {

                $button = "";
                $status = '';

                if(isset($azienda['phase']->status)){
                    $status = $azienda['phase']->status;
                }else {
                    $status = '';
                }


                switch ($status) {
                    case 'READY':
                        if($this->generaPulsanteInviiCausali())
                            $button =  '<span id="span_sent-'.$azienda['id'].'"><button class="btn btn-flat btn-warning btn-block invia_btn" jobs-order-id="'.$azienda['id'].'" id="inviaBtn">Invia</button></span>';

                        $button_xls = 'NO';
                    break;

                    case 'DONE':
                        $button = '<div class="text-green text-center"><i class="fa fa-check-circle"></i> INVIATO</div>';
                        $button_xls = 'SI';
                    break;

                    default:
                        if($this->generaPulsanteInviiCausali())
                            $button = '<button class="btn btn-flat btn-warning disabled btn-block invia_btn">Invia</button>';

                        $button_xls = '-';
                }

                if(empty($azienda['notes'])){
                    $note = '<button class="fa fa-pencil noteTextArea btn btn-sm btn-flat btn-default edit"  title="Note" data-toggle="popover" data-pk="'.$azienda['id'].'" data-html="true" data-placement="left" data-content="
                    <div class=\'row\' style=\'padding:0 10px;\'><textarea cols=\'30\' rows=\'4\' name=\''.$azienda['id'].'\'>'.$azienda['notes'].'</textarea></div><button value=\''.$azienda['id'].'\' class=\'pull-left fa fa-check\'></button><button class=\'pull-left fa fa-close\'></button>"
                    ></button>';
                }else{
                    $note = '<a class="notes-JobsOrders-link" title="Note" data-toggle="popover" data-pk="'.$azienda['id'].'" data-html="true" data-placement="left" data-content="
                    <div class=\'row\' style=\'padding:0 10px;\'><textarea cols=\'30\' rows=\'4\' name=\''.$azienda['id'].'\'>'.$azienda['notes'].'</textarea></div><button value=\''.$azienda['id'].'\' class=\'pull-left fa fa-check\'></button><button class=\'pull-left fa fa-close\'></button>"
                    >'.Text::truncate(  $azienda['notes'],  30,  [  'ellipsis' => '...',  'exact' => true  ]).'</a>';
                }
                $note_xls=$azienda['notes'];


                $milestone = '';

                if(isset($azienda['phase']->milestone)){
                    $milestone = $azienda['phase']->milestone;
                }else {
                    $milestone = '';
                }

                $stato = '<span id="span_milestone-'.$azienda['id'].'">'.$milestone.'</span>';

                if($xls){ // se excel allora il button è solo una label cosi' come lo stato non ha html
                    $button = $button_xls;
                    $stato = $milestone;
                    $note = $note_xls;
                }

                if(isset($azienda['order']['azienda']->denominazione)){

                    $rows[] = array(
                        $azienda['order']['azienda']->denominazione,
                        $azienda['order']['partner']['cognome'].' '.$azienda['order']['partner']['nome'],
                        $azienda['user']['cognome'] . ' ' . $azienda['user']['nome'],
                        $stato,
                        $button,
                        $note
                    );
                }
            }

            $out['total_rows'] = $totals;
            $out['rows'] = $rows;

            return $out;
        }

    }

    public function jobInviiCausaliUNICO($causaleId = -1, $year = -1,$office = -1,$xls=false){

        $Jobsattributes = TableRegistry::get('Consulenza.Jobsattributes');
        $Orders = TableRegistry::get('Consulenza.Orders');
        $JobsOrders = TableRegistry::get('Consulenza.JobsOrders');

        $jobs = $Jobsattributes->getJobsFiltered('ACCOUNTING')->toArray();
        $jobs = $jobs[0]['jobs'];
        $years = array_keys($Orders->getAllYears()->toArray());
        //$offices = $this->Office->getOffices();

        if($causaleId == -1) {
            $causaleId = $jobs[0]['id'];
        }

        if($year == -1) {
            $year = $years[0];
        }

        // Sergio 07/04/2016 task #5360, se non ho un ufficio li cerco tutti
        /*if($office == -1){
            $office = $offices[0]->id;
        }*/

        $pass =  $this->request->query;
        $out = array();

        if($jobs != "" && $causaleId != 0 && $year != null && $office != null){

            $res = $JobsOrders->retrieveReportDichiarativiUNICO($causaleId, $year,$office,$pass,false,$xls);
            $totals = $JobsOrders->retrieveReportDichiarativiUNICO($causaleId, $year,$office,$pass,true);

            /*echo "<pre>";
            print_r($res);exit;*/

            $rows = array();

            foreach ($res as $azienda) {

                $button = "";
                $status = '';

                if(isset($azienda['phase']->status)){
                    $status = $azienda['phase']->status;
                }else {
                    $status = '';
                }


                switch ($status) {
                    case 'READY':
                        $button =  '<span id="span_sent-'.$azienda['id'].'"><button class="btn btn-flat btn-warning btn-block invia_btn" jobs-order-id="'.$azienda['id'].'" id="inviaBtn">Invia</button></span>';
                        $button_xls = 'NO';
                    break;

                    case 'DONE':
                        $button = '<div class="text-green text-center"><i class="fa fa-check-circle"></i> INVIATO</div>';
                        $button_xls = 'SI';
                    break;

                    default:
                        $button = '<button class="btn btn-flat btn-warning disabled btn-block invia_btn">Invia</button>';
                        $button_xls = '-';
                }

                switch ($azienda['irapInviato']) {

                    case '1':
                        $invia_irap = '<div class="text-green text-center"><i class="fa fa-check-circle"></i> INVIATO</div>';
                        $irap_xls = 'SI';
                    break;

                    default:
                        // solo se se ha irap mostro il pulsante di invio
                        if($azienda['order']->hasIRAP=='1'){

                            //questo tasto deve comportarsi in base allo status come l'altro pulsante sopra
                            if($status=='READY' || $status=='DONE'){
                                $invia_irap = '<span id="span_sent_irap-'.$azienda['id'].'"><button class="btn btn-flat btn-warning btn-block invia_btn_irap" jobs-order-id="'.$azienda['id'].'" id="inviaIrapBtn">Invia</button></span>';
                                $irap_xls = 'NO';
                            } else {
                                $invia_irap = '<button class="btn btn-flat btn-warning disabled btn-block invia_btn">Invia</button>';
                                $irap_xls = '-';
                            }
                        } else {
                            $invia_irap = '';
                            $irap_xls = '';
                        }


                }
                if(empty($azienda['notes'])){
                    $note = '<button class="fa fa-pencil noteTextArea btn btn-sm btn-flat btn-default edit"  title="Note" data-toggle="popover" data-pk="'.$azienda['id'].'" data-html="true" data-placement="left" data-content="
                    <div class=\'row\' style=\'padding:0 10px;\'><textarea cols=\'30\' rows=\'4\' name=\''.$azienda['id'].'\'>'.$azienda['notes'].'</textarea></div><button value=\''.$azienda['id'].'\' class=\'pull-left fa fa-check\'></button><button class=\'pull-left fa fa-close\'></button>"
                    ></button>';
                }else{
                    $note = '<a class="notes-JobsOrders-link" title="Note" data-toggle="popover" data-pk="'.$azienda['id'].'" data-html="true" data-placement="left" data-content="
                    <div class=\'row\' style=\'padding:0 10px;\'><textarea cols=\'30\' rows=\'4\' name=\''.$azienda['id'].'\'>'.$azienda['notes'].'</textarea></div><button value=\''.$azienda['id'].'\' class=\'pull-left fa fa-check\'></button><button class=\'pull-left fa fa-close\'></button>"
                    >'.Text::truncate(  $azienda['notes'],  25,  [  'ellipsis' => '...',  'exact' => true  ]).'</a>';
                }
                $note_xls=$azienda['notes'];

                $milestone = '';

                if(isset($azienda['phase']->milestone)){
                    $milestone = $azienda['phase']->milestone;
                }else {
                    $milestone = '';
                }

                $stato = '<span id="span_milestone-'.$azienda['id'].'">'.$milestone.'</span>';

                //stato contabilità
                $milestoneCont = '';

                if(isset($azienda['JobsOrdersContabilita']['phase']->milestone)){
                    $milestoneCont = $azienda['JobsOrdersContabilita']['phase']->milestone;
                }else {
                    $milestoneCont = '';
                }

                $statoCont = '<span id="span_milestone-cont-'.$azienda['id'].'">'.$milestoneCont.'</span>';

                if($xls){ // se excel allora il button è solo una label cosi' come lo stato non ha html
                    $button = $button_xls;
                    $stato = $milestone;
                    $invia_irap = $irap_xls;
                    $statoCont = $milestoneCont;
                    $note = $note_xls;
                }

                if(isset($azienda['order']['azienda']->denominazione)){

                    if(isset($azienda['order']->dataConsegnaBilancino)){
                        $dataConsegnaBilancino = $azienda['order']->dataConsegnaBilancino->i18nFormat('dd/MM/yyyy');
                    } else {
                        $dataConsegnaBilancino ='';
                    }

                    if($azienda['order']->hasPIVA=='true'){
                        $hasPIVA = 'SI';
                    } else {
                        $hasPIVA = 'NO';
                    }

                    $rows[] = array(
                        $azienda['order']['azienda']->denominazione,
                        $azienda['order']['partner']['cognome'].' '.$azienda['order']['partner']['nome'],
                        $dataConsegnaBilancino,
                        $hasPIVA,
                        $azienda['JobsOrdersContabilita']['operatore']['cognome'].' '.$azienda['JobsOrdersContabilita']['operatore']['nome'],
                        $azienda['user']['cognome'] . ' ' . $azienda['user']['nome'],
                        $statoCont,
                        $stato,
                        $button,
                        $invia_irap,
                        $note
                    );
                }
            }

            $out['total_rows'] = $totals;
            $out['rows'] = $rows;
            //debug($out);die;
            return $out;
        }

    }

    public function jobInviiCausaliUNICOSC($causaleId = -1, $year = -1,$office = -1,$xls=false){

        $Jobsattributes = TableRegistry::get('Consulenza.Jobsattributes');
        $Orders = TableRegistry::get('Consulenza.Orders');
        $JobsOrders = TableRegistry::get('Consulenza.JobsOrders');

        $jobs = $Jobsattributes->getJobsFiltered('UNICOSC')->toArray();
        $jobs = $jobs[0]['jobs'];
        $years = array_keys($Orders->getAllYears()->toArray());
        //$offices = $this->Office->getOffices();

        if($causaleId == -1) {
            $causaleId = $jobs[0]['id'];
        }

        if($year == -1) {
            $year = $years[0];
        }

        // Sergio 07/04/2016 task #5360, se non ho un ufficio li cerco tutti
        /*if($office == -1){
            $office = $offices[0]->id;
        }*/

        $pass =  $this->request->query;
        $out = array();

        if($jobs != "" && $causaleId != 0 && $year != null && $office != null){

            $res = $JobsOrders->retrieveReportDichiarativiUNICOSC($causaleId, $year,$office,$pass,false,$xls);
            $totals = $JobsOrders->retrieveReportDichiarativiUNICOSC($causaleId, $year,$office,$pass,true);

          /*echo "<pre>";
            print_r($res);exit;*/

            $rows = array();

            foreach ($res as $azienda) {

                $button = "";
                $status = '';

                if(isset($azienda['phase']->status)){
                    $status = $azienda['phase']->status;
                }else {
                    $status = '';
                }


                switch ($status) {
                    case 'READY':
                        $button =  '<span id="span_sent-'.$azienda['id'].'"><button class="btn btn-flat btn-warning btn-block invia_btn" jobs-order-id="'.$azienda['id'].'" id="inviaBtn">Invia</button></span>';
                        $button_xls = 'NO';
                    break;

                    case 'DONE':
                        $button = '<div class="text-green text-center"><i class="fa fa-check-circle"></i> INVIATO</div>';
                        $button_xls = 'SI';
                    break;

                    default:
                        $button = '<button class="btn btn-flat btn-warning disabled btn-block invia_btn">Invia</button>';
                        $button_xls = '-';
                }

                switch ($azienda['irapInviato']) {

                    case '1':
                        $invia_irap = '<div class="text-green text-center"><i class="fa fa-check-circle"></i> INVIATO</div>';
                        $irap_xls = 'SI';
                    break;

                    default:
                        // solo se se ha irap mostro il pulsante di invio
                        if($azienda['order']->hasIRAP=='1'){

                            //questo tasto deve comportarsi in base allo status come l'altro pulsante sopra
                            if($status=='READY' || $status=='DONE'){
                                $invia_irap = '<span id="span_sent_irap-'.$azienda['id'].'"><button class="btn btn-flat btn-warning btn-block invia_btn_irap" jobs-order-id="'.$azienda['id'].'" id="inviaIrapBtn">Invia</button></span>';
                                $irap_xls = 'NO';
                            } else {
                                $invia_irap = '<button class="btn btn-flat btn-warning disabled btn-block invia_btn">Invia</button>';
                                $irap_xls = '-';
                            }
                        } else {
                            $invia_irap = '';
                            $irap_xls = '';
                        }


                }
                if(empty($azienda['notes'])){
                    $note = '<button class="fa fa-pencil noteTextArea btn btn-sm btn-flat btn-default edit"  title="Note" data-toggle="popover" data-pk="'.$azienda['id'].'" data-html="true" data-placement="left" data-content="
                    <div class=\'row\' style=\'padding:0 10px;\'><textarea cols=\'30\' rows=\'4\' name=\''.$azienda['id'].'\'>'.$azienda['notes'].'</textarea></div><button value=\''.$azienda['id'].'\' class=\'pull-left fa fa-check\'></button><button class=\'pull-left fa fa-close\'></button>"
                    ></button>';
                }else{
                    $note = '<a class="notes-JobsOrders-link" title="Note" data-toggle="popover" data-pk="'.$azienda['id'].'" data-html="true" data-placement="left" data-content="
                    <div class=\'row\' style=\'padding:0 10px;\'><textarea cols=\'30\' rows=\'4\' name=\''.$azienda['id'].'\'>'.$azienda['notes'].'</textarea></div><button value=\''.$azienda['id'].'\' class=\'pull-left fa fa-check\'></button><button class=\'pull-left fa fa-close\'></button>"
                    >'.Text::truncate(  $azienda['notes'],  20,  [  'ellipsis' => '...',  'exact' => true  ]).'</a>';
                }
                $note_xls=$azienda['notes'];

                // Pulsante deposita

                $button_bilancio = '';
                $button_bilancio_xls = '';

                if(@$azienda['JobsOrdersBilancio']['phase']->status !== null)
                {
                    switch($azienda['JobsOrdersBilancio']['phase']->status)
                    {
                         case 'READY':
                            $button_bilancio =  '<span id="span_sent-'.$azienda['JobsOrdersBilancio']->id.'"><button class="btn btn-flat btn-warning btn-block invia_btn_bilancio" jobs-order-id="'.$azienda['JobsOrdersBilancio']->id.'" id="inviaBtnBilancio">Deposita</button></span>';
                            $button_bilancio_xls = 'NO';
                        break;

                        case 'DONE':
                            $button_bilancio = '<div class="text-green text-center"><i class="fa fa-check-circle"></i> DEPOSITATO</div>';
                            $button_bilancio_xls = 'SI';
                        break;

                        default:
                            $button_bilancio = '<button class="btn btn-flat btn-warning disabled btn-block invia_btn">Deposita</button>';
                            $button_bilancio_xls = '-';
                    }
                }

                ########################################################################################################################

                $milestone = '';

                if(isset($azienda['phase']->milestone)){
                    $milestone = $azienda['phase']->milestone;
                }else {
                    $milestone = '';
                }

                $stato = '<span id="span_milestone-'.$azienda['id'].'">'.$milestone.'</span>';

                //stato contabilità
                $milestoneCont = '';

                if(isset($azienda['JobsOrdersContabilita']['phase']->milestone)){
                    $milestoneCont = $azienda['JobsOrdersContabilita']['phase']->milestone;
                }else {
                    $milestoneCont = '';
                }

                $statoCont = '<span id="span_milestone-cont-'.$azienda['id'].'">'.$milestoneCont.'</span>';

                // stato bilancio

                $milestoneBil = '';

                if(isset($azienda['JobsOrdersBilancio']['phase']->milestone)){
                    $milestoneBil = $azienda['JobsOrdersBilancio']['phase']->milestone;
                }else {
                    $milestoneBil = '';
                }

                $statoBil = '<span id="span_milestone-bil-'.$azienda['id'].'">'.$milestoneBil.'</span>';

                ########################################################################################################################

                if($xls){ // se excel allora il button è solo una label cosi' come lo stato non ha html
                    $button = $button_xls;
                    $stato = $milestone;
                    $invia_irap = $irap_xls;
                    $statoCont = $milestoneCont;
                    $statoBil = $milestoneBil;
                    $button_bilancio = $button_bilancio_xls;
                    $note = $note_xls;
                }

                if(isset($azienda['order']['azienda']->denominazione)){

                    if(isset($azienda['order']->dataConsegnaBilancino)){
                        $dataConsegnaBilancino = $azienda['order']->dataConsegnaBilancino->i18nFormat('dd/MM/yyyy');
                    } else {
                        $dataConsegnaBilancino ='';
                    }

                    if($azienda['order']->hasPIVA=='true'){
                        $hasPIVA = 'SI';
                    } else {
                        $hasPIVA = 'NO';
                    }

                    $rows[] = array(
                        $azienda['order']['azienda']->denominazione,
                        $azienda['order']['partner']['cognome'].' '.$azienda['order']['partner']['nome'],
                        $dataConsegnaBilancino,
                        $hasPIVA,
                        $azienda['JobsOrdersContabilita']['operatore']['cognome'].' '.$azienda['JobsOrdersContabilita']['operatore']['nome'],
                        $azienda['user']['cognome'] . ' ' . $azienda['user']['nome'],
                        $statoBil,
                        $statoCont,
                        $stato,
                        $button,
                        $invia_irap,
                        $button_bilancio,
                        $note
                    );
                }
            }

            $out['total_rows'] = $totals;
            $out['rows'] = $rows;

            return $out;
        }

    }

    public function jobInviiCausaliUNICOENC($causaleId = -1, $year = -1,$office = -1,$xls=false)
    {
        $Jobsattributes = TableRegistry::get('Consulenza.Jobsattributes');
        $Orders = TableRegistry::get('Consulenza.Orders');
        $JobsOrders = TableRegistry::get('Consulenza.JobsOrders');

        $jobs = $Jobsattributes->getJobsFiltered('UNICOENC')->toArray();
        $jobs = $jobs[0]['jobs'];
        $years = array_keys($Orders->getAllYears()->toArray());
        //$offices = $this->Office->getOffices();

        if($causaleId == -1) {
            $causaleId = $jobs[0]['id'];
        }

        if($year == -1) {
            $year = $years[0];
        }

        // Sergio 07/04/2016 task #5360, se non ho un ufficio li cerco tutti
        /*if($office == -1){
            $office = $offices[0]->id;
        }*/

        $pass =  $this->request->query;
        $out = array();

        if($jobs != "" && $causaleId != 0 && $year != null && $office != null){

            $res = $JobsOrders->retrieveReportDichiarativiUNICOENC($causaleId, $year,$office,$pass,false,$xls);
            $totals = $JobsOrders->retrieveReportDichiarativiUNICOENC($causaleId, $year,$office,$pass,true);

            /*echo "<pre>";
            print_r($res);exit;*/

            $rows = array();

            foreach ($res as $azienda) {

                $button = "";
                $status = '';

                if(isset($azienda['phase']->status)){
                    $status = $azienda['phase']->status;
                }else {
                    $status = '';
                }

                if(isset($azienda['JobsOrdersIrapEnc']['phase']->status))
                {
                    $statusIrap = $azienda['JobsOrdersIrapEnc']['phase']->status;
                }else
                {
                    $statusIrap = '';
                }

                ###################################################################################

                switch ($status) {
                    case 'READY':
                        $button =  '<span id="span_sent-'.$azienda['id'].'"><button class="btn btn-flat btn-warning btn-block invia_btn" jobs-order-id="'.$azienda['id'].'" id="inviaBtn">Invia</button></span>';
                        $button_xls = 'NO';
                    break;

                    case 'DONE':
                        $button = '<div class="text-green text-center"><i class="fa fa-check-circle"></i> INVIATO</div>';
                        $button_xls = 'SI';
                    break;

                    default:
                        $button = '<button class="btn btn-flat btn-warning disabled btn-block invia_btn">Invia</button>';
                        $button_xls = '-';
                    break;
                }

                switch ($statusIrap) {

                    case 'READY':
                        $button_irap =  '<span id="span_sent-'.$azienda['JobsOrdersIrapEnc']->id.'"><button class="btn btn-flat btn-warning btn-block invia_btn_irap" jobs-order-id="'.$azienda['JobsOrdersIrapEnc']->id.'" id="inviaIrapBtn">Invia</button></span>';
                        $button_irap_xls = 'NO';
                    break;

                    case 'DONE':
                        $button_irap = '<div class="text-green text-center"><i class="fa fa-check-circle"></i> INVIATO</div>';
                        $button_irap_xls = 'SI';
                    break;

                    default:
                        $button_irap = '<button class="btn btn-flat btn-warning disabled btn-block invia_btn">Invia</button>';
                        $button_irap_xls = '-';
                    break;

                }

                ##############################################################################################
                if(empty($azienda['notes'])){
                    $note = '<button class="fa fa-pencil noteTextArea btn btn-sm btn-flat btn-default edit"  title="Note" data-toggle="popover" data-pk="'.$azienda['id'].'" data-html="true" data-placement="left" data-content="
                    <div class=\'row\' style=\'padding:0 10px;\'><textarea cols=\'30\' rows=\'4\' name=\''.$azienda['id'].'\'>'.$azienda['notes'].'</textarea></div><button value=\''.$azienda['id'].'\' class=\'pull-left fa fa-check\'></button><button class=\'pull-left fa fa-close\'></button>"
                    ></button>';
                }else{
                    $note = '<a class="notes-JobsOrders-link" title="Note" data-toggle="popover" data-pk="'.$azienda['id'].'" data-html="true" data-placement="left" data-content="
                    <div class=\'row\' style=\'padding:0 10px;\'><textarea cols=\'30\' rows=\'4\' name=\''.$azienda['id'].'\'>'.$azienda['notes'].'</textarea></div><button value=\''.$azienda['id'].'\' class=\'pull-left fa fa-check\'></button><button class=\'pull-left fa fa-close\'></button>"
                    >'.Text::truncate(  $azienda['notes'],  20,  [  'ellipsis' => '...',  'exact' => true  ]).'</a>';
                }
                $note_xls=$azienda['notes'];

                $milestone = '';

                if(isset($azienda['phase']->milestone)){
                    $milestone = $azienda['phase']->milestone;
                }else {
                    $milestone = '';
                }

                $stato = '<span id="span_milestone-'.$azienda['id'].'">'.$milestone.'</span>';

                //stato contabilità
                $milestoneCont = '';

                if(isset($azienda['JobsOrdersContabilita']['phase']->milestone)){
                    $milestoneCont = $azienda['JobsOrdersContabilita']['phase']->milestone;
                }else {
                    $milestoneCont = '';
                }

                $statoCont = '<span id="span_milestone-cont-'.$azienda['id'].'">'.$milestoneCont.'</span>';

                // Stato IRAP

                $milestoneIrap = '';

                if(isset($azienda['JobsOrdersIrapEnc']['phase']->milestone)){
                    $milestoneIrap = $azienda['JobsOrdersIrapEnc']['phase']->milestone;
                }else {
                    $milestoneIrap = '';
                }

                $statoIrap = '<span id="span_milestone-cont-'.$azienda['id'].'">'.$milestoneIrap.'</span>';

                ##############################################################################################

                if($xls){ // se excel allora il button è solo una label cosi' come lo stato non ha html
                    $button = $button_xls;
                    $button_irap = $button_irap_xls;
                    $stato = $milestone;
                    $statoCont = $milestoneCont;
                    $statoIrap = $milestoneIrap;
                    $note = $note_xls;
                }

                if(isset($azienda['order']['azienda']->denominazione)){

                    if(isset($azienda['order']->dataConsegnaBilancino)){
                        $dataConsegnaBilancino = $azienda['order']->dataConsegnaBilancino->i18nFormat('dd/MM/yyyy');
                    } else {
                        $dataConsegnaBilancino ='';
                    }

                    if($azienda['order']->hasPIVA=='true'){
                        $hasPIVA = 'SI';
                    } else {
                        $hasPIVA = 'NO';
                    }

                    $rows[] = array(
                        $azienda['order']['azienda']->denominazione,
                        $azienda['order']['partner']['cognome'].' '.$azienda['order']['partner']['nome'],
                        $dataConsegnaBilancino,
                        $hasPIVA,
                        $azienda['JobsOrdersContabilita']['operatore']['cognome'].' '.$azienda['JobsOrdersContabilita']['operatore']['nome'],
                        $azienda['user']['cognome'] . ' ' . $azienda['user']['nome'],
                        $statoCont,
                        $stato,
                        $statoIrap,
                        $button,
                        $button_irap,
                        $note
                    );
                }
            }

            $out['total_rows'] = $totals;
            $out['rows'] = $rows;

            return $out;
        }
    }

    /*
    * metodo generaPulsanteInviiCausali
    *
    * stabilisce se il pulsante dell'invio della causale deve essere generato
    *
    * @api
    * @author
    * @return boolean
    * @thows Exception
    */

    public function generaPulsanteInviiCausali()
    {

        try
        {
            return $this->checkAuthInvioCausale();

        }catch(Exception $e)
        {
            return false;
        }

    }

    public function checkAuthInvioCausale()
    {
        try
        {
            $user = $this->request->session()->read('Auth.User');
            $inviiCausali = $this->request->session()->read('Report.InviiCausali');
            $jobsAttributes = TableRegistry::get('Consulenza.Jobsattributes');

            //admin di livello 100 o superiore
            if($user['role'] == 'admin' && $user['level'] >= '100')
                return true;

            // utente di livello 50 o superiore
            if($user['level'] >= '50')
            {
                // Se la causale ha un attributo allow50 restituisco true
                if(is_array($inviiCausali) && !empty($inviiCausali))
                {
                    if(in_array('ALLOW50',$this->JobsAttributes->getAttributeFromJobId($inviiCausali['causaleId'])))
                        return true;

                }

            }

            return false;

        }catch(Exception $e)
        {
            return false;
        }
    }

    public function saveNotesJobsOrders($params)
    {
      $jobsOrders= TableRegistry::get('JobsOrders');
      $joborder = $jobsOrders->get($params['id']);

      $joborder->notes = $params['notes'];

      return $jobsOrders->save($joborder);
    }

    public function checkLockJobsOrders($order_id = 0)
    {
        $opz['JobsOrders.order_id'] = $order_id;
        $jobsOrdersTable = TableRegistry::get('JobsOrders');
        $jobsOrders = $jobsOrdersTable->find('all')->where($opz)->toArray();
        //debug($jobsOrders);die;
        if($jobsOrders !== null){
            foreach($jobsOrders as $joborder){
              if($joborder['isLocked']){
                return true;
              }
            }
            $jobsOrdersTable->deleteAll($opz);
            return false;

        }else{
          return null;
        }

    }

}
