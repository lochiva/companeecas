<!-- small box -->
<div class="small-box <?= $boxClass ?>">
  <div class="inner">
    <h3><?= $tot['aziende'] ?></h3>
    <p><?= $label ?></p>
  </div>
  <div class="icon icon-fa">
    <i class="<?= $icon ?>"></i>
  </div>
  <a href="<?=$this->Url->build($url) ?>" class="small-box-footer"><b><?= $label_link ?></b> <i class="fa fa-arrow-circle-right"></i></a>
</div>