
<section class="content-header">
  <h1>
    Notifiche
    <small>Cronologia di tutte le notifiche</small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="#"><i class="fa fa-bell-o"></i> Notifiche</a></li>
  </ol>
</section>

<section class="content">
<!-- Small boxes (Stat box) -->
  <div class="box notifications-page">
    <div class="box-header with-border">
    	<i class="fa fa-bell-o"></i>
      <h3 class="box-title">Le tue notifiche</h3>
    </div><!-- /.box-header -->
    <div class="box-body">
    	<ul>
    		<?php foreach($notice as $notifica){ ?>
    		<li>
    			<p class="pull-right txt-gray"><small><b><?php echo $notifica->dateWrited->i18nFormat('dd/MM/yyyy'); ?></b></small></p>
    			<p>
    				<?php echo $notifica->message; ?>
    			</p>
				
				<span class=" pull-right badge bg-green"><?php if(isset($notifica->dateReaded) && $notifica->dateReaded!=''){ ?> Letta <?php } ?></span>
				

				<small  class="txt-gray">Inviata a <b><?php echo $notifica['users_dest']->nome . " " .$notifica['users_dest']->cognome; ?></b> da <b><?php echo $notifica['users_source']->nome . " " .$notifica['users_source']->cognome; ?></b></small>
    		</li>
    		<?php } ?>
    	</ul>	
    </div>
  </div>
</section>


