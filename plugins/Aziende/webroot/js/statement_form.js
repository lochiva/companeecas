$(document).ready(function () {
  $('select.select-company').on("change", function () {

    $('form#add-cost')[0].reset();
    $('form#add-cost input[required], form#add-costt select[required]').each(function() {
      $(this).parent().removeClass('has-error');
    });

    $("#accordion").text("");

    if ($(this).val() == "all") {
      $('#save-statement').prop('disabled', true);
      $('#save-statement').show();
      $("#company_specific").addClass("hidden");
      $('#add-cost').hide();
      $('#add-cost input[type=hidden][name=statement_company]').val('');
      $('#save-cat').prop('disabled', true);

      $('#comments').hide();
      $('#btn-actions').hide();
      $('#status-container').hide();

      $('#delete-statement').prop('disabled', true);
      $('#cost-headers').html("Spese (vista aggregata dell'ATI)");
      $.ajax({
        url:
          pathServer +
          "aziende/ws/getCosts/" +
          "all" +
          "/" +
          $('input[name="id"]').val(),
        type: "GET",
        dataType: "json",
      })
        .done(function (res) {
          if (res.response == "OK") {
            let cats = res.data;
            for (let cat in cats) {
              let toAppend = '<div class="panel panel-default">';

              if (cats[cat]['id'] == 'grandTotal') {
                toAppend += 
                `<div class="panel-heading" role="tab" style="background-color: white;">
                <h4>`;
              } else {
                toAppend +=
                `<div class="panel-heading" role="tab">
                <h4 class="panel-title">`;
              }
              toAppend +=
                cats[cat]["name"] +
                `<span class="f-right">&euro; ` + cats[cat]["tot"] + `</span>
                  </h4>
                    </div>
                    <div id="` +
                      cats[cat]["name"] +
                      `" class="panel-collapse collapse" role="tabpanel">
                    <div class="panel-body">
                    </div>
                </div>`;
              $("#accordion").append(toAppend);
            }
          } else {
            $("#accordion").append("<div>Nessuna spesa presente</div>");
          }
        })
        .fail(function (richiesta, stato, errori) {
          alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
        });
    } else {
      $('#cost-headers').html('Spese');
      $('#save-statement').prop('disabled', false);
      let id = $(this).val();
      $('#add-cost input[type=hidden][name=statement_company]').val(id);
      $('#save-cat').prop('disabled', false);
      $("#company_specific").removeClass("hidden");

      $('#status-container').show();

      // Dati
      $.ajax({
        url: pathServer + "aziende/ws/getStatementCompany/" + id,
        type: "GET",
        dataType: "json",
      })
        .done(function (res) {
          if (res.response == "OK") {
            $('input[name="companies[0][id]"]').val(res.data.id);
            $('input[name="companies[0][company_id]"]').val(res.data.company_id);
            $('input[name="companies[0][billing_reference]"]').val(
              res.data.billing_reference
            );
            $('input[name="companies[0][billing_date]"]').val(res.data.billing_date);
            $('input[name="companies[0][billing_net_amount]"]').val(
              res.data.billing_net_amount
            );
            $('input[name="companies[0][billing_vat_amount]"]').val(
              res.data.billing_vat_amount
            );

            if (res.data.uploaded_path.length) {
              $("#file_upload").removeClass("hidden");
              $("#file_upload").prop(
                "href",
                pathServer + "aziende/ws/downloadFileStatements/" + 'invoice/' + res.data.id
              );
            } else {
              $("#file_upload").addClass("hidden");
            }
            if (res.data.compliance.length) {
              $("#file_compliance_upload").removeClass("hidden");
              $("#file_compliance_upload").prop(
                "href",
                pathServer + "aziende/ws/downloadFileStatements/" + 'compliance/' + res.data.id
              );
            } else {
              $("#file_compliance_upload").addClass("hidden");
            }

            //Storico stato rendiconto
            renderHistory(res.data.history);

            var lastStatus = res.data.history[res.data.history.length - 1];

            // In corso
            if (lastStatus.status.id == 1) {
              if (role === 'ente_contabile') {
                $('.action-status').each(function(index, element) {
                  $(element).attr('data-id', res.data.id);
                  $(element).prop('disabled', false);
                });

                $('#statusNote').prop('disabled', false);

                $('#save-statement').prop('disabled', false);
                $('#save-statement').show();
                $('#delete-statement').prop('disabled', false);

                $('form#add-cost').show();
              } else {
                if (role === 'admin') {
                  $('#save-statement').prop('disabled', false);
                  $('#save-statement').show();
                }
                $('#delete-statement').prop('disabled', true);
                $('#statusNote').prop('disabled', true);

                $('.action-status').each(function(index, element) {
                  $(element).attr('data-id', res.data.id);
                  $(element).prop('disabled', true);
                });

                $('.action-status-dropdown').prop('disabled', true);
              }

              $('#status-container .box-footer').show();

            // Approvato
            }  else if (lastStatus.status.id == 2) {
              $('#status-container .box-footer').hide();

              $('#save-statement').prop('disabled', true);
              $('#save-statement').hide();

              $('#delete-statement').prop('disabled', true);

              $('form#add-cost').hide();

            // Integrazione
            } else if (lastStatus.status.id == 3) {
              if(role === 'ente_contabile') {
                $('.action-status').each(function(index, element) {
                  $(element).attr('data-id', res.data.id);
                  $(element).prop('disabled', false);
                });
                
                $('#save-statement').prop('disabled', false);
                $('#delete-statement').prop('disabled', false);

                $('#statusNote').prop('disabled', false);

                $('form#add-cost').show();

              } else {
                $('.action-status').each(function(index, element) {
                  $(element).attr('data-id', res.data.id);
                  $(element).prop('disabled', true);
                });

                $('.action-status-dropdown').prop('disabled', true);

                $('#statusNote').prop('disabled', true);

                if (role === 'admin') {
                  $('#save-statement').prop('disabled', false);
                  $('#save-statement').show();
                }

                $('#delete-statement').prop('disabled', true);
              }

              $('#status-container .box-footer').show();

            // In approvazione
            } else if (lastStatus.status.id == 4) {
              $('form#add-cost').hide();

              $('#save-statement').prop('disabled', true);
              $('#delete-statement').prop('disabled', true);

              if (role === 'ente_contabile') {
                $('.action-status').each(function(index, element) {
                  $(element).attr('data-id', res.data.id);
                  $(element).prop('disabled', true);
                });

                $('#statusNote').prop('disabled', true);
              } else {
                $('.action-status').each(function(index, element) {
                  $(element).attr('data-id', res.data.id);
                  $(element).prop('disabled', false);
                });

                if (role === 'admin') {
                  $('#save-statement').prop('disabled', true);
                  $('#save-statement').show();
                }

                $('.action-status-dropdown').prop('disabled', false);

                $('#statusNote').prop('disabled', false);
              }

              $('#status-container .box-footer').show();
            }
          } else {
            alert(res.msg);
          }
        })
        .fail(function (richiesta, stato, errori) {
          alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
        });

      // Costi
      $.ajax({
        url: pathServer + "aziende/ws/getCosts/" + false + "/" + id,
        type: "GET",
        dataType: "json",
      })
        .done(function (res) {
          if (res.response == "OK") {
            let cats = res.data;
            loadCosts(cats);
          } else {
            $("#accordion").append("<div>Nessuna spesa presente</div>");
          }
        })
        .fail(function (richiesta, stato, errori) {
          alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
        });
    }
  });

  $("form#main-form input[required], form#main-form select[required]").each(function () {
    $(this).on("change", function () {
      $(this).parent().parent().removeClass("has-error");
    });
  });

  $("input[name=file]").on("change", function () {
    if ($("input[name=file]").prop("files").length) {
      $('input[name="companies[0][uploaded_path]"]').val(
        $("input[name=file]").prop("files")[0].name
      );
    } else {
      $('input[name="companies[0][uploaded_path]"]').val();
    }
  });

  $("select[name=period_id]").on("change", function () {
    let opt = $("select[name=period_id] option:selected").text();

    if (opt == "Personalizzato") {
      $("input[name=period_label]").attr("readonly", false);
      $("input[name=period_start_date]").attr("readonly", false);
      $("input[name=period_end_date]").attr("readonly", false);

      $("input[name=period_label]").val("");
      $("input[name=period_start_date]").val("");
      $("input[name=period_end_date]").val("");
    } else {
      $("input[name=period_label]").attr("readonly", true);
      $("input[name=period_start_date]").attr("readonly", true);
      $("input[name=period_end_date]").attr("readonly", true);

      $.ajax({
        url:
          pathServer +
          "aziende/ws/getPeriod/" +
          $("select[name=period_id]").val(),
        type: "GET",
        dataType: "json",
      })
        .done(function (res) {
          if (res.response == "OK") {
            $("input[name=period_label]").val(res.data.label);
            $("input[name=period_start_date]").val(res.data.start_date);
            $("input[name=period_end_date]").val(res.data.end_date);
          } else {
            alert(res.msg);
          }
        })
        .fail(function (richiesta, stato, errori) {
          alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
        });
    }
  });

/*   $("#form-statement").submit(function (e) {
    e.preventDefault();
    let valid = true;
    $("input[required], select[required]").each(function () {
      $(this).parent().parent().removeClass("has-error");
    });

    $("input[required], select[required]").each(function () {
      if ($(this).val() == false) {
        $(this).parent().parent().addClass("has-error");
        valid = false;
      }
    });

    if (valid) {
      $("#form-statement").submit();
    }
  }); */

  $('#add-cost button[type=reset]').click(function() {
    $('form#add-cost #searchCat').val('');
    $('form#add-cost #searchCat').trigger('change');
  });

  $('#save-cat').click(function (e) {
    e.preventDefault();
    let start = new Date($("input[name=period_start_date]").val()).getTime();
    let end = new Date($("input[name=period_end_date]").val()).getTime();
    let date = new Date($("input[name=date]").val()).getTime();

    var conf = true;
    let errors = 0;

    if (date < start || date > end) {
      conf = confirm('Data non conforme al periodo, vuoi comunque inserire la la spesa?');
    } else {
      conf = true;
    }

    if (conf == true) {
      $('form#add-cost input[required], form#add-cost select[required]').each(function() {
        if($(this).val() == null ||  $(this).val()=='') {
          $(this).parent().addClass('has-error');
          errors ++;
        }
      });
  
      if(errors) {
        alert('Compilare tutti i campi in rosso');
      } else {
        var formData = new FormData($('#add-cost')[0]);
        $.ajax({
          url: pathServer + "aziende/ws/saveCost",
          type: "POST",
          data: formData,
          processData: false,
          contentType: false,
          dataType: "json",
        })
          .done(function (res) {
            if (res.response == "OK") {
              /*
                Resetto il form tranne
                categoria,
                data,
                fornitore
              */
              $('#add-cost input[name=amount]').val('');
              $('#add-cost input[name=share]').val('');
              $('#add-cost input[name=description]').val('');
              $('#add-cost input[name=notes]').val('');
              $('#add-cost input[name=number]').val('');
              $('#add-cost input[name=file]').val('');
              let cats = res.data;
              if (cats) {
                loadCosts(cats);
              } else {
                $("#accordion").append("<div>Nessuna spesa presente</div>");
              }
            } else {
              alert(res.msg)
            }
          })
          .fail(function (richiesta, stato, errori) {
            alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
          });
      }
    }
  });

  $('#searchCat').select2({
    language: 'it',
    placeholder: 'Cerca una categoria',
    width: '100%',
    closeOnSelect: true,
    //dropdownParent: $("#divSearchGuest"),
    ajax: {
      url: pathServer + 'aziende/ws/autocompleteCategories',
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
        return {
          results: data.data
        };
      },
      cache: true
    }
  });

  if (ati) {
    if (company) {
      $('select.select-company').val(company);
      $('select.select-company').change();
    } else {
      $('select.select-company').val('all');
      $('select.select-company').change();
    }

  } else {
    $.ajax({
      url: pathServer + "aziende/ws/getCosts/" + false + "/" + company,
      type: "GET",
      dataType: "json",
    })
      .done(function (res) {
        if (res.response == "OK") {
          let cats = res.data;
          loadCosts(cats);
        } else {
          $("#accordion").append("<div>Nessuna spesa presente</div>");
        }
      })
      .fail(function (richiesta, stato, errori) {
        alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
      });
  }

  $('form#add-cost input[required], form#add-cost select[required]').each(function() {
    $(this).on('change', function() {
      $(this).parent().removeClass('has-error');
    })

  });



  $(document).on('click', '.action-status:not(:disabled)', function() {
    changeStatus($(this).data('id'), $(this).data('status-id'))
  });

  attachmentsNumberForBadge('agreements', $('#idItemForAttachment').html(), 'button_attachment');

  $('#add-cost input[name=amount]').blur(
    function () {
      let val = $(this).val();
      if ($('#add-cost input[name=share]').val() == '') {
        $('#add-cost input[name=share]').val(val)
      }
    }
  );
});

function loadCosts(cats) {
  $("#accordion").html("");
  let status_id = $('#status').data('status_id');
  for (let cat in cats) {
    let toAppend = '';
    if (cats[cat]['id'] == 'grandTotal') {
      toAppend =
      `
        <div class="panel panel-default">
          <div class="panel-heading" role="tab" style="background-color: white;">
            <h4>` +
              cats[cat]["name"] +
              `<span class = "f-right">&euro;` + ` ` + cats[cat]["tot"] + `</span>` +
          `</h4>
        </div>`;
    } else {
      toAppend =
      `
        <div class="panel panel-default">
          <div class="panel-heading" role="tab">
            <h4 class="panel-title">
              <a role="button" data-toggle="collapse" data-parent="#accordion" href="#` +
                cats[cat]["id"] + `" aria-expanded="true">` + cats[cat]["name"] +
            `</a>`;

      if (cats[cat]["description"].length) {
        toAppend += `<span data-toggle="tooltip" data-html=true data-placement="top" title="<div class='text-justify'>`;
        toAppend += cats[cat]["description"];
        toAppend += `
        </div>">
        <i class="fa fa-question-circle"></i>
        </span>`;

      }

      toAppend += `<span class = "f-right">&euro;` + ` ` + cats[cat]["tot"] + `</span>` +
          `</h4>
        </div>
        
        <div id="` + cats[cat]["id"] + `" class="panel-collapse collapse" role="tabpanel">
          <div class="panel-body">
            <table class="table">
              <thead>
                <tr>
                  <th>Fornitore</th>
                  <th>Num doc</th>
                  <th>Data</th>
                  <th>Descrizione</th>
                  <th>Importo</th>
                  <th>Quota parte</th>
                  <th>Note</th>
                  <th>Allegato</th>`;

      if(status_id != 2) {
        toAppend += `<th class="spesa-col"></th>`;
      }

      toAppend += `</tr>
              </thead>
              <tbody>`;

      for (let cost in cats[cat]["costs"]) {
        toAppend +=
          `
          <tr>` +
          `<td>` +
          cats[cat]["costs"][cost]["supplier"] +
          `</td>` +
          `<td>` +
          cats[cat]["costs"][cost]["number"] +
          `</td>` +
          `<td>` +
          cats[cat]["costs"][cost]["date"];

          let start = new Date($("input[name=period_start_date]").val()).getTime();
          let end = new Date($("input[name=period_end_date]").val()).getTime();

          let [d, m, y] = cats[cat]["costs"][cost]["date"].split(/\D/);
          let date = new Date(y + '-' + m + '-' + d).getTime(); 

          if (date < start || date > end  ) {
            toAppend += `
              <span data-toggle="tooltip" data-html="true" data-placement="top" title="" data-original-title="<div class='text-justify'>Data non conforme al periodo</div>">
                <i class="fa fa-warning"></i>
              </span>`;
          }

          toAppend += `</td>` +
          `<td>` +
          cats[cat]["costs"][cost]["description"] +
          `</td>` +
          `<td>` +
          '&euro;' + ' ' + cats[cat]["costs"][cost]["amount"] +
          `</td>
            <td>` +
            '&euro;' + ' ' + cats[cat]["costs"][cost]["share"] +
          `</td>` +
          `<td>` +
          cats[cat]["costs"][cost]["notes"] +
          `</td><td>`;

          if (cats[cat]["costs"][cost]["attachment"]) {
            toAppend +=         '<a href="' + pathServer + 'aziende/ws/downloadFileCosts/' + cats[cat]["costs"][cost]["id"] + '">Scarica</a>';
          }

          toAppend += `</td>`;

          if((role == 'admin' || role == 'ente_contabile') && ![2,4].includes(status_id)) {
            toAppend += `<td class="spesa-col"> <a class="btn btn-xs btn-default delete-cost" onclick=deleteCost(`+cats[cat]["costs"][cost]["id"]+`)>
            <i data-toggle="tooltip" class="fa fa-trash" data-original-title="Elimina spesa"></i>
            </a>` +
            `</td>`;
          }

          toAppend += `</tr>`;
      }

    toAppend += `</tbody>
            </table>

        </div>
    </div>`;

    }
    $("#accordion").append(toAppend);
  }

}

function deleteCost (id) {
  let check = confirm('Cancellare la spesa selezionata?');
  if (check) {
  $.ajax({
    url: pathServer + "aziende/ws/deleteCost/" + id,
    type: "GET",
    dataType: "json",
  })
  .done(function (res) {
    if (res.response == "OK") {
      let cats = res.data;
      loadCosts(cats);
      if (cats.length) {
        loadCosts(cats);
      } else {
        $("#accordion").append("<div>Nessuna spesa presente</div>");
      }
    } else {
      alert(res.msg);
    }
  })
    .fail(function (richiesta, stato, errori) {
      alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
    }) 
  }
};

function checkStatus (id, status) {
  var ret = false;
  if (status != 4) {
    ret = true;
  } else {
    $.ajax({
      url: pathServer + "aziende/ws/checkStatusStatementCompany/" + id,
      type: "GET",
      dataType: "json",
      async: false
    })
    .done(function (res) {
      if (res.response == "OK") {
        ret = true;
      } else {
        alert(res.msg);
        ret = false;
      }
    })
    .fail(function (richiesta, stato, errori) {
      alert("E' evvenuto un errore. Lo stato della chiamata: " + stato);
      ret = false;
    })
  }
  return ret;
}

function changeStatus (id, status) {
  if (checkStatus(id, status)) {
    let msg = "ATTENZIONE!\n" + "Operazione irreversibile!\n";

    switch(status) {
      // Approva
      case 2:
        msg +=  "Si desidera approvare il rendiconto?";
        break;
  
      // Integrazione
      case 3:
        msg += "Si desidera richiedere l'integrazione del rendiconto?";
        break;
  
      // In approvazione
      case 4:
        msg += "Si desidera inviare il rendiconto in approvazione?";
        break;
    }

    if (confirm(msg)) {
      var form = $('<form></form>');
      form.attr("method", "post");
      form.attr("action", pathServer + "aziende/statements/updateStatusStatementCompany/" + id);
      form.attr("hidden", true);

      form.append($('#statusNote').clone());

      var field = $('<input></input>');
      field.attr("name", 'status');
      field.attr("value", status);
      form.append(field);
  
      $(document.body).append(form);
      form.submit();
    }
  }
};

function renderHistory(history) {
  var htmlLastStatusLabel = '';
  var lastStatus = history[history.length - 1];
  var badgeClass = '';
  switch (lastStatus.status.id) {
    case 1:
      badgeClass = 'btn-default';
      break;
    case 2:
      badgeClass = 'btn-success';
      break;
    case 3:
      badgeClass = 'btn-warning';
      break;
    case 4:
      badgeClass = 'btn-info';
      break;  
    default:
      badgeClass = 'btn-default';
  } 
  htmlLastStatusLabel += '<span data-status-id="<?= $lastStatus->status->id ?>" class="badge ' + badgeClass + ' badge-statement-status">' + lastStatus.status.name + '</span>';
  if (lastStatus.status.id == 2) {
    var createdLastStatus = lastStatus.created.split('T');
    var lastStatusDate = createdLastStatus[0].split('-').reverse().join('/');
    htmlLastStatusLabel += ' <span class="statement-status-date">approvato il ' + lastStatusDate + '</span>';
  }

  if (lastStatus.status.id == 4 && (role == 'admin' || role == 'ragioneria')) {
    var createdObj = new Date(lastStatus.created);
    createdObj.setMonth(createdObj.getMonth() + 1);
    var dueDate = [createdObj.getDate(), (createdObj.getMonth() < 9 ? '0'+(createdObj.getMonth()+1) : createdObj.getMonth()+1), createdObj.getFullYear()].join('/');
    htmlLastStatusLabel += ' <span class="statement-status-date">da approvare entro il ' + dueDate + '</span>';
  }

  var htmlStatusHistory = '';
  history.forEach(function(h) {
    htmlStatusHistory += '<div class="item">';
    switch (h.status.id) {
      case 1:
        badgeClass = 'btn-default';
        break;
      case 2:
        badgeClass = 'btn-success';
          break;
      case 3:
        badgeClass = 'btn-warning';
          break;
      case 4:
        badgeClass = 'btn-info';
          break;  
      default:
        badgeClass = 'btn-default';
    }
    htmlStatusHistory += '<span data-status-id="' + h.status.id + '" class="badge ' + badgeClass + ' badge-statement-status">' + h.status.name + '</span>';
    htmlStatusHistory += '<p class="message">';
    htmlStatusHistory += '<span class="name">';
    var created = h.created.split('T');
    var date = created[0].split('-').reverse().join('/');
    var time = created[1].substring(0, 8);
    var statusdate = date + ' ' + time;
    htmlStatusHistory += '<small class="text-muted pull-right"><i class="fa fa-clock-o"></i> ' + statusdate + '</small>';
    var userName = (h.user.nome.length == 0 && h.user.cognome.length == 0) ? '-' : h.user.nome+' '+h.user.cognome;
    var userRole = h.user.role.replace('_', ' ');
    htmlStatusHistory += '<span class="user-info '+ (h.note.length == 0 ? 'no-message' : '' ) +'">' + userName + ' (' + userRole + ')</span>';
    htmlStatusHistory += '</span>';
    htmlStatusHistory += h.note;
    htmlStatusHistory += '</p>';
    htmlStatusHistory += '</div>';
  });

  $('#status-container .statement-status-header #lastStatusLabel').html(htmlLastStatusLabel);
  $('#status-container .statement-status-body').html(htmlStatusHistory);
}

