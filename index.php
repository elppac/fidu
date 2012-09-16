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
<link rel='stylesheet' id='zh-cn-l10n-administration-screens-css'  href='http://<?php echo $site_path;?>/wp-content/languages/zh_CN-administration-screens.css?ver=20111120' type='text/css' media='all' />
<link rel='stylesheet' id='imgareaselect-css'  href='http://<?php echo $site_path;?>/wp-includes/js/imgareaselect/imgareaselect.css?ver=0.9.1' type='text/css' media='all' />
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
var pluploadL10n = {"queue_limit_exceeded":"\u60a8\u5411\u961f\u5217\u4e2d\u6dfb\u52a0\u7684\u6587\u4ef6\u8fc7\u591a\u3002","file_exceeds_size_limit":"%s \u8d85\u8fc7\u4e86\u7ad9\u70b9\u7684\u6700\u5927\u4e0a\u4f20\u9650\u5236\u3002","zero_byte_file":"\u6587\u4ef6\u4e3a\u7a7a\uff0c\u8bf7\u9009\u62e9\u5176\u5b83\u6587\u4ef6\u3002","invalid_filetype":"\u4e0d\u5141\u8bb8\u4e0a\u4f20\u8be5\u7c7b\u578b\u7684\u6587\u4ef6\uff0c\u8bf7\u9009\u62e9\u5176\u5b83\u6587\u4ef6\u3002","not_an_image":"\u8be5\u6587\u4ef6\u4e0d\u662f\u56fe\u50cf\uff0c\u8bf7\u4f7f\u7528\u5176\u5b83\u6587\u4ef6\u3002","image_memory_exceeded":"\u8fbe\u5230\u5185\u5b58\u9650\u5236\uff0c\u8bf7\u4f7f\u7528\u5c0f\u4e00\u4e9b\u7684\u6587\u4ef6\u3002","image_dimensions_exceeded":"\u8be5\u6587\u4ef6\u8d85\u8fc7\u4e86\u6700\u5927\u5927\u5c0f\uff0c\u8bf7\u4f7f\u7528\u5176\u5b83\u6587\u4ef6\u3002","default_error":"\u4e0a\u4f20\u65f6\u53d1\u751f\u4e86\u9519\u8bef\u3002\u8bf7\u7a0d\u540e\u518d\u8bd5\u3002","missing_upload_url":"\u914d\u7f6e\u6709\u8bef\u3002\u8bf7\u8054\u7cfb\u60a8\u7684\u670d\u52a1\u5668\u7ba1\u7406\u5458\u3002","upload_limit_exceeded":"\u60a8\u53ea\u80fd\u4e0a\u4f20\u4e00\u4e2a\u6587\u4ef6\u3002","http_error":"HTTP \u9519\u8bef\u3002","upload_failed":"\u4e0a\u4f20\u5931\u8d25\u3002","big_upload_failed":"\u8bf7\u5c1d\u8bd5\u4f7f\u7528%1$s\u6807\u51c6\u7684\u6d4f\u89c8\u5668\u4e0a\u4f20\u5de5\u5177%2$s\u6765\u4e0a\u4f20\u8fd9\u4e2a\u6587\u4ef6\u3002","big_upload_queued":"%s \u8d85\u51fa\u4e86\u60a8\u6d4f\u89c8\u5668\u5bf9\u9ad8\u7ea7\u591a\u6587\u4ef6\u4e0a\u4f20\u5de5\u5177\u6240\u505a\u7684\u5927\u5c0f\u9650\u5236\u3002","io_error":"IO \u9519\u8bef\u3002","security_error":"\u5b89\u5168\u9519\u8bef\u3002","file_cancelled":"\u6587\u4ef6\u5df2\u53d6\u6d88\u3002","upload_stopped":"\u4e0a\u4f20\u505c\u6b62\u3002","dismiss":"\u4e0d\u518d\u663e\u793a","crunching":"\u5904\u7406\u4e2d\u2026","deleted":"\u79fb\u52a8\u5230\u56de\u6536\u7ad9\u3002","error_uploading":"\u201c%s\u201d\u4e0a\u4f20\u5931\u8d25\u3002"};/* ]]> */
</script>
<script type='text/javascript' src='http://<?php echo $site_path;?>/wp-admin/load-scripts.php?c=1&amp;load=jquery,utils,plupload,plupload-html5,plupload-flash,plupload-silverlight,plupload-html4,plupload-handlers,json2&amp;ver=dbcc2d917a7e14bbe292c26bb476a7f4'></script>
 
<style type="text/css" media="print">
#wpadminbar {
	display:none;
}
</style> 
</head>
<body class="wp-admin no-js  media-new-php admin-bar branch-3-3 version-3-3-2 admin-color-fresh">
<script type="text/javascript">document.body.className = document.body.className.replace('no-js','js');</script>
<div id="wpwrap">
	<div id="wpbody">
		<div id="wpbody-content">
			<div class="wrap">
				<h2>Excel数据转换</h2>
				<form enctype="multipart/form-data" method="post" action="http://<?php echo $site_path;?>/wp-admin/media-upload.php?inline=&amp;upload-page-form=" class="media-upload-form type-form validate" id="file-form">
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
					<div id="html-upload-ui" class="hide-if-js">
						<p id="async-upload-wrap">
							<label class="screen-reader-text" for="async-upload">上传</label>
							<input type="file" name="async-upload" id="async-upload" />
							<input type="submit" name="html-upload" id="html-upload" class="button" value="上传"  />
							<a href="#" onclick="try{top.tb_remove();}catch(e){}; return false;">取消</a> </p>
						<div class="clear"></div>
						<p class="upload-html-bypass hide-if-no-js"> 您正在使用浏览器内置的标准上传工具。WordPress 提供了全新的上传工具，并支持拖放上传功能。<a href="#">改用新的上传工具</a>。 </p>
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
				</form>
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
	<script type='text/javascript' src='http://<?php echo $site_path;?>/wp-admin/load-scripts.php?c=1&amp;load=admin-bar,hoverIntent,common,jquery-color,imgareaselect,image-edit,set-post-thumbnail&amp;ver=97fc4dcc6a74df5da2a5756303474f8a'></script>
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
