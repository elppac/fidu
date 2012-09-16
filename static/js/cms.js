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
			fiduNavBox.html('未找到可用标签');
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
				moduleObject.data = getFiduTemplateText(newList[index],dataHtml);
				moduleObject.name = newList[index];
				$( "#fidu-iframe" ).dialog( "open" );
				$('#fidu-page').attr( 'src','fidu-enter.php');
			});
		});
	}
	
	$( "#fidu-iframe" ).dialog({
		autoOpen: false,
		height: $(window).height() - 40,
		width: $(window).width()- 40,
		modal: true
	});
	
	
	$('#btn-builder-nav').click( function(e){
		builderNav();
	});
})(jQuery);