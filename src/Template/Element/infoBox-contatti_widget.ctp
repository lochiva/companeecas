
<div class="small-box <?= $boxClass ?>">
    <div class="inner">
      <h3><?= $tot['contatti'] ?></h3>
      <p><?= $label ?></p>
    </div>
    <div class="icon">
      <i class="ion ion-android-contacts"></i>
    </div>
    <a href="<?=$this->Url->build('/aziende/contatti/index/all') ?>" class="small-box-footer"><b>Gestione Contatti</b> <i class="fa fa-arrow-circle-right"></i></a>
</div>