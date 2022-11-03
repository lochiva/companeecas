<?php

namespace Surveys\View\Helper;

use Cake\View\Helper;
use Cake\Core\Configure;

class SurveyHelper extends Helper
{
    public $helpers = ['Html'];

	public function printSectionSurvey($parentItems, $section, $index, $layout, $dataSheetsInfo, $activeComponents, $dimensions)
	{
		$html = '';

		// Se non è una sezione primaria, visibile solo se semrpe visibile o se attivo il componente indicato
		if ($section->primary || (!$section->visibility->visibility_by_component || $this->isComponentActive($activeComponents, $section->visibility->components))) {

			/* 
			if ($section->primary) {
				if ($section->title) {
					$html .= '<h2 style="text-align: center;">'.$this->computeNumberLabel($parentItems, $index).$section->title.'</h2>';
				}
				if ($section->subtitle) {
					$html .= '<h3 style="text-align: center;">'.$section->subtitle.'</h3>';
				}
			}
			*/

			if ($layout == 'single') {
				//LAYOUT SINGOLA COLONNA
				$html .= '<table width="100%" style="border-spacing: 0px;table-layout: fixed;">';
				$html .= '<tr style="page-break-inside: none;">';
				$html .= '<td style="padding: 10px;">';

				/* 				
				if (!$section->primary) {
					if ($section->title) {
						$html .= '<h2 style="text-align: center;">'.$this->computeNumberLabel($parentItems, $index).$section->title.'</h2>';
					}
					if ($section->subtitle) {
						$html .= '<h3 style="text-align: center;">'.$section->subtitle.'</h3>';
					}
				} 
				*/
		
				foreach ($section->questions as $key => $question) {

					if ($question->visible) {
				
						//TESTO FISSO
						if ($question->type == 'fixed_text') { 
							$html .= '<span>'.$question->value_to_show.'</span>';
						}
				
						//IMMAGINE
						if ($question->type == 'image' && $question->path != '') { 
							$basePath = WWW_ROOT.Configure::read('dbconfig.surveys.SURVEYS_IMAGE_BASE_PATH');
							$type = pathinfo($basePath.$question->path, PATHINFO_EXTENSION);
							$image = file_get_contents($basePath.$question->path);
							$html .= '<div style="width: 100%;text-align: center;">';
							$html .= '<img src="data:image/' . $type . ';base64,' . base64_encode($image) . '" style="width: 100%;"><br>';
							$html .= '<span>'.$question->caption.'</span>';
							$html .= '</div>';
						} 

						//RISPOSTA EDITOR DI TESTO
						if($question->type == 'answer_text_editor'){
							//$html .= '<span>'.$question->answer.'</span>';
							$html .= '<span>'.$question->value_to_show.'</span>';
						}

						//SCHEDA TECNICA
						if (
							$question->type == 'data_sheet' && 
							array_key_exists($question->data_sheet->id, $dataSheetsInfo) && 
							(!$question->visibility_by_component || $this->isComponentActive($activeComponents, $question->components))
						) {
							$html .= '<div>';
							$html .= '<span style="font-weight: bold;font-style: italic;text-decoration: underline;">'.$dataSheetsInfo[$question->data_sheet->id]->title.'</span>: ';
							$html .= '<span>'.$dataSheetsInfo[$question->data_sheet->id]->content.'</span>';
							$html .= '<span style="color: #92C45A;">'.$dataSheetsInfo[$question->data_sheet->id]->specifications.'</span>';
							$html .= '</div>';
							foreach ($dataSheetsInfo[$question->data_sheet->id]->images as $image) {
								$html .= '<div style="width: 100%;text-align: center;">';
								$html .= '<img src="data:'.$image['type'].';base64, '.$image['content'].'" style="width: 100%;">';
								$html .= '</div>';
							}
						}

						//MISURE
						if ($question->type == 'dimensions') {
							$html .= '<div style="width: 100%;text-align: center;">';
							$html .= '<div style="display: inline-block;border: 1px solid #92C45A;padding: 1px;">';
							$html .= '<table style="border: 1px solid #92C45A;font-weight: bold;">';
							foreach ($dimensions as $dimension) {
								$html .= '<tr style="page-break-inside: auto;">';
								$html .= '<td style="padding: 5px 25px;">Totale '.$dimension->label.'</td>';
								$html .= '<td style="padding: 5px 25px;">'.$dimension->value.'</td>';
								$html .= '</tr>';
							}
							$html .= '</table>';
							$html .= '</div>';
							$html .= '</div>';
						}

						//SALTO PAGINA
						if ($question->type == 'page_break') {
							$html .= '<div style="clear: both;page-break-after: always;"></div>';
						}
					}
				}

				$html .= '</td>';
				$html .= '</tr>';
				$html .= '</table>';

			} elseif ($layout == 'double') {
				if (
					!empty($section->left_column) ||
					!empty($section->right_column) ||
					(!$section->primary && (!empty($section->title) || !empty($section->subtitle)))
				) {
					//LAYOUT DOPPIA COLONNA
					$html .= '<table width="100%" style="border-spacing: 0px;table-layout: fixed;">';
					$html .= '<tr style="page-break-inside: auto;">';

					//COLONNA SINISTRA
					$html .= '<td width="35%" valign="top" style="border-right: 1px solid #92C45A;padding: 10px;">';

					foreach ($section->left_column as $key => $question) {
				
						if ($question->visible) {

							//IMMAGINE
							if ($question->type == 'image' && $question->path != '') { 
								$basePath = WWW_ROOT.Configure::read('dbconfig.surveys.SURVEYS_IMAGE_BASE_PATH');
								$type = pathinfo($basePath.$question->path, PATHINFO_EXTENSION);
								$image = file_get_contents($basePath.$question->path);
								$html .= '<div style="width: 100%;text-align: center;">';
								$html .= '<img src="data:image/' . $type . ';base64,' . base64_encode($image) . '" style="width: 100%;"><br>';
								$html .= '<span>'.$question->caption.'</span>';
								$html .= '</div>';
							}

							//IMMAGINI SCHEDA TECNICA
							if ($question->type == 'data_sheet_images' &&
								array_key_exists($question->data_sheet_id, $dataSheetsInfo) && 
								(!$question->visibility_by_component || $this->isComponentActive($activeComponents, $question->components))
							) { 
								foreach ($dataSheetsInfo[$question->data_sheet_id]->images as $image) {
									$html .= '<div style="width: 100%;text-align: center;">';
									$html .= '<img src="data:'.$image['type'].';base64, '.$image['content'].'" style="width: 100%;">';
									$html .= '</div>';
								}
							}
						}
					}

					$html .= '</td>';

					//COLONNA DESTRA
					$html .= '<td width="65%" valign="top" style="border-left: 2px solid #92C45A;padding: 10px;">';
					/* 
					if (!$section->primary) {
						if ($section->title) {
							$html .= '<h2 style="text-align: center;">'.$this->computeNumberLabel($parentItems, $index).$section->title.'</h2>';
						}
						if ($section->subtitle) {
							$html .= '<h3 style="text-align: center;">'.$section->subtitle.'</h3>';
						}
					}
 					*/
					foreach ($section->right_column as $key => $question) {
				
						if ($question->visible) {
					
							//TESTO FISSO
							if ($question->type == 'fixed_text') { 
								$html .= '<span>'.$question->value_to_show.'</span>';
							}

							//RISPOSTA EDITOR DI TESTO
							if ($question->type == 'answer_text_editor') {
								$html .= '<span>'.$question->answer.'</span>';
							} 

							//SCHEDA TECNICA
							if (
								$question->type == 'data_sheet' && 
								array_key_exists($question->data_sheet->id, $dataSheetsInfo) && 
								(!$question->visibility_by_component || $this->isComponentActive($activeComponents, $question->components))
							) {
								$html .= '<div>';
								$html .= '<span style="font-weight: bold;font-style: italic;text-decoration: underline;">'.$dataSheetsInfo[$question->data_sheet->id]->title.'</span>: ';
								$html .= '<span>'.$dataSheetsInfo[$question->data_sheet->id]->content.'</span>';
								$html .= '<span style="color: #92C45A;">'.$dataSheetsInfo[$question->data_sheet->id]->specifications.'</span><br>';
								$html .= '</div>';
							}

							//MISURE
							if ($question->type == 'dimensions') {
								$html .= '<div style="width: 100%;text-align: center;">';
								$html .= '<div style="display: inline-block;border: 1px solid #92C45A;padding: 1px;">';
								$html .= '<table style="border: 1px solid #92C45A;font-weight: bold;">';
								foreach ($dimensions as $dimension) {
									$html .= '<tr style="page-break-inside: auto;">';
									$html .= '<td style="padding: 5px 25px;">Totale '.$dimension->label.'</td>';
									$html .= '<td style="padding: 5px 25px;">'.$dimension->value.'</td>';
									$html .= '</tr>';
								}
								$html .= '</table>';
								$html .= '</div>';
								$html .= '</div>';
							}

							//SALTO PAGINA
							if ($question->type == 'page_break') {
								$html .= '<div style="clear: both;page-break-after: always;"></div>';
							}
						}
					}

					$html .= '</td>';
					$html .= '</tr>';
					$html .= '</table>';
				}
			}
		
			foreach ($section->items as $subIndex => $item) {
				$html .= $this->printSectionSurvey($section->items, $item, $subIndex, $layout, $dataSheetsInfo, $activeComponents, $dimensions);
			}
		}
	
		return $html;
	}

	private function computeNumberLabel($items, $index) {
		$excludedItems = 0;
		$number = '';
		if (!$items[$index]->primary && count($items[$index]->questions) > 0) {
			for($i = 0; $i <= $index; $i++) {
				if (count($items[$i]->questions) == 0) {
					$excludedItems++;
				}
			}
			$number = (($index + 1) - $excludedItems) . '. ';
		}

		return $number;
	}

	private function isComponentActive($activeComponents, $questionComponents) {
		$componentIds = [];
		foreach ($questionComponents as $component) {
			$componentIds[] = $component->id;
		}

		return array_intersect($componentIds, $activeComponents);
	}
}
