<!--
  <?php
/**
* Companee :    UsersList widget (https://www.companee.it)
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

  ?>
-->  
<div class="box <?= $boxClass ?>">
  <div class="box-header with-border">
    <i class="ion ion-log-in"></i>
    <h3 class="box-title"><?= $label ?></h3>

    <div class="box-tools pull-right">
      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
      </button>
      <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i>
      </button>
    </div>
  </div>
  <!-- /.box-header -->
  <div class="box-body no-padding">
    <ul class="users-list clearfix users-list-3-col">
      <?php foreach ($accessi as $value): ?>
        <li>
          <?= $this->Utils->userImage($value['id_user'],['class'=>'user-image img-circle','style'=>'width:128px;']) ?>
          <a class="users-list-name" href="<?=$this->Url->build('/registration/users/view/'.$value['id_user']) ?>"><?= h($value['user']) ?></a>
          <span class="users-list-date"><?= h($value['date']) ?></span>
        </li>
      <?php endforeach; ?>
    </ul>
    <!-- /.users-list -->
  </div>
  <!-- /.box-body -->

  <!-- /.box-footer -->
</div>