<div class="small-box <?= $boxClass ?>">
  <div class="inner">
    <h3><?= $tot['ordini'] ?></h3>
    <p><?= $label ?></p>
  </div>
  <div class="icon">
    <i class="ion ion-clipboard"></i>
  </div>
  <a href="<?=$this->Url->build('/aziende/orders/index/all') ?>" class="small-box-footer"><b>Gestione Ordini</b> <i class="fa fa-arrow-circle-right"></i></a>
</div>