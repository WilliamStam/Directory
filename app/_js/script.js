;


var toolbar = [
	['Source'],
	[
		'Cut',
		'Copy',
		'Paste',
		'Find',
		'Replace'
	],
	[
		'Bold',
		'Italic',
		'Underline',
		'StrikeThrough'
	],
	[
		'Styles',
		'Format',
		'Font',
		'FontSize'
	],
	[
		'NumberedList',
		'BulletedList'
	],
	[
		'Outdent',
		'Indent'
	]
];
var toolbar_small = [
	['Source'],
	[
		'Cut',
		'Copy',
		'Paste',
		'Find',
		'Replace'
	],
	[
		'Bold',
		'Italic',
		'Underline',
		'StrikeThrough'
	]
];
var ckeditor_config = {
	height            : '150px',
	toolbar           : toolbar,
	extraPlugins      : 'autogrow',
	autoGrow_minHeight: 150,
	autoGrow_maxHeight: 0,
	removePlugins     : 'elementspath',
	resize_enabled    : false,
	skin : 'bootstrapck,/app/_css/ckeditor/bootstrapck/',
	on :{
		instanceReady : function( ev ){
			this.dataProcessor.writer.setRules( '*',
					{
						indent : false,
						breakBeforeOpen : true,
						breakAfterOpen : false,
						breakBeforeClose : false,
						breakAfterClose : true
					});
		}
	}
};
var ckeditor_config_small = {
	height            : '117px',
	toolbar           : toolbar_small,
	removePlugins     : 'elementspath',
	resize_enabled    : false,
	extraPlugins      : 'autogrow',
	autoGrow_minHeight: 117,
	autoGrow_maxHeight: 0,
	skin : 'bootstrapck,/app/_css/ckeditor/bootstrapck/',
	on :{
		instanceReady : function( ev ){
			this.dataProcessor.writer.setRules( '*',
					{
						indent : false,
						breakBeforeOpen : true,
						breakAfterOpen : false,
						breakBeforeClose : false,
						breakAfterClose : true
					});
		}
	}
};


;$(document).ready(function () {
	
	$.fn.keepAlive();
	
	$("#footer-nav-bar-red").removeClass("navbar-fixed-bottom")


	$('[data-toggle="tooltip"]').tooltip();
	
	$("[data-collapse-group]").on('show.bs.collapse', function () {
		var $this = $(this);
		var thisCollapseAttr = $this.attr('data-collapse-group');
		$("[data-collapse-group='" + thisCollapseAttr + "']").not($this).collapse('hide');
	});
	
	
	
	
	$(document).on('click', '.resend-activation-email', function (e) {
		e.preventDefault();
		$.post("/save/profile/resend",{"p":"w"},function(data){
			data = data.data;
			//console.log(data);
			$("#form-modal").jqotesub($("#template-user-activate-sent"), data).modal("show");
		})
		
		
		
	});
	$(document).on('click', '.btn-row-details', function (e) {
		var $this = $(this), $table = $this.closest("table");
		var $clicked = $(e.target).closest("tr.btn-row-details");
		var active = true;

		if ($this.hasClass("active") && $clicked) active = false;

		$("tr.btn-row-details.active", $table).removeClass("active");
		if (active) {
			$this.addClass("active");
		}

		var show = $("tr.btn-row-details.active", $table).nextAll("tr.row-details");

		$("tr.row-details", $table).hide();
		if (show.length) {
			show = show[0];
			$(show).show();
		}

	});

	
	page_resize();

	$('#form-modal').on('hidden.bs.modal', function () {
		$.bbq.removeState("modal")
	});
	
	
	$(window).resize(function () {
		$.doTimeout(250, function () {
			page_resize();
			
		});
	});
	
	$(window).scroll(function (event) {
		scroll();
		// Do something
	});
	
	$("#category-drop-down").smartmenus({
		keepInViewport:true
	});
	
	//$('#category-drop-down').smartmenus('itemActivate', $('li.active a'));
	
	
	
	
	$(document).on('click', '[data-toggle="offcanvas"]', function () {
		$('#right-area').toggleClass('active')
	});
	
	
	
	
	
	$("#right-area").swipe({
		//Generic swipe handler for all directions
		swipe: function (event, direction, distance, duration, fingerCount, fingerData) {
			
			switch (direction) {
				case "right":
					$(this).addClass("active");
					break;
				case "left":
					$(this).removeClass("active");
					break;
			}
			
			
		}, //Default is 75px, set to 0 for demo so any distance triggers swipe
		threshold: 75, allowPageScroll: "auto"
	});
	$("#left-area").swipe({
		//Generic swipe handler for all directions
		swipe: function (event, direction, distance, duration, fingerCount, fingerData) {
			
			switch (direction) {
				
				case "left":
					$("#right-area").removeClass("active");
					break;
			}
			
		}, //Default is 75px, set to 0 for demo so any distance triggers swipe
		threshold: 75, allowPageScroll: "auto"
	}).addClass("affix-bottom");

	
	
});

function page_resize() {
	if ($("#right-area").length&&$("#left-area").length){
		$("#right-area").css({minHeight: $("#left-area").height() - 2 + "px"});
	}

	
	scroll();
	

}

function updatetimerlist(d, page_size) {
	//d = jQuery.parseJSON(d);

	if (!d || !typeof d == 'object') {
		return false;
	}
	//console.log(d);
	var data = d['timer'];
	var page = d['page'];
	var models = d['models'];
	var menu = d['menu'];




	if (data) {
		var highlight = "";
		if (page['time'] > 0.5)    highlight = 'style="color: red;"';

		var th = '<tr class="heading" style="background-color: #fdf5ce;"><td >' + page['page'] + '</td><td class="s g"' + highlight + '>' + page['time'] + '</td></tr>', thm;
		if (models) {
			thm = $("#template-timers-tr-models").jqote(models);
		} else {
			thm = "";
		}
		//console.log(thm)
		var timers = $("#template-timers-tr").jqote(data);
		//console.log(timers)

		//console.log($("#template-timers-tr"))
		$("#systemTimers").prepend(th + timers + thm);
		
		
		

		// console.log($("#systemTimers").prepend(th + $("#template-timers-tr").jqote(data, "*")));
	}

	//console.log(menu)
	
		page_resize()
	
	

};

function validationErrors(data, $form) {
	if (!$.isEmptyObject(data['errors'])) {
		var i = 0;
		$.each(data.errors, function (k, v) {
			i = i + 1;
			var $field = $("#" + k);
			//console.info(k)
			var $block = $field.closest(".form-group");

			$block.addClass("has-error");
			if ($field.parent().hasClass("input-group")) $field = $field.parent();
			if (v != "") {
				
				$field.after('<span class="help-block s form-validation">' + v + '</span>');
			}
			if ($block.hasClass("has-feedback")){
				$field.after('<span class="fa fa-times form-control-feedback form-validation" aria-hidden="true"></span>')
			}


		});
		$(".has-error").get(0).scrollIntoView();
		$("button[type='submit']", $form).addClass("btn-danger").html("(" + i + ") Error(s) Found");

	} 

	submitBtnCounter($form);
	
	
}

function submitBtnCounter($form) {
	var c = $(".form-group.has-error").length;
	var $btn = $("button[type='submit']", $form);
	if (c) {
		$btn.addClass("btn-danger").html("(" + c + ") Error(s) Found");
	} else {
		
		var tx = $btn.attr("data-text")||"Save changes";
		
		$btn.html(tx).removeClass("btn-danger");
	}
}

function scroll(){
	var $mobilemenu = $("#mobile-menu");
	var $rightArea = $("#right-area") ;
	var $wholeArea = $("#whole-area") ;
	var searchBarHeight = $("#search-bar").height();
	searchBarHeight = searchBarHeight + 8;
	var scroll = $(window).scrollTop();
	
	var pageContentPosition = $("#page-content").position();

	
	
	var $pagecontent = $("#page-content");
	
	
	var bodyHeight = $("body").height();
	var windowHeight = $(window).height();
	//var contentHeight = $pagecontent.height()
	//var contentTop = $pagecontent.position().top
	var footerHeight = $("#footer-nav-bar").outerHeight(true)
	//var footerTop = $("#footer-nav-bar").position().top
	
	
	var offset =  (((((windowHeight + scroll)) - bodyHeight) + footerHeight) )
	
	
	$("#footer-nav-bar .navbar-inverse ").css("padding-bottom",searchBarHeight);
	
	
	if ($("#left-area").length){
		var topForLeft = pageContentPosition.top - scroll;
		topForLeft = topForLeft>0?topForLeft:0;
		
		var bottomForLeft = offset<searchBarHeight?searchBarHeight:offset;
		$("#left-area").css({
			"top":topForLeft,
			"bottom":bottomForLeft
		});
	}
	if ($mobilemenu.is(":visible")){
		$rightArea.css("margin-top",44);
		$wholeArea.css("margin-top",44);
		topForLeft = 44
	} else {
		$rightArea.css("margin-top",0);
		$wholeArea.css("margin-top",0);
	}
}
