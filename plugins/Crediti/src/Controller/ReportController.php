<?php
/**
* Crediti is a plugin for manage attachment
*
* Companee :    Report  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
namespace Crediti\Controller;

use Crediti\Controller\AppController;
use Cake\Event\Event;

require_once(ROOT . DS . 'vendor' . DS  . 'phpoffice' . DS . 'phpexcel' . DS . 'Classes' . DS . 'PHPExcel.php');
require_once(ROOT . DS . 'vendor' . DS  . 'phpoffice' . DS . 'phpexcel' . DS . 'Classes' . DS . 'PHPExcel'. DS . 'Writer'. DS . 'Excel2007.php');

/**
 * Report Controller
 *
 * @author Rafael Esposito
 * @property \Crediti\Model\Table\TotalsCreditsTable $TotalsCredits
 */
class ReportController extends AppController
{
    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('Crediti.Credit');

    }
    /**
     * gestione method
     *
     * @param bool $xls in caso di esportazione excel
     * @return void
     */
    public function gestione($xls=false)
    {
      if($xls){

        $out = $this->Credit->getCreditsTotals(true);

        $this->autoRender = false;
  			$this->layout = 'xls';
  			$objPHPExcel = new \PHPExcel;

  			$objPHPExcel = new \PHPExcel();
  			$objPHPExcel->setActiveSheetIndex(0);


  			$objPHPExcel->getActiveSheet()->SetCellValue('A1', 'Data');
  			$objPHPExcel->getActiveSheet()->SetCellValue('B1', 'Socio di Riferimento');
  			$objPHPExcel->getActiveSheet()->SetCellValue('C1', 'Famiglia');
  			$objPHPExcel->getActiveSheet()->SetCellValue('D1', 'Codice Cliente');
  			$objPHPExcel->getActiveSheet()->SetCellValue('E1', 'Ragione Sociale');
  			$objPHPExcel->getActiveSheet()->SetCellValue('F1', 'Totale crediti');
        $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'Totale crediti scaduti');
        $objPHPExcel->getActiveSheet()->SetCellValue('H1', 'Rating');
        $objPHPExcel->getActiveSheet()->SetCellValue('I1', 'Notificato');

  			// filtri
  			$objPHPExcel->getActiveSheet()->setAutoFilter('A1:I1');

  			$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
          	$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
  			$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
          	$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
  			$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
  					$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
          	$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
          	$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
        	$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);


        $objPHPExcel->getActiveSheet()->fromArray($out['rows'], NULL, 'A2');

        $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

        $filename = "report-CREDITI-DEL-".date("Y-m-d_H-i");

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'.xlsx"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
        //$objWriter->save('/Applications/XAMPP/htdocs/tmp/'.$filename.'.xlsx');

      }else{
        $user = $this->request->session()->read('Auth.User');
        $this->set('operatore',$user['nome'].' '.$user['cognome']);
      }

    }
   /**
    * ElencoCrediti method
    *
    * @return void
    */
    public function elencoCrediti()
    {
      $sum = $this->Credit->getCreditsSum();

      $this->set('sommaCrediti',$sum);
    }

}
