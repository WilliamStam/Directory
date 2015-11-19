
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
	
	
	var markers = [];
	
	var lat_min = 10000;
	var lat_max = 0;
	var lng_min = 10000;
	var lng_max = 0;
	
	
	for (var i in places){
		var t = $('<div></div>').html(places[i].placeTitle).text();
		var pos = new google.maps.LatLng(places[i].lat, places[i].lng);
		markers[i] = new google.maps.Marker({
			position: pos,
			map: map,
			url:places[i].url,
			title: t,
			tooltip: places[i].tooltip
		});
		console.log(pos);
		
		//if (pos.lat() <= lat_min)lat_min = pos.lat()
		//if (pos.lng() <= lng_min)lng_min = pos.lng()
		
	}
	
	var infowindow = new google.maps.InfoWindow({
		content: ''
	});
	
	
	console.log("lat min:"+lat_min+" | lng min:"+lng_min+" | ")
	
	/*
	
	map.setCenter(new google.maps.LatLng(
			((lat_max + lat_min) / 2.0),
			((lng_max + lng_min) / 2.0)
	));
	map.fitBounds(new google.maps.LatLngBounds(
			//bottom left
			new google.maps.LatLng(lat_min, lng_min),
			//top right
			new google.maps.LatLng(lat_max, lng_max)
	));
	*/
	
	
	for ( i = 0; i < markers.length; i++ ) {
		var marker = markers[i];
		google.maps.event.addListener(marker, 'click', function() {
			window.location.href = this.url;  //changed from markers[i] to this[i]
		});
		
		
		google.maps.event.addListener(marker, 'mouseover', function() {
			//console.log(this.tooltip)
			infowindow.setContent(this.tooltip);
			infowindow.open(map, this);
			$(".gm-style-iw").next("div").hide();
		});
		google.maps.event.addListener(marker, 'mouseout', function() {
			infowindow.close();
		});
	}
	
	
	
	
}