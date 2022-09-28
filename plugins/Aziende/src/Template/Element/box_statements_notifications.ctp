<?php
use Cake\Routing\Router;
?>
<div class="box box-warning collapsed-box box-notifications">
    <div class="box-header">
        <h3 class="box-title"><span class="badge bg-aqua notifications-count"><?= $notificationsCount ?></span> Notifiche Rendiconti </h3>
        <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-plus"></i></button>
        </div>
    </div>
    <div style="display: none;" class="box-body">
        <?php if ($notificationsCount > 0) : ?>
            Ci sono <?=$notificationsCount?> notifiche
            <a href="<?= Router::url(['plugin' => 'Aziende', 'controller' => 'StatementsNotifications', 'action' => 'index'] ); ?>" class="btn btn-primary pull-right">
                Vai alla gestione delle notifiche
            </a>
        <?php  else : ?>
            Non ci sono notifiche.
        <?php endif ?>
    </div>
</div>    

