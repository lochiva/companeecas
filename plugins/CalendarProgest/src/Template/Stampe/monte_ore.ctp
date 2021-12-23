<style>
body{font-size: 12px;}
</style>
<div class="container-fluid monte-ore">
  <h4 class="box-title text-uppercase no-bold">
    Numero interventi e numero ore effettuate dagli operatori
  </h4>
  <h4 class="box-title no-bold">
    Settimana dal <b><?= $dal ?></b> al <b><?= $al ?></b>
  </h4>
  <div class="box-body no-padding" style="margin-top:50px;">
    <table class="table table-striped table-bordered table-moteOre ">
      <thead>
        <tr>
          <th class="text-center" width="30%">Nominativo</th>
          <th class="text-center" width="17.5%">Interventi</th>
          <th class="text-center" width="17.5%">Ore Utenti</th>
          <th class="text-center" width="17.5%">Ore Altro</th>
          <th class="text-center" width="17.5%">Totale Ore</th>
        </tr>
      </thead>
      <tbody>
      <?php foreach ($datiStampa as $operatore => $dati): ?>
      <tr>
        <td class="text-uppercase"><p class="pull-left text-right" style="margin-right:8px"><?= h($counter++.') ') ?></p><?= h($operatore) ?></td>
        <td class="text-right">N° <p class="pull-right"><?= $dati['interventi'] ?></p></td>
        <td class="text-right"><?= $dati['ore_utenti'] ?></td>
        <td class="text-right"><?= $dati['ore_altro'] ?></td>
        <td class="text-right"><?= $dati['tot_ore'] ?></td>
      </tr>
      <?php endforeach; ?>
      <tr>
        <th class="text-right"><b>TOTALE:</b> </th>
        <th class="text-right"><b><?= $totale['interventi'] ?></b></th>
        <th class="text-right"><b><?= $totale['ore_utenti'] ?></b></th>
        <th class="text-right"><b><?= $totale['ore_altro'] ?></b></th>
        <th class="text-right"><b><?= $totale['tot_ore'] ?></b></th>
      </tr>
      </tbody>
    </table>
  </div>
  <div style="page-break-before: always;"></div>
</div>
