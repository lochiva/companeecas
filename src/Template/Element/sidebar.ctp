<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

// ##########################################################################################################################
// CONFIGURAZIONE DEL MENU DA MOSTRARE
// ##########################################################################################################################

$menu = [
  [
    'name' => 'Home',
    'plugin' => [],
    'controller' => ['Home'],
    'action' => [],
    'levels' => ['admin', 'area_iv', 'ragioneria', 'ente_ospiti', 'ente_contabile'],
    'url' => Router::url('/'),
    'target' => '',
    'icon-class' => 'fa fa-home',
    'children' => []
  ],
  [
    'name' => 'Enti',
    'plugin' => ['Aziende'],
    'controller' => ['Home'],
    'action' => ['index'],
    'levels' => ['admin', 'area_iv', 'ragioneria'],
    'url' => Router::url('/aziende'),
    'target' => '',
    'icon-class' => 'fa fa-building',
    'children' => []
  ],
  [
    'name' => 'Strutture',
    'plugin' => ['Aziende'],
    'controller' => ['Sedi'],
    'action' => ['index'],
    'levels' => ['ente_ospiti', 'ente_contabile'],
    'url' => Router::url('/aziende/sedi/index/').$this->Utils->getEnteIDByUserLoggedIn(),
    'target' => '',
    'icon-class' => 'fa fa-home',
    'children' => []
  ],
  [
    'name' => 'Report',
    'plugin' => ['Aziende'],
    'controller' => ['Reports'],
    'action' => ['index'],
    'levels' => ['admin', 'area_iv', 'ente_ospiti'],
    'url' => Router::url('/aziende/reports/index/'),
    'target' => '',
    'icon-class' => 'fa fa-file',
    'children' => []
  ],
  [
    'name' => 'Rendiconti',
    'plugin' => ['Aziende'],
    'controller' => ['Rendiconti'],
    'action' => ['index'],
    'levels' => ['admin', 'area_iv', 'ragioneria', 'ente_contabile'],
    'url' => Router::url('/aziende/statements/index'),
    'target' => '',
    'icon-class' => 'fa fa-money',
    'children' => []
  ],
  /*[
    'name' => 'Gestione Scheda',
    'plugin' => ['Diary'],
    'controller' => ['Diary'],
    'action' => ['index'],
    'levels' => ['admin'],
    'url' => Router::url('/surveys/surveys/index'),
    'target' => '',
    'icon-class' => 'fa fa-file-text-o',
    'children' => []
  ],
  [
    'name' => 'Segnalazioni',
    'plugin' => ['Reports'],
    'controller' => ['Reports'],
    'action' => ['index'],
    'levels' => ['admin', 'centro', 'nodo'],
    'url' => Router::url('/reports/reports/index'),
    'target' => '',
    'icon-class' => 'fa fa-list-alt',
    'children' => []
  ],
  [
    'name' => 'Dati aziendali',
    'plugin' => ['Aziende'],
    'controller' => ['Clienti'],
    'action' => ['datiAziendali'],
    'levels' => ['companee_admin'],
    'url' => Router::url('/aziende/clienti/datiAziendali'),
    'target' => '',
    'icon-class' => 'fa fa-cogs',
    'children' => []
  ],
  [
    'name' => 'Calendario',
    'plugin' => ['Calendar'],
    'controller' => ['Home'],
    'action' => [],
    'levels' => ['admin'],
    'url' => Router::url('/calendar'),
    'target' => '',
    'icon-class' => 'fa fa-calendar',
    'children' => []
  ],
  [
    'name' => 'Crm',
    'plugin' => ['Crm'],
    'controller' => ['Home'],
    'action' => [],
    'levels' => ['admin'],
    'url' => '#',
    'target' => '',
    'icon-class' => 'fa fa-handshake-o',
    'children' => [
      [
        'name' => 'Report',
        'plugin' => ['Crm'],
        'controller' => ['Home'],
        'action' => ['index'],
        'levels' => ['admin'],
        'url' => Router::url('/crm'),
        'target' => '',
        'icon-class' => 'fa fa-circle-o text-yellow'
      ],
      [
        'name' => 'Offerte',
        'plugin' => ['Crm'],
        'controller' => ['Offers'],
        'action' => ['index'],
        'levels' => ['admin'],
        'url' => Router::url('/crm/offers'),
        'target' => '',
        'icon-class' => 'fa fa-circle-o text-aqua'
      ]
    ]
  ],
  [
    'name' => 'Gestione',
    'plugin' => ['Aziende'],
    'controller' => ['Home'],
    'action' => [],
    'levels' => ['admin'],
    'url' => '#',
    'target' => '',
    'icon-class' => 'fa fa-gears',
    'children' => [
      [
        'name' => 'Aziende',
        'plugin' => ['Aziende'],
        'controller' => ['Home'],
        'action' => ['index'],
        'levels' => ['admin'],
        'url' => Router::url('/aziende'),
        'target' => '',
        'icon-class' => 'fa fa-industry text-aqua'
      ],
      [
        'name' => 'Contatti',
        'plugin' => ['Aziende'],
        'controller' => ['Contatti'],
        'action' => ['index'],
        'levels' => ['admin'],
        'url' => Router::url('/aziende/contatti/index/all'),
        'target' => '',
        'icon-class' => 'fa fa-users text-blue'
      ],
      [
        'name' => 'Ordini',
        'plugin' => ['Aziende'],
        'controller' => ['Orders'],
        'action' => ['index'],
        'levels' => ['admin'],
        'url' => Router::url('/aziende/orders/index/all'),
        'target' => '',
        'icon-class' => 'glyphicon glyphicon-list-alt text-yellow'
      ],
      [
        'name' => 'Fatture Passive',
        'plugin' => ['Aziende'],
        'controller' => ['Fornitori'],
        'action' => ['fatture'],
        'levels' => ['admin'],
        'url' => Router::url('/aziende/fornitori/fatture/all'),
        'target' => '',
        'icon-class' => 'glyphicon glyphicon-inbox text-red'
      ],
      [
        'name' => 'Fatture Attive',
        'plugin' => ['Aziende'],
        'controller' => ['Clienti'],
        'action' => ['fatture'],
        'levels' => ['admin'],
        'url' => Router::url('/aziende/clienti/fatture/all'),
        'target' => '',
        'icon-class' => 'glyphicon glyphicon-inbox text-green'
      ]
    ]
  ],
  [
    'name' => 'Documentazione',
    'plugin' => ['Document'],
    'controller' => ['Home'],
    'action' => ['index'],
    'levels' => ['admin'],
    'url' => Router::url('/document/home/index'),
    'target' => '',
    'icon-class' => 'fa fa-folder-open',
    'children' => []
  ],
  [
    'name' => 'Scadenzario',
    'plugin' => ['Scadenzario'],
    'controller' => ['Home'],
    'action' => ['index'],
    'levels' => ['admin'],
    'url' => Router::url('/scadenzario/home/index'),
    'target' => '',
    'icon-class' => 'fa fa-clock-o',
    'children' => []
  ],
  [
    'name' => 'Cespiti',
    'plugin' => ['Cespiti'],
    'controller' => ['Home'],
    'action' => ['index'],
    'levels' => ['admin'],
    'url' => Router::url('/cespiti/home/index'),
    'target' => '',
    'icon-class' => 'glyphicon glyphicon-briefcase',
    'children' => []
  ],
  [
    'name' => 'Importazione dati',
    'plugin' => ['ImportData'],
    'controller' => ['Home'],
    'action' => ['index'],
    'levels' => ['admin'],
    'url' => Router::url('/import-data/home/index'),
    'target' => '',
    'icon-class' => 'glyphicon glyphicon-import',
    'children' => []
  ],
  [
    'name' => 'Mailing',
    'plugin' => ['ReminderManager'],
    'controller' => ['Submission'],
    'action' => ['index'],
    'levels' => ['admin'],
    'url' => Router::url('/reminder_manager/submission'),
    'target' => '',
    'icon-class' => 'fa fa-envelope',
    'children' => []
  ], 
  [
    'name' => 'Leads',
    'plugin' => ['Leads'],
    'controller' => ['Ensemble'],
    'action' => ['manage'],
    'levels' => ['admin'],
    'url' => '#',
    'target' => '',
    'icon-class' => 'fa fa-question-circle',
    'children' => [
      [
        'name' => 'Ensemble',
        'plugin' => ['Leads'],
        'controller' => ['Ensemble'],
        'action' => ['manage'],
        'levels' => ['admin'],
        'url' => Router::url('/admin/leads/ensemble/manage'),
        'target' => '',
        'icon-class' => 'fa fa-list-alt text-red'
      ],
      [
        'name' => 'Interviste',
        'plugin' => ['Leads'],
        'controller' => ['Interview'],
        'action' => ['home'],
        'levels' => ['admin'],
        'url' => Router::url('/admin/leads/interview/home'),
        'target' => '',
        'icon-class' => 'fa fa-microphone text-blue'
      ],
    ]
  ], 
  [
    'name' => 'Questionari',
    'plugin' => ['Surveys'],
    'controller' => ['Surveys'],
    'action' => ['index'],
    'levels' => ['admin'],
    'url' => '#',
    'target' => '',
    'icon-class' => 'fa fa-list-alt',
    'children' => [
      [
        'name' => 'Capitoli',
        'plugin' => ['Surveys'],
        'controller' => ['Surveys'],
        'action' => ['chapters'],
        'levels' => ['admin'],
        'url' => Router::url('/surveys/surveys/chapters'),
        'target' => '',
        'icon-class' => 'fa fa-align-left',
        'children' => []
      ],
      [
        'name' => 'Questionari',
        'plugin' => ['Surveys'],
        'controller' => ['Surveys'],
        'action' => ['index'],
        'levels' => ['admin'],
        'url' => Router::url('/surveys/surveys/index'),
        'target' => '',
        'icon-class' => 'fa fa-list-alt',
        'children' => []
      ]
    ]
  ],
  [
    'name' => 'Interviste',
    'plugin' => ['Surveys'],
    'controller' => ['Surveys'],
    'action' => ['managingEntities'],
    'levels' => ['user'],
    'url' => Router::url('/surveys/surveys/managingEntities'),
    'target' => '',
    'icon-class' => 'fa fa-list-alt',
    'children' => []
  ],*/
  [
    'name' => 'Admin',
    'plugin' => [],
    'controller' => [],
    'action' => [],
    'levels' => ['admin'],
    'url' => Router::url('/admin'),
    'target' => '_blank',
    'icon-class' => 'fa fa-lock',
    'children' => []
  ],

];

// ##########################################################################################################################
// FINE CONFIGURAZIONE
// ##########################################################################################################################

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

    <!-- NUOVA GESTIONE DEL MENU -->

    <?php foreach ($menu as $key => $item) { ?>

      <?php if(in_array($user['role'], $item['levels'])){ ?>

        <?php if(!(empty($item['plugin']) && count($item['controller']) == 1 && $item['controller'][0] == 'Home')) {
          if(($user['role'] == 'ente_ospiti' || $user['role'] == 'ente_contabile') && !$this->Utils->isValidEnte($user['id'])) {
            continue;
          }
        } ?>
        <?php
          $ck = [];
          if(!empty($item['plugin'])){
            $ck['plugin'] = $item['plugin'];
          }
          if(!empty($item['controller'])){
            $ck['controller'] = $item['controller'];
          }
          if(!empty($item['action'])){
            $ck['action'] = $item['action'];
          }

          if(!empty($ck)){
            $selected = $this->Utils->newCheckActiveMenu($ck);
          }else{
            $selected = "";
          }

        ?>

        <li class="<?= $selected ?>">

          <?php
            if(!empty($item['target'])){
              $target = $item['target'];
            }else{
              $target = '';
            }
          ?>

          <a href="<?= $item['url'] ?>" target="<?=$target?>">

            <i class="<?= $item['icon-class'] ?>"></i>
            <span><?= $item['name'] ?></span>

            <?php if(!empty($item['children'])){ ?>
              <i class="fa fa-angle-left pull-right"></i>
            <?php } ?>

          </a>

          <?php if(!empty($item['children'])){ ?>

            <?php
              $ck = [];
              if(!empty($item['plugin'])){
                $ck['plugin'] = $item['plugin'];
              }
              if(!empty($item['controller'])){
                $ck['controller'] = $item['controller'];
              }
              if(!empty($item['action'])){
                $ck['action'] = $item['action'];
              }

              if(!empty($ck)){
                $selected = $this->Utils->newCheckActiveMenu($ck);
              }else{
                $selected = "";
              }

            ?>

            <ul class="treeview-menu <?= $selected ?>">

              <?php foreach ($item['children'] as $key => $subItem) { ?>

                <li class="<?= $this->Utils->newCheckActiveMenu(['plugin' => $subItem['plugin'],'controller'=>$subItem['controller'],'action'=>$subItem['action']]) ?>">

                  <?php
                    if(!empty($item['target'])){
                      $target = $item['target'];
                    }else{
                      $target = '';
                    }
                  ?>

                  <a href="<?=$subItem['url']?>" target="<?=$target?>">

                    <i class="<?=$subItem['icon-class']?>"></i>
                    <span><?=$subItem['name']?></span>

                  </a>

                </li>

              <?php } ?>

            </ul>

          <?php } ?>

        </li>

      <?php } ?>

    <?php } ?>

    <!-- FINE NUOVA GESTIONE DEL MENU -->

  </ul><!-- /.sidebar-menu direttore-->

</section><!-- /.sidebar -->
