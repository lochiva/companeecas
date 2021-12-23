<?php
use Cake\Routing\Router;
use Cake\Core\Configure;
?>

<?php echo $this->Html->script('Calendar.modale_calendario'); ?>

<div class="modal fade" id="myModalCalendar" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Nuovo Evento</h4>
            </div>
			<div class="nav-tabs-custom">
              <ul class="nav nav-tabs">
                <li class="active"><a href="#evento" data-toggle="tab">Evento</a></li>
                <li><a href="#eventoDettagli" data-toggle="tab">Dettagli evento</a></li>
				<li><a href="#firma" data-toggle="tab">Firma</a></li>
                <!--<li><a href="#mappa" data-toggle="tab">Mappa</a></li>-->
              </ul>

              <div class="tab-content">
                <div class="tab-pane active" id="evento">
		            <div class="modal-body">
		                <form class="form-horizontal" id="myModalCalendarForm">
		                    <div class="box-body">
		                      <div id="radio-serviceType" class="form-group">
		                        <div class=" col-sm-offset-2 col-sm-10">
		                          <div class="col-sm-4">
		                            <label class="radio-inline"><input data-color="<?= h($categories[0]->color) ?>" value="1" type="radio" name="optCategory" checked><?= h($categories[0]->name) ?></label>
		                          </div>
		                          <div class="col-sm-4">
		                            <label class="radio-inline"><input data-color="<?= h($categories[1]->color) ?>" value="2" type="radio" name="optCategory"><?= h($categories[1]->name) ?></label>
		                          </div>
		                        </div>
		                      </div>
		                      <hr />
		                      <div class="form-group" id="idAziendaParent" hidden>
		                          <label class="col-sm-2 control-label required" for="idAzienda">Committente</label>
		                          <div id="parentAzienda" class="col-sm-10">
		                              <input type="hidden" name="id_azienda" id="idAzienda" class="form-control"></select>
		                          </div>
		                      </div>
		                      <div class="only-cat-1">
		                          <div class="form-group" id="idPersonParent">
		                              <label class="col-sm-2 control-label required" for="idPerson">Persona</label>
		                              <div class="col-sm-10">
		                                  <select name="id_person" id="idPerson" class="select2 form-control"></select>
		                              </div>
		                          </div>
		                          <div class="form-group" id="idOrderParent">
		                              <label class="col-sm-2 control-label required" for="idOrder">Buono d'ordine</label>
		                              <div class="col-sm-10">
		                                  <select name="id_order" id="idOrder" class="form-control"></select>
		                              </div>
		                          </div>
		                      </div>
		                      <div class="form-group" id="idOrderParent">
		                          <label class="col-sm-2 control-label required" for="idService">Servizio</label>
		                          <div class="col-sm-10">
		                              <select name="id_service" id="idService" class="form-control required"></select>
		                          </div>
		                      </div>
		                      <?php if($authUser['role'] == 'admin'): ?>
		                        <div class="form-group" id="idUserParent">
		                            <label class="col-sm-2 control-label required" for="idUser">Operatore:</label>
		                            <div class="col-sm-9">
		                              <select name="user_used" class="form-control select2 required" id="idUser">
		                                <option></option>
		                              </select>
		                            </div>
		                            <div class="col-sm-1">
		                              <button type="button" class="btn btn-default add-operatore"><i class="fa fa-plus" aria-hidden="true"></i></button>
		                            </div>
		                        </div>
		                      <?php endif ?>
		                      <div id="compresenze-list" class="only-cat-1">
		                      </div>
		                      <hr />
		                        <div class="form-group ">
		                            <label class="col-sm-2 control-label" for="inputTitle">Titolo</label>
		                            <div class="col-sm-9">
		                                <input type="text" placeholder="Titolo dell'evento" name="title" id="inputTitle" class="form-control" readonly>
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
		                        <div class="form-group">
		                            <label class="col-sm-2 control-label" for="inputNote">Note</label>
		                            <div class="col-sm-10">
		                                <textarea type="text" placeholder="Note" name="note" id="inputNote" class="form-control"></textarea>
		                            </div>
		                        </div>
		                        <hr />
		                        <div class="form-group" id="idTagsParent">
		                            <label class="col-sm-2 control-label" for="id_tags">Tag</label>
		                            <div class="col-sm-10">
		                                <select multiple="multiple" name="id_tags" id="idTags" class="select2 form-control"></select>
		                            </div>
		                        </div>
		                        <input type="hidden" name="idEvent" id="idEvent" >
		                        <input type="hidden" name="idGoogle" id="idGoogle" >
		                        <input type="hidden" name="id_group" id="idGroup" >
		                    </div>
		                </form>
		            </div>
					<button type="button" class="btn btn-primary showIfLive" id="salvaNuovoEvento" style="position:relative; left:100px; top:60px;">Salva</button>
				</div>
                <div class="tab-pane" id="eventoDettagli" style="margin-left: 30px">
					<form class="form-horizontal" id="formEventDetails">
						<input type="text" id="idEventDetail" value="" hidden />
						<input type="text" id="idEvento" value="" hidden />
						<input type="text" id="idOperatore" value="" hidden />
						<div class="col-sm-6">
							<h4>Start</h4>
							<p><b>Ora</b>: <input id="startData" type="date" name="user_start_date" value="" /><input id="startOra" type="time" name="user_start_time" value="" /> </br>
								(<span id='startRealOra'></span>)</p>
							<p><b>Latitudine</b>: <span id='startLat'></span></p>
							<p><b>Longitudine</b>: <span id='startLong'></span></p>
						</div>
						<div class="col-sm-6">
							<h4>Stop</h4>
							<p><b>Ora</b>: <input id="stopData" type="date" name="user_stop_date" value="" /><input id="stopOra" type="time" name="user_stop_time" value="" /> </br>
								(<span id='stopRealOra'></span>)</p>
							<p><b>Latitudine</b>: <span id='stopLat'></span></p>
							<p><b>Longitudine</b>: <span id='stopLong'></span></p>
						</div>
						<div class="col-sm-12">
							<h4>Note</h4>
							<textarea rows="2" cols="70" class="form-control" id="eventDetailsNote" name="event_details_note"></textarea></br>
							<input type="checkbox" id="eventDetailsNoteImportanza" name="note_importanza" /> Importante
						</div>
						<div class="col-sm-12" style="padding-top: 30px;" id="activitiesDiv">
							<h4>Attività</h4>
							<div id='eventDetailsActivities'></div>
						</div>
					</form>
					<button type="button" class="btn btn-primary showIfLive" id="salvaEventoDettagli" style="position:relative; left:70px; top:60px;" >Salva</button>
				</div>

				<div class="tab-pane" id="firma" style="margin-left: 30px">
					<div class="col-sm-12">
						<h4>Firma</h4>
						<span id='eventDetailsFirma' style="position: relative; left: 50px;"></span>
					</div>
				</div>
				<!--
				<div class="tab-pane" id="mappa" style="margin-left: 30px">
					<div class="col-sm-12">
						<h4>Mappa</h4>
						<div id="eventDetailsMap" style="width:100%; height:400px;"></div></br>
					</div>
			</div>
			-->
			  </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Chiudi</button>
                <button type="button" class="btn btn-danger showIfLive pull-left" id="eliminaEvento">Elimina</button>
              </div>
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

<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?= Configure::read('localconfig.GoogleApiKey'); ?>&callback=initMap"></script>
