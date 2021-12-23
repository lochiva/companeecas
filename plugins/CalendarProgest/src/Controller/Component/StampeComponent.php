<?php
namespace Calendar\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Sabre\VObject;
use Cake\I18n\Time;
use Cake\Core\Configure;
use Cake\Datasource\ConnectionManager;

class StampeComponent extends Component
{
    public function getEventsOperatore($operatore = 0, $start, $end)
    {
        $repeatedEvents = TableRegistry::get('Calendar.RepeatedEvents');
        $events = TableRegistry::get('Calendar.Eventi');
        $toRet = array();
        $noteList = array();
        $compresenzeList = array();

        $opt['contain'] = array();
        $opt['conditions']['Eventi.start >= '] = $start;
        $opt['conditions']['Eventi.start <= '] = $end;
        $opt['conditions']['Eventi.repeated'] = 0;
        $opt['conditions']['Eventi.allDay'] = 0;
        $opt['conditions']['AND']['OR']['Eventi.id_contatto'] = $operatore;
        $opt['conditions']['AND']['OR']['UsersGrouping.id_user'] = $operatore;
        $opt['order'] = "Eventi.start ASC";

        $opt2['contain'] = ['Eventi'];
        $opt2['conditions']['RepeatedEvents.start >= '] = $start;
        $opt2['conditions']['RepeatedEvents.start <= '] = $end;
        $opt2['conditions']['Eventi.id_contatto'] = $operatore;
        $opt2['order'] = "RepeatedEvents.start ASC";

        $events = $events->getEventsStampe($opt);
        $repeatedEvents = $repeatedEvents->getEventsStampe($opt2);
        $events = array_merge($events,$repeatedEvents);
        $pause = [0,0,0,0,0,0,0];
        foreach ($events as $event) {
            $event['interval'] = $event->start->i18nFormat('HH:mm').'/'.$event->end->i18nFormat('HH:mm');
            if(!$pause[$event->start->i18nFormat('e')] && $event['start']->i18nFormat('HH:mm') >= '13:00'){
                $toRet[$event->start->i18nFormat('e')][] = array('title'=>'','interval'=>'','id_group'=>0);
                $pause[$event->start->i18nFormat('e')] = 1;
            }
            $toRet[$event->start->i18nFormat('e')][] = $event;
            if (!empty($event['note'])){
               $noteList[] = $event['start']->i18nFormat('dd/MM/yyyy').' - '.h($event['interval']).
                ' <b>'.h($event['title']).'</b> '.h($event['note']);
            }
            if(!empty($event['id_group'])){
              $operatori = '';
              $first = true;
              foreach ($event['group']['operatori'] as $value) {
                  if($value['id'] != $operatore){
                      if(!$first){
                          $operatori .= ', ';
                      }
                      $operatori .= $value['cognome'].' '.$value['nome'];
                      $first = false;
                  }
              }
              $compresenzeList[] = $event['start']->i18nFormat('dd/MM/yyyy').' - '.h($event['interval']).
               ' <b>'.h($event['title']).'</b> ( '.h($operatori).' )';
            }

        }

        if(!empty($toRet)){
            $max = 0;
            foreach ($toRet as $key => $events) {
                $tmp = count($events);
                if($tmp > $max){
                    $max = $tmp;
                }
            }
            $toRet['max'] = $max;
            $toRet['noteList'] = $noteList;
            $toRet['compresenzeList'] = $compresenzeList;
        }

        return $toRet;
    }

    public function getEventsPersona($persona = 0, $start, $end)
    {
        $repeatedEvents = TableRegistry::get('Calendar.RepeatedEvents');
        $events = TableRegistry::get('Calendar.Eventi');
        $toRet = array();
        $noteList = array();

        $opt['contain'] = ['Orders','Contatti'=> function ($q) {
            return $q->select(['operatore'=>'CONCAT(Contatti.cognome,SPACE(1),Contatti.nome)']);
        }];
        $opt['conditions']['Eventi.start >= '] = $start;
        $opt['conditions']['Eventi.start <= '] = $end;
        $opt['conditions']['Eventi.repeated'] = 0;
        $opt['conditions']['Eventi.allDay'] = 0;
        $opt['conditions']['Orders.id_person'] = $persona;
        $opt['order'] = "Eventi.start ASC";

        $opt2['contain'] = ['Eventi'=>['Orders','Contatti'=> function ($q) {
            return $q->select(['operatore'=>'CONCAT(Contatti.cognome,SPACE(1),Contatti.nome)']);
        }]];
        $opt2['conditions']['RepeatedEvents.start >= '] = $start;
        $opt2['conditions']['RepeatedEvents.start <= '] = $end;
        $opt2['conditions']['Orders.id_person'] = $persona;
        $opt2['order'] = "RepeatedEvents.start ASC";

        $events = $events->getEventsStampe($opt);
        $repeatedEvents = $repeatedEvents->getEventsStampe($opt2);
        $events = array_merge($events,$repeatedEvents);
        foreach ($events as $event) {
            $event['interval'] = strtoupper($event->start->i18nFormat('E')).' '.$event->start->i18nFormat('HH:mm').
                '/'.$event->end->i18nFormat('HH:mm');
            $toRet[] = $event;
            if(!empty($event['group'])){
                $event['operatore'] = '';
                foreach ($event['group']['operatori'] as $key => $value) {
                    if($key > 0){
                        $event['operatore'] .= ', ';
                    }
                    $event['operatore'] .= $value['cognome'].' '.$value['nome'];
                }
            }
        }

        return $toRet;
    }

    public function getMonteOreOperatore($operatore = 0, $start, $end)
    {
      $repeatedEvents = TableRegistry::get('Calendar.RepeatedEvents');
      $events = TableRegistry::get('Calendar.Eventi');
      $toRet = array('interventi'=>0, 'ore_utenti'=>0,'ore_altro'=>0,'tot_ore'=>0 );

      $events = $events->getEventsMonteOre($operatore,$start,$end);
      $repeatedEvents = $repeatedEvents->getEventsMonteOre($operatore,$start,$end);

      $toRet['interventi'] = (int)$events['interventi']+(int)$repeatedEvents['interventi'];
      $toRet['ore_utenti'] = (int)$events['ore_utenti']+(int)$repeatedEvents['ore_utenti'];
      $toRet['ore_altro'] = (int)$events['ore_altro']+(int)$repeatedEvents['ore_altro'];
      $toRet['tot_ore'] = (int)$events['tot_ore']+(int)$repeatedEvents['tot_ore'];

      return $toRet;
    }

    public function formatTotalMonteOre(&$dati)
    {
        $totale = array('interventi'=>0, 'ore_utenti'=>0,'ore_altro'=>0,'tot_ore'=>0);
        foreach ($dati as $key => $toRet) {

          $totale['interventi'] += $toRet['interventi'];
          $totale['ore_utenti'] += $toRet['ore_utenti'];
          $totale['ore_altro'] += $toRet['ore_altro'];
          $totale['tot_ore'] += $toRet['tot_ore'];

          $dati[$key]['ore_utenti'] = $this->oreMinuti($toRet['ore_utenti']);
          $dati[$key]['ore_altro'] = $this->oreMinuti($toRet['ore_altro']);
          $dati[$key]['tot_ore'] = $this->oreMinuti($toRet['tot_ore']);
        }
        $totale['ore_utenti'] = $this->oreMinuti($totale['ore_utenti']);
        $totale['ore_altro'] = $this->oreMinuti($totale['ore_altro']);
        $totale['tot_ore'] = $this->oreMinuti($totale['tot_ore']);

        return $totale;
    }

    public function getListOperatori($operatori)
    {
      return TableRegistry::get('Aziende.Contatti')->find('list',['keyField' => 'id',
        'valueField' => 'operatore'])->select(['id'=>'id','operatore'=>'CONCAT(Contatti.cognome,SPACE(1),Contatti.nome)'])
        ->order(['operatore'=>'ASC'])->where(['id IN'=>$operatori])->toArray();
    }

    public function getListPersone($people)
    {
      return TableRegistry::get('Progest.People')->find('list',['keyField' => 'id',
        'valueField' => 'persona'])->select(['id'=>'id','persona'=>'CONCAT(People.surname,SPACE(1),People.name)'])
        ->order(['persona'=>'ASC'])->where(['id IN'=>$people])->toArray();
    }

    public function getListOperatoriMonteOre($skills)
    {
      return TableRegistry::get('Aziende.Contatti')->find('list',['keyField' => 'id',
        'valueField' => 'operatore'])->select(['id'=>'Contatti.id',
        'operatore'=>'CONCAT(Contatti.cognome,SPACE(1),Contatti.nome)'])
        ->contain(['SkillsGroup','Aziende'])->group('Contatti.id')->order(['operatore'=>'ASC'])
        ->where(['SkillsGroup.id_skill IN'=>$skills,'Aziende.interno'=>1])->toArray();
    }

    public function oreMinuti($sec)
    {
        $ore = sprintf('%02d', floor($sec/3600));
        $minuti = sprintf('%02d', floor(($sec%3600)/60));
        return $ore.':'.$minuti;
    }
}
