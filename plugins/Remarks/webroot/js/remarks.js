$(document).ready(function(){

    //apertura overlay remarks
    $('.open-overlay-remarks').click(function(){
        $('#disabled_background').fadeIn('200');
        $('#overlay_remarks').fadeIn('200');
        $('#reference_remarks').html($(this).parent().find('#reference_for_remarks').html());
        $('#reference_id_remarks').html($(this).parent().find('#reference_id_for_remarks').html());
        $('#label_notification').html($(this).parent().find('#label_notification_remarks').html());
        $('#visibility_public').prop('checked', true);
        loadOldRemarks();
    });

    //chiusura overlay remarks
    $('.close-overlay-remarks').click(function(){
        $('#disabled_background').fadeOut('200');
        $('#overlay_remarks').fadeOut('200');
        $('#reference_remarks').html('');
    	$('#reference_id_remarks').html('');
        $('#old_remarks').html('');
        $('#new_remark').val('');
        $('#show_deleted_remarks').prop('checked', false);
        //tolgo rating
        $('#stars li.star').each(function(){
            $(this).removeClass('selected');
        });
        //resetto visibilità a valore default pubblico
        $('#visibility_public').prop('checked', true);
        //svuoto file caricato
        $('#remark_attachment').val('');
        //Tolgo check mantieni allegato
        $('#div_check_attachment').html('');
        //Riabilito input allegato
        $('#remark_attachment').prop('disabled', false);
    });

    //chiusura overlay remarks al click background
    $('#disabled_background').click(function(e){
        $('#disabled_background').fadeOut('200');
        $('#overlay_remarks').fadeOut('200');
        $('#reference_remarks').html('');
    	$('#reference_id_remarks').html('');
        $('#old_remarks').html('');
        $('#new_remark').val('');
        $('#show_deleted_remarks').prop('checked', false);
        //tolgo rating
        $('#stars li.star').each(function(){
            $(this).removeClass('selected');
        });
        //resetto visibilità a valore default pubblico
        $('#visibility_public').prop('checked', true);
        //svuoto file caricato
        $('#remark_attachment').val('');
        //Tolgo check mantieni allegato
        $('#div_check_attachment').html('');
        //Riabilito input allegato
        $('#remark_attachment').prop('disabled', false);
    });


    //Salvataggio remark
    $('#save_remark').click(function(){
        $(this).prop('disable', true);
        if($(this).attr('data-id')){
            remark_id = $(this).attr('data-id');
        }else{
            remark_id = '';
        } 
        if($('#new_remark').val() != ''){
            if($('#remark_attachment').val() != ''){
                var attachment = new FormData($('#remark_attachment_upload')[0]);
            
                //carico allegato
                $.ajax({
                    url: pathServer+'remarks/ws/uploadRemarkAttachment',
                    type: "POST",
                    data: attachment,
                    processData: false,
                    contentType: false,
                    dataType: 'json'
                }).done(function(res){
                    if(res.response == 'OK'){
                        attachment = res.data;
                        saveRemark(remark_id, attachment);
                    }else{
                        alert(res.msg);
                        $('#save_remark').prop('disable', false);
                    }
                }).fail(function(richiesta,stato,errori){
                    alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
                    $('#save_remark').prop('disable', false);
                });
            }else{
                saveRemark(remark_id);
            }
        }else{
            alert('Inserire un testo per poter salvare la nota.');
        }

    });

    $('#show_deleted_remarks').click(function(){
        var show_deleted = $(this).is(':checked');
        loadOldRemarks(show_deleted);
    });

    //Cancellazione remark
    $(document).on('click', '.delete-remark', function(){
        if(confirm('Si è sicuri di voler cancellare la nota?')){
            var remark_id = $(this).attr('data-id');

            $.ajax({
                url: pathServer + 'remarks/ws/deleteRemark',
                type: 'POST',
                dataType: 'json',
                data: {remark_id: remark_id}
            }).done(function(res) {
                if(res.response == 'OK'){
                    var show_deleted = $('#show_deleted_remarks').is(':checked');
                    if(show_deleted){
                        $('.old-remark[data-id="'+remark_id+'"] .remark-text').addClass('remark-deleted-1');
                        $('.old-remark[data-id="'+remark_id+'"] .remark-text').removeClass('remark-deleted-0');
                        $('.delete-remark[data-id="'+remark_id+'"]').hide();
                    }else{
                        $('.old-remark[data-id="'+remark_id+'"]').fadeOut('200');
                    }

                    remarksNumberForBadge($('#reference_remarks').html(), $('#reference_id_remarks').html());
                }else{
                    alert(res.msg);
                }
            }).fail(function(richiesta,stato,errori){
                alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
            });
        }
    });

    /* Rating Start Widget */
        // 1. Visualizing things on Hover - See next part for action on click 
        $('#stars li').on('mouseover', function(){
            // The star currently mouse on
            var onStar = parseInt($(this).data('value'), 10); 
        
            // Now highlight all the stars that's not after the current hovered star
            $(this).parent().children('li.star').each(function(e){
                if(e < onStar){
                    $(this).addClass('hover');
                }else{
                    $(this).removeClass('hover');
                }
            });
            
        }).on('mouseout', function(){
            $(this).parent().children('li.star').each(function(e){
            $(this).removeClass('hover');
            });
        });
        
        
        // 2. Action to perform on click 
        $('#stars li').on('click', function(){
            // The star currently selected
            var onStar = parseInt($(this).data('value'), 10); 
            var stars = $(this).parent().children('li.star');
   
            for(i = 0; i < stars.length; i++){
                $(stars[i]).removeClass('selected');
            }
            
            for(i = 0; i < onStar; i++){
                $(stars[i]).addClass('selected');
            }
            
        });
    /* END Rating Star Widget */

    //Download allegato
    $(document).on('click', '.download-attachment', function(){
        var remark_id = $(this).attr('data-id');
        $('#remarks-loader').show();
        document.cookie = 'downloadStarted=0;path=/';    
		window.location = pathServer + 'remarks/ws/downloadAttachment/' + remark_id;
        checkCookieForLoader('downloadStarted', '1');
    });

    //Modifica nota
    $(document).on('click', '.edit-remark', function(){
        var remark_id = $(this).attr('data-id');

        $.ajax({
            url: pathServer + 'remarks/ws/getRemark/' + remark_id,
            type: 'GET',
            dataType: 'json',
        }).done(function(res) {
            if(res.response == 'OK'){
                $('#save_remark').attr('data-id', res.data.id);
                $('#new_remark').html(res.data.remark);
                var stars = $('#stars').children('li.star');  
                for(i = 0; i < stars.length; i++){
                    $(stars[i]).removeClass('selected');
                }
                for(i = 0; i < res.data.rating; i++){
                    $(stars[i]).addClass('selected');
                }
                if(res.data.visibility){
                    $('#visibility_private').prop('checked', true);
                }else{
                    $('#visibility_public').prop('checked', true);
                }
                if(res.data.attachment != ''){
                    var checkSameAttachment = '<input type="checkbox" name="same_attachment" id="check_same_attachment" checked="checked"/> Mantieni stesso allegato';
                    $('#div_check_attachment').html(checkSameAttachment);
                    $('#remark_attachment').prop('disabled', true);             
                }else{
                    $('#div_check_attachment').html('');
                    $('#remark_attachment').prop('disabled', false);
                }
                
            }else{
                alert(res.msg);
            }
        }).fail(function(richiesta,stato,errori){
            alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        });
    });

    //Al click sul check stesso allegato abilito/disabilito input per l'allegato
    $(document).on('click', '#check_same_attachment', function(){
        if($(this).is(':checked')){
            $('#remark_attachment').prop('disabled', true);
            $('#remark_attachment').val('');
        }else{
            $('#remark_attachment').prop('disabled', false);
        }
    });

});

function loadOldRemarks(show_deleted = false){
    var reference = $('#reference_remarks').html();
    var reference_id = $('#reference_id_remarks').html();

    $.ajax({
        url: pathServer + 'remarks/ws/getRemarksByRefId/' + reference + '/' + reference_id + '/' + show_deleted,
        type: 'GET',
        dataType: 'json',
    }).done(function(res) {
        var html = '';

        if(res.response == 'OK'){
            $('#old_remarks').html('');

            res.data.forEach(function(remark){
                html +='<div class="old-remark" data-id="'+remark.id+'">';
                html += '<div class="remark-info"><b>'+remark.user_name+' '+remark.user_surname+'</b><span class="pull-right">'+remark.private+remark.created+remark.attachment+remark.button_edit+remark.button_delete+'</span></div>';
                html += '<div class="remark-content">';
                html += '<div class="remark-user-rating">';
                html += remark.user_img;
                html += remark.rating;
                html += '</div>';
                html += '<div class="remark-text remark-deleted-'+remark.deleted+'">'+remark.remark+'</div>';
                html += '</div>';
                html += '</div>';
            });
            $('#old_remarks').append(html);
            setTimeout(function(){
                $('#old_remarks').scrollTop($('#old_remarks').prop('scrollHeight'));
            }, 100);
 
        }else{
            $('#old_remarks').html('');

            html +='<div class="old-remark" >';
            html += '<div class="remark-text no-remarks">Non sono ancora presenti note.</div>';
            html += '</div>'

            $('#old_remarks').append(html);
        }
    }).fail(function(richiesta,stato,errori){
        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
    });

}

function saveRemark(remark_id, attachment = ''){

    var reference = $('#reference_remarks').html();
    var reference_id = $('#reference_id_remarks').html();
    var new_remark = $('#new_remark').val();
    if($('#stars li.selected').last().data('value') != undefined){ 
        rating = parseInt($('#stars li.selected').last().data('value'), 10);
    }else{
        rating = 0;
    }

    var visibility = $('input[name="visibility"]:checked').val();

    var checkSameAttachment = $('#check_same_attachment').is(':checked');

    var labelNotification = $('#label_notification').html();

    var data = {
        id: remark_id, 
        reference: reference, 
        reference_id: reference_id, 
        remark: new_remark, 
        rating: rating, 
        visibility: visibility, 
        attachment: attachment, 
        check_attachment: checkSameAttachment,
        label_notification: labelNotification
    };

    $.ajax({
        url: pathServer + 'remarks/ws/saveRemark',
        type: 'POST',
        dataType: 'json',
        data: data,
    }).done(function(res) {
        if(res.response == 'OK'){
            $('#new_remark').val('');
            $('#save_remark').prop('disable', false);
            var show_deleted = $('#show_deleted_remarks').is(':checked');
            loadOldRemarks(show_deleted);
            remarksNumberForBadge($('#reference_remarks').html(), $('#reference_id_remarks').html());
            //tolgo rating
            $('#stars li.star').each(function(){
                $(this).removeClass('selected');
            });
            //resetto visibilità a valore default pubblico
            $('#visibility_public').prop('checked', true);
            //svuoto file caricato
            $('#remark_attachment').val('');
            //Tolgo check mantieni allegato
            $('#div_check_attachment').html('');
            //Riabilito input allegato
            $('#remark_attachment').prop('disabled', false);
        }else{
            alert(res.msg);
            $('#save_remark').prop('disable', false);
        }
    }).fail(function(richiesta,stato,errori){
        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
        $('#save_remark').prop('disable', false);
    });
}

function remarksNumberForBadge(reference, reference_id){
    $.ajax({
        url: pathServer + 'remarks/ws/remarksNumberForBadge/' + reference + '/' + reference_id,
        type: 'GET',
        dataType: 'json',
    }).done(function(res) {
        if(res.data != 0){
            $('.remarks_number').show();
            $('.remarks_number').html(res.data);
        }else{
            $('.remarks_number').hide();
        }
    }).fail(function(richiesta,stato,errori){
        alert("E' evvenuto un errore. Lo stato della chiamata: "+stato);
    });
}

function checkCookieForLoader(name, value) {
    var cookie = getCookie(name);

    if (cookie == value) {
        $('#remarks-loader').hide();
        document.cookie = 'downloadStarted=0;path=/';
    } else {
        setTimeout(function () { checkCookieForLoader(name, value); }, 300);
    }
}
