<?php
use Cake\Routing\Router;
use Cake\Core\Configure;
?>

<style>
@page { size: landscape;  margin:10px; }
</style>
<div class="container-fluid">
<?php foreach ($datiStampa as $operatore => $events): ?>

  <table class="table table-bordered intestazione" >
    <tr>
      <td width="10%" rowspan="3" class="text-center">
        <img src="<?php echo Router::url('/');?>img/logo-stampe.png">
      </td>
      <td width="10%" rowspan="3"></td>
      <td>
          <h5 class="text-center">SISTEMA QUALIT&Agrave;</h5>
      </td>
      <td colspan="2">
          <p>Codice modulo: <b>MR-09-04-03</b></p>
      </td>
    </tr>
    <tr>
      <td>
        <h4 class="box-title text-center text-uppercase">
          piano settimanale di lavoro
        </h4>
      </td>
      <td width="10%">Data Emiss: <b>29-03-00</b></td>
      <td width="10%">Num.pag: <b>1/1</b></td>
    </tr>
    <tr>
      <td><h5 class="text-center">Procedura di riferimento: PO-09-04</h5></td>
      <td>Ed. <b>Prima</b></td>
      <td>Rev: <b>B</b></td>
    </tr>
  </table>

  <div class="box-body no-padding">
    <table class="table table-striped table-bordered table-operatori" >
      <thead>
        <tr>
          <th width="14.2%">Lunedì</th>
          <th width="14.2%">Martedì</th>
          <th width="14.2%">Mercoledì</th>
          <th width="14.2%">Giovedì</th>
          <th width="14.2%">Venerdì</th>
          <th width="14.2%">Sabato</th>
          <th width="14.2%">Domenica</th>
        </tr>
      </thead>
      <tbody>
        <?php for ($i = 0; $i < $events['max']; $i++): ?>
          <tr>
            <?php for ($l = 1; $l <= 7; $l++): ?>
                <?php if (!empty($events[$l])): ?>
                    <?php $event = array_shift($events[$l]); ?>
                    <td><?= h($this->Text->truncate($event['title'],20)) ?>
                      <?php if ($event['id_group'] != 0): ?>
                        <span style="font-size:26px;float:right;">*</span>
                      <?php endif; ?>
                      <br /><?= h($event['interval']) ?>
                    </td>
                <?php else: ?>
                    <td></td>
                <?php endif; ?>
            <?php endfor; ?>
          </tr>
        <?php endfor; ?>
        <?php /*aggiungo linee vuote fino a riempire la pagina*/ ?>
        <?php
         $occupate= 0;
         if (!empty($events['compresenzeList'])) $occupate= count($events['compresenzeList']);
         $daaggiungere = 10 - $occupate;
         for ($j = $i; $j < $daaggiungere; $j++): ?>
          <tr>
            <?php for ($l = 1; $l <= 7; $l++): ?>
                  <td> <div style="min-height: 40px;"></div> </td>
            <?php endfor; ?>
          </tr>
        <?php endfor; ?>
      </tbody>
    </table>
  </div>
  <div class="operatori-info row" >
    <div class="col-sm-5" >
      <div class="box-info" >
        <h4 class="text-uppercase" style="margin-bottom: -5px;"><b><?= h($operatore) ?> </b></h4>
        <h4 class="text-uppercase">settimana dal <?= $dal ?> al <?= $al ?></h4>
      </div>
    </div>
    <div class="col-sm-7">
      <div class="box-info">
        <h4> <b>NOTE:</b> </h4>
        <?php foreach ($events['noteList'] as $nota): ?>
          <h5><?= $nota ?></h5>
        <?php endforeach; ?>
      </div>
    </div>
    <?php if (!empty($events['compresenzeList'])): ?>
      <div class="col-sm-12" >
        <div class="box-info" style="margin-top:20px;" >
          <h5> <b>COMPRESENZE:</b> </h5>
          <?php foreach ($events['compresenzeList'] as $compresenza): ?>
            <?= $compresenza ?><br />
          <?php endforeach; ?>
        </div>
      </div>
    <?php endif; ?>
  </div>
  <div style="page-break-before: always;"></div>
  <div class="separator row no-print"></div>
<?php endforeach; ?>
</div>
