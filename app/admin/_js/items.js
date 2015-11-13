$(document).ready(function () {
	$("#catlist").select2();
	getData();
	
	$(document).on("click", "#cat-list-area li > div", function (e) {
		var $this = $(this).closest("li"), ID = $this.attr("data-id");

		$.bbq.pushState({"ID":ID});

		getData();
		

	});

	
	$(document).on("click", "#btn-new", function (e) {
		e.preventDefault();
		$.bbq.removeState("ID");
		getData();
	});
	
	$(document).on("submit", "#search-form", function (e) {
		e.preventDefault();
		getData();
		return false;
		
	});
	$(document).on("click", "#lookup-coords-btn", function (e) {
		e.preventDefault();
		getCoords();
		return false;
		
	});
	
	$(document).on("click", "#list-action-awaiting", function (e) {
		e.preventDefault();
		getData();
		return false;
		
	});
	
	$(document).on("click", "#btn-delete", function (e) {
		e.preventDefault();
		var ID = $.bbq.getState("ID");
		if (confirm("Are you sure you want to delete this record?")) {
			$("#left-area .loadingmask").show();
			$.post("/admin/save/items/_delete/?ID=" + ID,{'r':Math.random()}, function (r) {
				$.bbq.removeState("ID");
				$.bbq.pushState({"msg":"Record Deleted"})
				getData();
			});
		}

	});
		$(document).on("change", "#catlist", function (e) {
		getData();

	});
	
	
	
	
	$(document).on("submit", "#capture-form", function (e) {
		e.preventDefault();
		var $this = $(this);
		var data = $this.serialize();
		var $submit = $("button[type='submit']", $this).html("Save changes").removeClass("btn-danger");
		$(".form-validation", $this).remove();
		$(".has-error", $this).removeClass("has-error");

		var ID = $.bbq.getState("ID");
		$("#left-area .loadingmask").show();
		$.post("/admin/save/items/_save/?ID=" + ID, data, function (r) {
			var data = r.data;
			//console.log(data)
			validationErrors(data, $this);
			if ($.isEmptyObject(data['errors'])) {
				$.bbq.pushState({"msg":"Record Saved"})
				getData();
			} else {
				$("#cat-list-area-checkboxes").addClass("has-error").after('<span class="help-block s form-validation">Need to choose at least 1 category</span>');
			}

		});
		return false;
	});
	$(document).on("change", "#cat-list-area-checkboxes label input:checkbox", function (e) {
		
		categories_active();
		
	});
	
	
	$(document).on("change","#gps_long, #gps_lat",function(){
		gps_changes()
	})
});

function getData() {

	var ID = $.bbq.getState("ID");

	var search = $("#search").val();
	var catID = $("#catlist").val();
	
	var msg = $.bbq.getState("msg");
	$.bbq.removeState("msg");
	$(".loadingmask").show();
	$.getData("/admin/data/items/data?ID="+ID+"&r="+Math.random(), {"search":search,"catID":catID}, function (data) {
		if (msg){
			data.msg = msg;
		}
		
		
		
		$("#cat-list-area").jqotesub($("#template-list"), data);
		$("#record-details").jqotesub($("#template-details"), data);
		
	
		
		
		//console.log(data.details.ID)
		
		if ($("#text").length) CKEDITOR.replace('text',ckeditor_config);
		if ($("#synopsis").length) CKEDITOR.replace('synopsis',ckeditor_config);
		categories_active()
		uploader();
		gps_changes();
		$(".loadingmask").fadeOut();

	},"data");

}

function uploader() {
	
	var uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight,html4',
		
		browse_button : 'item-uploader', // you can pass in id...
		container: document.getElementById('item-uploader-container'), // ... or DOM Element itself
		
		url: '/admin/save/items/upload',
		
		chunk_size: '30mb',
		unique_names: true,
		multiple_queues: true,
		
		
		init: {
			PostInit: function() {
				
			},
			
			FilesAdded: function(up, files) {
				setTimeout(function () { up.start(); }, 100);
			},
			
			UploadProgress: function(up, file) {
				
				$("#item-upload-info").html('<div class="progress"><div class="progress-bar" role="progressbar" aria-valuenow="2" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: '+file.percent+'%;">'+file.percent+'%</div></div>');
				//document.getElementById(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "%</span>";
			},
			FileUploaded: function (up, file, info) {
				//file.target_name
				$("#item-upload-info").html('<img alt="" src="/thumbnail/300/300?crop=false&file=/files/'+file.target_name+'" style="" class="img-thumbnail" />');
				
				$("#photo").val(file.target_name);
				
			},
			Error: function(up, err) {
				document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
			}
		}
	});
	
	uploader.init();
	
	
	//$("#item-upload-info").html('<img alt="" src="/thumbnail/300/300?crop=false&file=/items/<%= this.photo||'' %>" style="" class="img-thumbnail" />')
	
}function categories_active(){
	$("#cat-list-area-checkboxes label.active").removeClass("active");
	$("#cat-list-area-checkboxes input:checkbox:checked").each(function(){
		$(this).closest("label").addClass("active")
		
	})
}
function getCoords(){
	var address = $("#address").val();
	var geocoder = new google.maps.Geocoder();
	geocoder.geocode({
		"address": address
	}, function(results) {
		var lng = results[0].geometry.location.lng();
		var lat = results[0].geometry.location.lat();
		//console.log(results[0].geometry.location); //LatLng
		$("#gps_long").val(lng)
		$("#gps_lat").val(lat)
		initialize_map(results[0].geometry.location);
	});
	
	console.log(address)
}
function initialize_map(position) {
	var mapOptions = {
		zoom  :15,
		center:position,
		mapTypeId:google.maps.MapTypeId.ROADMAP,
		disableDefaultUI:true
	};
	geocoder = new google.maps.Geocoder();
	map = new google.maps.Map(document.getElementById("map-area"), mapOptions);
	
	var marker = new google.maps.Marker({
		position: position,
		map: map
	});
	
	
	
}

function gps_changes(){
	var gps_long = $("#gps_long").val();
	var gps_lat = $("#gps_lat").val();
	
	
	if (gps_long && gps_lat){
		var position = new google.maps.LatLng(gps_lat,gps_long);
		initialize_map(position)
	}
	
	
}