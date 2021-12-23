<ul class="timeline timeline-home">

    <!-- timeline time label -->
    <li class="time-label">
        <span class="bg-maroon">
            <b><?= $label ?></b>
        </span>
    </li>
    <!-- /.timeline-label -->

    <!-- timeline item -->
    <?php foreach ($movimenti as $value): ?>
      <li>
          <?= $this->Utils->userImage($value['id_user'],'user-image img-timeline') ?>
          <div class="timeline-item">
              <span class="time"><i class="fa fa-clock-o"></i><?= $value['label']['data'] ?></span>
              <h3 class="timeline-header">
                <a href="<?=$this->Url->build('/registration/users/view/'.$value['id_user']) ?>">
                  <?= h($value['label']['user']) ?> </a>
                <?= h($value['label']['action']) ?></h3>
              <!--<div class="timeline-body">
                  <b>Documento:</b> Nome documento
              </div>-->
              <?php if (!empty($value['label']['link'])): ?>
                <div class="timeline-footer">
                    <a href="<?= h($value['label']['link']) ?>" class="btn btn-success btn-xs">visualizza il record</a>
                </div>
              <?php endif; ?>
          </div>
      </li>
    <?php endforeach; ?>

    <li>
      <i class="fa fa-clock-o bg-gray"></i>
    </li>
</ul>