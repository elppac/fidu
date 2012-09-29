<?php
//获取当前的域名:
$site_path = $_SERVER['SERVER_NAME'];

require_once 'fidu/HTML2Data.php';
require_once 'fidu/FiduXML.php';
header('Content-Type: text/html; charset=UTF-8');
$moduleData =$_POST['module-data'];
$moduleName =$_POST['module-name'];
$cmsId =$_POST['cms-id'];
$error = '';
if( isset($_POST['action']) && $_POST['action']=="create-config" ){
	$confVal = $_POST['config-value'];
	$fiduxml = new FiduXML();
	$fiduxml->addItem( $cmsId, $moduleName, $confVal  );
}

$html2data = new HTML2Data( $moduleData,$moduleName,$cmsId );
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Fidu</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<script src="warpech/lib/jquery.js"></script>
	<script src="warpech/jquery.handsontable.js"></script>
	<script src="warpech/lib/bootstrap-typeahead.js"></script>
	<script src="warpech/lib/jquery.autoresize.js"></script>
	<script src="warpech/lib/jQuery-contextMenu/jquery.contextMenu.js"></script>
	<script src="warpech/lib/jQuery-contextMenu/jquery.ui.position.js"></script>
	<script src="warpech/demo/js/json2.min.js"></script>
	<link rel="stylesheet" media="screen" href="warpech/lib/jQuery-contextMenu/jquery.contextMenu.css">
	<link rel="stylesheet" media="screen" href="warpech/jquery.handsontable.css">
	<link rel="stylesheet" media="screen" href="static/css/fidu.css">
</head>
 <body>
	<?php
		//print_r($moduleData);
	?>
	<script>
		var json = <?php echo $html2data->toData();?>,
			moduleName = '<?php echo $moduleName?>';
	</script>
	<h1 id="title-h1"></h1>
	<div class="ui-tabs ui-green" id="tabs">
		<div class="ui-tabs-panels-wrap">
			<div class="ui-tabs-panels">
			</div>
		</div>
		<dl class="ui-tabs-nav">
		</dl>
	</div>
	<div class="ctrl"><button id="btn-save">Save</button></div>
	<form action="fidu-bus.php" id="excelForm">
		<input type="hidden" name="tableData" id="table-data" />
		<input type="hidden" name="tableName" id="table-name" />
		<input type="hidden" name="table" id="table" />
		<input type="hidden" name="action" id="action" value="savedata" />
	</form>
	 <form id="create-config-from" action="fidu.php" method="post">
		<textarea id="module-data" name="module-data"  style="display:none;"><?php  echo $moduleData;?></textarea>
		<input type="hidden" id="cms-id"  name="cms-id" value="<?php  echo $cmsId;?>"/>
		<input type="hidden" id="module-name" name="module-name" value="<?php  echo $moduleName;?>"/>
		<input type="hidden" id="config-value"  name="config-value" value=""/>
		<input type="hidden" id="create-config-action" name="action" value="create-config" />
	 </form>
	
	<textarea id="data-output" style="display:block; font-size:12px; width:648px; height:50px; display:none;" ><?php print_r(''); ?></textarea>
<script>
	(function($){
		var element = $('#tabs'),
			button = $('#btn-save'),
			title = $('#title-h1');
		function fillData(){
			title.html( '数据转换 - '+moduleName );
			
			var panelsArr = [],
				navsArr = [],
				tables = [],
				panels = element.find('.ui-tabs-panels'),
				navs = element.find('.ui-tabs-nav');
			json.dataList.forEach(function( item,index ){
				panelsArr.push('<div class="ui-tabs-panel '+((index==0)?'current':'')+'"></div>');
				navsArr.push('<dd class="ui-state-default '+((index==0)?'current':'')+'"><a href="javascript:void(0);">'+json.dataNameList[index]+'</a></dd>');
			});
			
			panels.html(panelsArr.join(''));
			navs.html(navsArr.join(''));
			
			panels.find('.ui-tabs-panel').each(function( index,item ){
				var ele = $(document.createElement('div')),
					data =json.dataList[index];
				ele.addClass('dataTable');
				ele.attr('id','data-table-'+index);
				$(item).append(ele);
				$(item).addClass('current');
				//$(item).css('visibility','hidden');
				setTimeout(function(){
					ele.handsontable({
						//rows: data.length,
						//cols: data[0].length,
						//rowHeaders: true,
						//colHeaders: true,
						minSpareCols: 1,
						minSpareRows: 1,
						contextMenu: true//,
						//RemoveRow: true
					});
					ele.handsontable("loadData", data);
				},200)
				tables.push(ele);
			});
			
			$("#excelForm").submit(function(event) {
				/* stop form from submitting normally */
				event.preventDefault(); 				
				/* get some values from elements on the page: */
				var $form = $( this ),
						//term = $form.find( 'input[name="s"]' ).val(),
						url = $form.attr( 'action' );
				/* Send the data using post and put the results in a div */
				$.post( url, 
					{
						'moduleName' : 'Featured Products',
						'moduleId' : '002',
						'tableData':$('#table-data').val(),
						'tableName':$('#table-name').val(),
						'table' : $('#table').val(),
						'action' : $('#action').val()
					},
					function( data ) {
						window.top.postMessage( {type:'html', html:data} , 'http://www.elppa.cn' );
						$('#data-output').html(data);
					}
				);
			});
			
			button.click(function(){
				var tableDataArray = [];
				tables.forEach(function( item,index ){
					var array = item.data('handsontable').getData(),
						rowsnum = array.length,
						rows = [];
					for(var i =0; i<rowsnum; i++){
						var row = array[i];
						rows.push(row);
					}
					tableDataArray.push(clearEmptyData(rows));
				});
				
				var tableNameArray = [];
				for( var i=0;i<json.dataNameList.length;i++){
					tableNameArray.push(json.dataNameList[i]);
				}
				var jsonObject = {
					sheets : tableDataArray,
					boundsheets : tableNameArray
				};
				
				$('#table-data').val(JSON.stringify(tableDataArray));
				$('#table-name').val(JSON.stringify(tableNameArray));
				$('#table').val(JSON.stringify(jsonObject));
					//alert( JSON.stringify(jsonObject) );
				//$('#data-output').html( JSON.stringify(jsonObject) );
				$("#excelForm").submit();
			});
			
		}
		
		function createConfig(){
			title.html('创建配置文件 - '+moduleName);
			
			var  panels = element.find('.ui-tabs-panels'), 
				navs = element.find('.ui-tabs-nav');
			panels.html('<div class="ui-tabs-panel  current"></div>');
			var ele = $(document.createElement('div'));
			ele.addClass('dataTable');
			ele.attr('id','data-table-0');
			panels.find('.ui-tabs-panel').append(ele);
			setTimeout(function(){
				ele.handsontable({
					//rowHeaders: true,
					//colHeaders: true,
					minSpareCols: 1,
					minSpareRows: 1,
					contextMenu: true,
					RemoveRow: true
				});
			},200);
			button.click(function(){
				var configData = clearEmptyData(ele.data('handsontable').getData());
				if( configData.length == 0){
					alert('请先维护好表格！');
				}else{
					$('#config-value').val(JSON.stringify(configData));
					$('#create-config-from').submit();
				}
			});
		}
		
		function clearEmptyData(arr){
			var emptyCols = [],
				emptyRows = [];
				
				if(arr === 0){
					return null;
				}
				
				var rowsNum = arr.length,
				colsNum = (rowsNum>0 ? arr[0].length : 0),
				val = '',
				i,
				j,
				newArray = [];
			for( i = 0;i<colsNum; i++ ){
				emptyCols.push(true);
			}
			
			for( i = 0;i<rowsNum; i++ ){
				var emptyRow = true;
				for( j = 0; j<colsNum; j++ ){
					val = arr[i][j];
					if(val !== ''){
						emptyCols[j] = false;
						emptyRow = false;
					}
				}
				emptyRows.push(emptyRow);
			}
			
			for( i = 0;i<rowsNum; i++){
				var itemArray = [];
				for( j = 0; j<colsNum; j++ ){
					val = arr[i][j];
					if( !emptyCols[j] ){
						itemArray.push(val);
					}
				}
				if( !emptyRows[i] ){
					newArray.push(itemArray);
				}
			}
			return newArray;
		}
		
		function tabs(elementId){
			var element = $('#'+elementId),
				panels = element.find('.ui-tabs-panels'),
				panel = element.find('.ui-tabs-panel'),
				nav = element.find('.ui-state-default'),
				itemHeight = panel.outerHeight(),
				itemWidth = panel.outerWidth(),
				count = nav.length,
				windowWidth = $(window).width();
			panels.css('width',windowWidth);
			panels.css('height',itemHeight*count);
			
			nav.each(function(index,item){
				//$(panel[index]).removeClass('current');
				//$(panel[index]).css('visibility','visible');
				//if(index == 0){
				//	$(panel[index]).addClass('current');
				//}
				$(item).click(function(){
					//panel.each(function(i,ele){
					//	$(ele).removeClass('current');
					//});
					//title.html( '表 '+$(this).text());
					nav.each(function(i,ele){
						$(ele).removeClass('current');
					});
					//$(panel[index]).addClass('current');
					$(nav[index]).addClass('current');
					
					panels.css('top', - itemHeight*index );
				});
			});
		}
		
		if( json.success  && json.dataList && json.dataList.length>0){
			fillData();
		}else{
			if( json.type == 'no-config' ){
				createConfig();
			}else if( json.type == 'match' ){
				alert('匹配出错！');
			}
		}
		
		//setTimeout(function(){
			tabs('tabs');
		//},10);
	})(jQuery);
	</script>
</body>
</html>