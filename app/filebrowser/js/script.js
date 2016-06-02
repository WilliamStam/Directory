/*
 * Date: 2011/03/09 - 8:44 AM
 */


$(document).ready(function(){
	//$("#path-heading .breadcrumb").html(breadcrumb_path("/"));
	var path = "/";
	if ($.bbq.getState("path")){
		path = $.bbq.getState("path");
	}
	
	var template = "thumbnails";
	if ($.bbq.getState("template")){
		template = $.bbq.getState("template");
	}
	$(".template-select.active").removeClass("active");
	$(".template-select[data-template='"+template+"']").addClass("active");
	
	if ($.bbq.getState("sort")){
		$("#filebrowser-sort").val($.bbq.getState("sort"));
	}
	
	order_state();
	

	$("#path-heading .breadcrumb").html(breadcrumb_path(path));
	getData()
	
	
	
	$(document).on("click","a.path_link",function(e){
		e.preventDefault();
		var $this =  $(this), path = $this.attr("data-path");
		$("ol.tree li.active").removeClass("active");
		$(".breadcrumb li.active").removeClass("active");
		
		
		
		$("#path-heading .breadcrumb").html(breadcrumb_path(path));
		getData();
		
	})	;

	$(document).on("click","#details-pane-content a", function(e) {
		
		e.preventDefault();
		fileselected($(this).closest("li"));
		return false;
	});
	$(document).on("click","#details-pane-content tr", function(e) {
		
		e.preventDefault();
		fileselected($(this));
		return false;
	});
	$(".template-select a").click(function(e){
		e.preventDefault();
	});
	
	$(".template-select").click(function(){
		var $this = $(this);
		$(".template-select.active").removeClass("active");
		$this.addClass("active");
		$.bbq.pushState({"template":$this.attr("data-template")});
		getData();
	});

	$("#filebrowser-sort").change(function(){

		$.bbq.pushState({"sort":$(this).val()});
		getData();
	});

	$(document).on("click","#filebrowser-order a",function(e){
		e.preventDefault();
		var order = "ASC";
		if ($(this).parent().attr("data-order")=="ASC"){
			order = "DESC";
		} 
		
		$.bbq.pushState({"order":order});
		order_state();
		getData();
	});
	$(document).on("click",".reload",function(e){
		e.preventDefault();
		getData();
	});
	$(document).on("click",".cancel-selected",function(e){
		e.preventDefault();
		fileselected("")
	});
	
	$(document).on("click","#btn-file-rename",function(e){
		e.preventDefault();
		var newname = prompt("Rename: "+$.bbq.getState("file"), $.bbq.getState("file"));
		if (newname){
			
			$.post("index.php?section=do_file_rename",{"path": $.bbq.getState("path"),"old": $.bbq.getState("file"),"new":newname},function(r){
				if (r['error']){
					alert(r['error']);
				} else {
					$.bbq.pushState({"file":r['new']});
					getData();
				}
				
			})
		}
		
		
	});
	
	$(document).on("click","#btn-file-delete",function(e){
		e.preventDefault();
		if (confirm("Are you sure you want to delete this file?\n\r\n\r"+$.bbq.getState("file"))){
			
			$.post("index.php?section=do_file_delete",{"path": $.bbq.getState("path"),"file": $.bbq.getState("file")},function(r){
				if (r['error']){
					alert(r['error']);
				} else {
					fileselected("");
					getData();
				}
				
			})
		}
		
		
	});

	$(document).on("click","#btn-folder-new",function(e){
		e.preventDefault();
		var newname = prompt("New folder inside:\n\r"+ $.bbq.getState("path"));
		if (newname){

			$.post("index.php?section=do_folder_new",{"path": $.bbq.getState("path"),"new":newname},function(r){
				if (r['error']){
					alert(r['error']);
				} else {
					$.bbq.pushState({"path":r['folder']});
					getData();
				}

			})
		}


	});

	$(document).on("click","#btn-folder-rename",function(e){
		e.preventDefault();
		
		var oldname = $.bbq.getState("path");
		oldname = oldname.split("/");
		oldname = oldname[oldname.length-1];
		
		
		var newname = prompt("Rename: "+$.bbq.getState("path"),oldname );
		if (newname){

			$.post("index.php?section=do_folder_rename",{"path": $.bbq.getState("path"),"old": oldname,"new":newname},function(r){
				if (r['error']){
					alert(r['error']);
				} else {
					$.bbq.pushState({"path":r['path']});
					getData();
				}

			})
		}


	});
	
	$(document).on("click","#btn-folder-delete",function(e){
		e.preventDefault();
		if (confirm("Are you sure you want to delete this folder?\n\r\n\r"+$.bbq.getState("path")+"\n\r\n\rAll contents will be deleted aswell")){
			
			$.post("index.php?section=do_folder_delete",{"path": $.bbq.getState("path")},function(r){
				if (r['error']){
					alert(r['error']);
				} else {
					$.bbq.pushState({"path":r['path']});
					fileselected("");
					getData();
				}
				
			})
		}
		
		
	});
	
	
	$(document).on("click",".upload-btn",function(e){
		e.preventDefault();
		
		$(".plupload_droptext").html("Drag files here or click 'Add Files', &nbsp; When done click 'Start Upload' to upload the files to the current folder");
		
		$("#upload-modal").modal("show")
		
	});
	
	$(document).on("hide","#upload-modal",function(){
		getData();
	});
	
	
	$(document).on("click","#select-btn",function(e){
		e.preventDefault();


		onSelectfile();
		
	});
	
	$(document).on("click","#close-btn",function(e){
		e.preventDefault();


		onClose();
		
	});

	$("#uploader").pluploadQueue({
		// General settings
		runtimes: 'html5,gears,flash,silverlight',
		url     : 'index.php?section=upload',

		chunk_size         : '3mb',
		unique_names       : false,
		multiple_queues    : true,

		

		// Flash settings
		flash_swf_url      : '/ui/plupload/js/Moxie.swf',

		// Silverlight settings
		silverlight_xap_url: '/ui/plupload/js/Moxie.xap',

		init: {
			BeforeUpload: function(uploader, file) {

				uploader.settings.url = "index.php?section=upload&path=" + $.bbq.getState("path");
				
			},
	
	
			Refresh       : function (up) {

			},
			StateChanged  : function (up) {

			},
			QueueChanged  : function (up) {

			},
			UploadProgress: function (up, file) {

			},
			FilesAdded    : function (up, files) {
				plupload.each(files, function (file) {

				});
			},
			FilesRemoved  : function (up, files) {

			},
			FileUploaded  : function (up, file, info) {



			},
			ChunkUploaded : function (up, file, info) {

			},
			Error         : function (up, args) {

			}

		}
	});
	
	
	
});

function breadcrumb_path(path){
	//path = path.replace("\/\/","\/");
	$("a[data-path='"+path+"']").closest("li").addClass("active");
	
	if (!path){
		path = "/";
	}
	
	if (path=="/" || path==""){
		$("#btn-folder-rename").closest("li").hide();
		$("#btn-folder-delete").closest("li").hide();
	} else {
		$("#btn-folder-rename").closest("li").show();
		$("#btn-folder-delete").closest("li").show();
	}


	if ($.bbq.getState("path") != path){
		$.bbq.pushState({"path":path});
	}
	
	var pathing = path.split("/");
	//pathing.splice(0, 1);
	var str = '';
		var p = "";
	for (index = 0; index < pathing.length; ++index) {
		p = p + "/" + pathing[index];
		p = p.replace("\/\/","\/");
		//console.log(p + " | " + index + " | "+pathing.length); 
		
		var item = pathing[index];
		if (item==""){
			item = "root";
		}
		
		if (index == (pathing.length - 1)){
			str += '<li class="active">'+item+'</li>';
		} else {
			str += '<li><a href="#" class="path_link" data-path="'+p+'">'+item+' </a> <span class="divider">/</span></li>';
		}
		
		//console.log(pathing[index]);
	}
	
	str = str.replace('<li><a href="#" class="path_link" data-path="/">root </a> <span class="divider">/</span></li><li class="active">root</li>','<li class="active">root</li>')


	
	//console.log(str); 
	
	return str;
}
function getData(){
	var path = $.bbq.getState("path");
	var show = "1";
	var sort = $("#filebrowser-sort").val();
	var order = $.bbq.getState("order");;
	
	var $details_pane_content = $("#details-pane-content").html('<img src="/ui/_images/loading-wide.gif" class="loading_wide" width="208" alt=""/>');
	$.post("index.php?section=files",{"path":path,"show":show,"sort":sort,"order":order},function(r){

		
		//console.log(r['folder']); 
		$("#folder-list .tree").html(create_folder_node(r['folders']));
		$("a[data-path='"+r['folder']+"']").closest("li").addClass("active");

		if (r['files'].length){
			
			var template = $.bbq.getState("template")?$.bbq.getState("template"):"thumbnails";
			
			$details_pane_content.jqotesub($("#template-files-"+template), r);
		} else {
			$details_pane_content.html("<div class='g no_records_found' >no files found in this folder</div>");
		}
		
		if ($.bbq.getState("file")){
			var $selected = $("*[data-fb-name='"+ $.bbq.getState("file") +"']")
		}
		fileselected($selected)

		$("#path-heading .breadcrumb").html(breadcrumb_path($.bbq.getState("path")));
	
		// /Folder 1/Folder 1.2/Folder 1.2.3
		// /Folder 1/Folder 1.2/Folder 1.2.3
	})
	
	
}
function create_folder_node(data){
	//console.log(data[0].text); 
	var str = "";
	var index=0;
	for (index = 0; index < data.length; ++index) {
		//console.log(data[index].text)
		
		str += '<li ><a href="#" data-path="'+data[index].path+'" class="path_link"><i class="icon-folder-close-alt"></i>'+data[index].text+'</a>';
		if (data[index].children.length){
			str += '<ol>';
			str += create_folder_node(data[index].children)
			str += '</ol>';
		}
		str += '</li>';
	}
	
	return str;
	
}
function onSelectfile() {
	//console.info(CKEditorFuncNum)
//	console.log(media_dir + $.bbq.getState("path") + "/" + $.bbq.getState("file"))
	
	
	if (window.opener) {
		window.opener.CKEDITOR.tools.callFunction(CKEditorFuncNum, media_dir + $.bbq.getState("path") + "/" + $.bbq.getState("file"));
	}
	onClose();

}
function onClose() {
	self.close();
}
function fileselected(file){
	$("#details-pane-content .selected").removeClass("selected");
	if (file && file.length){
			var data = {
				dim_y: file.attr("data-fb-dim-y"),
				dim_x: file.attr("data-fb-dim-x"),
				perms: file.attr("data-fb-perms"),
				c_time: file.attr("data-fb-c_time"),
				a_time: file.attr("data-fb-a_time"),
				m_time: file.attr("data-fb-m_time"),
				raw_size: file.attr("data-fb-raw_size"),
				size: file.attr("data-fb-size"),
				type: file.attr("data-fb-type"),
				name: file.attr("data-fb-name"),
				folder : file.attr("data-fb-folder")
			};

			if ($.bbq.getState("file") != data['name']){
				$.bbq.pushState({file:data["name"]});
			}

			$("#details-pane-content .selected").removeClass("selected");
			file.addClass("selected");

			$("#page-footer").jqotesub($("#template-file-selected"), data);

		var $selected = $("*[data-fb-name='"+ data['file'] +"']");

		$selected.addClass("selected");
		
		
		
	} else {
		$.bbq.removeState("file");
		
		$("#page-footer").jqotesub($("#template-no-file-selected"), {});
	}
	
	
}
function order_state(){
	var order = $.bbq.getState("order");
	if (!order){
		order = "ASC"
	}
	var $order = $("#filebrowser-order");
	
	//<a href="#"><i class="icon-sort-by-attributes"></i></a>
	
	switch(order){
		case "DESC":
			$order.attr("data-order","DESC").html('<a href="javascript:void();"><i class="icon-sort-by-attributes-alt"></i></a>');		
			break;
		default:
			$order.attr("data-order","ASC").html('<a href="javascript:void();"><i class="icon-sort-by-attributes"></i></a>');
			break;
	}
	
	
	
}