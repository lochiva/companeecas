<?php
namespace Calendar\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Sabre\VObject;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

class PianificazioneComponent extends Component
{
    public $components = array('Calendar');

    public function getOperatori($id = 0)
    {
        $query = TableRegistry::get('Aziende.Contatti')->find('list',['keyField' => 'id',
          'valueField' => 'operatore'])->select(['id'=>'Contatti.id',
          'operatore'=>'CONCAT(Contatti.cognome,SPACE(1),Contatti.nome)'])
          ->contain(['SkillsGroup','Aziende'])->group('Contatti.id')->order(['operatore'=>'ASC'])
          ->where(['SkillsGroup.id_skill IN'=>[1,2,3,4],'Aziende.interno'=>1]);
        if(!empty($id)){
            $query->where(['Contatti.id' => $id]);
        }
        return $query->toArray();
    }

    public function getEventsOperatoreForClone($id = 0,$start='',$end='',$oneclone=1)
    {
        $events = TableRegistry::get('Calendar.Eventi',['className'=>'Calendar.EventiFrozen']);

        return $events->getEventsForClone($id,$start,$end,$oneclone);
    }

    public function cloneEvents($events, $addTime)
    {
        $eventsFrozenTable = TableRegistry::get('Calendar.Eventi',['className'=>'Calendar.EventiFrozen']);
        $eventsTalbe = TableRegistry::get('Eventi',['className'=>'Calendar.Eventi']);
        $clonedId = array();

        foreach ($events as $dati) {
            //$dati = $dati->toArray();
            $clonedId[] = $dati['id'];
            $dati['cloned_from'] = $dati['id'];
            unset($dati['id']);
            $dati['start'] = $dati['start']->modify($addTime)->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $dati['end'] = $dati['end']->modify($addTime)->i18nFormat('yyyy-MM-dd HH:mm:ss');
            $dati['vobject'] = $this->Calendar->buildICalendar($dati);

            $dati['start'] = new Time($dati['start']);
            $dati['end'] = new Time($dati['end']);

            $event = $eventsTalbe->newEntity($dati,['associated' => ['Groups.UsersToGroups']]);
            $save = $eventsTalbe->save($event);
            if(!$save){
                array_pop($clonedId);
            }
        }
        if(!empty($clonedId)){
            $eventsFrozenTable->updateAll(['cloned' => 1],['id IN'=>$clonedId]);
        }
        return $clonedId;
    }

    public function checkEvents($start,$end)
    {
        $ordersTable = TableRegistry::get('Progest.Orders');
        $ordersServicesTable = TableRegistry::get('Progest.ServicesOrders');
        $orders = $ordersTable->getActiveOrdersWithNotes();
        //echo "<pre>"; print_r($orders); echo "</pre>";
        $toRet = array();

        foreach ($orders as $key => $order) {

            $res = $ordersServicesTable->getServicesEvents($order['id'],$start,$end);
            //debug($res);
            //echo "<pre>"; print_r($res); echo "</pre>";
            $order['errors'] = $this->checkErrorFrequency($res,$start);

            if(!empty($order['errors'])){
                $toRet[] = $order->toArray();
            }
        }
        return $toRet;
    }

    public function frozeCalendar($start,$end)
    {
        $eventsTable = TableRegistry::get('Calendar.Eventi');
        $eventsFrozenTable = TableRegistry::get('Calendar.EventiFrozen');
        $weeksTable = TableRegistry::get('Calendar.CalendarWeeks');

        if(!$weeksTable->saveFrozeWeek($start,$end)){
           return false;
        }

        $events = $eventsTable->getEventsForFroze($start,$end);
        // Mi Coustruisce le entity contenenti già le associazioni da salvare. La dot notation in 'Groups.UsersToGroups'
        // dice a cake construire entity oltre al primo livello di profondità.
        $eventsEntities = $eventsFrozenTable->newEntities($events, ['associated' => ['Groups.UsersToGroups']]);
        return $eventsFrozenTable->saveMany($eventsEntities);
    }

    public function checkFrozeWeek($start,$end)
    {
        $weeksTable = TableRegistry::get('Calendar.CalendarWeeks');
        if($weeksTable->find()->where(['start' => $start,'end' => $end])->count()){
            return true;
        }
        return false;
    }

    public function checkPeriodOperatore($dati,$eventId = 0)
    {
        $events = TableRegistry::get('Calendar.Eventi');
        $contatti = TableRegistry::get('Aziende.Contatti');
        $busy = '';

        if(!empty($dati['id'])){
            $eventId = $dati['id'];
        }
        if(empty($dati['operatore'])){
            $dati['operatore'] = array();
            if(!empty($dati['operatori']) ){
              $dati['operatore'] = $dati['operatori'];
            }
        }
        if(!empty($dati['id_contatto']) ){
            $dati['operatore'][] = $dati['id_contatto'];
        }

        foreach ($dati['operatore'] as $value) {
            $res = $events->getPeriodOperatore($dati['start'],$dati['end'],$value,$eventId);
            if(!empty($res)){
                $impegni = '';
                foreach($res as $event){
                    $impegni .= '"'.$event['title'].'" ';
                }
                $contatto = $contatti->find()->select(['nome'=>'CONCAT(cognome," ",nome)'])
                ->where(['id'=>$value])->first();
                $busy .= "\"".$contatto['nome']."\"\n";
            }
        }
        if(empty($busy)){
            return true;
        }

        return $busy;
    }

######################################## FUNZIONI PRIVATE ###############################################

    private function checkErrorFrequency($res,$date)
    {
        $errors = array();
        foreach ($res as $service) {
            switch ($service['frequenza']) {
              case 1:
                if($service['service_houres'] != $service['week_houres']){
                    $errors[] = 'Numero di ore settimanali '.$service['service'].' previste/impostate: '
                      .$service['service_houres'].'/'.$this->zero($service['week_houres']);
                }
                if($service['service_passages'] != $service['week_passages']){
                    $errors[] = 'Numero di passaggi settimanali '.$service['service'].' previsti/impostati: '.
                    $service['service_passages'].'/'.$this->zero($service['week_passages']);
                }
                if($service['service_houres_weekend'] > 0 && $service['service_houres_weekend'] != $service['week_houres_weekend']){
                    $errors[] = 'Numero di ore festive '.$service['service'].' previste/impostate: '
                      .$service['service_houres_weekend'].'/'.$this->zero($service['week_houres_weekend']);
                }
                break;
              case 2:
                if($service['service_houres'] != $service['two_week_houres']){
                    $errors[] = 'Numero di ore ogni 15 giorni '.$service['service'].' previste/impostate: '
                      .$service['service_houres'].'/'.$this->zero($service['two_week_houres']);
                }
                if($service['service_passages'] != $service['two_week_passages']){
                    $errors[] = 'Numero di passaggi ogni 15 giorni '.$service['service'].' previsti/impostati: '
                      .$service['service_passages'].'/'.$this->zero($service['two_week_passages']);
                }
                if($service['service_houres_weekend'] > 0 && $service['service_houres_weekend'] != $service['two_week_houres_weekend']){
                    $errors[] = 'Numero di ore festive '.$service['service'].' previste/impostate: '
                      .$service['service_houres_weekend'].'/'.$this->zero($service['two_week_houres_weekend']);
                }
                break;
              case 3:
                // controlla che nel mese identificato dal primo giorno della settimana ci siamo le ore configurate
                $dateTime = new \DateTime($date);
                // la data mi arriva considerando un intervallo aperto , quindi ne sottraggo un giorno
                $dateTime->modify('-1 day');
                $month = $dateTime->format('m');
                //$nextWeekMonth = $dateTime->modify('+14 day')->format('m');

                //if($service['service_houres'] < $service['month_houres'] || ($service['service_houres'] != $service['month_houres']  && $month != $nextWeekMonth)){
                if($service['service_houres'] != $service['month_houres'] ){
                    $errors[] = 'Numero di ore ogni mese '.$service['service'].' previste/impostate: '
                      .$service['service_houres'].'/'.$this->zero($service['month_houres']);
                }
                //if($service['service_passages'] < $service['month_passages'] || ($service['service_passages'] != $service['month_passages'] && $month != $nextWeekMonth)){
                if( $service['service_passages'] != $service['month_passages']){
                    $errors[] = 'Numero di passaggi ogni mese '.$service['service'].' previsti/impostati: '
                      .$service['service_passages'].'/'.$this->zero($service['month_passages']);
                }
                if($service['service_houres_weekend'] > 0 && $service['service_houres_weekend'] != $service['month_houres_weekend']){
                    $errors[] = 'Numero di ore festive '.$service['service'].' previste/impostate: '
                      .$service['service_houres_weekend'].'/'.$this->zero($service['month_houres_weekend']);
                }
                break;
            }
            if($service['errore_compresenza'] > 0){
                $errors[] = 'Sono presenti '.$service['errore_compresenza'].' eventi in compresenza con un unico operatore impostato.';
            }
        }

        return $errors;
    }

    private function zero($val)
    {
        if(empty($val)){
          return '0';
        }
        return $val;
    }

}
