<?php
	class Data2HTML{
		var $excel;
		/**
			$projects --> items --> type[list]
															--> listTemplate
															--> listTemplateName
															--> listTemplateText
															--> itemTemplate
															--> itemTemplateName
															--> itemTemplateText
															--> pageSize
															--> fistCss
															--> lastCss
															--> sheetName
															
								--> items --> type[panel]
															--> template
															--> sheetName
		*/
		var $projects = array();
		
		function Data2HTML($data){
			/*if( unset( $data ) ){
				echo '文件未找到';
				return;
			}*/
			$this->excel = $data;
			
			$this->_initProjects();
		}
		
		function _analyzeName( $templateText ){
			$templateName = str_replace( '##','',$templateText );
			$arr = split('-',$templateName);
			$retval = array();
			$sheetName = $arr[0];
			return array(
				'templateName' => $templateName,
				'templateText' => $templateText,
				'sheetName' => $arr[0],
				'type' => $arr[1],
				'name' => $arr[count($arr)-1]
			);
		}
		
		function _findMainKeyword( $templateName ){
			$template = $this->excel->getConfig($templateName);
			$arr = array();
			if(preg_match_all("/##.*?##/",$template,$matches)){
				$arr = $matches[0];
			}
			return $arr;;
		}
		function _getArrayValue($array, $key, $defaultVal){
			$value = $defaultVal;
			if(array_key_exists($array['sheetName'].'-'.$key, $this->excel->config)){
				$value = $this->excel->config[$array['sheetName'].'-'.$key];
			}else{
				if( array_key_exists( $key, $this->excel->config ) ){
					$value = $this->excel->config[ $key ];
				}
			}
			return $value;
		}
		function _initProjects(){
			$items = array();
			foreach( $this->_findMainKeyword('main') as $value ){
				$analyze = $this->_analyzeName( $value );
				//解决 template 共用，数据表名只存在main的关键字中。
				$analyze['templateName'] = str_replace($analyze['sheetName'].'-','',$analyze['templateName']);
				//print_r(str_replace($analyze['sheetName'].'-','',$analyze['templateName']));
				if( $analyze['type'] == 'list' ){
					$firstKeywords = $this->_findMainKeyword($analyze['templateName']);
					$firstKeyword = $firstKeywords[0];
					$firstAnalyze = $this->_analyzeName( $firstKeyword );
					
					$pageSize = $this->_getArrayValue( $analyze, 'page-size', 10000);
					$fistCss = $this->_getArrayValue( $analyze, 'first-css', 'first');
					$lastCss = $this->_getArrayValue( $analyze, 'last-css', 'last');	
					
					
					
					array_push( $items ,array(
						'type' => $analyze['type'],
						'listTemplate' => $this->excel->getConfig( $analyze['templateName'] ),
						'listTemplateName' => $analyze['templateName'],
						'listTemplateText' => $analyze['templateText'],
						'itemTemplate' => $this->excel->getConfig( $firstAnalyze['templateName'] ),
						'itemTemplateName' => $firstAnalyze['templateName'],
						'itemTemplateText' => $firstAnalyze['templateText'],
						'pageSize' => $pageSize,
						'fistCss' => $fistCss,
						'lastCss' => $lastCss,
						'sheetName' => $analyze['sheetName'],
						'name' => $analyze['name']
					));
				}else if( $analyze[type] == 'panel' ){
					
				}
			}
			
			$this->projects = array(
				'items' => $items,
				'mainTemplate' => $this->excel->getConfig( 'main' )
			);
		}
			
		function _itemToHTML( $item ){
			$itemTemplate = $item['itemTemplate'];
			$sheet = $this->excel->getSheet($item['sheetName']);
			$countRows = $this->excel->numRows( $sheet );
			$itemArr = array();
			for($i = 1; $i < $countRows; $i++ ){
				$tempItem = $itemTemplate;
				$row = $this->excel->getRow( $sheet, $i );
				foreach($row as $key => $value){
					if( strpos($itemTemplate, $key) ){
						$tempItem = str_replace( '{{'.$key.'}}',$value ,$tempItem );
					}
				}
				array_push( $itemArr, $tempItem );
			}
			
			return $itemArr;
		}
		
		function _listToHTML( $itemArray, $item ){
			//单页显示数量
			$pageSize = $item['pageSize'];
			$sheet = $this->excel->getSheet($item['sheetName']);
			//页数
			$pageCount = $this->excel->pageCount( $sheet,$pageSize );
			$listArr = array();  
			for( $i = 0 ; $i< $pageCount; $i++){
				$listTemplate = $item['listTemplate'];
				$max = ($i + 1) * $pageSize; 
				if( $max > count( $itemArray ) ){
					$max = count( $itemArray );
				}
				$pageItemArr = array_slice( $itemArray,$i * $pageSize,$max ); 
				
					
				if( count($pageItemArr) > 0 && strpos($pageItemArr[0], '$$first-last$$') ){
					$count = count($pageItemArr);
					for( $j = 0; $j < $count; $j++ ){
						$str = '';
						if( $j ==0){
							$str = $item['fistCss'];
						}else if( $j == $count -1){
							$str = $item['lastCss'];
						}
						$pageItemArr[$j] = str_replace( '$$first-last$$', $str, $pageItemArr[$j] );
					}
				}
				array_push( $listArr ,str_replace( $item['itemTemplateText'], implode( '',$pageItemArr ) ,$listTemplate ) );
			}
			return $listArr;
		}
		
		function toHTML(){
			$mainTemplate = $this->projects['mainTemplate'];
			foreach( $this->projects['items'] as $item ){
				$itemArray = $this->_itemToHTML( $item );
				$listArray = $this->_listToHTML( $itemArray, $item );
				$mainTemplate = str_replace( $item['listTemplateText'], implode( '',$listArray ), $mainTemplate );
			}
			
			return $mainTemplate;
		}
	}
?>