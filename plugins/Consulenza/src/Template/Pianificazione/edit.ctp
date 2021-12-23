<?php
use Cake\Routing\Router;
?>
<script>
/*Valorizzare solo variabili dal server*/
	var pathServer = '<?=Router::url('/')?>';
</script>
<?php
$user = $this->request->session()->read('Auth.User');
echo $this->Html->script('Consulenza.pianificazione');
echo $this->Html->css('Consulenza.pianificazione');

?>

<section class="content-header">
  <h1>
    Pianificazione cliente: <b><?php echo $client->denominazione ; ?></b>
    <small></small>
  </h1>
  <ol class="breadcrumb">
    <li><a href="<?php echo Router::url('/aziende/home');?>"><i class="fa fa-group"></i> Clienti</a></li>
    <li class="active">pianificazione</li>
  </ol>
</section>

<section class="content">
<!-- Small boxes (Stat box) -->
  <div class="row">

  	<form class="" action="<?php echo Router::url('/consulenza/pianificazione/saveDataOrder');?>" role="form" method="post">

    <div class="col-lg-3 col-md-12">
    	<div class="box box-solid">
	        <div class="box-header with-border">
	        	<i class="fa fa-gear"></i>
                <h4 class="box-title">Opzioni</h4>
	        </div>

	        <!--<form class="" action="<?php echo Router::url('/consulenza/pianificazione/saveDataOrder');?>" role="form" method="post">-->

	        	<input type="hidden" id="azienda_id" name="azienda_id" value="<?php echo $client->id; ?>" >
	        	<input type="hidden" id="order_id" name="order_id" value="<?php if(isset($dataOrder->id)){ echo $dataOrder->id; } ?>" >
	        	<input type="hidden" id="isLocked" name="isLocked" value="<?php if(isset($dataOrder->isLocked)){ echo $dataOrder->isLocked; } ?>" >

	        	<input type="hidden" id="savedjobsattribute_id" value="<?php if(isset($dataOrder->jobsattribute_id)){ echo $dataOrder->jobsattribute_id; } ?>" >

		        <div class="box-body">
		          <div class="row">
		          	<div class="col-lg-12 col-md-4">
	                    <div class="form-group">
	                     	<label class="control-label">Anno</label>
	                      	<select id="select-year" class="form-control select2" name="year" style="width: 100%;">
		                      <?php if(!empty($years)){ ?>
		                      	<?php foreach ($years as $key => $year) { ?>
		                      		<?php if($year == $order){ $selected = 'selected="selected"';}else{ $selected = ""; } ?>
		                      		<option value="<?php echo $year;?>" <?php echo $selected; ?> ><?php echo $year; ?></option>
		                      	<?php } ?>
		                      <?php } ?>
		                    </select>
	                    </div>
	                    <div class="form-group">
	                      	<label class="control-label">Studio</label>
	                        <select class="form-control select2" name="office_id" style="width: 100%;">
		                      <option value="0">Nessuno</option>
		                      <?php if(!empty($offices)){ ?>
		                      	<?php foreach ($offices as $key => $office) { ?>
		                      		<?php if(isset($dataOrder['office_id']) && $office->id == $dataOrder->office_id){ $selected = 'selected="selected"';}else{ $selected = ""; } ?>
		                      		<option value="<?php echo $office->id;?>" <?php echo $selected; ?> ><?php echo $office->name; ?></option>
		                      	<?php } ?>
		                      <?php } ?>
		                    </select>
	                    </div>
	                    <div class="form-group">
	                      	<label class="control-label">Socio di riferimento</label>
	                      	<?php //echo $dataOrder->userPartner_id; ?>
	                        <select class="form-control select2" name="userPartner_id" style="width: 100%;">
		                      <option value="0">Nessuno</option>
		                      <?php if(!empty($partners)){ ?>
		                      	<?php foreach ($partners as $key => $partner) { ?>
		                      	<?php if(isset($dataOrder->userPartner_id) && $partner->id == $dataOrder->userPartner_id){ $selected = 'selected="selected"';}else{ $selected = ""; } ?>
		                      		<option value="<?php echo $partner->id;?>" <?php echo $selected; ?> ><?php echo $partner->cognome . " " . $partner->nome;?></option>
		                      	<?php } ?>
		                      <?php } ?>
		                    </select>
	                    </div>
		          	</div>
		          	<div class="col-lg-12 col-md-4 border-div">
		          		<div class="form-group">
	                      	<label class="control-label">Partita IVA</label>
	                        <select class="form-control select2" name="hasPIVA" style="width: 100%;">
		                      <option value="1" <?php if(isset($dataOrder->hasPIVA) && $dataOrder->hasPIVA == 1){ echo 'selected="selected"';} ?> >si</option>
		                      <option value="0" <?php if(isset($dataOrder->hasPIVA) && $dataOrder->hasPIVA == 0){ echo 'selected="selected"';} ?> >no</option>
		                    </select>
	                    </div>
	                    <div class="form-group">
	                      	<label class="control-label">IRAP</label>
	                        <select class="form-control select2" name="hasIRAP" style="width: 100%;">
		                      <option value="1" <?php if(isset($dataOrder->hasIRAP) && $dataOrder->hasIRAP == 1){ echo 'selected="selected"';} ?> >si</option>
		                      <option value="0" <?php if(isset($dataOrder->hasIRAP) && $dataOrder->hasIRAP == 0){ echo 'selected="selected"';} ?> >no</option>
		                    </select>
	                    </div>
	                    <div class="form-group">
	                      	<label class="control-label">IVA autonoma</label>
	                      	<?php //echo $dataOrder->hasIVAAutonoma; ?>
	                        <select class="form-control select2" name="isIVAAutonoma" style="width: 100%;">
		                      <option value="1" <?php if(isset($dataOrder->isIVAAutonoma) && $dataOrder->isIVAAutonoma == 1){ echo 'selected="selected"';} ?> >si</option>
		                      <option value="0" <?php if(isset($dataOrder->isIVAAutonoma) && $dataOrder->isIVAAutonoma == 0){ echo 'selected="selected"';} ?> >no</option>
		                    </select>
	                    </div>
                        <div class="form-group">
                            <label class="control-label">Righe contabili</label>
                            <input class="form-control" type="text" name="righeContabili" value="<?php if(isset($dataOrder->righeContabili)){ echo $dataOrder->righeContabili; }else{ echo "0";} ?>" >
                        </div>
                        <div class="form-group">
                            <label class="control-label">Data Consegna Bilancino</label>
                            <div class="input-group">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <input class="form-control" type="text" name="dataConsegnaBilancino" value="<?php if(isset($dataOrder->dataConsegnaBilancino)){ echo $dataOrder->dataConsegnaBilancino->i18nFormat('dd/MM/YYYY'); }?>" readonly>
                                <!--<input type="text" placeholder="Start" name="startDate" id="inputStartDate" class="form-control" readonly>-->
                            </div>
                        </div>
                        <hr class="hidden-md">
		          	</div>

		          	<div class="col-lg-12 col-md-4">
                        <label>Tipologia</label>
	                    <div class="form-group">

	                    <?php if(isset($typeOfBusiness) && !empty($typeOfBusiness)){ ?>
	                    	<?php foreach ($typeOfBusiness as $key => $type) { ?>
	                    		<div class="radio">
	                        		<label>
	                        			<?php if(isset($dataOrder->jobsattribute_id) && $type->id == $dataOrder->jobsattribute_id){ $checked = 'checked';}else{ $checked = ""; } ?>
	                        			<?php if(isset($dataOrder->isLocked) && $dataOrder->isLocked == 1){ $disabled = "disabled"; }else{ $disabled = ""; } ?>
	                          			<input type="radio" name="jobsattribute_id" value="<?php echo $type->id; ?>" <?php echo $checked . " "  . $disabled; ?> >
	                          			<?php echo $type->name; ?>
	                        		</label>
	                      		</div>
	                    	<?php } ?>
	                    <?php } ?>

	                    </div>
		          	</div>
		          </div>

		        </div><!-- /.box-body -->
		        <div class="box-footer">
									<?php if($user['role'] == 'admin'): ?>
										<div data-toggle="tooltip" title="Per abilitare il tasto occorre che tutte la causali abbiamo il valore ore impostato a 0" class="tooltip-wrapper" >
											<button  id="sblocca_azienda" class="btn btn-warning btn-flat" type='button'>Sblocca Cliente</button>
										</div>
									<?php endif ?>
                    <button class="btn btn-warning btn-flat pull-right" type="submit">Salva</button>
            </div><!-- /.box-footer -->
	        <!--</form>-->
	    </div>
    </div>
    <div class="col-lg-9 col-md-12">
    	 <div class="box">
                <div class="box-header">
                	<i class="fa fa-th"></i>
                    <h3 class="box-title">Causali</h3>
                    <p class="total-time">(Ore Totali: <span>00:00</span>)</p>
                </div><!-- /.box-header -->
                <div class="box-body box-table">
                	<?php //echo "<pre>"; print_r($dataOrder); echo "</pre>"; ?>
                  <table id="table-job" class="table table-bordered table-striped table-hover">
                    <thead>
                      <tr>
                        <th>Codice Causale</th>
                        <th>Descrizione Causale</th>
                        <th style="min-width:75px;">Ore</th>
                        <th>Pianificazione</th>
                        <th>Assegnato a</th>
                        <th>Ore da assegnare</th>
                        <th>Azioni</th>
                        <th>Elenco attività pianificate</th>
                      </tr>
                    </thead>
                    <tbody>

                    	<?php if(!empty($jobs)){ ?>

                    		<?php foreach ($jobs as $key => $job) { ?>

                    			<?php
                    				//Genero gli attributi
                    				$dataTag = "";
                    				foreach ($job->jobsattributes as $key => $attr) {
                    					$dataTag .= ' data-attr-' . $attr->id . '="1"';
                    				}
                    			?>

                    			<tr <?php echo $dataTag; ?>>
                    				<td>
                    					<?php echo $job->code;?>
                    					<input type="hidden" name="jobs[<?php echo $job->id; ?>][job_id]" value="<?php echo $job->id;?>" >
                    					<?php if(isset($dataOrder->jobs[$job->id])){
                    						$id =  $dataOrder->jobs[$job->id]->_joinData->id;
                    					}else{
                                            $id = 0;
                                        } ?>
                                        <input type="hidden" name="jobs[<?php echo $job->id; ?>][id]" value="<?php echo $id;?>" >
                					</td>
                    				<td><?php echo $job->name;?></td>
                    				<!-- ORE -->
                    				<td>
                    					<?php
                    						if(isset($dataOrder->jobs[$job->id])){
                    							$time = $dataOrder->jobs[$job->id]->_joinData->totalTime;
                    							$jobOrderId = $dataOrder->jobs[$job->id]->_joinData->id;;
                    						}else{
                    							$time = "000:00";
                    							$jobOrderId = 0;
                    						}
            							?>
                        				<input class="form-control auto-save" data-id="<?php echo $jobOrderId; ?>" data-field="totalTime" data-job-id="<?php echo $job->id; ?>" type="text" name="jobs[<?php echo $job->id; ?>][totalTime]" placeholder="010:00" data-mask="" data-inputmask="'mask': '999:99'" value="<?php echo $time; ?>"
                                            data-order="<?php echo $dataOrder->id; ?>" >
                        			</td>
                        			<!-- PIANIFICAZIONE -->
                        			<td>

                                        <?php
                                            if(isset($dataOrder->jobs[$job->id]) && $dataOrder->jobs[$job->id]->_joinData->isLocked == 1){
                                                $disabled = 'disabled';

                                            }else{
                                                $disabled = '';
                                            }
                                        ?>
                        				<select class="form-control select2 auto-save" name="jobs[<?php echo $job->id; ?>][process_id]" style="width: 100%;" data-id="<?php echo $jobOrderId; ?>" data-field="process_id" data-job-id="<?php echo $job->id; ?>" <?php echo $disabled; ?> data-order="<?php echo $dataOrder->id; ?>">
                        	  				<option value="0" >--</option>

                        	  				<?php if(!empty($job->processes)){?>
                        	  					<?php foreach ($job->processes as $key => $process) { ?>
                        	  						<?php
			                    						if(isset($dataOrder->jobs[$job->id]) && $dataOrder->jobs[$job->id]->_joinData->process_id == $process->id){
			                    							$selected = 'selected="selected"';
			                    						}else{
			                    							$selected = '';
			                    						}
			            							?>
                        	  						<option value="<?php echo $process->id;?>" <?php echo $selected; ?> ><?php echo $process->name;?></option>
                        	  					<?php } ?>
                        	  				<?php } ?>

		                    			</select>
                        			</td>
                        			<!-- ASSEGNATO -->
                        			<td>

                                        <?php
                                            if(isset($dataOrder->jobs[$job->id]) && $dataOrder->jobs[$job->id]->_joinData->isLocked == 1){
                                                $disabled = 'disabled';

                                            }else{
                                                $disabled = '';
                                            }
                                        ?>
                        				<select class="form-control select2 select-user auto-save" name="jobs[<?php echo $job->id; ?>][user_id]" style="width: 100%;" data-id="<?php echo $jobOrderId; ?>" data-field="user_id" data-job-id="<?php echo $job->id; ?>" <?php echo $disabled; ?> data-order="<?php echo $dataOrder->id; ?>">
                        	  				<option value="0">--</option>

		                      				<?php if(!empty($users)){?>
                        	  					<?php foreach ($users as $key => $user) { ?>
                        	  						<?php
			                    						if(isset($dataOrder->jobs[$job->id]) && $dataOrder->jobs[$job->id]->_joinData->user_id == $user->id){
			                    							$selected = 'selected="selected"';
			                    						}else{
			                    							$selected = '';
			                    						}
			            							?>
                        	  						<option value="<?php echo $user->id;?>" <?php echo $selected; ?> ><?php echo $user->cognome . " " . $user->nome;?></option>
                        	  					<?php } ?>
                        	  				<?php } ?>
		                    			</select>
		                			</td>
                                    <!-- ORE DA ASSEGNARE -->
                        			<td>
                        				<?php
                    						if(isset($dataOrder->jobs[$job->id])){
                    							$time = $dataOrder->jobs[$job->id]->_joinData->toBeAssigned;
                    						}else{
                    							$time = "00:00";
                    						}
            							?>
                        				<span id="toBeAssigned-<?php echo $job->id; ?>"><?php echo $time; ?></span>
                    				</td>
                                    <!-- AZIONI -->
                        			<td>
                                        <?php
                                            if(isset($dataOrder->jobs[$job->id]) && $dataOrder->jobs[$job->id]->_joinData->isLocked == 1){
                                                $locked = '1';

                                                if($dataOrder->jobs[$job->id]->_joinData->tasksProgrammed == 0){
                                                    if($user['level'] >= 100){
                                                        $showCreate = 'style="display:none;"';
                                                        $showDelete = '';
                                                    }else{
                                                        $showCreate = '';
                                                        $showDelete = 'style="display:none;"';
                                                    }

                                                }else{
                                                    $showCreate = '';
                                                    $showDelete = 'style="display:none;"';
                                                }

                                            }else{
                                                $locked = '0';
                                                $showCreate = '';
                                                $showDelete = 'style="display:none;"';
                                            }
                                        ?>
                        				<a class="btn btn-flat btn-block btn-warning create-tasks disabled" <?php echo $showCreate; ?> data-id="<?php echo $job->id; ?>" data-locked="<?php echo $locked ;?>">Genera</a>
                                        <?php if($user['level'] >= 100){ ?>
                                            <a class="btn btn-flat btn-block btn-danger delete-tasks" <?php echo $showDelete; ?> data-id="<?php echo $job->id; ?>" >Cancella</a>
                                        <?php } ?>
                        			</td>

			                        <td>
                                        <?php
                                        if(isset($dataOrder->jobs[$job->id]) && $dataOrder->jobs[$job->id]->_joinData->isLocked == 1){
			                        	    $displayPlanned = '';
                                            $tasksPlanned = $dataOrder->jobs[$job->id]->_joinData->tasksPlanned;
                                        }else{
                                            $displayPlanned = 'style="display:none;"';
                                            $tasksPlanned = 0;
                                        }
                                        if(isset($dataOrder->jobs[$job->id]->_joinData->tasksManual) && $dataOrder->jobs[$job->id]->_joinData->tasksManual > 0){
                                            $displayManual = '';
                                            $tasksManual = $dataOrder->jobs[$job->id]->_joinData->tasksManual;
                                        }else{
                                            $displayManual = 'style="display:none;"';
                                            $tasksManual = 0;
                                        }
                                        ?>
                                        <a class="badge bg-aqua" data-id="<?php echo $job->id; ?>"<?php echo $displayPlanned; ?>>
                                            Attività Generate (<span data-id="<?php echo $job->id; ?>"><?php echo $tasksPlanned; ?></span>)
                                        </a>
                                        <a class="badge " data-id="<?php echo $job->id; ?>"<?php echo $displayManual; ?>>
                                            Attività Manuali (<?php echo $tasksManual; ?>)
                                        </a>
			                        </td>
                      			</tr>

                    		<?php } ?>

                    	<?php } ?>
<!--
                      <tr>
                        <td>100</td>
                        <td>REGISTRAZIONE PRIMA NOTA CONTABILE ORDINATA</td>
                        <td>
                        	<input class="form-control" type="text" placeholder="10">
                        </td>
                        <td>
                        	<select class="form-control select2" style="width: 100%;">
                        	  <option>Seleziona</option>
		                      <option selected="selected">Consumo</option>
		                      <option>Mensili</option>
		                      <option>Trimestrali</option>
		                    </select>
                        </td>
                        <td>
                        	<select class="form-control select2" style="width: 100%;">
                        	  <option>Seleziona</option>
		                      <option selected="selected">Marta</option>
		                      <option>Paola</option>
		                      <option>Alessandro</option>
		                    </select>
		                </td>
                        <td>0</td>
                        <td>
                        	<button class="btn btn-flat btn-warning disabled">Generato</button>
                        </td>
                        <td>
                        	<a class="badge bg-aqua">A consumo (10)</a>
                        </td>
                      </tr>
                      <tr>
                        <td>101</td>
                        <td>SITUAZIONI CONTABILI ORDINARIE</td>
                        <td>
                        	<input class="form-control" type="text" placeholder="10">
                        </td>
                        <td>
                        	<select class="form-control select2" style="width: 100%;">
                        	  <option>Seleziona</option>
		                      <option>Consumo</option>
		                      <option selected="selected">Mensili</option>
		                      <option>Trimestrali</option>
		                    </select>
                        </td>
                        <td>
                        	<select class="form-control select2" style="width: 100%;">
                        	  <option>Seleziona</option>
		                      <option selected="selected">Marta</option>
		                      <option>Paola</option>
		                      <option>Alessandro</option>
		                    </select>
		                </td>
                        <td style="color:red;">-2</td>
                        <td>
                        	<button class="btn btn-flat btn-warning disabled">Generato</button>
                        </td>
                        <td>
                        	<a class="badge bg-gray">Gen (1)</a>
                        	<a class="badge bg-aqua">Feb (1)</a>
                        	<a class="badge bg-aqua">Mar (1)</a>
                        	<a class="badge bg-aqua">Apr (1)</a>
                        	<a class="badge bg-aqua">Mag (1)</a>
                        	<a class="badge bg-aqua">Giu (1)</a>
                        	<a class="badge bg-aqua">Lug (1)</a>
                        	<a class="badge bg-aqua">Ago (1)</a>
                        	<a class="badge bg-aqua">Set (1)</a>
                        	<a class="badge bg-aqua">Ott (1)</a>
                        	<a class="badge bg-aqua">Nov (1)</a>
                        	<a class="badge bg-aqua">Dic (1)</a>
                        </td>
                      </tr>
                      <tr>
                        <td>102</td>
                        <td>REGISTRAZIONE PRIMA NOTA CONTABILE ORDINATA</td>
                        <td>
                        	<input class="form-control" type="text" placeholder="10">
                        </td>
                        <td>
                        	<select class="form-control select2" style="width: 100%;">
                        	  <option>Seleziona</option>
		                      <option>Consumo</option>
		                      <option>Mensili</option>
		                      <option>Trimestrali</option>
		                    </select>
                        </td>
                        <td>
                        	<select class="form-control select2" style="width: 100%;">
                        	  <option>Seleziona</option>
		                      <option>Marta</option>
		                      <option>Paola</option>
		                      <option>Alessandro</option>
		                    </select>
		                </td>
                        <td>10</td>
                        <td>
                        	<button class="btn btn-flat btn-warning">Genera</button>
                        </td>
                        <td>
                        </td>
                      </tr>
                      -->
                    </tbody>
                  </table>
                </div><!-- /.box-body -->
              </div><!-- /.box -->
    </div>
    </form>
  </div>

    <!-- Select2 -->

   <?php echo $this->Element('modal_new_order'); ?>
