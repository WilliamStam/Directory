
$(document).ready(function () {
	
	

	getData();

});
function getData() {
	var ID = $.bbq.getState("ID") || '';
	var page = $.bbq.getState("page") || '1';
	
	$(".loadingmask").show();
	
	
	
	
	$.getData("/data/home/data", {"page":page}, function (data) {

		

		$("#right-area-content").jqotesub($("#template-right"), data);
		$("#left-area-content").jqotesub($("#template-left"), data);
		

		$("#loading-mask").fadeOut();


		$.doTimeout(400,function(){
			page_resize();
			

		})
		
		

	});
	
	
}

