<?php
namespace ImportData\Controller\Component;

use Cake\Controller\Component;
use Cake\I18n\Time;

class FiltersComponent extends Component
{
	//FILTER LABEL 0
	public function to_integer($value){
		if(is_numeric($value)){
			$newValue = (int)$value;
		}$valueNoSpace = str_replace(' ', '', $value);
		if(is_numeric($valueNoSpace)){
			$newValue = (int)$valueNoSpace;
		}else{
			$dot = strpos($valueNoSpace, '.');
			if(!$dot){
				$valueNoComma = str_replace(',', '.', $valueNoSpace);

				$newValue = (int)$valueNoComma;
			}else{
				$comma = strpos($valueNoSpace, ',');
				if($comma > $dot){
					$valueFinal = str_replace('.', '', $valueNoSpace);
					$valueFinal = str_replace(',', '.', $valueFinal);
				}else{
					$valueFinal = str_replace(',', '', $valueNoSpace);
				}

				$newValue = (int)$valueFinal;
			}
		}

		return $newValue;
	}

	//FILTER LABEL 1
	public function to_decimal($value){
		if(is_numeric($value)){
			$newValue = (float)$value;
		}else{
			$valueNoSpace = str_replace(' ', '', $value);
			if(is_numeric($valueNoSpace)){
				$newValue = (float)$valueNoSpace;
			}else{
				$dot = strpos($valueNoSpace, '.');
				if(!$dot){
					$valueNoComma = str_replace(',', '.', $valueNoSpace);
					if(is_numeric($valueNoComma)){
						$newValue = (float)$valueNoComma;
					}else{
						$newValue = false;
					}
				}else{
					$comma = strpos($valueNoSpace, ',');
					if($comma > $dot){
						$valueFinal = str_replace('.', '', $valueNoSpace);
						$valueFinal = str_replace(',', '.', $valueFinal);
					}else{
						$valueFinal = str_replace(',', '', $valueNoSpace);
					}

					if(is_numeric($valueFinal)){
						$newValue = (float)$valueFinal;
					}else{
						$newValue = false;
					}
				}
			}
		}
		return $newValue;
	}

	//FILTER LABEL 2
	public function add_prefix($value, $prefix){
		$newValue = $prefix.$value;
		return $newValue;
	}

	//FILTER LABEL 3
	public function get_year_from_date($value){
		$timestamp = strtotime($value);

		if($timestamp){
			$date = new Time($value);
			$newValue = $date->year;
		}else{
			$fvalue = str_replace('/', '-', $value);
			$timestamp = strtotime($fvalue);
			if($timestamp){
				$date = new Time($fvalue);
				$newValue = $date->year;
			}else{
				$newValue = false;
			}
		}

		return $newValue;
	}

	//FILTER LABEL 4
	public function convert_data_dmy($value){
		$timestamp = strtotime($value);

		if($timestamp){
			$newValue = date('Y-m-d', $timestamp);
		}else{
			$fvalue = str_replace('/', '-', $value);
			$timestamp = strtotime($fvalue);
			if($timestamp){
				$newValue = date('Y-m-d', $timestamp);
			}else{
				$newValue = false;
			}
		}
		return $newValue;
	}

}
