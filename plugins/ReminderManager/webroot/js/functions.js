$(document).ready(function(){

  // ###################################################################################################################################################
  // Gestione editor html corpo della Mail
  
  /*
  $("#compose-textarea").wysihtml5({
    toolbar: {
      "fa": true,
      "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
      "emphasis": true, //Italics, bold, etc. Default true
      "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
      "html": true, //Button which allows you to edit the generated HTML. Default false
      "link": true, //Button to insert a link. Default true
      "image": true, //Button to insert an image. Default true,
      "color": true, //Button to change color of font  
      "blockquote": true //Blockquote  
      }, 
    "events": {
                "focus": function() {
                  $('#compose-textarea').parent('.form-group').removeClass('has-error');
                }
              }
  });
  */
  

  // ###################################################################################################################################################
  // Gestione selezione tipo di Invio

  $("#select-type-submission").change(function(){

    if($(this).val() != 0){
      $('#save-type').prop('disabled',true);
      $('#save-type').attr('title', 'Selezionare la tipologia Nuova per poter salvare');

      if($(this).attr('data-first-load') != '1'){
        loadTemplate($(this).val());
      }else{
        $(this).removeAttr('data-first-load');
      }


      $('.form-group').removeClass('has-error').removeClass('has-success');

    }else{
      $('#save-type').removeAttr('disabled');
      $('#save-type').removeAttr('title');
    }

  });

  // ###################################################################################################################################################
  // Gestione save

  $('#save').click(function(){
    //disabledBtnOnSave()
    saveSubmission();
  });

  // ###################################################################################################################################################
  // Gestione save - test

  $('#save-test').click(function(){

    //saveSubmission();
    var testMail = prompt("Inserire una mail valida per invio di test.");

    if(testMail != null){
      if(validateEmail(testMail)){

        //alert('Invierò la mail a ' + testMail);
        //disabledBtnOnSave()
        saveSubmission(false,testMail);

      }else{
        alert('Email non valida!');
      }
    }

  });


  // ###################################################################################################################################################
  // Gestione save and send

  $('#save-send').click(function(){
    //disabledBtnOnSave();
    saveSubmission(true);
  });

  // ###################################################################################################################################################
  // Gestione save and send

  $('#close').click(function(e){

    e.preventDefault();

    if(confirm('Si è sicuri di voler chiudre la videata senza salvare il lavoro fatto? I dati non salvati andranno persi')){
      window.location = this.href;
    }

  });

  // ###################################################################################################################################################
  // Gestione cancella tutti i destinatari

  $('.btn-delete-all-email-to-send').click(function(){

    $('.btn-delete-email-to-send').each(function(){

      removeEmailToSend($(this).attr('data-id'));

    });

  });


  $('iframe').click(function(){
    //alert('qui');
  });
});

// ##################################################################################################################################################
// Tolgo classe error se rientyro nel Campo

//$('').click(function(){
$(document).on('click', '.form-group.has-error .form-control', function(){
  $(this).parent('.form-group').removeClass('has-error');
});


// ###################################################################################################################################################
// Gestione skip mail

$(document).on('click', '.skip-mail', function(){
  skipSubmissionEmail($(this).attr('data-id'));
});

// ###################################################################################################################################################
// Gestione restore-mail

$(document).on('click', '.restore-mail', function(){
  restoreSubmissionEmail($(this).attr('data-id'));
});

// ###################################################################################################################################################
// Gestione attachments

$(document).on('click', '.get-attachments-mail', function(){
  getAttachmentEmail($(this).attr('data-id'));
});

// ###################################################################################################################################################
// Gestione seleziona mail recipient

$(document).on('click', '.btn-select-mail-recipient', function(){
  //alert('click');
  var id = $(this).attr('data-id');
  var email = $(this).attr('data-email');
  var denominazione = $(this).attr('data-name');

  mouveToSelected(id,email,denominazione);
});

// ###################################################################################################################################################
// Gestione seleziona tutte le mail

$(document).on('click', '.btn-select-all-mail', function(){

  var c = $(this).attr('data-selector');

  $('.' + c).each(function(){

    var id = $(this).attr('data-id');
    var email = $(this).attr('data-email');
    var denominazione = $(this).attr('data-name');

    mouveToSelected(id,email,denominazione);

  });

});

// ###################################################################################################################################################
// Gestione elimina email da inviare

$(document).on('click', '.btn-delete-email-to-send', function(){

  removeEmailToSend($(this).attr('data-id'));

});

// ###################################################################################################################################################
// Gestione elimina email da inviare

$(document).on('click', '.btn-delete-attachment', function(){

  //alert("elimino l'allegato");
  deleteAttachmentById($(this).attr('data-id'));

});


// ###################################################################################################################################################

function validateEmail(email) {
    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

function loading(){
  $('#tr-loading').html($('#tr-loading').html() + '.');
}

function disabledBtnOnSave(){

  $('#save').prop('disabled','disabled');
  $('#save-test').prop('disabled','disabled');
  $('#save-send').prop('disabled','disabled');
  $(document.body).css({'cursor' : 'wait'});
  //alert('qui');

}

function compileFormSubscription(data){

  $('[name="title"]').val(data.title);
  $('[name="object"]').val(data.object);
  $('[name="body"]').html(data.text);
  $('[name="template"]').filter('[value=' + data.template + ']').prop('checked', true);
  //$('iframe').contents().find('.wysihtml5-editor').html(data.text);

}

function loadType(attribute, selected){

  $.ajax({
    // definisco il tipo della chiamata
    type: "GET",
    // specifico la URL della risorsa da contattare
    url: urlTypeTemplate,
    // passo dei dati alla risorsa remota
    data: {attribute:attribute},
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        createListType(res.data, false, selected);

      }else{
        alert(res.msg);
      }

    },
    // ed una per il caso di fallimento
    error: function(){
      alert("Chiamata fallita!!!");
    }
  });

}

function createListType(list, disabled, selected){

  //console.log(list);
  //console.log(selected);

  $("#select-type-submission").select2({
    data: list
  });

  if(selected != undefined && selected > 0){
    $("#select-type-submission").val(selected).attr('data-first-load','1').trigger('change');
  }

  if(disabled == true){
    $("#select-type-submission").prop('disabled','disabled');
  }else{
    $("#select-type-submission").removeAttr('disabled');
  }

}

function loadTemplate(idTemplate){

  $.ajax({
    // definisco il tipo della chiamata
    type: "GET",
    // specifico la URL della risorsa da contattare
    url: urlLoadTemplate,
    // passo dei dati alla risorsa remota
    data: {id:idTemplate},
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        compileFormSubscription(res.data);

      }else{
        alert(res.msg);
      }

    },
    // ed una per il caso di fallimento
    error: function(){
      alert("Chiamata fallita!!!");
    }
  });

}

function checkFields(dataForm,type){

  var check = true;

  $.each(dataForm, function(index, item){

    //console.log(item.name + " " + type);

    if( item.name != "_wysihtml5_mode" && item.name != "idSubmission"){

      if(type != 'saveType' || item.name != 'sender_email'){

        //console.log('qui');
        if(item.value == ""){
          $('[name="' + item.name + '"]').parent().removeClass('has-success').addClass('has-error');
          check = false;
          //console.log('qui2');
        }else{
          $('[name="' + item.name + '"]').parent().removeClass('has-error').addClass('has-success');
          //console.log('qui3');
        }

      }

    }

  });

  return check;

}

function updateListType(list){

  //console.log(list);

  $("#select-type-submission").select2({
    data: list
  });

  $('#save-type').prop('disabled',true);
  $('#save-type').attr('title', 'Selezionare la tipologia Nuova per poter salvare');

}

function saveNewType(attr){

  var dataForm = $('#form-submission').serializeArray();

  //console.log(dataForm);

  if(checkFields(dataForm,'saveType')){

    var data = {};

    dataForm.map(function(obj){
      if(obj.name!="_wysihtml5_mode"){
          data[obj.name] = obj.value;
      }
    });

    //console.log(data);

    var type = prompt("Inserire un nome per il nuovo Tipo");

    //console.log(type);

    if(type != "" && type != null && type != undefined){

      //console.log('Posso salvare');

      $.ajax({
        // definisco il tipo della chiamata
        type: "POST",
        // specifico la URL della risorsa da contattare
        url: urlNewTypeTemplate,
        // passo dei dati alla risorsa remota
        data: {type:type, title:data.title, object:data.object, body:data.body, attribute:attr, template:data.template},
        // definisco il formato della risposta
        dataType: "json",
        // imposto un'azione per il caso di successo
        success: function(res){

          if(res.response == "OK"){

            updateListType(res.data);

            alert('Salvataggio nuova tipologia avvenuto con successo');

          }else{
            alert(res.msg);
          }

        },
        // ed una per il caso di fallimento
        error: function(){
          alert("Chiamata fallita!!!");
        }
      });

    }

  }


}

function saveSubmission($toSend = false,$emailTest = ''){

  var dataForm = $('#form-submission').serializeArray();
  var dataForm2 = $('#frm-list-clients').serializeArray();


  var form_data = new FormData();

	var file_data = $("#file-attachment").prop("files")[0];
	form_data.append('file', file_data);

  //console.log(dataForm);
  //console.log(dataForm2);
  //console.log(form_data);

  if(checkFields(dataForm)){
    disabledBtnOnSave();
    var data = {};
    var data2 = {};

    dataForm.map(function(obj){
      if(obj.name!="_wysihtml5_mode"){
          data[obj.name] = obj.value;
      }
    });

    //alert(data['attribute']);
    if(data['attribute'] == undefined || data['attribute'] == ""){
      data['attribute'] = 'SCHEDINI';
    }

    if($toSend == true){
      data['status'] = 1;
    }

    var key = "";
    var cont = 0;
    //console.log(dataForm2);
    dataForm2.map(function(obj){
      if(obj.name == "id"){
          //key = obj.value;
          cont ++;
          data2[cont] = {};
          data2[cont]['custom'] = {};
          data2[cont]['custom']['id_azienda'] = obj.value;
      }else{
        data2[cont][obj.name] = obj.value;
      }

    });

    //console.log(data);
    //console.log(data2);

    form_data.append('submissions', JSON.stringify(data));
    form_data.append('submissions_emails', JSON.stringify(data2));

    if($emailTest != ""){
      form_data.append('email_test', $emailTest);
    }

    $.ajax({
      // definisco il tipo della chiamata
      type: "POST",
      processData: false,
      contentType: false,
      // specifico la URL della risorsa da contattare
      url: urlSaveSubmission,
      // passo dei dati alla risorsa remota
      data: form_data,
      // definisco il formato della risposta
      dataType: "json",
      // imposto un'azione per il caso di successo
      success: function(res){

        if(res.response == "OK"){

          alert(res.msg);
          window.location.assign(urlSubmissionList);

        }else{
          alert(res.msg);
        }

      },
      // ed una per il caso di fallimento
      error: function(){
        alert("Chiamata fallita!!!");
      }
    });

  }


}

function changeStatusSubmission(newStatus, idSubmission){

  $.ajax({
    // definisco il tipo della chiamata
    type: "POST",
    // specifico la URL della risorsa da contattare
    url: urlChangestatusSubmission,
    // passo dei dati alla risorsa remota
    data: {newStatus:newStatus, idSubmission:idSubmission},
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        alert(res.msg);
        window.location.assign(urlSubmissionList);

      }else{
        alert(res.msg);
      }

    },
    // ed una per il caso di fallimento
    error: function(){
      alert("Chiamata fallita!!!");
    }
  });


}

function cloneSubmission(idSubmission){

  //alert('Clono l\'invio ' + idSubmission);

  $.ajax({
    // definisco il tipo della chiamata
    type: "POST",
    // specifico la URL della risorsa da contattare
    url: urlCloneSubmission,
    // passo dei dati alla risorsa remota
    data: {idSubmission:idSubmission},
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        alert(res.msg);
        window.location.assign(urlSubmissionList);

      }else{
        alert(res.msg);
      }

    },
    // ed una per il caso di fallimento
    error: function(){
      alert("Chiamata fallita!!!");
    }
  });

}

function skipSubmissionEmail(id){

  //alert('skip mail ' + id);

  setMailStatus(id,2);

}

function restoreSubmissionEmail(id){

  //alert('restore mail ' + id);

  setMailStatus(id,0);

}

function setMailStatus(id, status){

  $.ajax({
    // definisco il tipo della chiamata
    type: "POST",
    // specifico la URL della risorsa da contattare
    url: urlSetMailStatus,
    // passo dei dati alla risorsa remota
    data: {id:id, status:status},
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        alert(res.msg);

        refreshRecipient(idSubmission);

      }else{
        alert(res.msg);
      }

    },
    // ed una per il caso di fallimento
    error: function(){
      alert("Chiamata fallita!!!");
    }
  });

}

function getAttachmentEmail(id){

  window.open(urlAttachmentEmail + id, '_blank');

}

function loadSenderEmail(){

  $.ajax({
    // definisco il tipo della chiamata
    type: "GET",
    // specifico la URL della risorsa da contattare
    url: urlGetSenderEmail,
    // passo dei dati alla risorsa remota
    data: {},
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        $('[name="sender_email"]').val(res.data.sender_email);

      }else{
        alert(res.msg);
      }

    },
    // ed una per il caso di fallimento
    error: function(){
      alert("Chiamata fallita!!!");
    }
  });

}
/*
function loadOffices(){

  $.ajax({
    // definisco il tipo della chiamata
    type: "GET",
    // specifico la URL della risorsa da contattare
    url: urlGetOffices,
    // passo dei dati alla risorsa remota
    data: {},
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        updateListOffices(res.data.offices);

      }else{
        alert(res.msg);
      }

    },
    // ed una per il caso di fallimento
    error: function(){
      alert("Chiamata fallita!!!");
    }
  });

}

function updateListOffices(list){

  //console.log(list);

  $("#idOffice").select2({
    data: list
  });

}


function loadPartners(){

  $.ajax({
    // definisco il tipo della chiamata
    type: "GET",
    // specifico la URL della risorsa da contattare
    url: urlGetPartners,
    // passo dei dati alla risorsa remota
    data: {},
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        updateListPartners(res.data.partners);

      }else{
        alert(res.msg);
      }

    },
    // ed una per il caso di fallimento
    error: function(){
      alert("Chiamata fallita!!!");
    }
  });

}

function updateListPartners(list){

  //console.log(list);

  $("#socioRif").select2({
    data: list
  });

}
*/
function loadPossibleRecipients(){

  var offices = ''; //$("#idOffice").val();
  var partners = ''; //$("#socioRif").val();

  //console.log(offices);
  //console.log(partners);

  $.ajax({
    // definisco il tipo della chiamata
    type: "POST",
    // specifico la URL della risorsa da contattare
    url: urlGetPossibleRecipients,
    // passo dei dati alla risorsa remota
    data: {offices:offices, partners:partners},
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        //console.log(res.data);
        $('#num-recipient-found').html('('+res.data.aziende.length+')')
        createListPossibleRecipients(res.data.aziende)

      }else{
        alert(res.msg);
      }

    },
    // ed una per il caso di fallimento
    error: function(){
      alert("Chiamata fallita!!!");
    }
  });

}

function createListPossibleRecipients(list){

  //console.log(list);

  tr = '<tr class="select-all-mail">';
  tr += '<td>';
  tr += '<b><i>Invia a tutte ...</i></b>'
  tr += '</td>';
  tr += '<td>';
  tr += '<a class="btn-select-all-mail" data-selector="btn-select-mail-info" title="Seleziona tutte le mail info"><span class="badge bg-blue"><i class="fa  fa-info-circle"></i></span></a>'
  tr += '</td>';
  tr += '<td>';
  tr += '<a class="btn-select-all-mail" data-selector="btn-select-mail-cont" title="Seleziona tutte le mail contabilità"><span class="badge bg-blue"><i class="fa  fa-money"></i></span></a>'
  tr += '</td>';
  tr += '<td>';
  tr += '<a class="btn-select-all-mail" data-selector="btn-select-mail-soll" title="Seleziona tutte le mail solleciti"><span class="badge bg-blue"><i class="fa  fa-bomb"></i></span></a>'
  tr += '</td>';
  tr += '</tr>';

  $('#table-recipent tbody').html(tr);

  $.each(list, function(index, client){

    tr = "";
    tr += "<tr>";

    tr += "<td>";
    tr += client.denominazione
    tr += "</td>";

    tr += "<td>";
    if(client.email_info != ""){
      tr += '<a class="btn-select-mail-recipient btn-select-mail-info" title="' + client.email_info + '" data-id="' + client.id + '" data-email="' + client.email_info + '" data-name="' + client.denominazione + '"><span class="badge bg-green"><i class="fa  fa-info-circle"></i></span></a>';
    }else{
      tr += '<a title="La mail non è presente"><span class="badge bg-red"><i class="fa  fa-info-circle"></i></span></a>';
    }
    tr += "</td>";

    tr += "<td>";
    //tr += client.email_contabilita
    if(client.email_contabilita != ""){
      tr += '<a class="btn-select-mail-recipient btn-select-mail-cont" title="' + client.email_contabilita + '" data-id="' + client.id + '" data-email="' + client.email_contabilita + '" data-name="' + client.denominazione + '"><span class="badge bg-green"><i class="fa  fa-money"></i></span></a>';
    }else{
      tr += '<a title="La mail non è presente"><span class="badge bg-red"><i class="fa  fa-money"></i></span></a>';
    }
    tr += "</td>";

    tr += "<td>";
    //tr += client.email_solleciti
    if(client.email_solleciti != ""){
      tr += '<a class="btn-select-mail-recipient btn-select-mail-soll" title="' + client.email_solleciti + '" data-id="' + client.id + '" data-email="' + client.email_solleciti + '" data-name="' + client.denominazione + '"><span class="badge bg-green"><i class="fa  fa-bomb"></i></span></a>';
    }else{
      tr += '<a title="La mail non è presente"><span class="badge bg-red"><i class="fa  fa-bomb"></i></span></a>';
    }
    tr += "</td>";

    tr += "</tr>";

    $('#table-recipent tbody').append(tr);

  });

}

function mouveToSelected(id,email,denominazione){

  var insert = true;
  //$('#table-recipent-saved tbody')
  //console.log(id);
  //console.log(emailTosend.indexOf(id));
  if(emailTosend.indexOf(id) > -1){
    if(confirm('La mail risulta già presente nei destinatari, si è sicuri di volerla duplicare?')){
      insert = true;
      emailTosend.push(id);
    }else{
      insert = false;
    }
  }else{
    emailTosend.push(id);
    insert = true;
  }

  if(insert){

    tr = "";
    tr += '<tr id="email-to-send-'+id+'">';

    tr += '<td>';
    tr += '';
    tr += '</td>';

    tr += '<td>';
    tr += denominazione;
    tr += '<input type="hidden" name="id" value="' + id + '" />';
    tr += '<input type="hidden" name="name" value="' + denominazione + '" />';
    tr += '</td>';

    tr += '<td>';
    tr += email;
    tr += '<input type="hidden" name="email" value="' + email + '" />';
    tr += '</td>';

    tr += '<td>';
    tr += '<span class="badge bg-blue" title="Da Inviare"><i class="fa fa-envelope-o"></i></span>';
    tr += '</td>';

    tr += '<td>';
    tr += '<a class="btn-delete-email-to-send" data-id="'+id+'"><span class="badge bg-red" title="Elimina"><i class="fa fa-trash"></i></span></a>';
    tr += '</td>';

    tr += '</tr>';

    $('#table-recipent-saved tbody').append(tr);

    $('#save').removeAttr('disabled');
    $('#save').prop('title','Salva');
    $('#save-send').removeAttr('disabled');
    $('#save-send').prop('title','Salva e Invia');
    $('#save-test').removeAttr('disabled');
    $('#save-test').prop('title','Salva e Invia Test');

  }

}

function removeEmailToSend(id){

  var index = emailTosend.indexOf(id);
  if (index > -1) {
    emailTosend.splice(index, 1);
  }

  $('#email-to-send-'+id).remove();

  if(emailTosend.length == 0){
    $('#save').prop('disabled','disabled');
    $('#save').prop('title','Non ci sono destinatari');
    $('#save-send').prop('disabled','disabled');
    $('#save-send').prop('title','Non ci sono destinatari');
    $('#save-test').prop('disabled','disabled');
    $('#save-test').prop('title','Non ci sono destinatari');

  }
}

function loadAttachments(attachments){

  console.log(attachments);

  if(attachments.length > 0){

    var html = '<a class="btn-delete-attachment" data-id="'+attachments[0].id+'"><span class="badge bg-red" title="Elimina"><i class="fa fa-trash"></i></span></a> ' + attachments[0].filename;
    $('#file-attachment-file-name').html(html).show();
    $('#file-attachment').hide();
    $('.help-info-attachment').hide();
  }

}

function deleteAttachmentById(id){

  $.ajax({
    // definisco il tipo della chiamata
    type: "POST",
    // specifico la URL della risorsa da contattare
    url: urlDeleteAttachmentById,
    // passo dei dati alla risorsa remota
    data: {id:id},
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        alert('Allegato cancellato.');
        $('#file-attachment-file-name').html('').hide();
        $('#file-attachment').show();
        $('.help-info-attachment').show();

      }else{
        alert(res.msg);
      }

    },
    // ed una per il caso di fallimento
    error: function(){
      alert("Chiamata fallita!!!");
    }
  });

}
