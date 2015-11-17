
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
		
		
		
	}
	
	
}