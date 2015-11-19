
$(document).ready(function () {
	
	
	initialize()
	

});



function initialize() {
	var $map = $("#map-canvas");
	var location = new google.maps.LatLng($map.attr("data-lat") , $map.attr("data-lng"));
	if (location){
		var mapOptions = {
			zoom  :14,
			center:location,
			mapTypeId:google.maps.MapTypeId.ROADMAP,
			disableDefaultUI:true
		};
		geocoder = new google.maps.Geocoder();
		map = new google.maps.Map($map[0], mapOptions);
		
		
		var marker = new google.maps.Marker({
			position: location,
			map: map
		});
		
		var contentString = '<div id="content"><strong>'+$map.attr("title")+'</strong>'	+'</div>';
		
		var infowindow = new google.maps.InfoWindow({
			content: contentString
			
		});
		
		google.maps.event.addListener(infowindow, 'domready', function(){
			$(".gm-style-iw").next("div").hide();
		});
		
		infowindow.open(map,marker);
		
		
		var streetViewService = new google.maps.StreetViewService();
		var STREETVIEW_MAX_DISTANCE = 100;
		var latLng = location;
		streetViewService.getPanoramaByLocation(latLng, STREETVIEW_MAX_DISTANCE, function (streetViewPanoramaData, status) {
			if (status === google.maps.StreetViewStatus.OK) {
				// ok
				
				var panorama = new google.maps.StreetViewPanorama(
						document.getElementById('pano'), {
							position: location,
							
						});
				
				
				map.setStreetView(panorama);
				$("#pano").show();
			} else {
				$("#pano").hide();
				// no street view available in this range, or some error occurred
			}
		});
		
		
		
		
		
		
		
		
		//console.log(google.maps.StreetViewStatus)
			
	}
	
	
}