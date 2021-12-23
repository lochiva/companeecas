<?php
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Utility\Inflector;

//Carico i dati dell'utente
$user = $this->request->session()->read('Auth.User');

//Carico i plugin abilitati dal file di localconfig
$pluginUsed = $authEmail = Configure::read('localconfig.PluginUsed');

//Carico la lista di plugin caricati da cake
$pluginList = Plugin::loaded();

$adminControllers = Configure::read('localconfig.AdminControllers');

?>
<ul class="nav nav-sidebar">
    <li class="logged">
        <i class="glyphicon glyphicon-user"></i>
        Benvenuto <?=$user['username']?>
    </li>
</ul>

<ul class="nav nav-sidebar">

    <!--HOME PAGE ADMIN-->
    <?php
        if($this->request->params['controller'] == "Home"){
            $active = "active";
        }else{
            $active = "";
        }
    ?>
    <li class="<?=$active?>">
        <a href="<?=Router::url('/admin');?>"><i class="glyphicon glyphicon-home"></i> Admin Home</a>
    </li>
    <!--FINE HOME PAGE ADMIN-->

    <!--ACCESSI-->
    <?php if($user['level'] >= 900){ ?>
        <?php
            $controller = strtolower($this->request->params['controller']);
            if($controller == "users" && $this->plugin == ""){
                $active = "active";
            }else{
                $active = "";
            }
        ?>
        <li class="<?=$active?>">
            <a href="<?=Router::url('/admin/users');?>"><i class="glyphicon glyphicon-log-in"></i> Accessi</a>
        </li>
    <?php } ?>
    <!--FINE ACCESSI-->

    <!--CONFIGURATIONS-->
    <?php if($user['level'] >= 500){ ?>
        <?php
            $controller = strtolower($this->request->params['controller']);
            if($controller == "configurations"){
                $active = "active";
            }else{
                $active = "";
            }
        ?>
        <li class="<?=$active?>">
            <a href="<?=Router::url('/admin/configurations');?>"><i class="glyphicon glyphicon-cog"></i> Configurazioni</a>
        </li>
    <?php } ?>
    <!--FINE CONFIGURATIONS-->

    <!--ASPETTO-->
    <?php if($user['level'] >= 500){ ?>
        <?php
            $controller = strtolower($this->request->params['controller']);
            if($controller == "appearance" && $this->plugin == ""){
                $active = "active";
            }else{
                $active = "";
            }
        ?>
        <li class="<?=$active?>">
            <a href="<?=Router::url('/admin/appearance');?>"><i class="glyphicon glyphicon-eye-open"></i> Aspetto</a>
        </li>
    <?php } ?>
    <!--FINE ASPETTO-->

    <!--
    <li><a href="#">Analytics</a></li>
    <li><a href="#">Export</a></li>
    -->
</ul>

<!-- BLOCCO DEI PLUGIN -->

<?php //echo "<pre>"; print_r($this->plugin); echo "</pre>"; ?>
<ul class="nav nav-sidebar">
    <?php
    if(is_array($pluginList) && !empty($pluginList)){

        foreach ($pluginList as $key => $plugin) {

            if(isset($pluginUsed[$plugin])){

              if(strtolower($this->plugin) == strtolower($plugin)){
                  $active = "active";
                  $collapse = 'in';
              }else{
                  $active = "";
                  $collapse = '';
              }


              $class = $pluginUsed[$plugin]['icon'];
              $label = $pluginUsed[$plugin]['label'];

              if(!empty($pluginUsed[$plugin]['controllers'])){
                ?>
                <li  data-toggle="collapse" data-target="#<?=$plugin?>" class="collapsed <?=$active?>">
                          <a href="#"><i class="<?=$class?>"></i> <?=$label?><span class="arrow"></span></a>
                          </li>
                          <ul class="sub-menu collapse <?= $collapse ?>" id="<?=$plugin?>">
                <?php
                  foreach ($pluginUsed[$plugin]['controllers'] as $controller => $label) {
                      if(strtolower($this->plugin) == strtolower($plugin) && Inflector::underscore($this->request->params['controller']) == strtolower($controller)){
                          $active = "active";
                      }else{
                          $active = "";
                      }

                      echo '<li class="'.$active.'" ><a href="'.Router::url('/admin/'.strtolower($plugin).'/'.strtolower($controller)).'"><i class="glyphicon glyphicon-menu-right"></i> '.$label.'</a></li>';
                  }
                  echo "</ul>";
              }else{

                ?>

                <li class="<?=$active?>">
                    <a href="<?=Router::url('/admin/' . strtolower($plugin) . '/home/index');?>">
                        <i class="<?=$class?>"></i>
                        <?=$label?>
                    </a>
                </li>

                <?php
              }

            }

        }
    }
    ?>
    <?php
    if(is_array($adminControllers) && !empty($adminControllers)){

        foreach ($adminControllers as $key => $controller) {



                $class = $adminControllers[$key]['icon'];
                $label = $adminControllers[$key]['label'];

                if(strtolower($this->request->controller) == strtolower($key)){
                    $active = "active";
                }else{
                    $active = "";
                }

                ?>

                <li class="<?=$active?>">
                    <a href="<?=Router::url('/admin/' . strtolower($key) . '/index');?>">
                        <i class="<?=$class?>"></i>
                        <?=$label?>
                    </a>
                </li>

                <?php



        }
    }
    ?>


</ul>
<!--
<ul class="nav nav-sidebar">
    <li><a href="">Nav item again</a></li>
    <li><a href="">One more nav</a></li>
    <li><a href="">Another nav item</a></li>
</ul>
-->
