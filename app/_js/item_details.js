
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
		
		
		var panoOptions = {
			position: location,
			panControl: false,
			addressControl: false,
			linksControl: false,
			zoomControlOptions: false
		};
		// initialize a new panorama API object and point to the element with ID streetview as container
		var pano = new  google.maps.StreetViewPanorama(document.getElementById('pano'),panoOptions);
		// initialize a new streetviewService object
		var service = new google.maps.StreetViewService;
		// call the "getPanoramaByLocation" function of the Streetview Services to return the closest streetview position for the entered coordinates
		service.getPanoramaByLocation(pano.getPosition(), 50, function(panoData) {
			// if the function returned a result
			if (panoData != null) {
				// the GPS coordinates of the streetview camera position
				var panoCenter = panoData.location.latLng;
				// this is where the magic happens!
				// the "computeHeading" function calculates the heading with the two GPS coordinates entered as parameters
				var heading = google.maps.geometry.spherical.computeHeading(panoCenter, location);
				// now we know the heading (camera direction, elevation, zoom, etc) set this as parameters to the panorama object
				var pov = pano.getPov();
				pov.heading = heading;
				pano.setPov(pov);
				// set a marker on the location we are looking at, to verify the calculations were correct
				var marker = new google.maps.Marker({
					map: pano,
					position: location
				});
				$("#pano").show();
			} else {
				// no streetview found :(
				$("#pano").hide();
			}
		});
		
		
		
		
		
		
		
		
		
		
		//console.log(google.maps.StreetViewStatus)
			
	}
	
	
}