<?php
use Cake\Routing\Router;
use Cake\Core\Configure;
 
echo $this->Element('include');

//echo "<pre>"; print_r($dcn); echo "</pre>";
//echo "<pre>"; print_r($drn); echo "</pre>";
//echo "<pre>"; print_r($documents); echo "</pre>";

?>

<?= $this->element('dashboard') ?>
<!-- Main content -->
<section class="content">
<!-- Small boxes (Stat box) -->
  <div class="row">
    
    <div class="col-md-10">
      <div class="info-box bg-teal">
        <span class="info-box-icon"><i class="fa fa-filter"></i></span>
        <div class="info-box-content">
          <span class="info-box-text"><?= __('Filtri') ?></span>
          <?= $this->Form->create($documents,['method' => 'post']) ?>
          <form role="form">
          	<div class="col-md-5">
          		<div class="form-group">
		            <label>Cliente</label>
		            <?= $this->Form->select('id_client',$clients,['id' => 'id_client', 'class' => 'home form-control select2', 'style' => 'width: 100%', 'empty' => 'Tutti', 'required' => false]) ?>
		        </div>
          	</div>
	        <div class="col-md-5">  	
		        <div class="form-group">
		            <label>Progetto</label>
		            <?= $this->Form->select('id_project',$projects,['id' => 'id_project', 'class' => 'home form-control select2', 'style' => 'width: 100%', 'empty' => 'Tutti', 'required' => false]) ?>
		        </div>
          	</div>
          	<div class="col-md-2">
          	<?= $this->Form->button( __('Cerca')); ?>
          	</div>
          </form>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->
    </div><!-- /.col -->
    <div class="col-md-2">
    	<div class="info-box bg-teal">
        	<span class="info-box-icon"><i class="fa fa-filter"></i></span>
        	<div class="info-box-content">
        		<a class="btn btn-primary" href="<?=Router::url('/document/home/add/0')?>" title="Crea nuovo documento">
                    <?php echo $this->Html->image('Document.add.png', ['alt' => 'Nuovo', 'style' => 'height:25px;']); ?>
                </a>
        	</div>
        </div>
    </div>
   
    
    <div class="col-md-12">
    <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Latest Orders</h3>

              <div class="box-tools pull-right">
                <button data-widget="collapse" class="btn btn-box-tool" type="button"><i class="fa fa-minus"></i>
                </button>
                <button data-widget="remove" class="btn btn-box-tool" type="button"><i class="fa fa-times"></i></button>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body" style="display: block;">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Order ID</th>
                    <th>Item</th>
                    <th>Status</th>
                    <th>Popularity</th>
                  </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td><a href="pages/examples/invoice.html">OR9842</a></td>
                    <td>Call of Duty IV</td>
                    <td><span class="label label-success">Shipped</span></td>
                    <td>
                      <div data-height="20" data-color="#00a65a" class="sparkbar"><canvas style="display: inline-block; width: 34px; height: 20px; vertical-align: top;" width="34" height="20"></canvas></div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">OR1848</a></td>
                    <td>Samsung Smart TV</td>
                    <td><span class="label label-warning">Pending</span></td>
                    <td>
                      <div data-height="20" data-color="#f39c12" class="sparkbar"><canvas style="display: inline-block; width: 34px; height: 20px; vertical-align: top;" width="34" height="20"></canvas></div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                    <td>iPhone 6 Plus</td>
                    <td><span class="label label-danger">Delivered</span></td>
                    <td>
                      <div data-height="20" data-color="#f56954" class="sparkbar"><canvas style="display: inline-block; width: 34px; height: 20px; vertical-align: top;" width="34" height="20"></canvas></div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                    <td>Samsung Smart TV</td>
                    <td><span class="label label-info">Processing</span></td>
                    <td>
                      <div data-height="20" data-color="#00c0ef" class="sparkbar"><canvas style="display: inline-block; width: 34px; height: 20px; vertical-align: top;" width="34" height="20"></canvas></div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">OR1848</a></td>
                    <td>Samsung Smart TV</td>
                    <td><span class="label label-warning">Pending</span></td>
                    <td>
                      <div data-height="20" data-color="#f39c12" class="sparkbar"><canvas style="display: inline-block; width: 34px; height: 20px; vertical-align: top;" width="34" height="20"></canvas></div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">OR7429</a></td>
                    <td>iPhone 6 Plus</td>
                    <td><span class="label label-danger">Delivered</span></td>
                    <td>
                      <div data-height="20" data-color="#f56954" class="sparkbar"><canvas style="display: inline-block; width: 34px; height: 20px; vertical-align: top;" width="34" height="20"></canvas></div>
                    </td>
                  </tr>
                  <tr>
                    <td><a href="pages/examples/invoice.html">OR9842</a></td>
                    <td>Call of Duty IV</td>
                    <td><span class="label label-success">Shipped</span></td>
                    <td>
                      <div data-height="20" data-color="#00a65a" class="sparkbar"><canvas style="display: inline-block; width: 34px; height: 20px; vertical-align: top;" width="34" height="20"></canvas></div>
                    </td>
                  </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.table-responsive -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix" style="display: block;">
              <a class="btn btn-sm btn-info btn-flat pull-left" href="javascript::;">Place New Order</a>
              <a class="btn btn-sm btn-default btn-flat pull-right" href="javascript::;">View All Orders</a>
            </div>
            <!-- /.box-footer -->
          </div>
          </div>
  <?php if($documentsNumber > 0){ ?>
  
  <?php foreach ($documents as $key => $doc) { ?>
    <div class="col-md-12">
    	<div class="box box-info">
    		<div class="box-header with-border">
              <h3 class="box-title"><?=ucfirst($doc->title)?></h3>
              <div class="box-tools pull-right">
            	<a data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></a>
            	<a data-widget="remove" class="btn btn-box-tool" href="<?=Router::url('/document/home/delete/' . $doc->id)?>" title="Elimina documento"><i class="fa fa-times"></i></a>
              </div>
            </div>
            <div class="box-body ">
            	<div class="row">
            		<div class="col-md-3 col-xs-12 col-md-push-9 text-right">
            			<div class="btn-group ">
		                  <a class="btn btn-info" href="<?=Router::url('/document/home/view/' . $doc->id_document)?>" title="Visualizza documento" ><i class="fa fa-eye"></i></button>
		                  <a class="btn btn-info" href="<?=Router::url('/document/home/edit/' . $doc->id)?>" title="Modifica documento" ><i class="fa fa-pencil"></i></a>
		                  <a class="btn btn-info" href="<?=Router::url('/document/home/add/' . $doc->id_document)?>" title="Aggiungi figlio"><i class="fa fa-plus"></i></a>
		                </div>
            		</div>
            		<div class="col-md-9 col-xs-12 col-md-pull-3">
            			<p class="info small col-lg-3 col-md-6"><b>Cliente:</b> <?php if(is_object($doc->client)){ echo $doc->client->name; }else{ echo "-"; } ?></p>
		                <p class="info small col-lg-3 col-md-6"><b>Progetto:</b> <?php if(is_object($doc->project)){ echo $doc->project->name; }else{ echo "-"; } ?></p>
		                <p class="info small col-lg-3 col-md-6">Dipendenze: <?=$dcn[$doc->id]?></p>
		                <p class="info small col-lg-3 col-md-6">Revisioni: <?=$drn[$doc->id]?></p>
		                <p class="info creato small row col-md-12">Data: <?=$doc->created?></p>
            		</div>
            	</div>
            	
            	<div id="accordion" class="box-group">
            		<div class="panel box ">
            			<div class="box-header with-border">
			              <h3 class="box-title"><?=ucfirst($doc->title)?></h3>
			              <div class="box-tools pull-right">
			                <a data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></a>
            				<a data-widget="remove" class="btn btn-box-tool" href="<?=Router::url('/document/home/delete/' . $doc->id)?>" title="Elimina documento"><i class="fa fa-times"></i></a>
			              </div>
			            </div>
			            
            		</div>
            	</div>
            	
            	<div id="accordion" class="box-group">
            		<div class="panel box ">
            			<div class="box-header with-border">
			              <h3 class="box-title">Doc 2 figlio di titolo 1</h3>
			              <div class="box-tools pull-right">
			                <a data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></a>
			                <a data-widget="remove" class="btn btn-box-tool" href="<?=Router::url('/document/home/delete/' . $doc->id)?>" title="Elimina documento"><i class="fa fa-times"></i></a>
			              </div>
			            </div>
			            <div class="box-body">
			            	<div class="row">
			            		<div class="col-md-3 col-xs-12 col-md-push-9 text-right">
			            			<div class="btn-group ">
					                  <a class="btn btn-default" href="<?=Router::url('/document/home/view/' . $doc->id_document)?>" title="Visualizza documento" ><i class="fa fa-eye"></i></button>
					                  <a class="btn btn-default" href="<?=Router::url('/document/home/edit/' . $doc->id)?>" title="Modifica documento"><i class="fa fa-pencil"></i></a>
					                  <a class="btn btn-default" href="<?=Router::url('/document/home/add/' . $doc->id_document)?>" title="Aggiungi figlio"><i class="fa fa-plus"></i></a>
					                </div>
			            		</div>
			            		<div class="col-md-9 col-xs-12 col-md-pull-3">
			            			<p class="info small col-lg-3 col-md-6"><b>Cliente:</b> <?php if(is_object($doc->client)){ echo $doc->client->name; }else{ echo "-"; } ?></p>
					                <p class="info small col-lg-3 col-md-6"><b>Progetto:</b> <?php if(is_object($doc->project)){ echo $doc->project->name; }else{ echo "-"; } ?></p>
					                <p class="info small col-lg-3 col-md-6">Dipendenze: <?=$dcn[$doc->id]?></p>
					                <p class="info small col-lg-3 col-md-6">Revisioni: <?=$drn[$doc->id]?></p>
					                <p class="info creato small row col-md-12">Data: <?=$doc->created?></p>
			            		</div>
			            	</div>
                        </div>
            		</div>
            	</div>
            	
            </div>
    	</div>
    </div>
  <?php } ?>  
    
  <?php }else{ ?>
    <div class="alert alert-danger">Non ci sono documenti</div>
  <?php } ?>



 
<div class="filter-documents">
        <fieldset>
            <legend><?= __('Filtri') ?></legend>
            <?= $this->Form->create($documents,['method' => 'post']) ?>
                <div class="filter-home">
                    <label for="id_client">Cliente</label>
                    <?= $this->Form->select('id_client',$clients,['id' => 'id_client', 'class' => 'home', 'empty' => 'Tutti', 'required' => false]) ?>
                </div>
                <div class="filter-home">
                    <label for="id_client">Progetto</label>
                    <?= $this->Form->select('id_project',$projects,['id' => 'id_project', 'class' => 'home', 'empty' => 'Tutti', 'required' => false]) ?>
                </div>
                <div style="clear: both;"></div>
                <?= $this->Form->button(__('Cerca')); ?>
            </form>
        </fieldset>
    </div>
      
    <div class="list-documents">
        <fieldset>
            <legend><?= __('Lista dei Documenti') ?></legend>
            
            <div class="toolbar-documents">
                <a class="btn btn-primary" href="<?=Router::url('/document/home/add/0')?>" title="Crea nuovo documento">
                    <?php echo $this->Html->image('Document.add.png', ['alt' => 'Nuovo', 'style' => 'height:25px;']); ?>
                </a>
            </div>
            <hr>
            <?php if($documentsNumber > 0){ ?>
                <ul data-level="0" data-parent="0">
                <?php foreach ($documents as $key => $doc) { ?>
                    <li>
                        <div class="info">
                            <p class="titolo"><?=ucfirst($doc->title)?></p>
                            <p class="info small"><b>Cliente:</b> <?php if(is_object($doc->client)){ echo $doc->client->name; }else{ echo "-"; } ?></p>
                            <p class="info small"><b>Progetto:</b> <?php if(is_object($doc->project)){ echo $doc->project->name; }else{ echo "-"; } ?></p>
                            <br/>
                            <p class="info creato small">Data: <?=$doc->created?></p>
                            <p class="info small">Dipendenze: <?=$dcn[$doc->id]?></p>
                            <p class="info small">Revisioni: <?=$drn[$doc->id]?></p>
                            
                        </div>
                        <div class="action">
                            <a class="btn btn-primary" href="<?=Router::url('/document/home/view/' . $doc->id_document)?>" title="Visualizza documento" >
                                <?php echo $this->Html->image('Document.view.png', ['alt' => 'Apri', 'style' => 'height:25px;']); ?>
                            </a>
                            <a class="btn btn-primary" href="<?=Router::url('/document/home/edit/' . $doc->id)?>" title="Modifica documento" >
                                <?php echo $this->Html->image('Document.edit.png', ['alt' => 'Modifica', 'style' => 'height:25px;']); ?>
                            </a>
                            <?php
                                $disabled ="";
                                if($dcn[$doc->id] == 0){
                                    $disabled ='disabled';
                                }
                            ?>
                            <a class="btn btn-primary show-child <?=$disabled?>" data-parent="<?=$doc->id_document?>" title="Visualizza figli" >
                                <?php echo $this->Html->image('Document.mostra.png', ['alt' => 'Mostra Figli', 'style' => 'height:25px;']); ?>
                            </a>
                            <a class="btn btn-primary" href="<?=Router::url('/document/home/add/' . $doc->id_document)?>" title="Aggiungi figlio">
                                <?php echo $this->Html->image('Document.add.png', ['alt' => 'Aggiungi Figli', 'style' => 'height:25px;']); ?>
                            </a>
                            <a class="btn btn-primary delete-doc" href="<?=Router::url('/document/home/delete/' . $doc->id)?>" title="Elimina documento">
                                <?php echo $this->Html->image('Document.delete.png', ['alt' => 'Elimina', 'style' => 'height:25px;']); ?>
                            </a>
                        </div>
                        <div style="clear: both;"></div>
                    </li>
                    
                <?php } ?>
                </ul>
            <?php }else{ ?>
                <div class="alert alert-danger">Non ci sono documenti</div>
            <?php } ?>
        </fieldset>
    </div>
</div>
</section>
