
$(document).ready(function () {
	
	
	initialize()
	

});



function initialize() {
	var mapOptions = {
		zoom  :14,
		center:new google.maps.LatLng(-23.043528 , 29.905191),
		mapTypeId:google.maps.MapTypeId.ROADMAP,
		disableDefaultUI:true
	};
	geocoder = new google.maps.Geocoder();
	map = new google.maps.Map(document.getElementById("map-canvas"), mapOptions);
	
}