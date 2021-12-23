$(document).ready(function(){

    //Carico lista allegati 
    var context = $('#contextForAttachment').html();
    var id_item = $('#idItemForAttachment').html();
    $.ajax({
        url: pathServer+'attachment-manager/ws/getAttachments/'+context+'/'+id_item,
        type: "GET",
        dataType: 'json'
    }).done(function(res){
        if(res.response == 'OK'){
            html = '';
            
            if(res.data.length > 0){
                res.data.forEach(function(attachment){
                    html += '<li class="item">';
                    html += '<div class="product-img icon-attachment">';
                    html += getFileIconByType(attachment.file_type);             
                    html += '</div>';
                    html += '<div class="product-info" style="position:relative">';
                    html += '<p class="product-title">'+attachment.file+'</p>';
                    html += '<div class="tools-hover icon-attachment-actions">';
                    html += '<a href="" class="download-attached-file" data-id="'+attachment.id+'"><i data-toggle="tooltip" data-placement="left" title="Scarica allegato" class="text-blue fa fa-download"></i></a>';
                    html += '&nbsp;';
                    html += '<a href="" class="delete-attached-file" data-id="'+attachment.id+'"><i data-toggle="tooltip" data-placement="left" title="Elimina allegato" class="text-red glyphicon glyphicon-trash"></i></a>';
                    html += '</div>';
                    html += '<span class="product-description" style="margin-right:-50px">';
                    html += 'Tipo: '+attachment.file_type+'<br />';
                    html += 'Dimensione: '+attachment.file_size+' kB<br />';
                    html += 'Data caricamento: '+attachment.upload_date;
                    html += '</span>';
                    html += '</div>';
                    html += '</li>';
                });
            }else{
                html += '<li class="item">';
                html += '<span class="product-description">Non ci sono allegati caricati.</span>';
                html += '</li>';
            }
            
            $('#boxAttachments .products-list').html(html);
        }else{
            alert(res.msg);
        }
    }).fail(function(richiesta,stato,errori){
        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
    });

    //Setto numero attachments badge
    attachmentsNumberForBadge($('#contextForAttachment').html(), $('#idItemForAttachment').html());

    //Scarica allegato
    $(document).on('click', '.download-attached-file', function(e){
        e.preventDefault();
        var id = $(this).attr('data-id');
        $('#template-spinner').show();
        document.cookie = 'downloadStarted=0;path=/';    
		window.location = pathServer + 'attachment-manager/ws/downloadAttachment/' + id;
        checkCookieForLoader('downloadStarted', '1');
    });

    //Elimina allegato
    $(document).on('click', '.delete-attached-file', function(e){
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
                location.reload();
            }else{
                alert(res.msg);
            }
        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        });
    });

});