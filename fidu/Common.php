<?php
	function code2Html($code){
		$code = preg_replace(
			array(
				"'&lt;'",
				"'&gt;'",
				"'&quot;'",
				"'[\s][\s]*'",  //移掉多余空隔
				//"'/'", //转译'/'
				"'>[\s]*<'",//移掉标签之间的空隔
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
		$code = str_replace('\n','',$code);
		
		if (get_magic_quotes_gpc()){
			return stripslashes($code);
		}
		return $code;
	}
	function code2RegexpString($code,$leftKey,$rightKey){
		$code = preg_replace(
			array(
				"'> ".$leftKey."'",
				"'".$rightKey." <'",
				"'/'",
				"'\('",
				"'\)'",
				"'".$leftKey.".*?".$rightKey."'"
			),
			array(
				'>'.$leftKey.'',
				''.$rightKey.'<',
				'\\/',
				'\\(',
				'\\)',
				'(.*?)'
			),
			$code);
		return $code;
	} 
	function clearKey($code,$leftKey,$rightKey){
		$code = preg_replace(
			array(
				"'".$leftKey."'",
				"'".$rightKey."'"
			),
			array(
				'',
				''
			),
			$code);
		return $code;
	} 
?>