$(document).ready(function(){

  getSubmissionsByCustom(cKey,cValue);

});

function getSubmissionsByCustom(cKey, cValue){

  $.ajax({
    // definisco il tipo della chiamata
    type: "GET",
    // specifico la URL della risorsa da contattare
    url: urlGetSubmissionsByCustom + cKey + '/' + cValue,
    // passo dei dati alla risorsa remota
    data: {},
    // definisco il formato della risposta
    dataType: "json",
    // imposto un'azione per il caso di successo
    success: function(res){

      if(res.response == "OK"){

        //console.log(res.data);
        createList(res.data);

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

function createList(list){

  $('#table-list-widget-reminder tbody').html('');

  $.each(list, function(index, submission){

    console.log(submission);

    tr = "";
    tr += "<tr>";

    tr += "<td>";
    tr += submission.submissionDate;
    tr += "</td>";

    tr += "<td>";
    tr += submission.name;
    tr += "</td>";

    tr += "<td>";
    tr += submission.submissionEmailEmail;
    tr += "</td>";

    tr += "<td>";
    tr += submission.submissionEmailSended == 1 ? '<span class="badge bg-green" title="Inviata"><i class="fa fa-check"></i></span>' : submission.submissionEmailSended == 0 ? '<span class="badge bg-blue" title="Da Inviare"><i class="fa fa-envelope-o"></i></span>' : '<span class="badge bg-orange" title="Esclusa"><i class="fa fa-envelope-o"></i></span>' ;
    tr += "</td>";

    tr += "<td>";
    tr += '<a class="show-attahement get-attachments-mail" href="' + submission.linkSubmission + '" title="Visualizza l\'invio" target="_blank"><span class="badge bg-blue"><i class="fa  fa-eye"></i></span>' ;
    if(submission.linkAttachmentEmail != ""){
      tr += '<a class="show-attahement get-attachments-mail" href="' + submission.linkAttachmentEmail + '" title="Visualizza l\'allegato" target="_blank"><span class="badge bg-info"><i class="fa  fa-file-pdf-o"></i></span>' ;
    }
    if(submission.linkAttachment != ""){
      tr += '<a class="show-attahement get-attachments-mail" href="' + submission.linkAttachment + '" title="Visualizza l\'allegato" target="_blank"><span class="badge bg-info"><i class="fa  fa-file-pdf-o"></i></span>' ;
    }
    tr += "</td>";

    tr += "</tr>";

    $('#table-list-widget-reminder tbody').append(tr);

  });

}
