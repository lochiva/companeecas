<?php
use Cake\Routing\Router;
?>
<div class="box box-warning collapsed-box box-notifications">
    <div class="box-header">
        <h3 class="box-title"><span class="badge bg-aqua notifications-count"><?= $notificationsCount ?></span> Notifiche <?= $enteType == 2 ? 'Emergenza Ucraina' : '' ?></h3>
        <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div style="display: none;" class="box-body">
        <?php if (count($notifications) > 0) { ?>
            <?php foreach ($notifications as $n) { ?>
            <p><?= $n['message'] ?></p>
            <?php } ?>
            <a href="<?= Router::url('/aziende/guests/notifications/'.$enteType); ?>" class="btn btn-primary pull-right">
                Vai alla gestione delle notifiche
            </a>
        <?php } else { ?>
            Non ci sono notifiche.
        <?php } ?>
    </div>
</div>    