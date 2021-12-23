<?php
use Cake\Routing\Router;
?>
<?php echo $this->Element('Calendar.include'); ?>
<?php $this->assign('title',$title) ?>
<section class="content-header">
    <h1>
        Calendario
        <small>gestione calendario</small>
        <span class="showIfFrozen frozen-title" hidden>CALENDARIO CONGELATO</span>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-home"></i> Home</a></li>
        <!--<li><a href="<?=Router::url('/calendar/home');?>">Calendario</a></li>-->
        <li class="active">Calendario</li>
    </ol>
</section>

<section class="content">
  <div class="row">
          <div class="col-xs-12">
            <div class="box box-solid calendar-filter">
              <!-- /.box-header -->
              <div class="box-body"  style="padding-bottom:0px">
                  <?php echo $this->Element('Calendar.filter'); ?>
              </div>
              <!-- /.box-body -->
        </div>
            <!-- /.box -->
      </div>
          <!-- /.col -->
  </div>
    <div class="row">
        <div class="col-md-12">
            <div class="box box-primary">
	           <div class="box-body no-padding">

	               <div id="calendar" class="fc fc-ltr fc-unthemed">
                   </div>
                </div>
	        </div>

        </div>

        <div class="col-md-12">
          <div class="box box-info"><div class="box-header with-border"><h3 class="box-title"><i class="fa fa-users"></i> Compresenza</h3></div>
            <div class="eventsCompresenzeList margin10-bot">

            </div>
          </div>
        </div>
        <div class="col-md-12">
        <div class="box box-info"><div class="box-header with-border"><h3 class="box-title"><i class="fa fa-file-text"></i> Note</h3></div>
          <div class="eventsNoteList margin10-bot">

          </div>
        </div></div>

    </div>
</section>
<?php echo $this->Element('Calendar.modale_calendario'); ?>
<?php echo $this->Element('Calendar.modale_stampa'); ?>
<?php echo $this->Element('Calendar.modale_check'); ?>
