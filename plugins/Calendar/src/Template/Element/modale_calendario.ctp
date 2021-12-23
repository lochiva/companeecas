<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

$connectionTimetask = Configure::read('dbconfig.calendar.TIMETASK_CONNECTION');
$webappActivated = Configure::read('dbconfig.calendar.CALENDAR_APP_ACTIVATED');
?>

<?php echo $this->Html->script('Calendar.modale_calendario'); ?>
<?php $this->Html->script('Calendar.tinymce/jquery.tinymce.min', ['block' => 'script']);?>
<?php $this->Html->script('Calendar.tinymce', ['block' => 'script']); ?>

<div class="modal fade" id="myModalCalendar" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Nuovo Evento</h4>
            </div>
            <?php if($webappActivated == '1'){ ?>
            <div class="event-status">
            	<span class="badge badge-event badge-event-todo">EVENTO DA FARE</span>
    			<span class="badge badge-event badge-event-doing">EVENTO IN CORSO</span>
    			<span class="badge badge-event badge-event-done">EVENTO COMPLETATO</span>
            </div>
            <?php } ?>
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a id="click_tab_1" href="#tab_1" data-toggle="tab">Evento</a></li>
                    <li><a id="click_tab_2" href="#tab_2" data-toggle="tab">Note</a></li>
                    <?php if($webappActivated == '1'){ ?>
                    <li><a href="#tab_3" data-toggle="tab" id="eventDetails" disabled="disabled">Dettagli evento</a></li>
                    <li><a href="#tab_4" data-toggle="tab" id="signatureDetails" disabled="disabled">Firma</a></li>
                    <li><a href="#tab_5" data-toggle="tab" id="mapDetails" disabled="disabled">Mappa</a></li>
                    <?php } ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="tab_1">
                        <form class="form-horizontal" id="myModalCalendarForm">
                            <div class="box-body">
                            <?php if($authUser['role'] == 'admin'): ?>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label required" for="idUser">Assegnato a:</label>
                                    <div class="col-sm-10">
                                    <select name="user_used" class="form-control select2" style="width:100%;" id="idUser">
                                        <?php foreach ($users as $key => $user) :?>
                                        <option value="<?=  $user['id'];?>" ><?= $user['username'].": ".$user['cognome'] . " " . $user['nome'];?></option>
                                        <?php endforeach ?>
                                    </select>
                                    </div>
                                </div>
                                <hr />
                            <?php endif ?>
                            <?php if($connectionTimetask == '1'){ ?>
                            <input type="text" id="idTimeTimetask" name="idTimeTimetask" value="" hidden />
                            <div class="form-group">
                                <label class="col-sm-2 control-label" for="taskNumber">Numero task</label>
                                <div class="col-sm-5">
                                    <input type="text" name="task_number" id="taskNumber" class="form-control" />
                                </div>
                                <div class=" col-sm-1">
                                    <button type="button" id="loadTask" class="btn btn-primary" title="Carica task"><i class="fa fa-link"></i></button>
                                </div>
                                <input hidden id="idTask" type="text" value="" />
                            </div>

                            <div hidden id="timetask-data">
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="taskClient">Cliente</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="task_client" id="taskClient" class="form-control" readonly />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="taskProject">Progetto</label>
                                    <div class="col-sm-10">
                                        <input type="text" name="task_project" id="taskProject" class="form-control" readonly />
                                    </div>
                                </div>
                            </div>                
                            <hr>
                            <?php } ?>
                            <div class="form-group" id="idAziendaParent">
                                <label class="col-sm-2 control-label required" for="idAzienda">Azienda</label>
                                <div id="parentAzienda" class="col-sm-10">
                                    <select name="id_azienda" id="idAzienda" class="select2 form-control"></select>
                                </div>
                            </div>
                            <div class="form-group" id="idOrderParent">
                                <label class="col-sm-2 control-label required" for="idOrder">Ordine</label>
                                <div class="col-sm-10">
                                    <select name="id_order" id="idOrder" class="select2 form-control"></select>
                                </div>
                            </div>
                            <div class="form-group " >
                                <label class="col-sm-2 control-label required" for="idContatto">Contatto di riferimento</label>
                                <div class="col-sm-10">
                                    <select name="id_contatto" id="idContatto" class="form-control " >
                                            <option style="color: graytext;" value="0">Nessuno</option>
                                    </select>
                                </div>
                            </div>
                            <hr />
                                <div class="form-group ">
                                    <label class="col-sm-2 control-label required" for="inputTitle">Titolo</label>
                                    <div class="col-sm-9">
                                        <input type="hidden" name="idEvent" id="idEvent" >
                                        <input type="hidden" name="idGoogle" id="idGoogle" >
                                        <input type="text" placeholder="Titolo dell'evento" name="title" id="inputTitle" class="form-control required" >

                                    </div>
                                    <div class=" col-sm-1 colorpicker-element">
                                    <div class="form-control">
                                        <select id="inputColor" name="color" class=""  >
                                        <?php foreach($eventColors as $color): ?>
                                            <option value="<?=$color?>" data-color="<?=$color?>"><?=$color?></option>
                                        <?php endforeach ?>
                                        </select>
                                    </div>
                                    </div>
                                    <?php /*<div class=" col-sm-1 my-colorpicker2 colorpicker-element">
                                            <div class="input-group-addon">
                                                <i style="background-color: rgb(255, 255, 0);"></i>
                                            </div>
                                            <input type="hidden" class="form-control" value="#3a87ad" name="color" id="inputColor">
                                        </div>--> */?>

                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="checkAllDay" id="checkAllDay" value="1"> Giornata intera
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputStart">Da</label>
                                    <div class="col-sm-3 data-da">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" placeholder="Start" name="startDate" id="inputStartDate" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 ora-da">
                                        <input type="text" name="startTime" id="inputStartTime" class="form-control" data-mask="" data-inputmask="'mask': '99:99'">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-2 control-label" for="inputEnd">A</label>
                                    <div class="col-sm-3 data-a">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" placeholder="End" name="endDate" id="inputEndDate" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-2 ora-a">
                                        <input type="text" name="endTime" id="inputEndTime" class="form-control" data-mask="" data-inputmask="'mask': '99:99'">
                                    </div>
                                </div>

                                <hr>
                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <div class="checkbox">
                                            <label>
                                                <input id="checkBoxRepeated" type="checkbox" name="repeated" value="1"> Evento ripetuto
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div id="collapseRepeated" class="collapse">
                                <div class="form-group ">
                                    <label class="col-sm-2 control-label required" for="INTERVAL">Ripeti ogni... </label>
                                    <div class="col-sm-4">
                                    <input type="number" min="0" step="1" value="1" class="form-control" name="INTERVAL" id="INTERVAL">
                                    </div>
                                    <div class="col-sm-4">
                                    <select class="form-control" name="FREQ" id="FREQ">
                                    <option value="DAILY">giorno</option>
                                    <option value="WEEKLY">settimana</option>
                                    <option value="MONTHLY">mese</option>
                                    <option value="YEARLY">anno</option>
                                    </select>
                                    </div>
                                </div>
                                <div class="form-group ">
                                    <label class="col-sm-2 control-label required" for="repeatedEndType">Termina </label>
                                    <div class="col-sm-4">
                                    <select class="form-control" name="repeatedEndType" id="repeatedEndType">
                                    <option value="NEVER">Mai</option>
                                    <option value="COUNT">Dopo</option>
                                    <option value="UNTIL">Fino al</option>
                                    </select>
                                    </div>
                                    <div class="col-sm-4" id="repeatedEndCount" hidden>
                                    <div class="row">
                                        <div class="col-sm-6">
                                        <input type="number" min="0" step="1" value="1" class="form-control" name="COUNT" id="COUNT">
                                        </div>
                                        <div class="col-sm-6">
                                        <p style="margin-top:5px;">
                                            occorrenze
                                        </p>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-sm-4" id="repeatedEndUntil" hidden>
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-calendar"></i>
                                            </div>
                                            <input type="text" placeholder="Fine" name="UNTIL" id="UNTIL" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <input type="hidden" name="EXDATE" id="EXDATE" class="form-control" value='' readonly>
                                </div>
                                </div>                                
                                <hr />
                                <div class="form-group" id="idTagsParent">
                                    <label class="col-sm-2 control-label" for="id_tags">Tag</label>
                                    <div class="col-sm-10">
                                        <select multiple="multiple" name="id_tags" id="idTags" class="select2 form-control"></select>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>

                    <div class="tab-pane" id="tab_2">
                        <div class="box-body">
                            <div class="form-group">
                                <div class="col-md-12" >
                                    <textarea id="inputNote" class="editor-html"></textarea>
                                </div>
                            </div>
                            <?= $this->Form->create(null, array( 'enctype' => 'multipart/form-data', 'style'=>'width:0px;height:0px;overflow:hidden', 'id'=>'tinymce_upload_form')) ?>
                                <input name="uploadedfile" type="file" id="tinymce_upload" class="" >
                            <?= $this->Form->end(); ?>
                        </div>
                    </div>
                <?php if($webappActivated == '1'){ ?>
                    <div class="tab-pane" id="tab_3">
                        <div class="box-body">
                            <form class="form-horizontal" id="formEventDetails">

                                <div class="form-hidden" hidden>

                                    <p class="operatorP" hidden></p>
                                    <hr class="operatorLine" id="" hidden>

                                    <input type="text" id="" name="idEventDetail" value="" hidden />
                                    <input type="text" id="" name="idEvento" value="" hidden />
                                    <input type="text" id="" name = "idOperatore" value="" hidden />

                                    <div class="col-sm-6">
                                        <h4>Start</h4>
                                        <p>
                                            <b>Ora</b>:
                                            <input class="status-done" id="" type="date" name="user_start_date" value="" />
                                            <input class="status-done" id="" type="time" name="user_start_time" value="" />
                                            <br />
                                            (<span id='' name="user_real_start"></span>)
                                        </p>
                                        <p style="margin:0; padding:0;">
                                            <b>Latitudine</b>:
                                            <span id='' name="user_start_lat"></span>
                                        </p>
                                        <p>
                                            <b>Longitudine</b>:
                                            <span id='' name="user_start_long"></span>
                                        </p>
                                    </div>

                                    <div class="col-sm-6">
                                        <h4>Stop</h4>
                                        <p>
                                            <b>Ora</b>:
                                            <input class="status-done" id="" type="date" name="user_stop_date" value="" />
                                            <input class="status-done" id="" type="time" name="user_stop_time" value="" />
                                            <br />
                                            (<span id='' name="user_real_stop"></span>)
                                        </p>
                                        <p style="margin:0; padding:0;">
                                            <b>Latitudine</b>:
                                            <span id='' name="user_stop_lat"></span>
                                        </p>
                                        <p>
                                            <b>Longitudine</b>:
                                            <span id='' name="user_stop_long"></span>
                                        </p>
                                    </div>

                                    <div class="col-sm-12">
                                        <h4 style="display: inline-block;">Note</h4>
                                        <input type="hidden" name="note_importanza" value="0" />
                                        <input class="status-done" type="checkbox" id="" name="note_importanza" value="1"/> Importante
                                        <textarea rows="2" cols="70" class="form-control status-done" id="" name="event_details_note"></textarea><br />
                                    </div>
                                </div>

                            </form>
                            </div>
                        <?php if($connectionTimetask == '1'){ ?>
                        <button type="button" class="btn btn-primary showIfLive status-done" id="sendTimeTimetask" style="position:relative; top:60px;" >Invia tempo a timetask</button>
                        <?php } ?>
                    </div>

                    <div class="tab-pane" id="tab_4" style="margin-top: 25px;">
                        <div class="box-body">
                            <div class="col-sm-12" id="eventFirma">
                                <div class="firma-hidden" hidden>
                                    <p class="operatoreFirma"></p>
                                    <hr class="operatorLineFirma" id="" hidden>
                                    <h4>Firma</h4>
                                    <span id='eventDetailsFirma' name="eventDetailsFirma" style="position: relative; left: 50px;"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="tab-pane" id="tab_5">
                        <div class="box-body">
                            <div class="col-sm-12">
                                <h4 id="eventMapTitle" style="padding-top: 5px;">Mappa</h4>
                                <i id="eventMapTitle" class="fa fa-map-marker" style="font-size:30px; color: rgb(31, 230, 61);"></i><span id="eventMapTitle"> Inizio evento</span>
                                <i id="eventMapTitle" class="fa fa-map-marker" style="font-size:30px; color: red;"></i><span id="eventMapTitle"> Fine evento</span>
                                <div id="eventDetailsMap" style="width:100%; height:400px;"></div><br />
                                <div id="nonDisponibile" style="width:100%; height:400px; margin-left: -15px; display: flex; align-items: center; justify-content: center;" hidden><h2>Mappa non disponibile</h2></div><br />
                            </div>
                        </div>
                    </div>
                <?php } ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-danger" id="eliminaEvento">Elimina</button>
                <button type="button" class="btn btn-primary" id="salvaNuovoEvento" >Salva</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="myModalRepeatedCalendar" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modifica evento ripetuto</h4>
      </div>
      <div class="modal-body">
        <div class="row" style="margin-top:10px;">
          <div class="col-sm-3">
              <button value="allEvents" type="button" class="btn btn-primary repeatedModifyEvents" data-dismiss="modal">Tutti gli eventi</button>
          </div>
          <div class="col-sm-9">
              <p>Le modifiche verranno apportate a tutti gli eventi della serie passati e futuri.</p>
          </div>
        </div>
        <div class="row" style="margin-top:10px;">
          <div class="col-sm-3">
              <button value="futureEvents" type="button" class="btn btn-primary repeatedModifyEvents" data-dismiss="modal">Solo eventi futuri</button>
          </div>
          <div class="col-sm-9">
              <p>Le modifiche verranno apportate solo agli eventi futuri rispetto a questo evento</p>
          </div>
        </div>
        <div class="row" style="margin-top:10px;">
          <div class="col-sm-3">
              <button value="thisEvent" type="button" class="btn btn-primary repeatedModifyEvents" data-dismiss="modal">Solo questo evento</button>
          </div>
          <div class="col-sm-9">
              <p>Le modifice verranno apportate solo a questo evento.</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="myModalRepeatedDelete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Cancellazione evento ripetuto</h4>
      </div>
      <div class="modal-body">
        <div class="row" style="margin-top:10px;">
          <div class="col-sm-3">
              <button value="allEvents" type="button" class="btn btn-warning repeatedDeleteEvents" data-dismiss="modal">Tutti gli eventi</button>
          </div>
          <div class="col-sm-9">
              <p>Le modifiche verranno apportate a tutti gli eventi della serie passati e futuri.</p>
          </div>
        </div>
        <div class="row" style="margin-top:10px;">
          <div class="col-sm-3">
              <button value="thisEvent" type="button" class="btn btn-warning repeatedDeleteEvents" data-dismiss="modal">Solo questo evento</button>
          </div>
          <div class="col-sm-9">
              <p>Le modifice verranno apportate solo a questo evento.</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= Configure::read('localconfig.GoogleApiKey'); ?>"></script>
