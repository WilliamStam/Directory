$(document).ready(function () {
	var ckoptionsmasterhead = {
		uiColor: '#f1f1f1',
		height:'500px',
		contentsCss: '/app/style.css',
		allowedContent:true,
		toolbar: [
			//{ name: 'document',    groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', 'Save', 'NewPage', 'DocProps', 'Preview', 'Print', 'Templates', 'document' ] },
			{ name: 'source',   items: [ 'Source' ] },
			{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', 'Undo', 'Redo' ] },
			{ name: 'editing',     groups: [ 'find', 'selection' ], items: [ 'Find', 'Replace', 'SelectAll' ] },
			{ name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'SpecialChar' ] },
			{ name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
			{ name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
			{ name: 'colors', items: [ 'TextColor', 'BGColor' ] },
			'/',
			{ name: 'styles', items: [ 'Styles', 'Format', 'FontSize' ] },
			{ name: 'basicstyles', groups: [ 'basicstyles' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript' ] },
			{ name: 'basicstyles', groups: [ 'cleanup' ], items: [ 'RemoveFormat' ] },
			{ name: 'paragraph', groups: [ 'align' ], items: [ 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock' ] },
			{ name: 'paragraph', groups: [ 'list', 'indent', 'blocks' ], items: [ 'NumberedList', 'BulletedList', 'Outdent', 'Indent', 'CreateDiv', 'Blockquote',  ] },
		],
		
		basicEntities: false,
		filebrowserBrowseUrl : '/app/filebrowser/index.php',
	filebrowserWindowWidth : '950',
	filebrowserWindowHeight : '480',
	
	};
	
		if ($("#page-text").length) CKEDITOR.replace('page-text',ckoptionsmasterhead);
	
});
