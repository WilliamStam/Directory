var map = null;
var markers = [];

$(document).ready(function () {
	
	resize()
	if ($.bbq.getState("catID")){
		getData();
	} else {
		initialize(places)
	}
	
	$(window).resize(function () {
		$.doTimeout(250, function () {
			resize();
			
		});
	});

	$("#catlist").on("change",function(){
		$.bbq.pushState({catID:$(this).val()})
		getData();
	}).select2();
});
function getData() {
	var catID = $.bbq.getState("catID");
	
	$(".loadingmask").show();
	
	$("#catlist").val(catID)
	
	$.getData("/data/map/data", {"catID":catID}, function (data) {

		

	//	$("#right-area-content").jqotesub($("#template-right"), data);
	//	$("#left-area-content").jqotesub($("#template-left"), data);
		
		var geocoder = null;
		var map = null;
		
		initialize(data);;

		$(".loadingmask").fadeOut();


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

function initialize(places) {
	var mapOptions = {
		zoom  :14,
		center:new google.maps.LatLng(-23.043528 , 29.905191),
		mapTypeId:google.maps.MapTypeId.ROADMAP,
		disableDefaultUI:true
	};
	geocoder = new google.maps.Geocoder();
	map = new google.maps.Map(document.getElementById("map-area"), mapOptions);
	
	
	
	
	
	var bounds = new google.maps.LatLngBounds();
	for (var i in places){
		var t = $('<div></div>').html(places[i].placeTitle).text();
		var pos = new google.maps.LatLng(places[i].lat, places[i].lng);
		markers[i] = new google.maps.Marker({
			position: pos,
			map: map,
			url:places[i].url,
			title: t,
			tooltip: places[i].tooltip,
			//icon: image
		});
		bounds.extend(pos );
		
	}
	
	
	var infowindow = new google.maps.InfoWindow({
		content: ''
	});
	
	if (bounds.getNorthEast().equals(bounds.getSouthWest())) {
		var extendPoint1 = new google.maps.LatLng(bounds.getNorthEast().lat() + 0.01, bounds.getNorthEast().lng() + 0.01);
		var extendPoint2 = new google.maps.LatLng(bounds.getNorthEast().lat() - 0.01, bounds.getNorthEast().lng() - 0.01);
		bounds.extend(extendPoint1);
		bounds.extend(extendPoint2);
	}
	
	map.fitBounds(bounds);
	
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
function fitBoundsToVisibleMarkers() {
	
	var bounds = new google.maps.LatLngBounds();
	
	for (var i=0; i<markers.length; i++) {
		bounds.extend( markers[i].getPosition() );
	}
	
	map.fitBounds(bounds);
	
}