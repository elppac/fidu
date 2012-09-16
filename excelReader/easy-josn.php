<?php
	require_once 'Excel/reader.php';
	$data = new Spreadsheet_Excel_Reader();
	$data->setOutputEncoding('utf-8');
	$data->read('test.xls');
	error_reporting(E_ALL ^ E_NOTICE);
	
	function getColKeys( $sheet ){
		$arr = array();
		for( $i = 1; $i <= $sheet['numCols']; $i++ ){
			array_push( $arr ,$sheet['cells'][1][$i] );
		}
		return $arr;
	}
	function getColIndex( $sheet, $colName ){
		$arr = getColKeys($sheet);
		$index = -1;
		for( $i = 0; $i <= count( $arr ); $i++ ){
			if( $arr[$i] == $colName ){
				$index = $i+1;
				break;
			}
		}
		return $index;
	}
	function getCol( $sheet, $colName, $line ) {
		$index = getColIndex( $sheet, $colName );
		if( $index == 0 ){
			return '';
		}
		return $sheet['cells'][$line][$index];
	}
	
	function getConfig( $sheet, $colName ){
		return getCol( $sheet, $colName, 2 );
	}
	
	$dataTabel = $data -> sheets[0]['cells'];
	$dataSheet = $data -> sheets[0];
	$config = $data -> sheets[1];
	
	$countCols = $dataSheet['numCols'];
	$countRows = $dataSheet['numRows'];
	$arr	= array();
	for($i = 2; $i <= $countRows; $i++ ){
		$arrCols = array();
		for($j = 1; $j <= $countCols; $j++){
			 $arrCols[$dataTabel[1][$j]] = $dataTabel[$i][$j];
		};
		array_push($arr, $arrCols);
	}
	$jsonstr = json_encode($arr);
	//echo $jsonstr;
	
	$itemTemplate = getConfig($config, 'itemTemplate');
	$colKeysArr = getColKeys( $dataSheet );
	$output = $itemTemplate;
	foreach($colKeysArr as $value)
	{
		if( strpos($itemTemplate, $value) ){
			$output = str_replace( '{{'.$value.'}}', getCol($dataSheet,$value,2),$output );
		}
	}
	
	echo $output;
?>