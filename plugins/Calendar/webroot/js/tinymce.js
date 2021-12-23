$(function() {
        $('textarea.editor-html').tinymce({
                // Location of TinyMCE script
                script_url : pathServer + 'calendar/js/tinymce/tinymce.min.js',
                themes: "modern",
                // General options
                //theme : "",
                theme: 'modern',
                plugins: [
                  'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                  'searchreplace wordcount visualblocks visualchars code ',
                  'insertdatetime media nonbreaking save table contextmenu directionality',
                  'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
                ],
                toolbar1: 'undo redo | styleselect | fontselect fontsizeselect | forecolor backcolor | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist codesample',
                toolbar2: '',
				        fontsize_formats: "8pt 9pt 10pt 12pt 14pt 16pt 18pt 20pt 24pt 28pt 32pt 36pt",
                image_advtab: true,
                theme_advanced_toolbar_location : "top",
                theme_advanced_toolbar_align : "left",
                theme_advanced_statusbar_location : "bottom",
                theme_advanced_resizing : true,
                min_height: 400,
                max_height: 400,
                resize: false,
                statusbar: false,
                // medai options
                media_alt_source: false,
                media_poster: false,
                relative_urls : false,
                remove_script_host : false,
                convert_urls : true,
                media_live_embeds: true,
                video_template_callback: function(data) {
                  return '<video width="' + data.width + '" height="' + data.height + '"' + (data.poster ? ' poster="' + data.poster + '"' : '') + ' autoplay loop>\n' + '<source src="' + data.source1 + '"' + (data.source1mime ? ' type="' + data.source1mime + '"' : '') + ' />\n' + (data.source2 ? '<source src="' + data.source2 + '"' + (data.source2mime ? ' type="' + data.source2mime + '"' : '') + ' />\n' : '') + '</video>';
                },

                // Example content CSS (should be your site CSS)
                content_css : "",

                // Drop lists for link/image/media/template dialogs
                template_external_list_url : "lists/template_list.js",
                external_link_list_url : "lists/link_list.js",
                external_image_list_url : "lists/image_list.js",
                media_external_list_url : "lists/media_list.js",

                // Replace values for the template plugin
                template_replace_values : {
                        username : "Some User",
                        staffid : "991234"
                },
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
                      var checkFile = true;
                      // controllo nel caso di caricamento di immagini e media
                      switch (meta.filetype) {
                        case 'image':
                          if(fileType != 'image'){
                              checkFile = false;
                          }
                          break;
                        case 'media':
                          var ValidMedia = ["video","audio"];
                          if ($.inArray(fileType, ValidMedia ) < 0) {
                              checkFile = false;
                          }
                          break;
                      }

                      if(!checkFile){
                        alert('Il file non è del formato corretto!');
                        return;
                      }

                      formData= new FormData(document.getElementById('tinymce_upload_form') );
                      // eseguo la chiamata ajax ache salva l'immagine, e di ritorno avrò l'url
                      $.ajax({
                          url: pathServer+'calendar/ws/uploadFile',
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
                  // Provide alternative source and posted for the media dialog


        			  },
        });
});
