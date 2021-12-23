$(document).ready(function(){

    //chiusura modale documento
    $('#modalDocument').on('hidden.bs.modal', function(){
        $('#dropZone #inputDocument').val('');
        $('#displayUploadedDocuments').html('');
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
            $('#dropZone #inputDocument').prop('files', files).trigger('change');
        }
    }, true);

    $('#dropZone #inputDocument').change(function(){ 
        $(this).attr('title', '');
        var html = '';
        if($(this).prop('files').length > 0) {  
            for(i = 0; i < $(this).prop('files').length; i++){ 
                html += '<div class="row document-row">';
                html += '<div class="col-md-3 text-center document-icon">';
                html += '<i class="fa fa-file-o"></i><br />';
                html += $(this).prop('files')[i].name;
                html += '</div>';
                html += '<div class="col-md-3 document-details">';
                var type = $(this).prop('files')[i].type.split('/');
                html += '<b>Tipo</b>: '+type[1]+'<br />';
                html += '<b>Dimensione</b>: '+(($(this).prop('files')[i].size) / 1000).toFixed(1)+' kB<br />';
                html += '</div>';
                html += '<div class="col-md-6 document-inputs">';
                html += '<div class="col-md-12 input document-title">';
                html += '<input type="text" name="documents_title['+i+']" maxlength="255" placeholder="Titolo" value="" class="form-control" />';
                html += '</div>';
                html += '<div class="col-md-12 document-description">';
                html += '<textarea name="documents_description['+i+']" maxlength="255" placeholder="Descrizione" value="" class="form-control document-textarea"></textarea>';
                html += '</div>';
                html += '</div>';
                html += '</div>';
            }
        }

        $('#displayUploadedDocuments').html(html);

    });

    // FINE JS DRAG AND DROP FILE
    /************************************************************************************************************************/

    $('#saveDocument').click(function(){
        $('#saveDocument').prop('disable', true);

        var formData = new FormData($("#formDocument")[0]);

        $.ajax({
            url: pathServer+'reports/ws/saveDocument',
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json'
        }).done(function(res){
            if(res.response == 'OK'){
                $('#modalDocument').modal('hide');
                $('#saveDocument').prop('disable', false);
                $('#table-documents').trigger('update');
            }else{
                alert(res.msg);
                $('#saveDocument').prop('disable', false);
            }
        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            $('#saveDocument').prop('disable', false);
        });
    });

});

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