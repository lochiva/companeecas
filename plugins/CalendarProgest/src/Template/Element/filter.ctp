<?php
use Cake\Routing\Router;
use Cake\Core\Configure;
?>
<style>
.calendar-filter .select2{
    max-width:100%;
    width: 100% !important;
}
</style>
<script>
  $(document).ready(function(){
    $('#user-caledar-view').change(function(){
        $('.eventsNoteList').html('');
        $('.eventsCompresenzeList').html('');
        var userId = $(this).val();
        var events = pathServer + 'calendar/ws/getCalendarEvents/1/' +userId;
        $('#calendar').fullCalendar( 'removeEvents');
        $('#calendar').fullCalendar( 'removeEventSources');
        $('#calendar').fullCalendar( 'addEventSource', events);
        /*var initData = loadTempCalendar();
        if(initData.default != true){
            $('#calendar').fullCalendar( 'changeView', initData.view );
            $('#calendar').fullCalendar( 'gotoDate', initData.date );
        }*/
        //document.getElementById('import-form').action = '<?= Router::url(['plugin' => 'Calendar','controller' => 'home','action' => 'importCalendar']) ?>/'+userId;
        //$('#calendar').fullCalendar( 'refetchEvents');
        localStorage.setItem('companee-calendar-idUser',userId);
        //$('#calendar').fullCalendar('refetchEvents');
    });
    $('#person-caledar-view').change(function(){
        $('.eventsNoteList').html('');
        $('.eventsCompresenzeList').html('');
        var userId = $(this).val();
        var events = pathServer + 'calendar/ws/getCalendarEvents/2/' +userId;
        $('#calendar').fullCalendar( 'removeEvents');
        $('#calendar').fullCalendar( 'removeEventSources');
        $('#calendar').fullCalendar( 'addEventSource', events);
        /*var initData = loadTempCalendar();
        if(initData.default != true){
            $('#calendar').fullCalendar( 'changeView', initData.view );
            $('#calendar').fullCalendar( 'gotoDate', initData.date );
        }*/
        //document.getElementById('import-form').action = '<?= Router::url(['plugin' => 'Calendar','controller' => 'home','action' => 'importCalendar']) ?>/'+userId;
        //$('#calendar').fullCalendar( 'refetchEvents');
        localStorage.setItem('companee-calendar-idUser',userId);
        //$('#calendar').fullCalendar('refetchEvents');
    });

    $(".select2-user").select2({
       language: 'it',
       width: '100%',
       placeholder: 'Seleziona un operatore'
     });
     $(".select2-person").select2({
        language: 'it',
        width: '100%',
        placeholder: 'Seleziona una persona'
      });
     $('#user-type').change(function(){
         $('.eventsNoteList').html('');
         $('.eventsCompresenzeList').html('');
         var val = $(this).val();
         localStorage.setItem('companee-calendar-userType',val);
         switch (val) {
           case '1':
             $('#radio-serviceType').show();
             $('.select-filter-2').hide();
             $('.select-filter-1').show();
             $('#user-caledar-view').trigger('change');
             break;
           case '2':
             $('#radio-serviceType').hide();
             $('.select-filter-1').hide();
             $('.select-filter-2').show();
             $('#person-caledar-view').trigger('change');
             break;
         }
     });
  });
  $(document).on('click','#syncGoogle', function(){
      var date = $('#calendar').fullCalendar('getDate');
      var start = moment(date).startOf('month').format('YYYY-MM-DD');
      var end = moment(date).endOf('month').add(1,'days').format('YYYY-MM-DD');
      $.ajax({
                  url : pathServer + "calendar/ws/syncGoogle/",
                  type  : "get",
                  data : {start:start, end:end},
                  dataType : "json",
                  success : function (data,stato) {
                    if (data.response == 'OK'){
                      $('#calendar').fullCalendar( 'refetchEvents' );
                    }else{
                      alert(data.msg);
                    }

                  },
                  error: function(data) {

                  }
            });

  });

</script>

<div class="row form-horizontal">
        <?php if($authUser['role'] !== 'admin'):?>
          <div class="col-sm-9 ">
            <label style="margin-top:7px;">Programmazione di <b><?= $authUser['username'] ?></b></label>
            <input type="hidden" name="user_used" value="<?= (!empty($contactUser['id'])?$contactUser['id'] : '') ?>" id="user-caledar-view">
            <input type="hidden" name="user_type" class="form-control" id="user-type" value="1">
          </div>
        <?php else: ?>

          <label style="text-align:left" class="control-label col-md-3 col-sm-12 ">Programmazione di</label>

          <div class="col-md-3 col-sm-6 margin10-bot">
              <select name="user_type" class="form-control" id="user-type">
                  <option value="1">Operatore</option>
                  <option value="2">Persona</option>
              </select>
          </div>
          <div class="col-md-3 col-sm-6 select-filter-1 margin10-bot" >
              <select name="user_used" class="form-control select2-user" id="user-caledar-view">
                <?php foreach ($contacts as $key => $contact) :?>
                  <option></option>
                  <?php $selected = ($authUser['id'] == (empty($contact['User']['id']) ? '':$contact['User']['id'])? 'selected' : ''); ?>
                  <option value="<?=  $contact['id'];?>" <?=  $selected;?>><?= h($contact['cognome'] . " " . $contact['nome']);?></option>
                <?php endforeach ?>
              </select>
          </div>

          <div class="col-md-3 col-sm-6 select-filter-2 margin10-bot" hidden>
              <select name="person_used" class="form-control select2-person" id="person-caledar-view">
                <option></option>
                <?php foreach ($people as $key => $person) :?>
                  <option value="<?=  $person['id'];?>" ><?= h($person['text'])?></option>
                <?php endforeach ?>
              </select>
          </div>
        <?php endif ?>

  <div class="col-md-3 col-sm-12 text-right">


    <div class="btn-group margin10-bot">
      <div class="btn-group" data-toggle="tooltip" data-placement="top" title="Stampa piano settimanale">
          <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><i class="fa fa-print"></i>&nbsp;
            <span class="fa fa-caret-down"></span></button>
          <ul class="dropdown-menu pull-right">
            <li><a data-toggle="modal" data-target="#myModalStampOperatori" href="#">Calendario operatori</a></li>
            <li><a data-toggle="modal" data-target="#myModalStampPersone" href="#">Calendario persone</a></li>
            <li><a data-toggle="modal" data-target="#myModalStampMonteOre" href="#">Monte ore sett. operatori</a></li>
          </ul>
      </div>
      <?php if($authUser['role'] == 'admin'):?>
          <div class="btn-group" data-toggle="tooltip" data-placement="top" title="Duplica settimana del passato">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" ><i class="fa fa-clone"></i>&nbsp;
                <span class="fa fa-caret-down"></span></button>
              <ul class="dropdown-menu pull-right">
                <li><a id="cloneEvents7-this" href="#">Operatore corrente settimana precedente</a></li>
                <li><a id="cloneEvents7-all" href="#">Tutti gli operatori settimana precedente</a></li>
                <li><a id="cloneEvents14-this" href="#">Operatore corrente settimana precedente precedente</a></li>
                <li><a id="cloneEvents14-all" href="#">Tutti gli operatori settimana precedente precedente</a></li>
              </ul>
          </div>
          <div class="btn-group" data-toggle="tooltip" data-placement="top" title="Congela settimana">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false" ><i class="fa fa-calendar"></i>&nbsp;
                <span class="fa fa-caret-down"></span></button>
              <ul class="dropdown-menu pull-right">
                <li><a id="frozeCalendar" href="#">Congela</a></li>
                <li><a id="view-frozenCalendar" class="showIfLive" href="#">Visualizza congelata</a></li>
                <li hidden class="showIfFrozen"><a id="view-liveCalendar" href="#">Visualizza live</a></li>
              </ul>
          </div>
          <div  class="btn-group" data-toggle="tooltip" data-placement="top" title="Controllo">
          <button data-toggle="modal" data-target="#myModalCheck" type="button" class="btn btn-default checkEvents" ><i class="fa fa-cog"></i></button>
          </div>
      <?php endif ?>
    </div>
  </div>


  <?php
  /*<div class="col-md-6 col-sm-12">
    <?php if($authUser['role'] == 'admin'):?><label class="mobile-hidden">&nbsp;</label><?php endif ?>
    <?= $this->Form->create(null, array('url' => '/calendar/home/importCalendar/'.$authUser['id'] ,'enctype' => 'multipart/form-data', 'id'=>'import-form')) ?>

      <div class="input-group margin10-bot import-calendar">

        <input class="form-control " style=" cursor: pointer;" type="file" name="uploadedfile" required />
        <span class="input-group-btn">
          <button class="btn btn-flat btn-info "  data-toggle="tooltip"  title="Importa un calendario in formato ical"><i class="glyphicon glyphicon-import" aria-hidden="true"></i></button>
        </span>


      </div>
    <?= $this->Form->end(); ?>
    <button id="exportIcal" class="btn btn-flat btn-info margin10-bot pull-right" data-toggle="tooltip"  title="Esporta il calendario in formato ical"><i class="glyphicon glyphicon-export" aria-hidden="true"></i></button>
  </div>*/
 ?>


 <?php
  /*<div class="col-md-1 col-sm-12">
    <button id="syncGoogle" class="btn btn-flat btn-success pull-right margin10-bot btn-block" data-toggle="tooltip"  title="Sincronizzati con il tuo calendario google" style="margin-left:15px;"><i class="fa fa-google" aria-hidden="true"></i></button>
  </div>*/
 ?>
</div>

<?php /*
<?php if($authUser['role'] !== 'admin'):?>
  Programmazione di <b><?= $authUser['username'] ?></b>
  <input type="hidden" name="user_used" value="<?php echo $authUser['id']; ?>" id="user-caledar-view">
<?php else: ?>
  <label>Programmazione di</label>
  <select name="user_used" class="form-control select2" style="width:250px" id="user-caledar-view">
    <?php foreach ($users as $key => $user) :?>
      <?php $selected = ($authUser['id'] == $user['id']? 'selected' : '') ?>
      <option value="<?=  $user['id'];?>" <?=  $selected;?>><?= $user['username'].": ".$user['cognome'] . " " . $user['nome'];?></option>
    <?php endforeach ?>
  </select>
<?php endif ?>
<button id="syncGoogle" class="btn btn-flat btn-default pull-right" data-toggle="tooltip"  title="Sincronizzati con il tuo calendario google" style="margin-left:15px;"><i class="fa fa-google" aria-hidden="true"></i></button>
<button id="exportIcal" class="btn btn-flat btn-default pull-right" data-toggle="tooltip"  title="Esporta il calendario in formato ical">Esporta <i class="fa fa-calendar" aria-hidden="true"></i></button>
<?= $this->Form->create(null, array('url' => '/calendar/home/importCalendar/'.$authUser['id'] ,'enctype' => 'multipart/form-data', 'class'=>'pull-right', 'id'=>'import-form')) ?>
  <div class="row">
    <div class="col-sm-8">
        <input class="form-control " type="file" name="uploadedfile" required />
    </div>
    <div class="col-sm-2">
      <button class="btn btn-flat btn-default " data-toggle="tooltip"  title="Importa un calendario in formato ical">Importa <i class="fa fa-calendar" aria-hidden="true"></i></button>
    </div>
  </div>
<?= $this->Form->end(); ?>
*/ ?>
