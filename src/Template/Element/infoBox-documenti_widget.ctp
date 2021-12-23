<div class="small-box <?= $boxClass ?>">
  <div class="inner">
    <h3><?= $tot['documenti'] ?></h3>
    <p><?= $label ?></p>
  </div>
  <div class="icon">
    <i class="ion ion-folder"></i>
  </div>
  <a href="<?=$this->Url->build('/document') ?>" class="small-box-footer"><b>Gestione Documenti</b> <i class="fa fa-arrow-circle-right"></i></a>
</div>