<?php
namespace ReminderManager\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Routing\Router;

class SispacComponent extends Component
{

  public function getSispacFromFileName($filename){

    $a = substr($filename,0,-4);

    $codSispac = $a;

    return $codSispac;

  }


}
