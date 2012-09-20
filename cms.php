<?php
//获取当前的域名:
$site_path = $_SERVER['SERVER_NAME'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Fidu</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<script type="text/javascript" src="static/js/jquery/jquery.js"></script>
	<script type="text/javascript" src="static/js/jquery/ui/jquery.ui.core.js"></script>
	<script type="text/javascript" src="static/js/jquery/ui/jquery.ui.widget.js"></script>
	<script type="text/javascript" src="static/js/jquery/ui/jquery.ui.mouse.js"></script>
	<script type="text/javascript" src="static/js/jquery/ui/jquery.ui.draggable.js"></script>
	<script type="text/javascript" src="static/js/jquery/ui/jquery.ui.position.js"></script>
	<script type="text/javascript" src="static/js/jquery/ui/jquery.ui.resizable.js"></script>
	<script type="text/javascript" src="static/js/jquery/ui/jquery.ui.dialog.js"></script>
	<script type="text/javascript" src="static/js/json2.js"></script>
	<link rel="stylesheet" media="screen" href="static/css/fidu.css">
	<link rel="stylesheet" media="screen" href="static/css/themes/base/jquery.ui.all.css">
</head>
 <body style="text-align:center;">
 <div style="width:800px; margin:0 auto; text-align:left; position:relative;">
	<textarea id="data-html" style="display:block; font-size:12px; width:100%; height:600px;"></textarea>
	<button id="btn-builder-nav">生成目录</button>
	<nav id="fidu-nav-box" class="fidu-nav"> 
	</nav>
</div>
<div id="fidu-iframe" title="Fidu" style="display:none;"><iframe scrolling="no" id="fidu-page" frameborder="0" style="width:100%; height:100%;"></iframe></div>
<script>
var moduleObject = {
	data : null,
	id : '002',
	name : null,
	type : 'moduleInfo'
} 
function replaceHtml(newHTML){
	var dataTextArea = $('#data-html'),
		moduleName = moduleObject.name,
		dataHtml = dataTextArea.val();
	var html = dataHtml.replace(new RegExp( '<!--@begin '+moduleName+'-->[\\s\\S]*?<!--@end '+moduleName+'-->', 'i' ), newHTML);
	dataTextArea.val(html);
}

window.addEventListener(
'message', 
function(e) {
	if (e.origin == 'http://<?php echo $site_path?>') {
		if(e.data.type == 'moduleInfo'){
			$('#fidu-page')[0].contentWindow.postMessage( moduleObject , 'http://<?php echo $site_path?>');
		}else if( e.data.type == 'html' ){
			alert('数据保存成功！');
			$( "#fidu-iframe" ).dialog( "close" );
			replaceHtml(e.data.html);
		}
	}
} , false); 


</script>
<script type="text/javascript" src="static/js/cms.js"></script>
</body>
</html>