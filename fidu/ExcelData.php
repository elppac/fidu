<?php
	require_once 'excelReader/Excel/reader.php';
	class ExcelData{
		var $database;
		var $sheets = array();
		var $config = array();
		
		
		function ExcelData($path){
			if( !file_exists( $path ) ){
				echo '文件未找到';
				return;
			}
			 
			$this->database = new Spreadsheet_Excel_Reader();
			$this->database->setOutputEncoding('utf-8');
			$this->database->read( $path );
			
			$this->sheets = $this->database->sheets;
			
			error_reporting(E_ALL ^ E_NOTICE);
		}
		
		/**
		* get config by key
		* @key key
		* return string
		*/
		function getConfig( $key ){
			if( count($this->config)==0 ){
				$sheet = $this->getSheet('config');
				$table = $sheet['cells'];
				$numRows = $sheet['numRows'];
				for( $i = 1; $i <= $numRows; $i ++){
					$this->config[$table[$i][1]] = $table[$i][2];
				}
			}
			
			return $this->config[$key];
		}
		
		/**
		* get sheet by sheet name
		* @sheetName sheet name
		* return sheet or null
		*/
		function getSheet( $sheetName ){
			$i = 0;
			foreach( $this->database->boundsheets as $obj){
				if( $obj['name'] == $sheetName ){
					return $this->sheets[$i];
				}
				$i = $i+1;
			}
			return null;
		}
		
		/**
		* get table keys by sheet
		* @sheet sheet 
		* return array
		*/
		function getTableKeys( $sheet ){
			$arr = array();
			for( $i = 1; $i <= $sheet['numCols']; $i++ ){
				array_push( $arr ,$sheet['cells'][1][$i] );
			}
			return $arr;
		}
		
		/**
		* get row
		* @sheet sheet
		* @line row number
		* return array
		*/
		function getRow( $sheet, $line ){
			$arr = array();
			$keys = $this->getTableKeys( $sheet );
			for( $i = 1; $i <= $sheet['numCols']; $i++ ){
				$arr[$keys[$i-1]] = $sheet['cells'][$line+1][$i];
			}
			return $arr;
		}
		
		function numRows( $sheet ){
			return $sheet['numRows'];
		}
		
		function pageCount( $sheet, $pageSize ){
			return ceil( ($this->numRows( $sheet )-1) / $pageSize );
		}
	}
?>