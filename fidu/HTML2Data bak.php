<?php
	require_once 'Common.php';
	class HTML2Data{
		var $code;
		var $config;
		var $projects = array();
		var $dataNameList = array();
		var $dataList = array();
		
		function HTML2Data( $moduleData,$moduleName, $id ){
			 $this->code = code2Html( $moduleData );
			 $this->config = array(
				'main'=>'<!--@begin Hot Products--><section class="ui-box ui-box-normal ui-box-wrap margin-bottom-normal hot-products"> <h3 class="ui-box-title">Hot Products</h3> <div class="ui-box-content clearfix">  <div class="prev-wrapper"><a id="hot-products-prev-btn" href="javascript:void(0);"></a></div>  <div id="hot-products-wrapper" class="hot-container-wrapper">   <ul id="hot-products-container">    ##data-list-dataTemplate##   </ul>  </div>  <div class="next-wrapper"><a id="hot-products-next-btn" href="javascript:void(0);"></a></div> </div></section><ul>##tabs-list-tabsTemplate##</ul><!--@end Hot Products-->',
				'list-dataTemplate'=>'<li class="hot-list-wrapper">
												 <ol class="clearfix hot-list">
												  ##list-item-dataTemplate##
												 </ol>
												</li>',
				'list-item-dataTemplate'=>'<li><a class="item-picture" href="{{url}}" target="_blank"><img src="{{image-src}}" alt="{{alt}}"></a>
														 <p class="item-discript"><span><a href="{{url}}" title="{{title}}">{{text}}</a></span></p>
														</li>',
				'list-tabsTemplate'=>'##list-item-tabsTemplate##',
				'list-item-tabsTemplate'=>'<li>{{text}}</li>',
				'page-size'=>'10',
				'version'=>'1'
			 );
			print_r( $this->config );
			//$this->_initProjects();
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
			$template = $this->config[$templateName];
			$arr = array();
			if(preg_match_all("/##.*?##/",$template,$matches)){
				$arr = $matches[0];
			}
			return $arr;
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
					
					#echo str_replace($analyze['sheetName'].'-','',$firstAnalyze['templateName']);

					$secondKeywords = $this->_findMainKeyword($firstAnalyze['templateName']);
					$secondKeyword = $secondKeywords[0];
					$secondAnalyze = $this->_analyzeName( $secondKeyword );
					
					$pageSize = $this->config[$analyze['sheetName']+'-page-size'];
					
					if(!isset($pageSize) || $pageSize==null){
						$pageSize = $this->config['page-size'];
						if(!isset($pageSize)){
							$pageSize = 1000;
						}
					}
					
					$fistCss = $this->config[$analyze['sheetName']+'-first-css'];
					if(!isset($fistCss) || $fistCss==null){
						$fistCss = $this->config['first-css'];
						if(!isset($fistCss)){
							$fistCss = 'first';
						}
					}
					
					$lastCss = $this->config[$analyze['sheetName']+'-last-css'];
					if(!isset($lastCss) || $lastCss==null){
						$lastCss = $this->config['last-css'];
						if(!isset($lastCss)){
							$lastCss = 'last';
						}
					}
					
					
					
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
				}else if( $analyze[type] == 'panel' ){
					
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
			print_r($mainRegExpString);
			//转成RegExp字符串
			if(!preg_match( '{'.$mainRegExpString.'}',code2Html($this->code),$mainMatches )){
				echo '匹配出错';
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
					if(!in_array($key,$keysArray)){
						array_push( $keysArray, $key );
							//echo $i;
						$j = 0;
						for(;$j<count($list[$i]);$j++ ){
							//echo $j;
							$item	= $list[$i+1][$j];
							
							if($i == 0){
								$newArray = array();
								$newArray[0] = $item;
								array_push( $listArray, $newArray );
							}else{
								$listArray[$j][$i] = $item;
							}
						}
					}
					$i = $i + 1;
				}
				array_unshift($listArray,$keysArray);
				array_push( $this->dataList, $listArray );
			}else{
				/*foreach( $keys[0] as $key ){
					if(!in_array($key,$keysArray)){
						array_push( $keysArray, $key );
						$j = 0;
						for(;$j<count($list[$i]);$j++ ){
							$item = $list[$i+1][$j];
							$listArray[$j][count($listArray[$j])] = $item;
						}
					}
					$i = $i + 1;
				}*/
			}
		}
		function _itemToData($item){
			$itemTemplate = code2Html($item['itemTemplate']);
			if(!preg_match_all("/{{.*?}}/",$itemTemplate,$itemKeyMatches)){
				echo '匹配字段出错';
				return;
			}
			$itemRegExpString = code2RegexpString($itemTemplate,'{{','}}');
			if(!preg_match_all( '{'.$itemRegExpString.'}',code2Html($item['html']),$itemDataMatches )){
				echo '匹配字段数据出错';
				return;
			}
			echo 'asfsdf';
			//$this->_listToArray( $item['sheetName'],$itemKeyMatches,$itemDataMatches )
			//print_r($item['sheetName'] );
			//print_r($this->_listToArray( $item['sheetName'],$itemKeyMatches,$itemDataMatches ));
			//print_r($item);
		}
		function toData(){
			echo 'asfsdf';
			//$this->_findListHTML();
			//foreach( $this->projects['items'] as $item ){
				//$itemArray = $this->_itemToData( $item );
				//$listArray = $this->_listToHTML( $itemArray, $item );
				//$mainTemplate = str_replace( $item['listTemplateText'], implode( '',$listArray ), $mainTemplate );
				
			///s}
			/*print_r( json_encode( array(
				'dataNameList'=> $this->dataNameList,
				'dataList' =>$this->dataList
			) ) );*/
			//print_r($this->projects);
		}
	}
?>