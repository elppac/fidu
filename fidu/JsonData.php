<?php
	class JsonData{
		var $database;
		var $sheets = array();
		var $config = array();
		
		
		function JsonData($string){
			$this->database = json_decode( str_replace('\\"','"',$string) , false );
			$this->sheets = $this->database->sheets;
		}
		
		/**
		* get config by key
		* @key key
		* return string
		*/
		function getConfig( $key ){
			if( count($this->config)==0 ){ 
				$sheet = $this->getSheet('config');
				$numRows = $this->numRows($sheet);
				for( $i = 0; $i < $numRows; $i ++){
					$this->config[$sheet[$i][0]] = $sheet[$i][1];
				}
			}
			//return $this->config[$key];
			return $this->code2Html($this->config[$key]);
		}
		
		/**
		* get sheet by sheet name
		* @sheetName sheet name
		* return sheet or null
		*/
		function getSheet( $sheetName ){
			$tablenameArray = $this->database->boundsheets; 
			for( $i = 0; $i < count($tablenameArray); $i++ ){
				if( $tablenameArray[$i] == $sheetName ){
					return $this->sheets[$i];
				}
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
			for( $i = 0; $i < count( $sheet[0] ); $i++ ){
				array_push( $arr ,$sheet[0][$i] );
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
			for( $i = 0; $i < count($sheet[0]); $i++ ){
				$arr[$keys[$i]] = $sheet[$line][$i];
			}
			return $arr;
		}
		
		function numRows( $sheet ){
			return count($sheet);
		}
		
		function pageCount( $sheet, $pageSize ){
			return ceil( $this->numRows( $sheet ) / $pageSize );
		}
		
		function code2Html($code){
			$code = preg_replace(
				array( 
					"'&lt;'",
					"'&gt;'",
					"'&quot;'",
					"'\s'",  //移掉多余空隔
					//"'/'", //转译'/'
					"'>\s<'"//移掉标签之间的空隔
				),
				array(
					'<',
					'>',
					'"',
					' ',
					//'\\/',
					'><'
				),
				$code);
			return str_replace('\n','',$code);
		}
	}
?>