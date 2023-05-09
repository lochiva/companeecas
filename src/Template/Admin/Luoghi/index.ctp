<?php
/** 
* Companee :    index (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
use Cake\Routing\Router;
use Cake\I18n\Time;
?>
<script>
    $(document).ready(function(){

      $("#table-province").tablesorter({
            theme: 'bootstrap',
            headerTemplate: '{content} {icon}',
            widgets : [ 'zebra', 'cssStickyHeaders' , 'columns', 'filter', 'uitheme' ],
            widgetOptions: {
              cssStickyHeaders_offset        : $('header:first').height(),
              cssStickyHeaders_addCaption    : false,
              // jQuery selector or object to attach sticky header to
              cssStickyHeaders_attachTo      : null,
              cssStickyHeaders_filteredToTop : false,
              cssStickyHeaders_zIndex        : 99000,

              filter_functions:{
                  //1:function(e,n,f,i,$r){return e===f;},
                  //2:provider,
              }
            },
            headers: { }
        }).tablesorterPager({

              container: $(".pager"),

              ajaxUrl : '<?= $this->Url->build('/') ?>' + 'admin/ws/tableProvince?{filterList:filter}&{sortList:column}&size={size}&page={page}',
              // modify the url after all processing has been applied
              customAjaxUrl: function(table, url) {
                  // manipulate the url string as you desire
              // url += '&cPage=' + window.location.pathname;
              // trigger my custom event
              $(table).trigger('changingUrl', url);
              // send the server the current page
                  $('#template-spinner').show();
                  return url;
              },


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
                    if(d[r][c] == null || d[r][c] == undefined){
                      d[r][c] = '';
                    }
                    row.push(d[r][c]); // add each table cell data to row array
                  }
                }
                rows.push(row); // add new row array to rows array
              }
              // in version 2.10, you can optionally return $(rows) a set of table rows within a jQuery object
                   $('#template-spinner').hide();

                  return [ total, rows, headers ];

                }
                $('#template-spinner').hide();
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

        }).bind("pagerComplete pagerInitialized",function(e, options){

             if(parseInt(options.totalRows) == 0 && $('span#no-result').length == 0)
             {
                $('#'+$(this).attr("id")+' tbody').append('<tr><td colspan="'+$('#'+$(this).attr("id")).find('thead th').length+'"><span id="no-result">Nessun risultato trovato.</span></td></tr>');
             }
             calculateTableDropdownPosition();

        }).bind('pagerChange', function(e, options){

            var tableId = e.currentTarget.id;
            var pageSize = localStorage.getItem("tablesorter-pager-temp");

            if(pageSize != undefined && pageSize != null){
              pageSize = JSON.parse(pageSize);
              if(pageSize[tableId] != undefined && pageSize[tableId] != null){
                 $('#'+tableId).trigger('pageAndSize', pageSize[tableId] );
                 delete pageSize[tableId];
                 pageSize = JSON.stringify(pageSize);
                 localStorage.setItem("tablesorter-pager-temp",pageSize);
              }
            }
        });

    });
</script>
<div>
    <h1><i class="glyphicon glyphicon-log-in"></i> Gestione Luoghi </h1>
    <h3>Da questa pagina è possibile gestire i luoghi abilitati del portale.</h3>
</div>
<hr>
  <div class="box-body">
          <div id="pager-province" class="pager col-sm-6">
              <form>
                  <i class="first glyphicon glyphicon-step-backward"></i>
                  <i class="prev glyphicon glyphicon-backward"></i>
                  <span class="pagedisplay"></span> <!-- this can be any element, including an input -->
                  <i class="next glyphicon glyphicon-forward"></i>
                  <i class="last glyphicon glyphicon-step-forward"/></i>
                  <select class="pagesize">
                      <option selected="selected" value="10">10</option>
                      <option value="20">20</option>
                      <option value="30">30</option>
                      <option value="40">40</option>
                  </select>
              </form>
          </div>


          <div class="table-content">
              <table id="table-province" class="table table-bordered table-hover">
                  <thead>
                      <tr>
                          <th>Numero</th>
                          <th>Sigla</th>
                          <th>Abilitato</th>
                          <th style="min-width: 84px;" data-sorter="false" data-filter="false" ></th>
                      </tr>
                  </thead>
                  <tbody>
                      <tr>
                          <td colspan="7">Non ci sono dati</td>
                      </tr>
                  </tbody>
              </table>
          </div>
  </div>
