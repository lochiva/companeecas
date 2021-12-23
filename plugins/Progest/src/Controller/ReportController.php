<?php
namespace Progest\Controller;

use Progest\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;
/**
 * Report Controller
 *
 */
class ReportController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Progest.Report');
        $this->set('title', 'Report');
    }

    public function index()
    {
      $gruppi = TableRegistry::get('Aziende.AziendeGruppi')->getList();
      $services = TableRegistry::get('Progest.Services')->getListCat(1);

      $this->set('services',$services);
      $this->set('gruppi',$gruppi);
    }

    public function reportBirthdays($month = 0,$gruppo = 0,$xls = false)
    {
        $this->Report->reportBirthdays($month,$gruppo,$xls);
    }

    public function reportIndirizzario($gruppo = 0,$servizio = 0,$xls = false)
    {
        $this->Report->reportIndirizzario($gruppo,$servizio,$xls);
    }

    /*public function reportAge($date = 0,$xls = false)
    {
        $this->Report->reportAge();
    }*/
}
