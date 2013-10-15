$(function(){
  $("#url_submit").click(function(){
     var url = $("#input_url").val();
	 $.post("api/url.php",{url:url},function(result){
	    $(".demo-content").show();
		var i=0;
	    var encoded = $.toJSON(result);
		var title = $.evalJSON(encoded).title;
		var content = $.evalJSON( encoded ).content;
		var text = $.evalJSON( encoded ).text;
		var elements = $.evalJSON( encoded ).elements;
		var elements_table = "<div class='table-responsive'><table class='table table-hover' style='text-align:center'><thead><tr><th style='width:50%'>元素</th><th>相关</th></tr></thead><tbody>";
		$.each(elements.keywords, function( index, value ) {
		elements_table = elements_table+"<tr><td>"+value.word+"</td><td><a href='#' class='popover2' data-placement='top' data-original-title='"+value.word+"'>显示更多</a></td></tr>";
		   i = i+1;
        });
		$.each(elements.noun, function( index, value ) {
        if( index <10){
		   elements_table = elements_table+"<tr><td>"+value.word+"</td><td>显示更多</td></tr>";
		   i = i+1;
		}else{
		  return false;
		}
        });
		$.each(elements.vn, function( index, value ) {
        if( index <4){
		   elements_table = elements_table+"<tr><td>"+value.word+"</td><td>显示更多</td></tr>";
		   i = i+1;
		}else{
		  return false;
		}
        });
		$.each(elements.ns, function( index, value ) {
		   elements_table = elements_table+"<tr><td>"+value.word+"</td><td>显示更多</td></tr>";
		   i = i+1;
        });
		$.each(elements.nr, function( index, value ) {
		   elements_table = elements_table+"<tr><td>"+value.word+"</td><td>显示更多</td></tr>";
		   i = i+1;
        });
		$.each(elements.nt, function( index, value ) {
		   elements_table = elements_table+"<tr><td>"+value.word+"</td><td>显示更多</td></tr>";
		   i = i+1;
        });
		$.each(elements.nz, function( index, value ) {
		   elements_table = elements_table+"<tr><td>"+value.word+"</td><td>显示更多</td></tr>";
		   i = i+1;
        });
		$.each(elements.names, function( index, value ) {
		   elements_table = elements_table+"<tr><td>"+value+"</td><td>显示更多</td></tr>";
		   i = i+1;
        });
		elements_table = elements_table+"</tbody></table></div><script type='text/javascript' src='../js/freebase.js'>/script>";
		$("#title").html(title);
		$("#content").html(content);
		$("#elements").html(elements_table);
		$("#elements_num").html(i);
		$.post("api/getsummary.php",{text:text},function(result){$("#summary").html(result)});
	 });
  });
  
    $("#text_submit").click(function(){
     var text = $("#input_text").val();
	 $.post("api/text.php",{text:text},function(result){
	    $(".demo-content").show();
		var i=0;
	    var encoded = $.toJSON(result);
		var content = $.evalJSON( encoded ).content;
		var text = $.evalJSON( encoded ).text;
		var elements = $.evalJSON( encoded ).elements;
		var elements_table = "<div class='table-responsive'><table class='table table-hover' style='text-align:center'><thead><tr><th style='width:50%'>元素</th><th>相关</th></tr></thead><tbody>";
		$.each(elements.noun, function( index, value ) {
        if( index <10){
		   elements_table = elements_table+"<tr><td>"+value.word+"</td><td>显示更多</td></tr>";
		   i = i+1;
		}else{
		  return false;
		}
        });
		$.each(elements.vn, function( index, value ) {
        if( index <4){
		   elements_table = elements_table+"<tr><td>"+value.word+"</td><td>显示更多</td></tr>";
		   i = i+1;
		}else{
		  return false;
		}
        });
		$.each(elements.ns, function( index, value ) {
		   elements_table = elements_table+"<tr><td>"+value.word+"</td><td>显示更多</td></tr>";
		   i = i+1;
        });
		$.each(elements.nr, function( index, value ) {
		   elements_table = elements_table+"<tr><td>"+value.word+"</td><td>显示更多</td></tr>";
		   i = i+1;
        });
		$.each(elements.nt, function( index, value ) {
		   elements_table = elements_table+"<tr><td>"+value.word+"</td><td>显示更多</td></tr>";
		   i = i+1;
        });
		$.each(elements.nz, function( index, value ) {
		   elements_table = elements_table+"<tr><td>"+value.word+"</td><td>显示更多</td></tr>";
		   i = i+1;
        });
		$.each(elements.names, function( index, value ) {
		   elements_table = elements_table+"<tr><td>"+value+"</td><td>显示更多</td></tr>";
		   i = i+1;
        });
		elements_table = elements_table+"</tbody></table></div>";
		$("#content").html(content);
		$("#elements").html(elements_table);
		$("#elements_num").html(i);
		$.post("api/getsummary.php",{text:text},function(result){$("#summary").html(result)});
	 });
  });
  
})