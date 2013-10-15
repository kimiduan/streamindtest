$(function(){
		$('.popover2').popover({
			 html : true,
			 content: function() {
				 $.get('https://www.googleapis.com/freebase/v1/search?query='+$(this).data('original-title')+'&lang=zh',function(result){ 
				 var encoded = $.toJSON(result);
				 var elements = $.evalJSON( encoded ).result;var topic = elements[0].mid;
				 $.get('https://www.googleapis.com/freebase/v1/topic/'+topic+'?filter=/common/topic/description&lang=zh',function(result){
					 var encoded2 = $.toJSON(result);
					 var description = $.evalJSON( encoded2 ).property;
					 $.each(description, function( index, value){$('#popover_content_wrapper').html(value.values[0].value);})})})
					 return $('#popover_content_wrapper').html();}});});