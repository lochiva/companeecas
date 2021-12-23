<?php

namespace Pmm\Controller\Component;

use Cake\Controller\Component;
use Cake\Event\Event;
use Cake\ORM\TableRegistry;

/**
 * classe SchedeComponent
 * Gestisce i metodi relativi alle schede.
 *
 * @api
 *
 * @category Component
 */
class SchedeComponent extends Component
{
    public $schedeTable;

    /**
     * @api
     *
     * @var - contiene l'istanza del controller
     */
    protected $_controller;

    public function startup(Event $event)
    {
        $this->_controller = $this->_registry->getController();
        $this->schedeTable = TableRegistry::get('Pmm.CrmSchede');
    }

    /**
     * metodo getLibroSociForXls.
     *
     * restituisce un array di adesioni nel formato richiesto da tablesorter
     *
     * @api
     *
     * @author Rafael Esposito
     *
     * @return array
     */
    public function getLibroSociForXls()
    {
        try {
            $libroSoci = $this->schedeTable->getLibroSoci();

          //echo "<pre>";print_r($libroSoci);die;

          if (!empty($libroSoci)) {
              $objPHPExcel = new \PHPExcel();
              $objPHPExcel->setActiveSheetIndex(0);

              $objPHPExcel->getActiveSheet()->SetCellValue('A1', 'RAGIONE SOCIALE');
              $objPHPExcel->getActiveSheet()->SetCellValue('B1', 'INDIRIZZO');
              $objPHPExcel->getActiveSheet()->SetCellValue('C1', 'CITTA\'');
              $objPHPExcel->getActiveSheet()->SetCellValue('D1', 'PR');
              $objPHPExcel->getActiveSheet()->SetCellValue('E1', 'DATA Adesione');
              $objPHPExcel->getActiveSheet()->SetCellValue('F1', 'P. IVA');
              $objPHPExcel->getActiveSheet()->SetCellValue('G1', 'COD. FISCALE');

        // filtri
        $objPHPExcel->getActiveSheet()->setAutoFilter('A1:G1');

        // Autosize delle celle
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
              $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
              $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
              $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
              $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
              $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
              $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);

              $objPHPExcel->getActiveSheet()->fromArray($libroSoci, null, 'A2');

            // Setto per ogni cella dell'excel il tipo di dato a stringa.
            $cells = $objPHPExcel->getActiveSheet()->getCellCollection();
              foreach ($cells as $cell) {
                  $objPHPExcel->getActiveSheet()->getCell($cell)->setDataType(\PHPExcel_Cell_DataType::TYPE_STRING);
              }

              $objWriter = new \PHPExcel_Writer_Excel2007($objPHPExcel);

              header('Content-Type: application/xls');
              header('Content-Disposition: attachment; filename=libro_soci_'.date('d_m_Y').'.xlsx');
              header('Pragma: no-cache');

              $objWriter->save('php://output');
            //$objWriter->save('/Applications/XAMPP/htdocs/tmp/'."libro_soci_". date('d_m_Y') .".xlsx");
          } else {
              throw new Exception('Errore durante la lettura delle adesioni');
          }
        } catch (Exception $e) {
            die('Impossibile generare il file xls.');
        }
    }

    /******************************************************* FUNZIONI PRIVATE *******************************************************************************/
}
