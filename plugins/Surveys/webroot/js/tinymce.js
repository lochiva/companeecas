$(function() {
	$('textarea.editor-html').tinymce({
		script_url : pathServer + 'plugins/tinymce/tinymce.min.js',
		language: 'it_IT', 
		branding: false,
		plugins: ['image', 'table', 'code', 'paste', 'autoresize'],
		menubar: 'file edit view insert format table',
		content_style: "* { font-family: Times; }",
		paste_retain_style_properties: 'color font-size background-color padding-left padding-right text-align padding-top padding-bottom line-height border-collapse collapse width border-style word-wrap border cellpadding page-break-inside',
		resize: true, 
		min_height: 300,
		max_height: 900,
		relative_urls : false,
		remove_script_host : false,
		convert_urls : true,
		indentation: '10%',
		file_picker_callback: function(callback, value, meta) {
			// svuoto l'input
			$('#tinymce_upload').val('');
			// Provide image and alt text for the image dialog
			$('#tinymce_upload').off('change');
			$('#tinymce_upload').trigger('click');
			$('#tinymce_upload').on('change', function(){
			// eseguo opportuni controlli sul file
				file = this.files[0];
				if(file === undefined){
					return;
				}
				var fileType = file.type.substr(0,file.type.indexOf('/'));
				
				if(fileType != 'image'){
					alert('Il file non è del formato corretto!');
					return;
				}

				formData= new FormData(document.getElementById('tinymce_upload_form') );
				// eseguo la chiamata ajax ache salva l'immagine, e di ritorno avrò l'url
				$.ajax({
					url: pathServer+'surveys/ws/saveImagePath/1',
					type: "POST",
					data: formData,
					processData: false,
					contentType: false,
					dataType: 'json',
					success: function(data){
						if(data.response == 'OK'){
							callback(data.data, {
								alt: ''
							});
						}else{
							alert(data.msg);
						}
					},

				});
			});
		},
	});
});
