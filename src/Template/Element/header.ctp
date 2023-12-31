<?php
/** 
* Companee :  header   (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
use Cake\Routing\Router;
use Cake\Core\Configure;

$user = $this->request->session()->read('Auth.User');

$roleLabels = [
  'admin' => 'Admin',
  'area_iv' => 'Area IV',
  'ragioneria' => 'Ragioneria',
  'questura' => 'Questura',
  'ente_ospiti' => 'Ente ospiti',
  'ente_contabile' => 'Ente contabile'
];
?>
<script>

  var pathServer = '<?=Router::url('/')?>'; 

  $(document).ready(function(){

    $('#close-sidebar').click(function(){
      if($('body').hasClass('sidebar-collapse')){
        document.cookie= "sidebar-closed;path=/";
      } else {
        document.cookie= "sidebar-closed;path=/;expires=Thu, 01 Jan 1970 00:00:01 GMT;";
      }

    });

  });

</script>

<?php // echo $this->Element('Consulenza.notice_js'); ?>

	<a href="<?= Router::url('/');?>" class="logo">
    	<!-- LOGO -->
      <?= $this->Html->Image('/img/local/logo_xs.png',['class'=>'logo-mini text-center']) ?>	
			<?= $this->Html->Image('/img/local/logo_header.png',['class'=>'logo-lg'])?>
  </a>
  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button" id="close-sidebar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">

        <!-- Notifications: style can be found in dropdown.less -->
        <!--
        <li class="dropdown notifications-menu">
          <a href="<?= Router::url('/registration/users/view/'.$user['id'].'/notifications');?>">
            <i class="fa fa-bell-o"></i>
            <span class="label label-info notify_count_label"></span>
          </a>
          <ul class="dropdown-menu">
            <li class="header">Hai <span class="notify_count"></span> notifiche da leggere</li>
            <li>
              <ul class="menu" id="notify_container">
              </ul>
            </li>
            <li class="footer"><a href="<?= Router::url('/registration/users/view/'.$user['id'].'/notifications');?>">Tutte le tue notifiche</a></li>
          </ul>
        </li>
        -->

        <?php 
        if ($user['role'] == 'admin' || $user['role'] == 'area_iv') {
          echo $this->element('Aziende.guests_notify'); 
        }
        ?>

        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <?= $this->Utils->userImage($user['id'],'user-image') ?>
            <span class="hidden-xs"><?= $user['username'];?></span>
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              <?= $this->Utils->userImage($user['id'],'img-circle') ?>
              <p>
                <?php echo $user['cognome'] . " " . $user['nome'];?>
                <small><?php echo $roleLabels[$user['role']];?></small>
              </p>
            </li>
            <!-- Menu Body -->
            <!--
            <li class="user-body">
              <div class="col-xs-4 text-center">
                <a href="#">Followers</a>
              </div>
              <div class="col-xs-4 text-center">
                <a href="#">Sales</a>
              </div>
              <div class="col-xs-4 text-center">
                <a href="#">Friends</a>
              </div>
            </li>
            -->
            <!-- Menu Footer-->
            <li class="user-footer">
              <div class="pull-left">
                <a href="<?php echo Router::url('/registration/users/view/' . $user['id']);?>" class="btn btn-default btn-flat">Profilo</a>
              </div>
              <div class="pull-right">
                <a href="<?php echo Router::url('/registration/home/logout');?>" class="btn btn-default btn-flat">Esci</a>
              </div>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
