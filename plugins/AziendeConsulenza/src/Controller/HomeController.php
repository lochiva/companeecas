<?php
namespace Aziende\Controller;

use Aziende\Controller\AppController;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

require_once(ROOT . DS . 'vendor' . DS  . 'phpoffice' . DS . 'phpexcel' . DS . 'Classes' . DS . 'PHPExcel.php');
require_once(ROOT . DS . 'vendor' . DS  . 'phpoffice' . DS . 'phpexcel' . DS . 'Classes' . DS . 'PHPExcel'. DS . 'Writer'. DS . 'Excel2007.php');


/**
 * Aziende Controller
 *
 * @property \Aziende\Model\Table\AziendeTable $Aziende
 */
class HomeController extends AppController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Aziende.Azienda');
        $this->loadComponent('Aziende.Sedi');
        $this->loadComponent('Aziende.Contatti');
        $this->loadComponent('Csrf');
        $this->loadComponent('Crediti.Credit');
    }

    public function beforeFilter(Event $event)
    {
        parent::beforeFilter($event);
        //$this->Auth->allow(['index','info']);

    }

    /**
     * Index method
     *
     * @return void
     */
    public function index($xls=false)
    {
        // export xls
        if($xls){
            $aziende = $this->Azienda->getAziendeXls();

            $this->autoRender = false;
            $this->layout = 'xls';
            $objPHPExcel = new \PHPExcel;

            $objPHPExcel = new \PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);


            $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Denominazione');
            $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Nome');
            $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Cognome');
            $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Famiglia');
            $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Telefono');
            $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Fax');
            $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Email Info');
            $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Email Contabilità');
            $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Email Solleciti');
            $objPHPExcel->getActiveSheet()->SetCellValue('J1', 'Codice Paese');
            $objPHPExcel->getActiveSheet()->SetCellValue('K1', 'Partita Iva');
            $objPHPExcel->getActiveSheet()->SetCellValue('L1', 'Codice Fiscale');
            $objPHPExcel->getActiveSheet()->SetCellValue('M1', 'Codice Sispac');

            // filtri
            $objPHPExcel->getActiveSheet()->setAutoFilter('A1:M1');

            $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
            $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);


            $objPHPExcel->getActiveSheet()->fromArray($aziende, NULL, 'A2');

            $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

            $filename = "elenco_clienti-".date('H-m-s');

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
            header('Cache-Control: max-age=0');
            $objWriter->save('php://output');
            die();
        }

    }

    public function info($idAzienda = 0){

        if($idAzienda != 0){

            ################################################################################
            //Recupero i dati dell'azienda
            $azienda = $this->Azienda->_get($idAzienda);

            ################################################################################
            //recupero le sedi
            $pass['idAzienda'] = $idAzienda;
            $sedi = $this->Sedi->getSedi($pass);

            //echo "<pre>"; print_r($sedi); echo "</pre>";

            ################################################################################
            //recupero i contatti
            $pass['id'] = $idAzienda;
            $pass['tipo'] = 'azienda';

            $contatti = $this->Contatti->getContatti($pass);
            #######################################################################
            // recupero rating azienda
            $rating = $this->Credit->getCurrentRatingAzienda($idAzienda);

            $this->set('azienda',$azienda);
            $this->set('sedi',$sedi);
            $this->set('contatti',$contatti);
            $this->set('idAzienda',$idAzienda);
            $this->set('rating',$rating);

        }else{
            $this->redirect('/aziende');
        }

    }

}
