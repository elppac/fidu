<?php
//获取当前的域名:
$site_path = $_SERVER['SERVER_NAME'];
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Fidu Enter</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<script src="warpech/lib/jquery.js"></script>
	<script src="warpech/demo/js/json2.min.js"></script>
</head>
 <body>
 <div id="output"></div>
 <form id="fidu-enter-from" action="fidu.php" method="post">
	<input type="hidden" id="module-data" name="module-data"/>
	<input type="hidden" id="cms-id" value="" name="cms-id"/>
	<input type="hidden" id="module-name" name="module-name"/>
 </form>
	<script>
		var output = $('#output');
		window.addEventListener(
		'message', 
		function(e) {
			if (e.origin == 'http://<?php echo $site_path?>')  {
				if( e.data.type == 'moduleInfo' ){
					if( e.data.data == ''){
						output.html('未创建');
					}else{
						$('#module-data').val(e.data.data);
						$('#module-name').val(e.data.name);
						$('#cms-id').val(e.data.id);
						
						$('#fidu-enter-from').submit();
					}
				}
			 }
		} , false); 
		//get moduleInfo
		window.top.postMessage( {type:'moduleInfo'} , 'http://<?php echo $site_path?>' );
	</script>
 </body>
</html>