<div class="container-fluid persone">
<?php foreach ($datiStampa as $persona => $events): ?>
  <h4 class="box-title text-uppercase no-bold">
    Visite Programmate al signor/a <b><?= h($persona) ?></b>
  </h4>
  <h4 class="box-title no-bold">
    Settimana dal <b><?= $dal ?></b> al <b><?= $al ?></b>
  </h4>
  <div class="box-body no-padding" style="margin-top:40px;">
    <table class="table table-striped table-persone">
      <tbody>
        <?php foreach ($events as $event): ?>
          <tr>
            <td width="33%" class="text-uppercase"><?= h($event['operatore']) ?></td>
            <td width="33%"><?= h($event['interval']) ?></td>
            <td style="font-size:14px;" width="33%">&nbsp; </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div style="page-break-before: always;"></div>
  <div class="separator row no-print"></div>
<?php endforeach ?>

</div>
