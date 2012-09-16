<?php
//获取当前的域名:
$site_path = $_SERVER['SERVER_NAME'];
?>
	
<!DOCTYPE html>
<!--[if IE 8]>
<html xmlns="http://www.w3.org/1999/xhtml" class="ie8"  dir="ltr" lang="en">
<![endif]-->
<!--[if !(IE 8) ]><!-->
<html xmlns="http://www.w3.org/1999/xhtml"  dir="ltr" lang="en">
<!--<![endif]-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Excel数据转换</title>
<script type="text/javascript">
addLoadEvent = function(func){if(typeof jQuery!="undefined")jQuery(document).ready(func);else if(typeof wpOnload!='function'){wpOnload=func;}else{var oldonload=wpOnload;wpOnload=function(){oldonload();func();}}};
var userSettings = {
		'url': '/',
		'uid': '1',
		'time':'1340160785'
	},
	ajaxurl = 'http://<?php echo $site_path;?>/wp-admin/admin-ajax.php',
	pagenow = 'media',
	typenow = '',
	adminpage = 'media-new-php',
	thousandsSeparator = ',',
	decimalPoint = '.',
	isRtl = 0;
</script>
<link rel='stylesheet' href='http://<?php echo $site_path;?>/wp-admin/load-styles.php?c=1&amp;dir=ltr&amp;load=admin-bar,wp-admin&amp;ver=7f0753feec257518ac1fec83d5bced6a' type='text/css' media='all' />

<link rel='stylesheet' id='colors-css'  href='http://<?php echo $site_path;?>/wp-admin/css/colors-fresh.css?ver=20111206' type='text/css' media='all' />
<!--[if lte IE 7]>
<link rel='stylesheet' id='ie-css'  href='http://<?php echo $site_path;?>/wp-admin/css/ie.css?ver=20111130' type='text/css' media='all' />
<![endif]--> 
<style type="text/css">
body{
font-family:arial,'微软雅黑';
padding:10px;
}
.output{padding:10px;}
.textarea {
    -moz-box-sizing: border-box;
    border: 1px solid #ddd;
    outline: medium none;
    padding: 0;
    width: 99%;
height:200px;
}
#wpbody-content{padding-bottom:20px;}
</style>
<script type='text/javascript'>
/* <![CDATA[ */
var pluploadL10n = {"queue_limit_exceeded":"您向队列中添加的文件过多。","file_exceeds_size_limit":"%s 超过了站点的最大上传限制。","zero_byte_file":"文件为空，请选择其它文件。","invalid_filetype":"不允许上传该类型的文件，请选择其它文件。","not_an_image":"该文件不是图像，请使用其它文件。","image_memory_exceeded":"达到内存限制，请使用小一些的文件。","image_dimensions_exceeded":"该文件超过了最大大小，请使用其它文件。","default_error":"上传时发生了错误。请稍后再试。","missing_upload_url":"配置有误。请联系您的服务器管理员。","upload_limit_exceeded":"您只能上传一个文件。","http_error":"HTTP 错误。","upload_failed":"上传失败。","big_upload_failed":"请尝试使用%1$s标准的浏览器上传工具%2$s来上传这个文件。","big_upload_queued":"%s 超出了您浏览器对高级多文件上传工具所做的大小限制。","io_error":"IO 错误。","security_error":"安全错误。","file_cancelled":"文件已取消。","upload_stopped":"上传停止。","dismiss":"不再显示","crunching":"上传完成，请选择相应的处理","deleted":"移动到回收站。","error_uploading":"“%s”上传失败。"};/* ]]> */
</script>
<script type='text/javascript' src='http://<?php echo $site_path;?>/wp-admin/load-scripts.php?c=1&amp;load=jquery,utils,plupload,plupload-html5,plupload-flash,plupload-silverlight,plupload-html4,plupload-handlers,json2&amp;ver=dbcc2d917a7e14bbe292c26bb476a7f4'></script>
 
<style type="text/css" media="print">
#wpadminbar {
	display:none;
}
</style> 
</head>
<body class="no-js">
<script type="text/javascript">document.body.className = document.body.className.replace('no-js','js');</script>
<div id="wpwrap">
	<div id="wpbody">
		<div id="wpbody-content">
			<div class="wrap">
				<h2>Excel数据转换</h2>
				
					<div id="media-upload-notice"></div>
					<div id="media-upload-error"></div>
					<script type="text/javascript">
						var resize_height = 1024, resize_width = 1024,
						wpUploaderInit = {
						"runtimes":"html5,silverlight,flash,html4",
						"browse_button":"plupload-browse-button",
						"container":"plupload-upload-ui",
						"drop_element":"drag-drop-area",
						"file_data_name":"async-upload",
						"multiple_queues":true,
						"max_file_size":"52428800b",
						"url":"async-upload.php",
						"flash_swf_url":"http:\/\/<?php echo $site_path;?>\/wp-includes\/js\/plupload\/plupload.flash.swf",
						"silverlight_xap_url":"http:\/\/<?php echo $site_path;?>\/wp-includes\/js\/plupload\/plupload.silverlight.xap",
						"filters":[{"title":"hello","extensions":"xls,xlsx"}],
						"multipart":true,"urlstream_upload":true,"multipart_params":{}};
					</script>
					<div id="plupload-upload-ui" class="hide-if-no-js">
						<div id="drag-drop-area">
							<div class="drag-drop-inside">
								<p class="drag-drop-info">将文件拖到这里</p>
								<p>或</p>
								<p class="drag-drop-buttons">
									<input id="plupload-browse-button" type="button" value="选择文件" class="button" />
								</p>
							</div>
						</div>
						<!--<p class="upload-flash-bypass"> 您正在使用高级多文件上传工具。不能正确上传？请尝试使用<a href="#">标准的浏览器上传工具</a> 。 </p>-->
					</div>
					
					<p class="after-file-upload">文件上传之后，请点击你需要的操作。</p>
					<script type="text/javascript">
						jQuery(function($){
							var preloaded = $(".media-item.preloaded");
							if ( preloaded.length > 0 ) {
								preloaded.each(function(){prepareMediaItem({id:this.id.replace(/[^0-9]/g, '')},'');});
							}
							updateMediaForm();
							post_id = 0;
							shortform = 1;
						});
					</script>
					<input type="hidden" name="post_id" id="post_id" value="0" />
					<div id="media-items" class="hide-if-no-js"></div>
					 
					<!--<button class="button" onclick="toHtml(event,'20120628091016-1340845816.xls');">Ajax</button>-->
			</div>
			<div class="clear"></div>
		</div>
		<!-- wpbody-content -->
		<div class="clear"></div>
	</div>
	<h3>输出 </h3>
	<textarea cols="20" id="results" class="textarea" style="height:198px;"></textarea>
	<!-- <button class="button" id="btn-copy">Copy</button>-->
	<script>
		function toJson( e, filename ){
			var event = new jQuery.Event(e);
			event.preventDefault();
			toData('josn',filename);
		}
		function toHtml( e, filename ){
			var event = new jQuery.Event(e);
			event.preventDefault();
			toData('html',filename);
		}
		function toData( type, filename){
			jQuery.ajax({
				url: 'fidu-bus.php',
				data: { type: type, filename: filename},
				cache: false
			}).done(function( html ) {
			  jQuery("#results").val(html);
			});
		}
	</script>
</div>
<!--<script type="text/javascript">if(typeof wpOnload=='function')wpOnload();</script>
<script type="text/javascript" src="http://<?php echo $site_path;?>/packer/excel2/static/ZeroClipboard.min.js"></script>
<script>
	ZeroClipboard.setMoviePath( 'http://<?php echo $site_path;?>/packer/excel2/static/ZeroClipboard10.swf' );
	var clip = new ZeroClipboard.Client();
	
	clip.setHandCursor( true );
	clip.setCSSEffects( true );
	jQuery(function($){
		$('#btn-copy').on('mouseover',function(e){
			clip.glue( 'btn-copy' );
		});
		clip.addEventListener( 'onMouseDown', function(client) {
			var text = document.getElementById('results').value;
			if( text == ''){
				alert( '嘿嘿！为什么是光溜溜的！' );
			}else{
				alert(text);
				clip.setText( text );
			}
		 });
	});
</script>-->
</body>
</html>
