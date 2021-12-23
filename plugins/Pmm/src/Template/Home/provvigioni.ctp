<?php
use Cake\Routing\Router;
?>
<script>
$(document).ready(function(){
  $('#xls-export').click(function(){
    var anno = $('#anno').val();
    var mese = $('#mese').val();
    if(anno != '' && anno != 0){
        window.open('<?= Router::url(['plugin' => 'Pmm','controller' => 'Ws','action' => 'getXlsProvvigioni'])  ?>'+'/'+anno+'/'+mese,'_self');
      }else{
        alert("Devi inserire almeno l'anno");
      }
  });
});
</script>
<section class="content-header">
    <h1>
        PROVVIGIONI
        <small> Esportazione provvigioni </small>
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-home"></i>Home</a></li>
        <li class="active">Proviggioni</li>
    </ol>
</section>

<div class="row">
  <div class="col-sm-6">
    <div class="form-horizontal">
        <div class="box-body">
          <div class="form-group ">
              <label class="col-sm-2 control-label required">Anno</label>
              <div class="col-sm-10">
                <select name="anno" id="anno" class="form-control required" >
                    <?php foreach ($anni as $key => $anno): ?>
                        <option value="<?= $key ?>"><?= $anno ?></option>
                    <?php endforeach ?>
                </select>
              </div>
          </div>

          <div class="form-group">
              <label class="col-sm-2 control-label" >Mese</label>
              <div class="col-sm-10">
                <select name="mese" id="mese" class="form-control required" >
                    <?php foreach ($mesi as $key => $mese) : ?>
                        <option value="<?= $key ?>"><?= $mese ?></option>
                    <?php endforeach ?>
                </select>
              </div>
          </div>
          <div class="form-group">
              <label class="col-sm-2 control-label" ></label>
              <div class="col-sm-10">
                <button class="btn btn-flat btn-default " data-toggle="tooltip" id="xls-export"
                  title="" data-original-title="Esporta il report in formato xlsx per Excel"><img src="<?php echo Router::url('/'); ?>img/xls.png">Genera</button>
              </div>
          </div>
        </div>
    </div>
  </div>
</div>
