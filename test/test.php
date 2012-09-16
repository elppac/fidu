<?php
//require_once '../phpExcelReader/Excel/reader.php';
header('Content-Type: text/html; charset=UTF-8');
print('正则取关键字 <br/>');
if(preg_match_all("/##.*?##/", '<!--@begin Hot Products-->
<section class=""ui-box ui-box-normal ui-box-wrap margin-bottom-normal hot-products"">
 <h3 class=""ui-box-title"">Hot Products</h3>
 <div class=""ui-box-content clearfix"">
  <div class=""prev-wrapper""><a id=""hot-products-prev-btn"" href=""javascript:void(0);""></a></div>
  <div id=""hot-products-wrapper"" class=""hot-container-wrapper"">
   <ul id=""hot-products-container"">
		##data-list-template##
   </ul>
   <ul>
		##data-tabs-template##
	</ul>
  </div>
  <div class=""next-wrapper""><a id=""hot-products-next-btn"" href=""javascript:void(0);""></a></div>
 </div>
</section>
<!--@end Hot Products-->
', $matches)){
    print_r( $matches);
} else {
    print "A match was not found.";
}

print('<hr/>');
print('如何创建array 键/值 <br/>');
$array = array();
$array = array(
	'sheetName' => 'data',
	'listTemplate' => '##data-list-template##',
	'listTemplateName' => 'data-list-template',
	'itemTemplate' => '##data-item-template##',
	'itemTemplateName' => 'data-item-template',
);
print_r($array);
print('<br/>');
print('取array值<br/>');
print($array['listTemplate']);
print('<hr/>');


$itemTemplate = '<li><a class="item-picture" href="{{url}}" target="_blank"><img src="{{image-src}}" alt="{{alt}}"></a>
	<p class="item-discript"> <span> <a href="{{url}}" title="{{title}}">{{text}}</a> </span></p>
	</li>';

$htmlString = '<li><a class="item-picture" href="1http://bestltd.en.alibaba.com/product/213448053-200375822/Flexible_Hang_Tabs.html" target="_blank"><img src="http://i03.i.aliimg.com/images/cms/upload/memberhome_twn/TWpage/Aug_HP/1.JPG" alt="Flexible Hang Tabs"></a>
						<p class="item-discript"><span><a href="http://bestltd.en.alibaba.com/product/213448053-200375822/Flexible_Hang_Tabs.html" title="Flexible Hang Tabs">Flexible Hang Tabs</a></span></p>
					</li>
					<li><a class="item-picture" href="2http://miis.en.alibaba.com/product/586982183-213879973/Medical_endoscopy_camera_system_for_eye_fundus.html" target="_blank"><img src="http://i02.i.aliimg.com/images/cms/upload/memberhome_twn/TWpage/Aug_HP/2.JPG" alt="Medical endoscopy camera system for eye-fundus"></a>
						<p class="item-discript"><span><a href="http://miis.en.alibaba.com/product/586982183-213879973/Medical_endoscopy_camera_system_for_eye_fundus.html" title="Medical endoscopy camera system for eye-fundus">Medical endoscopy camera system for eye-fundus</a></span></p>
					</li>
					<li><a class="item-picture" href="3http://miis.en.alibaba.com/product/586982183-213879973/Medical_endoscopy_camera_system_for_eye_fundus.html" target="_blank"><img src="http://i02.i.aliimg.com/images/cms/upload/memberhome_twn/TWpage/Aug_HP/2.JPG" alt="Medical endoscopy camera system for eye-fundus"></a>
						<p class="item-discript"><span><a href="http://miis.en.alibaba.com/product/586982183-213879973/Medical_endoscopy_camera_system_for_eye_fundus.html" title="Medical endoscopy camera system for eye-fundus">Medical endoscopy camera system for eye-fundus</a></span></p>
					</li>';
print('正则item的关键字<br/>');
$itemList  = array();
if(preg_match_all("/{{.*?}}/",$itemTemplate,$itemList)){
	print_r( $itemList);
}
print('<br/>');
print('逆向工程<br/>');

$itemTemplatePattern = $itemTemplate;
forEach( $itemList  as $value ){
	$itemTemplatePattern = str_replace( $value,'(.*?)',$itemTemplatePattern );
}
$search  = array(
	"'([\r\n])[\s]+'",	// 去掉空白字符
	//"'\\s'",  //移掉多余空隔
	"'/'", //转译'/'
	"'>[\s]*<'"//移掉标签之间的空隔
);
$replace = array(
	'.*?',
	//' ',
	'\\/',
	'><'
);
$itemTemplatePattern = preg_replace ($search, $replace, $itemTemplatePattern);
echo $itemTemplatePattern;

if(preg_match_all( '/'.$itemTemplatePattern.'/is',$htmlString,$matches )){
	print_r( $matches );
}else{
	print( '失败' );
}
$file_path = dirname(__FILE__).'/test.xls';
if( !file_exists( $file_path ) ){
	echo '文件未找到';
}
/*
$data = new Spreadsheet_Excel_Reader();
$data->setOutputEncoding('utf-8');
$data->read( $file_path );
error_reporting(E_ALL ^ E_NOTICE);
print_r($data->boundsheets);*/ 
?>