$(document).ready(function(){

    //apertura overlay attachment
    $('.open-overlay-attachment').click(function(){
        var context = $(this).parent().find('#contextForAttachment').html();
        var id_item = $(this).parent().find('#idItemForAttachment').html();
        var elementReadOnly = $(this).parent().find('#attachmentReadOnly');
        var read_only = 0;
        if (elementReadOnly.length > 0 && elementReadOnly.html().length > 0) {
            read_only = elementReadOnly.html();
        }
        if (read_only) {
            $('#saveAttachment').prop('disabled', true);
        } else {
            $('#saveAttachment').prop('disabled', false);
        }
        $('#disabled_background_attachment').fadeIn('200');
        $('#overlay_attachment').fadeIn('200');
        $('#contextAttachment').val(context);
        $('#idItemAttachment').val(id_item);
        $('#dropZone #inputAttachment').attr('title', '');
        getUploadedAttachments(context, id_item, read_only);
        $(this).addClass('button-attachment-opened');
    });

    //chiusura overlay attachment
    $('.close-overlay-attachment').click(function(){
        $('#disabled_background_attachment').fadeOut('200');
        $('#overlay_attachment').fadeOut('200');
        $('#dropZone #inputAttachment').val('');
        $('#contextAttachment').val('');
        $('#idItemAttachment').val('');    
        $('.open-overlay-attachment.button-attachment-opened').removeClass('button-attachment-opened');
        setTimeout(function() {
            $('#displayUploadedAttachments').html('');
        }, 300);
    });

    //chiusura overlay attachment al click background
    $('#disabled_background_attachment').click(function(e){
        $('#disabled_background_attachment').fadeOut('200');
        $('#overlay_attachment').fadeOut('200');
        $('#dropZone #inputAttachment').val('');
        $('#contextAttachment').val('');
        $('#idItemAttachment').val('');
        $('.open-overlay-attachment.button-attachment-opened').removeClass('button-attachment-opened');
        setTimeout(function() {
            $('#displayUploadedAttachments').html('');
        }, 300);
    });

    /***********************************************************************************************************************/
    //JS PER DRAG AND DROP FILE

    var dropZone = $('#dropZone');

    document.getElementById('dropZone').addEventListener("dragover", function (e) {
        e.stopPropagation();
        e.preventDefault();

        dropZone.addClass('mouse-over');
    }, true);

    document.getElementById('dropZone').addEventListener("dragleave", function (e) {
        dropZone.removeClass('mouse-over');
    }, true);

    document.getElementById('dropZone').addEventListener("drop", function (e) {
        e.stopPropagation();
        e.preventDefault();

        dropZone.removeClass('mouse-over');

        files = e.dataTransfer.files; 
        if(files.length > 0) {  
            $('#dropZone #inputAttachment').prop('files', files).trigger('change');
        }
    }, true);

    $('#dropZone #inputAttachment').change(function(){ 
        $(this).attr('title', '');
        var html = '';
        if($(this).prop('files').length > 0) {  
            for(i = 0; i < $(this).prop('files').length; i++){ 
                html += '<div class="row">';
                html += '<div class="col-md-6 text-center attachment-icon">';
                html += '<i class="fa fa-file-o"></i><br />';
                html += $(this).prop('files')[i].name;
                html += '</div>';
                html += '<div class="col-md-6 attachment-details">';
                var type = $(this).prop('files')[i].type.split('/');
                html += '<b>Tipo</b>: '+type[1]+'<br />';
                html += '<b>Dimensione</b>: '+(($(this).prop('files')[i].size) / 1000).toFixed(1)+' kB<br />';
                html += '</div>';
                html += '</div>';
            }
        }

        $('#displayUploadedAttachments').html(html);

    });

    // FINE JS DRAG AND DROP FILE
    /************************************************************************************************************************/

    $('#saveAttachment').click(function(){
        $('#saveAttachment').prop('disable', true);

        var formData = new FormData($("#formAttachment")[0]);

        $.ajax({
            url: pathServer+'attachment-manager/ws/saveAttachment',
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json'
        }).done(function(res){
            if(res.response == 'OK'){
                if($('#boxAttachments').length > 0){
                    location.reload();
                }else{
                    var id = $('.open-overlay-attachment.button-attachment-opened').attr('id'); 
                    $('#displayUploadedAttachments').html('');
                    getUploadedAttachments($('#contextAttachment').val(), $('#idItemAttachment').val());
                    attachmentsNumberForBadge($('#contextAttachment').val(), $('#idItemAttachment').val(), id); 
                    $('#saveAttachment').prop('disable', false);
                }
            }else{
                alert(res.msg);
                $('#saveAttachment').prop('disable', false);
            }
        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            $('#saveAttachment').prop('disable', false);
        });
    });

    //Scarica allegato
    $(document).on('click', '.download-saved-attach', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#template-spinner').show();
        document.cookie = 'downloadStarted=0;path=/';    
		window.location = pathServer + 'attachment-manager/ws/downloadAttachment/' + id;
        checkCookieForLoader('downloadStarted', '1');
    });

    //Elimina allegato
    $(document).on('click', '.delete-saved-attach', function(e){
        e.preventDefault();
        
        var id = $(this).attr('data-id');
        
        $.ajax({
            url: pathServer+'attachment-manager/ws/deleteAttachment',
            type: "POST",
            data: {id: id},
            dataType: 'json'
        }).done(function(res){
            if(res.response == 'OK'){
                alert(res.msg);
                if($('#boxAttachments').length > 0){
                    location.reload();
                }else{
                    getUploadedAttachments($('#contextAttachment').val(), $('#idItemAttachment').val());
                    var id = $('.open-overlay-attachment.button-attachment-opened').attr('id');    
                    attachmentsNumberForBadge($('#contextAttachment').val(), $('#idItemAttachment').val(), id); 
                }   
            }else{
                alert(res.msg);
            }
        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        });
    });

});

function getUploadedAttachments(context, id_item, read_only = 0) {
    $.ajax({
        url: pathServer + 'attachment-manager/ws/getAttachments/' + context + '/' + id_item,
        type: 'GET',
        dataType: 'json',
    }).done(function(res) {
        if(res.response == 'OK'){
            if(res.data.length > 0){
                var html = '';
                res.data.forEach(function(attachment){
                    html += '<div class="row">';
                    html += '<div class="col-md-5 text-center attachment-icon">';
                    html += getFileIconByType(attachment.file_type) + '<br />';
                    html += attachment.file;
                    html += '</div>';
                    html += '<div class="col-md-7 attachment-details">';
                    html += '<div class="col-md-8">';
                    html += '<b>Data caricamento</b> '+attachment.upload_date;
                    html += '</div>';
                    html += '<div class="col-md-4">';
                    html += '<a href="" class="download-saved-attach" data-id="'+attachment.id+'" title="Scarica allegato"><i class="text-blue fa fa-download"></i></a>';
                    html += '&nbsp;';
                    if (read_only) {
                        html += '<div class="div-disabled-delete">';
                    }
                    html += '<a href="" class="delete-saved-attach';
                    if (read_only) {
                        html += ' disabled-delete'; 
                    }
                    html += '" data-id="'+attachment.id+'" title="Elimina allegato"><i class="text-red glyphicon glyphicon-trash"></i></a>';
                    if (read_only) {
                        html += '</div>';
                    }
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                    html += '</div>';
                });
                $('#displaySavedAttachments').html(html);
            }else{
                $('#displaySavedAttachments').html('<div class="text-center">Nessun allegato caricato.</div>');
            }
        }else{
            alert(res.msg);
        }
    }).fail(function(richiesta,stato,errori){
        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
    });
}

function attachmentsNumberForBadge(context, id_item, id) {
    $.ajax({
        url: pathServer + 'attachment-manager/ws/attachmentsNumberForBadge/' + context + '/' + id_item,
        type: 'GET',
        dataType: 'json',
    }).done(function(res) {  console.log(id);
		if(id != 'button_attachment_box'){   
			var button = $('#'+id);
			if(res.data > 0){ 
				button.find('.attachments-number').html(res.data);
				button.find('.attachments-number').show();
			}else{
				button.find('.attachments-number').hide();
			}
		}
        
        if($('#boxAttachments').length > 0){
            $('#boxAttachments').find('.box-attachments-number').html('('+res.data+')');
        }
    }).fail(function(richiesta,stato,errori){
        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
    });
}

function getFileIconByType(file_type) {
    var html = '';

    switch(file_type){
        case 'gzip':
        case 'zip':
        case 'x-7z-compressed':
        case 'x-tar':
        case 'x-rar-compressed':
        case 'java-archive':
        case 'x-bzip':
        case 'x-bzip2':
        case 'x-freearc':
            html = '<i class="fa fa-file-archive-o"></i>';
            break;
        case 'x-sh':
        case 'x-php':
        case 'javascript':
        case 'html':
        case 'css':
        case 'x-csh':
        case 'xhtml+xml':
        case 'xml':
        case 'sql':
            html = '<i class="fa fa-file-code-o"></i>';
            break;
        case 'bmp':
        case 'gif':
        case 'vnd.microsoft.icon':
        case 'jpeg':
        case 'png':
        case 'svg+xml':
        case 'tiff':
        case 'webp':
        case 'x-icon':
            html = '<i class="fa fa-file-image-o"></i>';
            break;
        case 'x-msvideo':
        case 'mpeg':
        case 'ogg':
        case 'webm':
        case '3gpp':
        case '3gpp2':
            html = '<i class="fa fa-file-video-o"></i>';
            break;
        case 'aac':
        case 'wav':
            html = '<i class="fa fa-file-audio-o"></i>';
            break;
        case 'vnd.ms-powerpoint':
        case 'vnd.openxmlformats-officedocument.presentationml.presentation':
            html = '<i class="fa fa-powerpoint-o"></i>';
            break;
        case 'vnd.ms-excel':
        case 'vnd.openxmlformats-officedocument.spreadsheetml.sheet':
            html = '<i class="fa fa-file-excel-o"></i>';
            break;
        case 'pdf':
            html = '<i class="fa fa-file-pdf-o"></i>';
            break;
        default:
            html = '<i class="fa fa-file-o"></i>';
            break;
    }

    return html;
}

function checkCookieForLoader(name, value) {
    var cookie = getCookie(name);

    if (cookie == value) {
        $('#template-spinner').hide();
        document.cookie = 'downloadStarted=0;path=/';
    } else {
        setTimeout(function () { checkCookieForLoader(name, value); }, 300);
    }
}