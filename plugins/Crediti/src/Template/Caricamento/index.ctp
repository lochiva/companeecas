<?php
use Cake\Routing\Router;
?>
<script>
$.fn.datepicker.dates['it'] = {
    days: ["Domenica", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    daysMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
    months: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
    monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    today: "Today",
    clear: "Clear",
    format: "dd/mm/yyyy",
    titleFormat: "MM yyyy",
    weekStart: 1
};


$(function() {


      $("#datepicker").datepicker({ language: 'it', format: 'dd/mm/yyyy', autoclose:true, todayHighlight:true});


  });
$(document).on('click','input[name="save-credits_totals"]',function(){
  if($('input[name="save-credits_totals"]').is( ':checked' ) && !$('#save-file').is( ':checked' )  ){
    $('#save-file').prop( "checked", true );
  }
});

</script>
<section class="content-header">
    <h1>
        Crediti
        <small>Importazione file</small>
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-bank"></i>Crediti</a></li>
        <li class="active">Importazione</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-caricamento" class="box">
              <form class="form-horizontal" enctype="multipart/form-data" method="POST" action="<?= Router::url(['controller' => 'Caricamento', 'action' => 'add']); ?>" />
              <div class="box-body">


                   <div class="row">
                      <div class="col-md-6">
                          <div class="form-group">
                              <label class="col-md-3 control-label required" for="inputCognome">Check file</label>
                              <div class="col-md-9">
                                  <?= $this->Form->input('check-file' , array('type'=>'checkbox','class' => 'form-cotrol',
                                  'label'=>false,"onclick"=>"return false;","onkeydown"=>"return false;","checked" => true)) ?>
                              </div>

                          </div>
                          <div class="form-group">
                              <label class="col-md-3 control-label required" for="inputCognome">Importa crediti</label>
                              <div class="col-md-9">
                                  <?= $this->Form->input('save-file' , array('id'=>'save-file','type'=>'checkbox','class' => 'form-cotrol','label'=>false)) ?>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-md-3 control-label required" for="inputCognome">Calcola Totali</label>
                              <div class="col-md-9">
                                  <?= $this->Form->input('save-credits_totals' , array('type'=>'checkbox','class' => 'form-cotrol','label'=>false)) ?>
                              </div>
                          </div>
                          <div class="form-group">
                              <label class="col-md-3 control-label required" for="inputCognome">Prefisso</label>

                                <div class="col-md-3">
                                  <?= $this->Form->input('prefix',array( 'class' => 'form-control','label'=>false, 'value' => 'CI-','required'=>true)) ?>
                                </div>

                               <div class="col-md-12" id="divCheckPasswordLength"></div>

                          </div>
                          <div class="form-group">
                              <label class="col-md-3 control-label required" for="inputCognome">Data Caricamento</label>
                              <div class="col-md-3">
                                <?= $this->Form->input('date',array('id' => 'datepicker', 'class' => 'form-control','label'=>false ,'required'=>true,'value'=>date('d/m/Y'))) ?>
                              </div>

                          </div>
                          <div class="form-group">
                              <label class="col-md-3 control-label required" for="inputCognome">Condizioni di pagamento</label>
                              <div class="col-md-3">
                                <?= $this->Form->input('giorni',array('type'=>'number', 'class' => 'form-control','label'=>false ,'required'=>true,'value'=>60)) ?>
                              </div>

                          </div>
                          <div class="form-group">
                              <label class="col-md-3 control-label required" for="inputCognome">Carica file</label>
                              <div class="col-md-9">
                               <?= $this->Form->input('file',array('type' => 'file', 'class' => 'form-control','label'=>false ,'required'=>true)) ?>
                               <div class="col-md-12" id="divCheckPasswordMatch"></div>
                              </div>
                          </div>

                      </div>
                      <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label required" for="inputCognome">Separatore CSV</label>

                                  <div class="col-md-3">
                                    <?= $this->Form->input('separatore',array( 'class' => 'form-control','label'=>false ,'required'=>true,'value'=>';')) ?>
                                  </div>
                                 <div class="col-md-12" id="divCheckPasswordLength"></div>

                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label required" for="inputCognome">Campo Codice cliente</label>
                                <div class="col-md-9">
                                    <?= $this->Form->input('campi[]' , array('class' => 'form-cotrol','label'=>false,'required'=>true,'value'=>'Codice Cliente')) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label required" for="inputCognome">Campo Num. documento</label>
                                <div class="col-md-9">
                                    <?= $this->Form->input('campi[]' , array('class' => 'form-cotrol','label'=>false,'required'=>true,'value'=>'Num.documanto')) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label required" for="inputCognome">Campo Data</label>
                                <div class="col-md-9">
                                    <?= $this->Form->input('campi[]' , array('class' => 'form-cotrol','label'=>false,'required'=>true,'value'=>'Data')) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label required" for="inputCognome">Campo Residuo</label>
                                <div class="col-md-9">
                                    <?= $this->Form->input('campi[]' , array('class' => 'form-cotrol','label'=>false,'required'=>true,'value'=>'Residuo')) ?>
                                </div>
                            </div>

                      </div>
                  </div>
              </div>
              <div class="box-body">
                <div class ="row">
                  <div class="col-md-6">

                  </div>
                  <div class="col-md-6">
                  </div>
                </div>
              </div>
              <div class="box-footer">
                  <?= $this->Form->button(__('Carica'), array('class' => 'btn btn-warning btn-flat pull-right btn_save_edit')); ?>
              </div>
              <?= $this->Form->end() ?>

            </div>
        </div>
    </div>
</section>
