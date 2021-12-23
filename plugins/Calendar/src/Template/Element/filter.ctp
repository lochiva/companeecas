<?php
use Cake\Routing\Router;
use Cake\Core\Configure;
?>
<script>
  $(document).ready(function(){
    $('#user-caledar-view').change(function(){
        var userId = $(this).val();
        var events = pathServer + 'calendar/ws/getCalendarEvents/' +userId;
        $('#calendar').fullCalendar( 'removeEvents');
        $('#calendar').fullCalendar( 'removeEventSources');
        $('#calendar').fullCalendar( 'addEventSource', events);
        var initData = loadTempCalendar();
        if(initData.default != true){
            $('#calendar').fullCalendar( 'changeView', initData.view );
            $('#calendar').fullCalendar( 'gotoDate', initData.date );
        }
        document.getElementById('import-form').action = '<?= Router::url(['plugin' => 'Calendar','controller' => 'home','action' => 'importCalendar']) ?>/'+userId;
        //$('#calendar').fullCalendar( 'refetchEvents');


        //$('#calendar').fullCalendar('refetchEvents');
    });
    $('#exportIcal').click(function(){
        var userId = $('#user-caledar-view').val();
        window.open('<?= Router::url(['plugin' => 'Calendar','controller' => 'Ws','action' => 'export']) ?>/'+userId,'_self');
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

<div class="row">
  <div class="col-md-5 col-dm-12 margin10-bot" >
    <?php if($authUser['role'] !== 'admin'):?>
      <p style="margin-top:6px;margin-bottom: 0px">Programmazione di <b><?= $authUser['username'] ?></b></p>
      <input type="hidden" name="user_used" value="<?php echo $authUser['id']; ?>" id="user-caledar-view">
    <?php else: ?>
      <label style="margin-right: 10px;">Programmazione di</label>
      <select name="user_used" class="form-control select2" style="max-width:250px" id="user-caledar-view">
        <?php foreach ($users as $key => $user) :?>
          <?php $selected = ($authUser['id'] == $user['id']? 'selected' : '') ?>
          <option value="<?=  $user['id'];?>" <?=  $selected;?>><?= $user['username'].": ".$user['cognome'] . " " . $user['nome'];?></option>
        <?php endforeach ?>
      </select>
    <?php endif ?>
  </div>

  
  <div class="col-md-4 col-sm-9 col-xs-12">
  
    <?= $this->Form->create(null, array('url' => '/calendar/home/importCalendar/'.$authUser['id'] ,'enctype' => 'multipart/form-data', 'id'=>'import-form')) ?>
      <div class="input-group margin10-bot">
      
        <input class="form-control " style=" cursor: pointer;" type="file" name="uploadedfile" required />
        <span class="input-group-btn">
          <button class="btn btn-flat btn-info "  data-toggle="tooltip"  title="Importa un calendario in formato ical"><i class="glyphicon glyphicon-import" aria-hidden="true"></i></button>
        </span>
      
      </div>
    <?= $this->Form->end(); ?>
  </div>
  <div class="col-md-2 col-sm-3 col-xs-12">
      <button id="exportIcal" class="btn btn-flat btn-info margin10-bot btn-block" data-toggle="tooltip"  title="Esporta il calendario in formato ical">Esporta <i class="glyphicon glyphicon-export" aria-hidden="true"></i></button>
  
  </div>

  <div class="col-md-1 col-sm-12">
    
    
    <button id="syncGoogle" class="btn btn-flat btn-success pull-right margin10-bot btn-block" data-toggle="tooltip"  title="Sincronizzati con il tuo calendario google" style="margin-left:15px;"><i class="fa fa-google" aria-hidden="true"></i></button>
    
    

  </div>

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