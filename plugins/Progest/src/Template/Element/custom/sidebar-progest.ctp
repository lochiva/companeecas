<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

$user = $this->request->session()->read('Auth.User');
?>
<!-- Inner sidebar -->
<section class="sidebar">
  <!-- user panel (Optional) -->
  <div class="user-panel">
    <div class="pull-left image">
      <?= $this->Utils->userImage($user['id'],'img-circle') ?>
    </div>
    <div class="pull-left info">
      <p><?= $user['username'] ?></p>
      <a href="<?=  Router::url('/registration/users/view/' . $user['id']);?>"><i class="fa fa-user text-success"></i>profilo</a>


    </div>
  </div><!-- /.user-panel -->

  <!-- Search Form (Optional)
  <form action="#" method="get" class="sidebar-form">
    <div class="input-group">
      <input type="text" name="q" class="form-control" placeholder="Search...">
      <span class="input-group-btn">
        <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
      </span>
    </div>
  </form> /.sidebar-form -->

  <!-- Sidebar Menu direttore-->
  <ul class="sidebar-menu">

    <li class="<?= $this->Utils->checkActiveMenu(['plugin' => 'Progest', 'controller' => 'Home' ]) ?>">
      <a href="<?= Router::url('/');?>"><i class="fa fa-home"></i> <span>Home</span></a>
    </li>

    <li class="<?= $this->Utils->checkActiveMenu(['plugin' => 'Calendar']) ?>">
      <a href="<?= Router::url('/calendar');?>"><i class="fa fa-calendar"></i> <span>Calendario</span></a>
    </li>
    <li class="<?= $this->Utils->checkActiveMenu(['plugin' => 'Progest', 'controller' => 'People']) ?>">
      <a href="<?= Router::url('/progest/people');?>"><i class="fa fa-user-circle-o"></i> <span>Persone</span></a>
    </li>
    <li class="<?= $this->Utils->checkActiveMenu(['plugin' => 'Progest', 'controller' => 'Report']) ?>">
      <a href="<?= Router::url('/progest/report');?>"><i class="fa fa-line-chart"></i> <span>Report</span></a>
    </li>
    <li class="<?= $this->Utils->checkActiveMenu(['plugin' => 'Aziende','controller'=>'Orders','action'=>'index']) ?>">
      <a href="<?=Router::url('/aziende/orders/index/all');?>"><i class="glyphicon glyphicon-list-alt"></i> <span>Buoni d'ordine</span></a></li>
    <li class="treeview <?= $this->Utils->checkActiveMenu(['plugin' => 'Aziende', 'controller !='=>'Orders']) ?>">
      <a href="#"><i class="fa fa-gears"></i> <span>Gestione</span> <i class="fa fa-angle-left pull-right"></i></a>
      <ul class="treeview-menu <?= $this->Utils->checkActiveMenu(['plugin' => 'Aziende'],true) ?>">
          <li class="<?= $this->Utils->checkActiveMenu(['plugin' => 'Aziende','controller'=>'Home','action'=>'index']) ?>">
            <a href="<?=Router::url('/aziende');?>"><i class="fa fa-industry text-aqua"></i><span>Committenti</span></a></li>
          <li class="<?= $this->Utils->checkActiveMenu(['plugin' => 'Aziende','controller'=>'Contatti','action'=>'index']) ?>">
            <a href="<?=Router::url('/aziende/contatti/index/all');?>"><i class="fa fa-users text-blue"></i><span>Contatti</span></a></li>
      </ul>
    </li>
    <!--<li class="<?= $this->Utils->checkActiveMenu(['plugin' => 'Progest', 'controller' => 'programmazione']) ?>">
      <a href=""><i class="glyphicon glyphicon-tasks"></i> <span>Programmazione</span></a>
    </li>-->
    <?php if($user['role'] == 'admin'): ?>
      <li><a target="_blank" href="<?=Router::url('/admin');?>"><i class="fa fa-lock"></i> <span>Admin</span></a></li>
    <?php endif ?>


  </ul><!-- /.sidebar-menu direttore-->

</section><!-- /.sidebar -->
