<?php

namespace App\Librarys;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Cell;

class Excel{
	
	public function __construct(){
		
	}
	
	public function loadExcel(){
		return new PHPExcel();
	}
	
	public function read($filename,$encode='utf-8'){
		$extend = pathinfo($filename);
		$extend = strtolower($extend["extension"]);
		if(strtolower($extend) == 'xlsx'){
			$objReader = PHPExcel_IOFactory::createReader('Excel2007');
		}else{
			$objReader = PHPExcel_IOFactory::createReader('Excel5');
		}
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($filename);
		$objWorksheet = $objPHPExcel->getActiveSheet();
		$highestRow = $objWorksheet->getHighestRow();
		$highestColumn = $objWorksheet->getHighestColumn();
		$highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
		$excelData = array();
		for ($row = 1; $row <= $highestRow; $row++) {
			for ($col = 0; $col < $highestColumnIndex; $col++) {
				$excelData[$row][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getFormattedValue();
			}
		}
		return $excelData;
	}   
	
	function getTime($val){
		if($val>0){
			return ($val - 25569) * 24*60*60;
		}else{
			return false;
		}
	}
	
	function getDate($val){
		if($val>0){
			$time = $this->getTime($val);
			return date("Y-m-d",$time);
		}else{
			return false;
		}
	}

}

?>