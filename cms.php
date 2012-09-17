<?php
//获取当前的域名:
$site_path = $_SERVER['SERVER_NAME'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Fidu</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<script src="handsontable/lib/jquery.js"></script>
	<script src="handsontable/demo/js/json2.min.js"></script>
	<link rel="stylesheet" media="screen" href="static/css/fidu.css">
</head>
 <body>
 <div style="width:500px; float:left;">
	<textarea id="data-html" style="display:block; font-size:12px; width:500px; height:200px;"></textarea>
	<button id="btn-builder-nav">生成目录</button>
	<nav id="fidu-nav-box">
	</nav>
</div>
<div style="width:800px; float:right;">
	<iframe src="fidu-enter.php" id="fidu-page" frameborder="0" style="width:800px; height:600px;"></iframe>
	<input type="hidden" id="module-data"/>
	<input type="hidden" id="cms-id" value="002"/>
	<input type="hidden" id="module-name"/>
</div>
<script>
var fiduPage = $('#fidu-page');
function getModuleInfo(){
	var obj = {
		data : $('#module-data').val(),
		id :  $('#cms-id').val(),
		name :  $('#module-name').val(),
		type : 'moduleInfo'
	}
	return obj;
}

function replaceHtml(newHTML){
	var dataTextArea = $('#data-html'),
		moduleName =  $('#module-name').val(),
		dataHtml = dataTextArea.val();
	var html = dataHtml.replace(new RegExp( '<!--@begin '+moduleName+'-->[\\s\\S]*?<!--@end '+moduleName+'-->', 'i' ), newHTML);
	dataTextArea.val(html);
}

window.addEventListener(
'message', 
function(e) {
	if (e.origin == 'http://<?php echo $site_path?>') {
		if(e.data.type == 'moduleInfo'){
			fiduPage[0].contentWindow.postMessage( getModuleInfo() , 'http://<?php echo $site_path?>');
		}else if( e.data.type == 'html' ){
			replaceHtml(e.data.html);
		}
	}
} , false); 


(function($){
	
	function validateFiduTemplate( name,html ){
		var reg = new RegExp( '<!--@begin '+name+'-->[\\s\\S]*?<!--@end '+name+'-->', 'i' );
		return reg.test( html );
	}
	function getFiduTemplateText( name,html ){
		var reg = new RegExp( '<!--@begin '+name+'-->([\\s\\S]*?)<!--@end '+name+'-->', '' );
		return html.match( reg )[0];
	}
	function fiduNameList( list,html ){
		var newArray = [];
		list.forEach( function( item,index ){
			var name = item.match(new RegExp('<!--@begin (.*?)-->','i'))[1];
			if( validateFiduTemplate( name,html )){
				newArray.push( name );
			}
		});
		return newArray;
	}
	function builderNav(){
		var dataTextArea = $('#data-html'),
			dataHtml = dataTextArea.val();
			fiduList = dataHtml.match( new RegExp('<!--@begin (.*?)-->','g') ),
			fiduNavBox = $('#fidu-nav-box'),
			newList = [];
		
		if( fiduList && fiduList.length>0){
			newList = fiduNameList( fiduList,dataHtml )
		}else{
			fiduNavBox.html('无可用标记！');
			return;
		}
		var fiduNavHtml = [];
		fiduNavHtml.push('<ol>')
		newList.forEach( function( item,index ){
			fiduNavHtml.push('<li>');
			fiduNavHtml.push('<a href="javascript:void(0);">'+item+'</a>');
			fiduNavHtml.push('</li>');
		});
		fiduNavHtml.push('</ol>');
		fiduNavBox.html( fiduNavHtml.join('') );
		
		fiduNavBox.find('li').each( function( index, item ){
			$(item).click(function( e ){
				$('#module-data').val( getFiduTemplateText(newList[index],dataHtml));
				$('#module-name').val( newList[index]);
				fiduPage.attr( 'src',fiduPage.attr('src'));
			});
		});
	}
	
	$('#btn-builder-nav').click( function(e){
		builderNav();
	});
})(jQuery);
</script>

 </body>
</html>