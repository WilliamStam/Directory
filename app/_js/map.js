
$(document).ready(function () {
	
	resize()
	getData();
	
	$(window).resize(function () {
		$.doTimeout(250, function () {
			resize();
			
		});
	});

});
function getData() {
	var ID = $.bbq.getState("ID") || '';
	var page = $.bbq.getState("page") || '1';
	
	$(".loadingmask").show();
	
	
	
	
	$.getData("/data/home/data", {"page":page}, function (data) {

		

	//	$("#right-area-content").jqotesub($("#template-right"), data);
	//	$("#left-area-content").jqotesub($("#template-left"), data);
		
		var geocoder = null;
		var map = null;
		
		initialize();;

		$("#loading-mask").fadeOut();


		$.doTimeout(400,function(){
			page_resize();
			resize()

		})
		
		

	});
	
	
}

function resize(){
	var h = $(window).height();
	var searchBarHeight = $("#search-bar").outerHeight(true);
	h = h - searchBarHeight - 10
	$("#map-area").css({"height":h})
}

function initialize() {
	var mapOptions = {
		zoom  :14,
		center:new google.maps.LatLng(-23.043528 , 29.905191),
		mapTypeId:google.maps.MapTypeId.ROADMAP,
		disableDefaultUI:true
	};
	geocoder = new google.maps.Geocoder();
	map = new google.maps.Map(document.getElementById("map-area"), mapOptions);
	
}