<?php
	require_once 'fidu/JsonData.php';
	require_once 'fidu/ExcelData.php';
	require_once 'fidu/Data2HTML.php';
	require_once 'fidu/Common.php';
	require_once 'fidu/FiduXML.php';
	header('Content-Type: text/html; charset=UTF-8');

	if(isset($_POST['action'])){
		JsonToHTML( );
	}else{
		ExcelToHTML();
	}
	
	function JsonToHTML(){
		$table  = $_POST['table'];
		$jsondata = new JsonData($table);
		$dataToHtml = new Data2HTML( $jsondata);
		
		$fiduXml = new FiduXML();
		$fiduXml->editItem($_POST['moduleId'], $_POST['moduleName'], json_encode($jsondata -> getSheet('config')));
		
		echo $dataToHtml->toHTML();
	}
	function ExcelToHTML(){
		$file_path = dirname(dirname(dirname(__FILE__))).'/wp-content/uploads/excel/'.$_GET['filename'];
		$exceldata = new ExcelData($file_path);
		$dataToHtml = new Data2HTML( $exceldata); 
		$type = $_GET['type']; 
		if( isset($type) && $type=='html' ){
			echo $dataToHtml->toHTML();
		}else{
			echo $dataToHtml->toJson();
		}
	}
	
?>