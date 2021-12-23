<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

$registrationType = Configure::read('dbconfig.registration.REGISTRATION_TYPE');
?>

<?= $this->Html->css('Registration.password'); ?>
<?= $this->Html->script('Registration.password', ['block']); ?>

<script type="text/javascript">
function validateImage(){

	if($('#fileInputImage').get(0).files.length > 0){

	    var fileSize = $('#fileInputImage').get(0).files[0].size;
      var file = $('#fileInputImage').get(0).files[0];
          var fileType = file["type"];
          var ValidImageTypes = [ "image/jpeg", "image/png", "image/jpg"];
          if ($.inArray(fileType, ValidImageTypes) < 0) {
               return false;
          }

		    if(fileSize > 500000){
		    	return false;
		    }else{
		    	return true;
		    }
		}
	return true;
	}

function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#imagePreview').attr('src', e.target.result);
                $('#cropPreview').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

  function preview(img, selection) {
    if (!selection.width || !selection.height)
        return;
        var scaleX = 150 / selection.width;
        var scaleY = 150 / selection.height;

        var screenImage = $("#imagePreview");

         // Create new offscreen image to test
         var theImage = new Image();
         theImage.src = screenImage.attr("src");

         // Get accurate measurements from that.
         var imgWidth = theImage.width;
         var imgHeight = theImage.height
     $('#cropPreview').css({
        width: Math.round(scaleX * imgWidth)+"px",
        height: Math.round(scaleY * imgHeight)+"px",
        marginLeft: -Math.round(scaleX * selection.x1),
        marginTop: -Math.round(scaleY * selection.y1)
      });
  }

$(document).ready(function(){
    $("#fileInputImage").change(function () {
      if(validateImage()){
        $('#divCheckImage').html("");
        $('#cropPreviewDiv')
            .css({
                //float:'left',
                //position: 'relative',
                overflow: 'hidden',
                width: '150px',
                height: '150px'
            })
            .insertAfter($('.cropPreview-col'));
         readURL(this);
         $("#imagePreview").load(function(){
          var screenImage = $("#imagePreview");
            $('#cropPreview').removeClass('img-responsive');
           // Create new offscreen image to test
           var theImage = new Image();
           theImage.src = screenImage.attr("src");

           // Get accurate measurements from that.
           var imgWidth = theImage.width;
           var imgHeight = theImage.height;
           if(imgWidth<150 || imgHeight<150){
              $('#divCheckImage').html("L'immagine &egrave; troppo piccola, il risultato potrebbe non essere dei migliori.");
           }
           $('#imagePreview').imgAreaSelect({ aspectRatio: '1:1', handles: true, onSelectChange: preview ,imageHeight:imgHeight ,
           imageWidth:imgWidth, x1: 0, y1: 0, x2: 120, y2: 120,
                onSelectEnd: function (img, selection) {
              $('input[name="x"]').val(selection.x1);
              $('input[name="y"]').val(selection.y1);
              $('input[name="height"]').val(selection.x2-selection.x1);
              $('input[name="width"]').val(selection.y2-selection.y1);
          } });

        });



       }else{
         $('#divCheckImage').html("L'immagine &egrave; troppo grande o il formato non &egrave; supportato.");

       }
   });

   $('.list-notice').hover(function(){
     var notice = $(this);
     if(notice.attr('readed') != 1){
        notice.attr('readed','1');
         $.ajax({
             url : pathServer + "Ws/readNotice/"+notice.attr('value'),
             type: "GET",
             dataType: "json",
             success : function (data,stato) {
               if(data.response == 'OK'){
                 $('#notice-'+notice.attr('value')+' > ul > li > a').html('Letta il : '+data.data);
               }
             },
             error: function(data){
             }
           });
        }
   });

   $('#unlinkGoogle').click(function(){
      if(confirm('Sicuro di voler scollegare l\'account google? ')){
          window.location = '<?= $this->Url->build('/') ?>registration/users/unlikGoogle';
      }
   });

});
</script>

<section class="content-header">


    <h1>
      <?= __('Profilo utente') ?>
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-user"></i> Profilo</a></li>
    </ol>
</section>

<section class="content  users form">
    <div class="row">
      <div class="col-md-3">

        <!-- Profile Image -->
        <div class="box box-primary">
          <div class="box-body box-profile">
            <?= $this->Utils->userImage($user['id'],'profile-user-img img-responsive img-circle') ?>

            <h3 class="profile-username text-center"><?= h($user['nome'].' '.$user['cognome']) ?></h3>

            <p class="text-muted text-center"><?= h($user['username']) ?></p>

            <ul class="list-group list-group-unbordered">
              <li class="list-group-item">
                <b>Accessi</b> <a class="pull-right"><?= $info['tot']['accessi'] ?></a>
              </li>
              <li class="list-group-item">
                <b>Attività</b> <a class="pull-right"><?= $info['tot']['actions'] ?></a>
              </li>
              <!--<li class="list-group-item">
                <b>Task in calendario</b> <a class="pull-right"><?= $info['tot']['tasks'] ?></a>
              </li>-->
            </ul>

            <a href="#" class="btn btn-primary btn-block" data-toggle="modal" data-target="#myModalNotice"><b>Manda una notifica</b></a>
          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->

        <!-- About Me Box -->
        <div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">Info Utente</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">
            <strong><i class="fa fa-book margin-r-5"></i>Ruolo</strong>

            <p class="text-muted">
              <?= h($user['role']) ?>
            </p>

            <hr>

            <strong><i class="fa fa-info margin-r-5"></i> Codice fiscale</strong>

            <p class="text-muted"><?= h($user['cf']) ?></p>

            <hr>

            <strong><i class="fa fa-envelope margin-r-5"></i> Email</strong>

            <p class="text-muted"><a href="mailto:<?= h($user['email']) ?>" target="_top"><?= h($user['email']) ?></a></p>

          </div>
          <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
      <div class="col-md-9">
        <div class="nav-tabs-custom">
          <ul class="nav nav-tabs">
            <li class="<?= $tabs['timeline'] ?>"><a href="#timeline" data-toggle="tab">Attività</a></li>
            <?php if ($authUser['id'] == $user['id']): ?>
                <li class="<?= $tabs['notifications'] ?>"><a href="#notifications" data-toggle="tab">Notifiche</a></li>
                <li class="<?= $tabs['modify'] ?>"><a href="#modify" data-toggle="tab">Modifica</a></li>
            <?php endif; ?>
          </ul>
          <div class="tab-content">
            <!-- /.tab-pane -->
            <div class="<?= $tabs['timeline'] ?> tab-pane" id="timeline">
              <!-- The timeline -->
              <ul class="timeline timeline-inverse">

                <!-- timeline time label -->
                <li class="time-label">
                    <span class="bg-maroon">
                        <b>Ultime attività</b>
                    </span>
                </li>
                <!-- /.timeline-label -->

                <!-- timeline item -->
                <?php foreach ($info['timeline'] as $value): ?>
                  <li>
                      <i class="fa fa-info bg-blue"></i>
                      <div class="timeline-item">
                          <span class="time"><i class="fa fa-clock-o"></i><?= $value['label']['data'] ?></span>
                          <h3 class="timeline-header"><a href="#"><?= h($value['label']['user']) ?> </a>
                            <?= h($value['label']['action']) ?></h3>
                          <!--<div class="timeline-body">
                              <b>Documento:</b> Nome documento
                          </div>-->
                          <?php if (!empty($value['label']['link']) && $this->request->session()->read('Auth.User.role') == 'admin'): ?>
                            <div class="timeline-footer">
                                <a href="<?= h($value['label']['link']) ?>" class="btn btn-success btn-xs">visualizza il record</a>
                            </div>
                          <?php endif; ?>
                      </div>
                  </li>
                <?php endforeach; ?>

                <li>
                  <i class="fa fa-clock-o bg-gray"></i>
                </li>
            </ul>
            </div>
            <!-- /.tab-pane -->
            <?php if ($authUser['id'] == $user['id']): ?>
              <div class="tab-pane <?= $tabs['notifications'] ?>" id="notifications">
                <!-- Post -->
                <?php if (empty($info['notifications'])): ?>
                  <p>Non sono presenti notifiche.</p>
                <?php endif; ?>
                <?php foreach ($info['notifications'] as $key => $value): ?>
                  <div id="notice-<?= $value['id'] ?>" class="post clearfix list-notice " value="<?= $value['id'] ?>"
                      readed=" <?= !empty($value['readed']) ? '1': '0' ?>">
                    <div class="user-block">
                      <?= $this->Utils->userImage($value['Creator']['id'],'img-circle img-bordered-sm') ?>
                          <span class="username">
                            <?php if($this->request->session()->read('Auth.User.role') == 'admin'){ ?>
                            <a href="<?= Router::url('/registration/users/view/'.$value['Creator']['id']) ?>"><?= h($value['creator'] )?></a>
                            <?php }else{ ?>
                            <?= h($value['creator'] )?>
                            <?php } ?>
                            <a href="#" class="pull-right btn-box-tool"><i class="fa fa-exclamation"></i></a>
                          </span>
                      <span class="description">Notifica del - <?= $value['created'] ?></span>
                    </div>
                    <!-- /.user-block -->
                    <p><?= $value['message'] ?></p>
                    <ul class="list-inline">
                      <li><a href="#" class="link-black text-sm ">
                        <?= !empty($value['readed']) ? 'Letta il : '.$value['readed'] : 'Da leggere' ?></a></li>
                    </ul>
                  </div>
                <?php endforeach; ?>
                <!-- /.post -->
              </div>
              <div class="tab-pane <?= $tabs['modify'] ?>" id="modify">
                <?= $this->Form->create($user, array('url'=>'/registration/users/edit/'.$user['id'],'class' => 'form-horizontal','type' => 'file')) ?>
                <div class="box-body">
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="col-md-3 control-label required" for="inputCognome">Username</label>
                                <div class="col-md-9">
                                    <?= $this->Form->input('username' , array('readonly' => true, 'class' => 'form-control','label'=>false)) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label required" for="inputCognome">Email</label>
                                <div class="col-md-9">
                                    <?= $this->Form->input('email', array('class' => 'form-control','label'=>false)) ?>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label required" for="newPassword">Nuova password</label>
                                <div class="col-md-9">
                                  <?= $this->Form->input('new_password', array('id' => 'newPassword', 'value' => '', 'required' => false, 'class' => 'form-control', 'label' => false, 'type' => 'password', 'autocomplete' => 'new-password', 'style' => 'padding: 6px 12px;')) ?>
                                 <div hidden class="col-md-12" id="divPasswordValidation">
                                  <b>La password deve contenere:</b><br>
                                  <span id="lowercaseValidation" class="invalid">almeno una lettera <b>minuscola</b></span><br>
                                  <span id="uppercaseValidation" class="invalid">almeno una lettera <b>maiuscola</b></span><br>
                                  <span id="numberValidation" class="invalid">almeno un <b>numero</b></span><br>
                                  <span id="specialValidation" class="invalid">almeno un <b>carattere speciale</b></span><br>
                                  <span id="lengthValidation" class="invalid">un minimo di <b>8 caratteri</b></span><br>
                                 </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label required" for="confirmPassword">Conferma password</label>
                                <div class="col-md-9">
                                  <?= $this->Form->input('confirm_password', array('id'=>'confirmPassword', 'value' => '', 'required' => false, 'type' => 'password', 'class' => 'form-control', 'label'=>false, 'autocomplete' => 'new-password')) ?>
                                  <div class="col-md-12" id="divCheckPasswordMatch"></div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6">

                            <?php

                            switch ($registrationType) {
                                case '1':
                                ?>
                                    <!--Registrazione con anagrafica-->
                                <div class="form-group">
                                    <label class="col-md-3 control-label required" for="inputCognome">Nome</label>
                                    <div class="col-md-9">
                                      <?= $this->Form->input('nome', array('class' => 'form-control','label'=>false)) ?>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-md-3 control-label required" for="inputCognome">Cognome</label>
                                    <div class="col-md-9">
                                      <?= $this->Form->input('cognome', array('class' => 'form-control','label'=>false)) ?>

                                    </div>
                                </div>
                                <div class="form-group">
                                    <a href="<?= $googleAuthLink ?>" class="" target="_blank" >
                                      <label class="col-md-3 control-label required" for="inputCognome" style="cursor:pointer !important;">
                                        <i class="fa fa-hand-pointer-o" ></i>
                                        Collega a google</label></a>
                                    <div class="col-md-9">
                                      <div class="input-group">
                                        <?= $this->Form->input('googleAuth', array('class' => 'form-control','label'=>false,
                                        'placeholder'=>(empty($user['googleAccessToken']) ? 'Codice google' : 'Account già collegato' ) )) ?>
                                        <span class="input-group-btn">
                                          <a id="unlinkGoogle" data-toggle="tooltip" title="Scollega account google" class="btn btn-warning btn-flat"><i class="fa fa-chain-broken" aria-hidden="true"></i></a>
                                        </span>
                                      </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                  <label class="col-md-3 control-label " for="inputImage">Immagine profilo</label>
                                  <div class="col-md-9">
                                    <?= $this->Form->file('inputImage', ['class' => 'form-control', 'label' => false, 'id'=>'fileInputImage']) ?>
                                    <div class="col-md-12" id="divCheckImage"></div>
                                  </div>
                                </div>
                                <?php if(file_exists(WWW_ROOT .'img/user/'.$user['id'].'.jpg')): ?>
                                  <div class="form-group">
                                    <label class="col-md-3 control-label " for="deleteImage">Cancella immagine profilo</label>
                                    <div class="col-md-9">
                                      <?= $this->Form->input('deleteImage', ['type'=>'checkbox','class' => 'form-cotrol','label'=>false]) ?>
                                    </div>
                                  </div>
                                <?php endif ?>
                                <?php
                                break;

                                case '0':
                                default:
                                    //Registrazione base solo con email, username e password.
                                break;
                            }

                            ?>

                        </div>
                    </div>
                </div>
                <div class="box-body">
                  <div class ="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label class="col-md-3 control-label " >Anteprima</label>
                        <div class="col-md-9">
                          <img id="imagePreview" src="<?php echo Router::url('/');?>img/boxed-bg.png" alt="Preview profile image" class="img-thumbnail" />

                        </div>
                        <input type="hidden" name="x" value="" />
                        <input type="hidden" name="y" value="" />
                        <input type="hidden" name="width" value="" />
                        <input type="hidden" name="height" value="" />
                      </div>
                    </div>
                    <div class="col-md-6 cropPreview-col">
                      <div id="cropPreviewDiv">
                        <?php if(file_exists(WWW_ROOT .'img/user/'.$user['id'].'.jpg')): ?>
                          <?= $this->Html->image('user/'.$user['id'].'.jpg' , ['alt' => 'Crop preview','id'=>'cropPreview']); ?>
                        <?php else : ?>
                          <img id="cropPreview" src="<?php echo Router::url('/');?>img/user.png" alt="Crop preview" class="img-responsive" />
                        <?php endif ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                    <?= $this->Form->button(__('Salva'), array('class' => 'btn btn-primary pull-right btn_save_edit', 'id' => 'btnSave')); ?>
                </div>
                <?= $this->Form->end() ?>
              </div>
            <?php endif; ?>
            <!-- /.tab-pane -->
          </div>
          <!-- /.tab-content -->
        </div>
      </div>

    </div>
</section>
<?= $this->Element('modale_notice'); ?>
