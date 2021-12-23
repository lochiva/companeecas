<?php

use Cake\Routing\Router;

?>

<script type="text/javascript">

  // variabile globale che indica se selezionare o deselezionare tutto
  var selectAll = "select";

	$(document).ready(function(){

		$('table#table-adesioni').tablesorter({
		  theme: 'bootstrap',
          headerTemplate: '{content} {icon}',
          widgets : [ 'zebra', 'filter', 'uitheme'],
          headers: {
          	12: { filter: false, sorter: false},
          	13: { filter: false, sorter: false}
          }}).tablesorterPager({

                // **********************************
                //  Description of ALL pager options
                // **********************************

                // target the pager markup - see the HTML block below
                container: $(".pager"),
                ajaxUrl : '<?= Router::url(['plugin' => 'Pmm','controller' => 'Ajax','action' => 'getAdesioni']) ?>?{filterList:filter}&{sortList:column}&size={size}&page={page}',

                // modify the url after all processing has been applied
                customAjaxUrl: function(table, url) {
                    // manipulate the url string as you desire
                // url += '&cPage=' + window.location.pathname;
                // trigger my custom event
                $(table).trigger('changingUrl', url);
                // send the server the current page
                      $('#load-data').show();
                      $("#loading-spinner-table").css('z-index',10);
                    return url;
                },

                // add more ajax settings here
                // see http://api.jquery.com/jQuery.ajax/#jQuery-ajax-settings
                ajaxObject: {
                  dataType: 'json'
                },

                ajaxProcessing: function(data){
                  if (data && data.hasOwnProperty('rows')) {
                var r, row, c, d = data.rows,
                // total number of rows (required)
                total = data.total_rows,
                // array of header names (optional)
                headers = data.headers,
                // all rows: array of arrays; each internal array has the table cell data for that row
                rows = [],
                // len should match pager set size (c.size)
                len = d.length;

                // this will depend on how the json is set up - see City0.json
                // rows
                for ( r=0; r <= len; r++ ) {
                  row = []; // new row array
                  // cells
                  for ( c in d[r] ) {
                    if (typeof(c) === "string") {
                      row.push(d[r][c]); // add each table cell data to row array
                    }
                  }
                  rows.push(row); // add new row array to rows array
                }

                // in version 2.10, you can optionally return $(rows) a set of table rows within a jQuery object
                     $('#load-data').hide();

                     $("#loading-spinner-table").css('z-index',-10);
                    return [ total, rows, headers ];

                  }
                },

                // output string - default is '{page}/{totalPages}'; possible variables: {page}, {totalPages}, {startRow}, {endRow} and {totalRows}
                output: '{startRow} to {endRow} ({totalRows})',

                // apply disabled classname to the pager arrows when the rows at either extreme is visible - default is true
                updateArrows: true,

                // starting page of the pager (zero based index)
                page: 0,

                // Number of visible rows - default is 10
                size: 10,

                // if true, the table will remain the same height no matter how many records are displayed. The space is made up by an empty
                // table row set to a height to compensate; default is false
                fixedHeight: false,

                // remove rows from the table to speed up the sort of large tables.
                // setting this to false, only hides the non-visible rows; needed if you plan to add/remove rows with the pager enabled.
                removeRows: false,

                // css class names of pager arrows
                cssNext        : '.next',  // next page arrow
                cssPrev        : '.prev',  // previous page arrow
                cssFirst       : '.first', // go to first page arrow
                cssLast        : '.last',  // go to last page arrow
                cssPageDisplay : '.pagedisplay', // location of where the "output" is displayed
                cssPageSize    : '.pagesize', // page size selector - select dropdown that sets the "size" option
                cssErrorRow    : 'tablesorter-errorRow', // error information row

                // class added to arrows when at the extremes (i.e. prev/first arrows are "disabled" when on the first page)
                cssDisabled    : 'disabled' // Note there is no period "." in front of this class name
          });

          // Avviso tabella vuota
          $('table#table-adesioni').bind("pagerComplete pagerInitialized",function(e, options){
               if(parseInt(options.totalRows) == 0 && $('span#no-result').length == 0)
               {
                  $('table#table-adesioni tbody').append('<tr><td colspan="'+$('table#table-adesioni').find('thead th').length+'"><span id="no-result">Nessun risultato trovato.</span></td></tr>')
               }

          });

          // operazione su selezione multipla
          $('select#operation').change(function(){

            if($(this).val() != "")
            {
                // lista degli id delel adesioni seleizonate
                var ids = getSelected();

                // controllo di avere almeno un id
                if(ids.length < 1)
                {
                  alert("Seleziona almeno un'adesione.");
                  $(this).val("");
                  return false;
                }

                manageMultipleOperation($(this).val(),ids);

            }

          });

	});

   // Seleziona tutto \\
   $(document).on('click','#selectAll',function(){

      if(selectAll == 'select')
      {
        selectAll = 'deselect';
        $('input[type="checkbox"].select-adesione').prop('checked','checked').attr('checked','checked');
      }else
      {
        selectAll = 'select';
        $('input[type="checkbox"].select-adesione').prop('checked',false).attr('checked',false);
      }
   });

   // Modifica adesione \\

   $(document).on('click','a.edit-adesione',function(){
      editAdesione($(this).attr('data-id'));
   });

   // reset form adesione \\

   $(document).on('click','#reset-adesione',function(e){
      e.preventDefault();

      $('form#form-adesione input:not(.not-reset),#form-adesione select:not(.not-reset) ,#form-adesione textarea:not(.not-reset)').filter(function(){
        return $(this).attr('type') != 'submit' && $(this).attr('type') != 'reset';
      }).each(function(){
          $(this).val("");
      });

   });

   // salvataggio edit adesione \\

   $(document).on('submit','#form-adesione',function(e){
      e.preventDefault();

      //controllo i campi obbligatori
      if(!formValidation($(this)))
        return false;

      saveAdesione($('#form-adesione').serialize());

      return false;
   });

   // salvataggio adesioni multiple \\

   $(document).on('submit','#form-adesioni-multiple',function(e){
      e.preventDefault();

      //controllo i campi obbligatori
      if(!formValidation($(this)))
        return false;

      saveAdesioniMultiple($('#form-adesioni-multiple').serialize());

      return false;

   }) ;

   $(document).on('click','#reset-adesioni-multiple' ,function(e){
      resetMultipleForm();
   });

   // FUNZIONI \\

   function editAdesione(id)
   {
      if(typeof id != "undefined")
      {
          $.ajax({
            url : '<?= Router::url(['plugin' => 'Pmm','controller' => 'Ws','action' => 'getAdesione']) ?>',
            type : 'POST',
            dataType : 'json',
            data : {'id' : id},
            success : function(data)
            {
              if(data.response = 'OK')
              {
                if(Object.keys(data.data).length > 0)
                {
                  fillFormAdesione(data.data);
                  showModal($('#modale-adesioni'));
                }else
                {
                  alert("Impossibile caricare i dati dell'adesione, nessun dato ricevuto.");
                }
              }else
              {
                alert(data.msg);
              }
            },
            error : function(data)
            {
              alert("Impossibile caricare i dati dell'adesione: "+data.status+' '+data.statusText);
            }
          });
      }
   }

   function fillFormAdesione(data)
   {
      if(typeof data != 'undefined')
      {
        $('div#modale-adesioni span#user-name').html(data.Scheda.scheda_nome);

        $('form#form-adesione input,#form-adesione select').each(function(){

          if(typeof $(this).attr('name') != 'undefined' && typeof data[$(this).attr('name')] != 'undefined')
          {
            if($(this).hasClass('datepicker'))
            {
              // Cambio il formato della data
              if(data[$(this).attr('name')] != null && data[$(this).attr('name')] != "0000-00-00")
              {
                date = new Date(data[$(this).attr('name')]);

                value = (parseInt(date.getDate()) < 10 ? '0'+date.getDate() : date.getDate()) + '/'
                        + (parseInt((date.getMonth() + 1)) < 10 ? '0' + (date.getMonth() + 1) : (date.getMonth() + 1)) + '/'
                         + date.getFullYear();

                $(this).val(value);
              }

            }else
            {
              $(this).val(data[$(this).attr('name')]);
            }

          }

        });
      }
   }

   function saveAdesione(toSave)
   {
      if(typeof toSave != 'undefined')
      {
        $.ajax({
          url : '<?= Router::url(['plugin' => 'Pmm','controller' => 'Ws','action' => 'saveAdesione']) ?>',
          type : 'post',
          data : toSave,
          dataType : 'json',
          success : function(data)
          {
            if(data.response == 'OK')
            {
              // Chiudo la modale
              $('#modale-adesioni').trigger('reveal:close');
              // Aggiorno la tabella
              $('table#table-adesioni').trigger('update');
            }else
            {
              alert(data.msg);
            }
          },
          error : function(data)
          {
            alert("Impossibile salvare l'adesione: "+data.status+' '+data.stausText);
          }
        });
      }
   }

   function getSelected()
   {
      var toRet = [];

     $('input.select-adesione:checked').each(function(index){
        toRet.push($(this).attr('data-id'));
     });

     return toRet;
   }

   function manageMultipleOperation(operation,data)
   {
      if(typeof operation != 'undefined')
      {
          switch(operation)
          {
              case 'edit':

                if(typeof data != 'undefined' && data.length > 0)
                {
                  //$('form#form-adesioni-multiple input#ids').val(data);

                  for(i = 0;i < data.length ; i++)
                  {
                    $('form#form-adesioni-multiple').append('<input type="hidden" name="ids['+i+']" value="'+data[i]+'" class="ids" />');
                  }

                  showModal($('#modale-adesioni-multiple'));
                }

              break;

              default:

              break;
          }

          // resetto la select delle operazioni
          $('select#operation').val("");
      }
   }

   function saveAdesioniMultiple(toSave)
   {
      if(typeof toSave != 'undefined')
      {
        $.ajax({
          url : '<?= Router::url(['plugin' => 'Pmm','controller' => 'Ws','action' => 'saveAdesioniMultiple']) ?>',
          type : 'post',
          dataType : 'json',
          data : toSave,
          success : function(data)
          {
            if(data.response == 'OK')
            {

              // Mostro il risultato in un alert
              var content = "Risultato del salvataggio multiplo: \n\n";
              for(i in data.data)
              {
                content = content + i + ': '+data.data[i] + '\n';
              }

              alert(content);

              // Chiudo la modale
              $('#modale-adesioni-multiple').trigger('reveal:close');
              $('#selectAll').prop("checked",false);
              resetMultipleForm();

              // Aggiorno la tabella
              $('table#table-adesioni').trigger('update');
            }else
            {
              alert(data.msg);
            }
          },
          error : function(data)
          {
            alert('Impossibile salvare le adesioni multiple: '+data.status+' '+data.statusText);
          }
        });
      }
   }

   function enableDisableInput(id){
     var disabled = $("#"+id).prop("disabled");
     $("#"+id).prop("disabled", !disabled);
   }

   function resetMultipleForm(){
       $('#form-adesioni-multiple :input[type=text]').each(function () {
           $(this).prop("disabled",true);
      });
      $('#form-adesioni-multiple select').each(function () {
          $(this).prop("disabled",true);
     });
   }

</script>
<?= $this->element('loading_spinner_table') ?>
<section class="content-header">
    <h1>
        ELENCO ADESIONI
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-home"></i>ADESIONI</a></li>
        <li class="active">Elenco</li>
    </ol>
</section>

<?= $this->element('Pmm.filtri_adesioni') ?>


<div class="row">
  <div class="pull-left col-lg-4">
    <div id="pager" class="pager col-sm-6">
        <form>
            <i class="first glyphicon glyphicon-step-backward"></i>
            <i class="prev glyphicon glyphicon-backward"></i>
            <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
            <i class="next glyphicon glyphicon-forward"></i>
            <i class="last glyphicon glyphicon-step-forward"/></i>
            <select class="pagesize">
                <option selected="selected" value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="200">200</option>
            </select>
        </form>
    </div>
  </div>

  <div class="pull-right col-lg-2" style="margin-right:14px;">
    <select id="operation" class="form-control">
      <option value="">-- Con i selezionati --</option>
      <option value="edit">Modifica</option>
    </select>
  </div>
</div>

<section class="content">
    <div class="row">
      <div class="col-md-12">
      <div class="box">
        <div class="box-body box-table" style="overflow:auto;">
          <table id="table-adesioni" class="table-bordered table-striped table-hover">
            <thead>
              <tr>
                <th>Data adesione</th>
                <th>Nome</th>
                <th>Partita IVA</th>
                <th>Comune</th>
                <th>CAP</th>
                <th width="15px;">Prov.</th>
                <th>Cell (Tit)</th>
                <th>Cell (Rap)</th>
                <th>Stato</th>
                <th>POS</th>
                <th>PDR</th>
                <th>Data</th>
                <th>Azioni</th>
                <th><input type="checkbox" id="selectAll" title="Seleziona/deseleziona tutto" /></th>
              </tr>
            </thead>
            <tbody>

            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</section>


<?= $this->element('Pmm.modale_adesioni') ?>
<?= $this->element('Pmm.modale_adesioni_multiple') ?>
