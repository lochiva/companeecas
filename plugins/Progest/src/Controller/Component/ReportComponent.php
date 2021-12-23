<?php

namespace Progest\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;

class ReportComponent extends Component
{
    public $components = ['Excel'];

    public function initialize(array $config)
    {
        parent::initialize($config);
    }

    public function reportBirthdays($month = 0, $gruppo = 0, $xls = false)
    {
        $people = TableRegistry::get('Progest.People')->birthdays($month, $gruppo);
        $gruppo = TableRegistry::get('Aziende.AziendeGruppi')->find()->where(['id'=>$gruppo])->first();
        $data = array();

        $dateObj   = \DateTime::createFromFormat('!m', $month);
        $monthName = __($dateObj->format('F'));

        $title = 'Report Compleanni - '.$monthName;
        if (!empty($gruppo)) {
            $title.= ', '.$gruppo->name;
        }

        $opt = array('title' => $title, 'filter' => true,
          'columns' => ['N° ' => 'num', 'Cognome' => 'string', 'Nome' => 'string', 'Data di nascita' => 'date'], );
        foreach ($people as $key => $person) {
            $data[$key][] = ($key + 1);
            $data[$key][] = $person['surname'];
            $data[$key][] = $person['name'];
            if (!empty($person['birthdate'])) {
                if ($xls) {
                    $data[$key][] = \PHPExcel_Shared_Date::PHPToExcel($person['birthdate']);
                } else {
                    $data[$key][] = $person['birthdate']->i18nFormat('dd/MM/yyyy');
                }
            } else {
                $data[$key][] = '';
            }
        }

        if ($xls) {
            $this->Excel->generateExcel($data, $opt);
            $this->Excel->download();
        } else {
            $this->Excel->printTable($data, $opt);
        }
    }

    public function reportIndirizzario($gruppo = 0, $servizio = 0, $xls = false)
    {
        $res = TableRegistry::get('Progest.People')->reportIndirizzario($gruppo, $servizio);
        $gruppo = TableRegistry::get('Aziende.AziendeGruppi')->find()->where(['id'=>$gruppo])->first();
        $servizio = TableRegistry::get('Progest.Services')->find()->where(['id'=>$servizio])->first();
        $header = 'Report indirizzario - ';
        if (!empty($gruppo)) {
            $header.= $gruppo->name;
        }
        if (!empty($servizio)) {
            $header.= ', '.$servizio->name;
        }
        $data = array();
        $num = 0;

        foreach ($res as $key => $person) {
            ++$num;
            $newData = [$num, $person['surname'], $person['name']];

            if (!empty($person['extension'])) {
                $fullAddress = $person['extension']['address'].' '.$person['extension']['cap']
                    .' '.$person['extension']['comune'].' '.$person['extension']['provincia'];
                $extension = [trim($fullAddress), $person['extension']['tel'], $person['extension']['cell']];
            } else {
                $extension = ['', '', ''];
            }
            $newData = array_merge($newData, $extension);
            $data[] = array_merge($newData, ['', '', '', '', '']);
            foreach ($person['familiari'] as $familiare) {
                $data[] = [
                    $num, '', '', '', '', '',
                    $familiare['grado_parentela']['name'],
                    $familiare['name'],
                    $familiare['surname'],
                    $familiare['tel'],
                    $familiare['cell'],
                  ];
            }
        }
        $opt = array('title' => 'Report indirizzario', 'filter' => true, 'header' => $header, //'landscape' => true,
            'columns' => [
              'N°' => 'num', 'Cognome' => 'string', 'Nome' => 'string', 'Indirizzo completo' => 'string',
              'Telefono' => 'string', 'Cellulare' => 'string', 'Parentela' => 'string', 'Cognome Familiare' => 'string',
              'Nome Familiare' => 'string', 'Telefono Familiare' => 'string', 'Cellulare Familiare' => 'string',
              ]);

        if ($xls) {
            $this->Excel->generateExcel($data, $opt);
            $this->Excel->download();
        } else {
            $this->Excel->printTable($data, $opt);
        }
    }

    public function reportAge()
    {
        $peopleTable = TableRegistry::get('Progest.People');
        $data = array();
        //debug($peopleTable->reportAgeGender());die;
    }
}
