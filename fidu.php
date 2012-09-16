<?php
require_once 'fidu/HTML2Data.php';
header('Content-Type: text/html; charset=UTF-8');
$moduleData =$_POST['module-data'];
$moduleName =$_POST['module-name'];
$cmsId =$_POST['cms-id'];

$html2data = new HTML2Data( $moduleData,$moduleName,$cmsId );
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Fidu</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
	<script src="handsontable/lib/jquery.js"></script>
	<script src="handsontable/jquery.handsontable.js"></script>
	<script src="handsontable/lib/bootstrap-typeahead.js"></script>
	<script src="handsontable/lib/jquery.autoresize.js"></script>
	<script src="handsontable/lib/jQuery-contextMenu/jquery.contextMenu.js"></script>
	<script src="handsontable/lib/jQuery-contextMenu/jquery.ui.position.js"></script>
	<script src="handsontable/demo/js/json2.min.js"></script>
	<link rel="stylesheet" media="screen" href="handsontable/lib/jQuery-contextMenu/jquery.contextMenu.css">
	<link rel="stylesheet" media="screen" href="handsontable/jquery.handsontable.css">
	<link rel="stylesheet" media="screen" href="static/css/fidu.css">
	<style type="text/css">
	</style>
</head>
 <body>

	<script> 
		var json = <?php echo $html2data->toData();?>

	</script>
	<div class="ui-tabs ui-green" id="tabs">
		<div class="ui-tabs-panels-wrap">
			<div class="ui-tabs-panels">
			</div>
		</div>
		<dl class="ui-tabs-nav">
		</dl>
	</div>
	<div class="ctrl"></div>
	<form action="fidu-bus.php" id="excelForm">
		<input type="hidden" name="tableData" id="table-data" />
		<input type="hidden" name="tableName" id="table-name" />
		<input type="hidden" name="table" id="table" />
		<input type="hidden" name="action" id="action" value="savedata" />
	</form>
	<button id="btn-save">Save</button>
	<textarea id="data-output" style="display:block; font-size:12px; width:648px; height:200px;" ><?php print_r(''); ?></textarea>
	<script>
	(function($){
		if(json.dataList && json.dataList.length>0){
			var 	element = $('#tabs'); 
				panelsArr = [],
				navsArr = [],
				tables = [],
				panels = element.find('.ui-tabs-panels'),
				navs = element.find('.ui-tabs-nav'),
				button = $('#btn-save');
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
				
				ele.handsontable({
					//rows: data.length,
					//cols: data[0].length,
					rowHeaders: true,
					colHeaders: true,
					minSpareCols: 1,
					minSpareRows: 1,
					contextMenu: true,
					RemoveRow: true
				});
				ele.handsontable("loadData", data);
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
						'moduleName' : '<?php echo $moduleName; ?>',
						'moduleId' : '<?php echo $cmsId; ?>',
						'tableData':$('#table-data').val(),
						'tableName':$('#table-name').val(),
						'table' : $('#table').val(),
						'action' : $('#action').val()
					},
					function( data ) {
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
				count = nav.length;
			
			panels.css('width',itemWidth);
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
					nav.each(function(i,ele){
						$(ele).removeClass('current');
					});
					//$(panel[index]).addClass('current');
					$(nav[index]).addClass('current');
					
					panels.css('top', - itemHeight*index );
				});
			});
		}
		setTimeout(function(){
			tabs('tabs');
		},10);
	})(jQuery);
	</script>
 </body>
</html>