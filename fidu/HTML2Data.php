<?php
	require_once 'Common.php';
	require_once 'FiduXML.php';
	class HTML2Data{
		var $code;
		var $config;
		var $projects = array();
		var $dataNameList = array();
		var $dataList = array();
		
		function HTML2Data( $moduleData,$moduleName, $id ){
			$this->code = code2Html( $moduleData );
			$fiduxml = new FiduXML();
			$this->config = $fiduxml -> getDataLikeJson(  $id ,$moduleName);
			$this->_initProjects();
		}
		
		function _analyzeName( $templateText ){
			$templateName = str_replace( '##','',$templateText );
			$arr = preg_split('/-/',$templateName);
			$retval = array();
			$sheetName = $arr[0];
			return array(
				'templateName' => $templateName,
				'templateText' => $templateText,
				'sheetName' => $arr[0],
				'type' => (count($arr)>1?$arr[1]:''),
				'name' => $arr[count($arr)-1]
			);
		}
		
		function _findMainKeyword( $templateName ){
			$template = $this->config[$templateName];
			$arr = array();
			if(preg_match_all("/##.*?##/",$template,$matches)){
				$arr = $matches[0];
			}
			return $arr;
		}
		function _getArrayValue($array, $key, $defaultVal){
			$value = $defaultVal;
			if(array_key_exists($array['sheetName'].'-'.$key, $this->config)){
				$value = $this->config[$array['sheetName'].'-'.$key];
			}else{
				if( array_key_exists( $key, $this->config ) ){
					$value = $this->config[ $key ];
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
					
					//#echo str_replace($analyze['sheetName'].'-','',$firstAnalyze['templateName']);
					
					$pageSize = $this->_getArrayValue( $analyze, 'page-size', 10000);
					$fistCss = $this->_getArrayValue( $analyze, 'first-css', 'first');
					$lastCss = $this->_getArrayValue( $analyze, 'last-css', 'last');					
					
					
					
					array_push( $items ,array(
						'type' => $analyze['type'],
						'listTemplate' => $this->config[ $analyze['templateName'] ],
						'listTemplateName' => $analyze['templateName'],
						'listTemplateText' => $analyze['templateText'],
						'itemTemplate' => $this->config[ $firstAnalyze['templateName'] ],
						'itemTemplateName' => $firstAnalyze['templateName'],
						'itemTemplateText' => $firstAnalyze['templateText'],
						'pageSize' => $pageSize,
						'fistCss' => $fistCss,
						'lastCss' => $lastCss,
						'sheetName' => $analyze['sheetName'],
						'name' => $analyze['name']
					));
				}else if( $analyze['type'] == 'panel' ){
					
				}
			}
			
			$this->projects = array(
				'items' => $items,
				'mainTemplate' => $this->config[ 'main' ]
			);
		}
		function _findListHTML(){
			//print_r(code2Html($this->config['main']));
			$mainTemplate = code2Html($this->projects['mainTemplate']);
			//转成html
			$mainRegExpString = code2RegexpString($mainTemplate,'##','##');
			
			//转成RegExp字符串
			if(!preg_match( '{'.$mainRegExpString.'}',code2Html($this->code),$mainMatches )){
				throw new Exception("匹配List模板HTML出错");
				return;
			}
			$i = -1;
			forEach( $mainMatches  as $value ){
				if($i > -1){
					$this->projects['items'][$i]['html'] = $value;
				}
				$i = $i+1;
			}
		}
		function _listToArray( $dataName, $keys, $list ){
			if( !in_array( $dataName,$this->dataNameList )){
				array_push( $this->dataNameList, $dataName );
				$keysArray = array();
				$listArray = array();
				$i = 0;
				foreach( $keys[0] as $key ){
					$keyName = clearKey( $key,'{{','}}' );
					if(!in_array($keyName,$keysArray)){
						array_push( $keysArray, $keyName ); 
						$j = 0;
						for(;$j<count($list[$i]);$j++ ){ 
							$item	= $list[$i+1][$j];
							
							if($i == 0){
								$newArray = array();
								array_push( $newArray,  $item);
								array_push( $listArray, $newArray );
							}else{
								array_push( $listArray[$j], $item );
							}
						}
					}
					$i = $i + 1;
				}
				array_unshift($listArray,$keysArray);
				array_push( $this->dataList, $listArray );
				//print_r($listArray);
			}else{
				foreach( $keys[0] as $key ){
					if(!in_array($key,$keysArray)){
						array_push( $keysArray, $key );
						$j = 0;
						for(;$j<count($list[$i]);$j++ ){
							$item = $list[$i+1][$j];
							$listArray[$j][count($listArray[$j])] = $item;
						}
					}
					$i = $i + 1;
				}
			}
		}
		function _itemToData($item){
			$itemTemplate = code2Html($item['itemTemplate']);
			if(!preg_match_all("/{{.*?}}/",$itemTemplate,$itemKeyMatches)){ 
				throw new Exception("匹配字段出错");
				return;
			}
			$itemRegExpString = code2RegexpString($itemTemplate,'{{','}}');
			if(!preg_match_all( '{'.$itemRegExpString.'}',code2Html($item['html']),$itemDataMatches )){
				throw new Exception("匹配字段数据出错");
				return;
			} 
			//print_r($item['sheetName'] );
			$this->_listToArray( $item['sheetName'],$itemKeyMatches,$itemDataMatches );
			//print_r($item);
		}
		function toData(){
			if( $this->config == null ){
				return  json_encode( array(
					'success'=> false,
					'type' => 'no-config',
					'info' =>'未找到配置文件'
				) ) ;
			}
			
				
			try{
				$this->_findListHTML();
			}catch(Exception $e){
				return json_encode( array(
					'success'=> false,
					'type' => 'match',
					'info' =>$e->getMessage()
				) ) ;
			} 
			foreach( $this->projects['items'] as $item ){
				try{
					$itemArray = $this->_itemToData( $item );
				}catch(Exception $e){
					$success = false;
					$info = $e->getMessage();
				}
			}
			
			
			$success = true;
			$info = '';
			if( !in_array( 'config', $this->dataNameList ) ){
				$configDataArray = array();
				foreach( $this->config as $key=>$val ){
					array_push( $configDataArray, array(
						clearKey($key, '##', '##'),
						code2Html($val)
					));
				}
				array_push( $this->dataNameList, 'config' );
				array_push( $this->dataList, $configDataArray);
			}
			
			return json_encode( array(
				'success'=> $success,
				'info'=>$info,
				'type'=>'match',
				'dataNameList'=> $this->dataNameList,
				'dataList' =>$this->dataList
			) ) ;
		}
	}
?>