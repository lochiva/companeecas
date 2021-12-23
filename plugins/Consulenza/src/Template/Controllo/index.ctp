<?php
use Cake\Routing\Router;
use Cake\Core\Configure;
?>
<meta http-equiv="refresh" content="300">
<section class="content-header">
  <h1>
    Controllo
    <small>Attività assegnate agli operatori.</small>
  </h1>
  <ol class="breadcrumb">
    <li><a><i class="fa fa-tasks"></i> Controllo</a></li>
  </ol>
  <h5>La pagina si aggiornerà automaticamente ogni 5 minuti (<a href="<?php echo Router::url('/consulenza/controllo');?>">aggiorna ora</a>). L'asterisco vicino all'orario di fine indica il giorno seguente.</h5>
</section>

<section class="content">

<!-- Small boxes (Stat box) -->
  <div class="row">

    <?php foreach($user_tasks as $user_task) {?> 
    <div class="col-md-4">
      <div class="box box-default task-consulenza">
            <div class="box-header">
              <i class="fa fa-user"></i>
                <h3 class="box-title"><?php echo '<b>'.$user_task->cognome . '</b> ' . $user_task->nome ;  ?></h3>
            </div><!-- /.box-header -->

            <div class="box-body">
              <ul class="todo-list">
                
                <?php foreach($user_task['tasks'] as $single_task){ ?>
                    <li class="<?php if($single_task->completed=='1') echo "done" ?>" style="border-color:#<?php echo $single_task->borderColor; ?>;background-color:<?php echo $single_task->backgroundColor; ?>">
                      <span class="handle">
                        <i class="ion ion-clipboard"></i>
                      </span>
                      <?php $start_day = date('d', $single_task->start->toUnixString()); ?>
                      <?php $end_day = date('d', $single_task->end->toUnixString()); ?>
                      <?php if($end_day!=$start_day) $dayafter=' *'; else $dayafter=''; ?>
                       <small class=" pull-right label label-info"><i class="fa fa-clock-o"></i>&nbsp; <?php echo date('H:i', $single_task->start->toUnixString()); ?> - <?php echo date('H:i', $single_task->end->toUnixString()) . $dayafter; ?></small>
                      <span class=""><b><?php echo strtoupper($single_task->azienda) ?></b></span><br/>
                      <span class="note"><?php echo $single_task->title ?></span>
                      <?php if($single_task->note!=''){ ?>
                      <br/>
                        <span><b>NOTE</b>: <?php echo $single_task->note ?></span>
                      <?php } ?>
                    </li>
                <?php } ?>

                  </ul>
            </div>

<?php /*            
            <div class="box-body">
              <ul class="todo-list">
                    <li class="done green">
                      <!-- drag handle -->
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <!-- todo text -->
                      <span class="text">Task 1</span>
                      <!-- Emphasis label -->
                      <small class="pull-right label label-info"><i class="fa fa-clock-o"></i> 2 mins</small>
                    </li>
                    <li class="yellow">
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <span class="text">Task 2</span>
                      <small class=" pull-right label label-info"><i class="fa fa-clock-o"></i> 4 hours</small>
                    </li>
                    <li>
                      <span class="handle">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                      <span class="text">Task 3</span>
                      <small class=" pull-right label label-info"><i class="fa fa-clock-o"></i> 4 hours</small>
                    </li>
                  </ul>
            </div>
*/ ?>

        </div>

    </div>
    <?php } ?>

    
  </div>
</section>