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
	
		

	
	$(document).on("click", "#btn-delete", function (e) {
		e.preventDefault();
		var ID = $.bbq.getState("ID");
		if (confirm("Are you sure you want to delete this record?")) {
			$("#left-area .loadingmask").show();
			$.post("/admin/save/users/_delete/?ID=" + ID,{'r':Math.random()}, function (r) {
				$.bbq.removeState("ID");
				$.bbq.pushState({"msg":"Record Deleted"})
				getData();
			});
		}

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
		$.post("/admin/save/users/_save/?ID=" + ID, data, function (r) {
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
	$.getData("/admin/data/users/data?ID="+ID, {"search":search,"catID":catID}, function (data) {
		
		if (msg){
			data.details.msg = msg;
		}
		
		//console.log(data.msg)
		
		var $recordsList = $("#cat-list-area").html("");
		
		$recordsList.jqotesub($("#template-list"), data);
		
		
		if (data['search']) $("#search").val(data['search']);
		

		$("#record-details").jqotesub($("#template-details"), data['details']);


		
		
		
		$(".loadingmask").fadeOut();

	},"data");

}