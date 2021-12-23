<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\ORM\TableRegistry;
use Cake\Core\Configure;
use Cake\Log\Log;

class ExcelComponent extends Component
{

   public $objPHPExcel;
   protected $_subColLarge = false;


   public function initialize(array $config)
   {
      $this->objPHPExcel = new \PHPExcel;
   }

   /**
     * Generate the Excel file with the data and the options and output for download.
     * In the options array must be a 'title' and a 'columns'(array) value.
     * Columns type: string, num, date, currency .
     * example:
     * generateExcel($data, array('title'=>'Title', 'filter' => true, 'columns' =>
     * 		'column_name'=>'type' ,'mydate' => 'date','name' => 'string' ). 'bold'=>'true'
     * 		'header' => 'set header')
     * Warning: Not use columns with same name!
     *
     * @param  array $data     array of data
     * @param  array $options   array of options
     * @return [type]          [description]
     */
    public function generateExcel(array $data,array $options = array())
    {

			$colsType = array();
      $r = 1;
      $cols = $this->_generateExcelCols(count($options['columns']));

      $this->objPHPExcel->setActiveSheetIndex(0);
      if(!empty($options['title'])){
          $this->objPHPExcel->getActiveSheet()->setTitle($options['title']);
      }else{
          $options['title'] = 'export_excel';
      }
      $this->objPHPExcel->title = $options['title'];


      if(!empty($options['header'])){
          $this->objPHPExcel->getActiveSheet()->mergeCells('A'.$r.':'.$cols[count($options['columns'])-1].$r);
          $this->objPHPExcel->getActiveSheet()->SetCellValue('A'.$r,$options['header'])->getStyle()
            ->getFont()->applyFromArray(['bold' => true, 'size' => 18]);
          $r++;
      }

      if(!empty($options['columns'])){
        $i = 0;
        foreach($options['columns'] as  $column => $type){
          $this->objPHPExcel->getActiveSheet()->SetCellValue($cols[$i].$r,$column);
					if(!empty($options['bold']) && $options['bold']){
          		$this->objPHPExcel->getActiveSheet()->getStyle( $cols[$i].$r)->getFont()->setBold( true );
					}
          $this->objPHPExcel->getActiveSheet()->getColumnDimension($cols[$i])->setAutoSize(true);
					$colsType[$cols[$i]] = $type;
          $i++;
        }
      }

      if(!empty($options['filter']) && $options['filter']){
        $this->objPHPExcel->getActiveSheet()->setAutoFilter($cols[0].$r.':'.$cols[--$i].$r);
      }
      $r++;
			$this->objPHPExcel->getActiveSheet()->fromArray($data, NULL, 'A'.$r);

			$cells = $this->objPHPExcel->getActiveSheet()->getCellCollection();
			$colsNum = count($colsType);
      if($colsNum > 26){
          $this->_subColLarge = true;
      }
      $colsNum = $colsNum * ($r - 1);
			foreach($cells as $key => $cell){
					if($key >= $colsNum){
							switch ($colsType[$this->_subCol($cell)]) {
								case 'date':
									$this->objPHPExcel->getActiveSheet()->getCell($cell)->setDataType(\PHPExcel_Cell_DataType::TYPE_NUMERIC);
									$this->objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode('dd/mm/yyyy');
									break;
								case 'num':
									$this->objPHPExcel->getActiveSheet()->getCell($cell)->setDataType(\PHPExcel_Cell_DataType::TYPE_NUMERIC);
									break;
								case 'currency':
									$this->objPHPExcel->getActiveSheet()->getCell($cell)->setDataType(\PHPExcel_Cell_DataType::TYPE_NUMERIC);
									$this->objPHPExcel->getActiveSheet()->getStyle($cell)->getNumberFormat()->setFormatCode('#,##0.00');
									break;
								default:
									$this->objPHPExcel->getActiveSheet()->getCell($cell)->setDataType(\PHPExcel_Cell_DataType::TYPE_STRING);
									break;
							}
					}
			}

      return $this->objPHPExcel;
    }

    /**
     * Generate a printable page of table. De options are the same of generateExcel,
     * except for the landscape options, for set the type of print.
     *
     * @param  array  $data    array of data
     * @param  array  $options array of options
     * @return void
     */
    public function printTable(array $data,array $options = array())
    {
        $controller = $this->_registry->getController();
        $controller->set('data', $data);
        $controller->set('columns', $options['columns']);
        $controller->set('title', $options['title']);
        if(!empty($options['landscape'])){
            $controller->set('landscape', $options['landscape']);
        }
        if(!empty($options['header'])){
            $controller->set('header', $options['header']);
        }

        $controller->viewBuilder()->layout('excel');
        $controller->viewBuilder()->templatePath('Excel');
        $controller->viewBuilder()->template('table');

        $controller->render();
    }

    public function save($path)
    {
        $objWriter = new \PHPExcel_Writer_Excel2007($this->objPHPExcel);
        $objWriter->save($path);
    }

    public function download()
    {
        $title = $this->objPHPExcel->title;
        $title = str_replace(' ','_',$title);

        $objWriter = new \PHPExcel_Writer_Excel2007($this->objPHPExcel);

        header("Content-Type: application/xls");
        header('Content-Disposition: attachment;filename="'.$title.'-'.date('d_m_Y').'.xlsx"' );
        header("Pragma: no-cache");

        $objWriter->save('php://output');
        die;
    }

		private function _generateExcelCols($num)
		{
				$colsBase = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O',
						'P','Q','R','S','T','U','V','W','X','Y','Z');
				$cols = $colsBase;

				if($num > count($cols)){

						foreach($cols as $col){
							foreach ($colsBase as $value) {
								 $cols[]=$col.$value;
							}
							if($num <= count($cols)){
								break;
							}
						}
				}

				return $cols;
		}

    private function _subCol(&$col)
    {
        if($this->_subColLarge){
            return preg_replace('/[0-9]+/', '', $col);
        }
        return $col[0];
    }
}
