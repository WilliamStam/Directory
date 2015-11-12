$(document).ready(function () {
	
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
			$.post("/admin/save/categories/_delete/?ID=" + ID,{'r':Math.random()}, function (r) {
				$.bbq.removeState("ID");
				$.bbq.pushState({"msg":"Record Deleted"})
				getData();
			});
		}

	});
	$(document).on("change", "#cat-list-area label input:checkbox", function (e) {

		categories_active();
		
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
		$.post("/admin/save/categories/_save/?ID=" + ID, data, function (r) {
			var data = r.data;
			//console.log(data)
			validationErrors(data, $this);
			if ($.isEmptyObject(data['errors'])) {
				$.bbq.pushState({"msg":"Record Saved"})
				getData();
			}

		});
		return false;
	});

	

});

function getData() {

	var ID = $.bbq.getState("ID");

	var search = $("#search").val();
	var catID = $("#search-catID").val();
	
	var msg = $.bbq.getState("msg");
	$.bbq.removeState("msg");
	$(".loadingmask").show();
	$.getData("/admin/data/categories/data?ID="+ID, {"search":search,"catID":catID}, function (data) {
		if (msg){
			data.details.msg = msg;
		}
		
		
		
		var $recordsList = $("#cat-list-area").html("");
		
		$recordsList.jqotesub($("#template-list"), data);
		sort();
		
		
		if (data['search']) $("#search").val(data['search']);
		

		$("#record-details").jqotesub($("#template-details"), data['details']);


		$(".publications").select2();
		categories_active();
		
		CKEDITOR.replace('text',ckeditor_config);
		
		uploader()
		$(".loadingmask").fadeOut();

	},"data");

}
function categories_active(){
	$("#cat-list-area label.active").removeClass("active");
	$("#cat-list-area input:checkbox:checked").each(function(){
		$(this).closest("label").addClass("active")
		
	})
	
	
}
function sort(){
	$("#cat-list-area > ol").nestedSortable({
		handle          : 'div',
		items           : 'li',
		toleranceElement: '> div',
		
		update:function(){
			
			var orderNew = $('#cat-list-area > ol').nestedSortable('serialize');
			//console.log(orderNew)

	

			 $.post("/admin/save/categories/order",orderNew,function(d){
				 getData()
			 });
		
		}
		
	});
}
function uploader() {
	
	var uploader = new plupload.Uploader({
		runtimes : 'html5,flash,silverlight,html4',
		
		browse_button : 'item-uploader', // you can pass in id...
		container: document.getElementById('item-uploader-container'), // ... or DOM Element itself
		
		url: '/admin/save/categories/upload',
		
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
				$("#item-upload-info").html('<img alt="" src="/thumbnail/300/300?crop=false&file=/categories/'+file.target_name+'" style="" class="img-thumbnail" />');
				
				$("#photo").val(file.target_name);
			},
			Error: function(up, err) {
				document.getElementById('console').innerHTML += "\nError #" + err.code + ": " + err.message;
			}
		}
	});
	
	uploader.init();
	
	
	//$("#item-upload-info").html('<img alt="" src="/thumbnail/300/300?crop=false&file=/categories/<%= this.photo||'' %>" style="" class="img-thumbnail" />')
	
}