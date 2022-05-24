<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

$registrationType = Configure::read('dbconfig.registration.REGISTRATION_TYPE');
?>

<script type="text/javascript">
function checkPasswordMatch() {
    var password = $("#txtNewPassword").val();
    var confirmPassword = $("#txtConfirmPassword").val();

    if (password == "" && confirmPassword == "") {
        $("#divCheckPasswordMatch").html("");
        $(".btn_save_edit").attr('disabled', false);
    }
    else if (password == "" && password != confirmPassword) {
        $("#divCheckPasswordMatch").html("Le password non corrispondono!");
        $(".btn_save_edit").attr('disabled', true);
    }
    else if (password != "" && confirmPassword == "") {
        $("#divCheckPasswordMatch").html("");
        isValidPassword();
        $(".btn_save_edit").attr('disabled', true);
    }
    else if (password != "" && password != confirmPassword) {
        $("#divCheckPasswordMatch").html("Le password non corrispondono!");
          isValidPassword();
          $(".btn_save_edit").attr('disabled', true);

    }
    else if (password != "" && password == confirmPassword) {
        $("#divCheckPasswordMatch").html("Le password corrispondono.");
        if (isValidPassword()) {
            $(".btn_save_edit").attr('disabled', false);
        }
        else{
            $(".btn_save_edit").attr('disabled', true);
        }

    }
}

function isValidPassword() {
    var password = $("#txtNewPassword").val();
    var confirmPassword = $("#txtConfirmPassword").val();

    if (password.length >= 5) {
        $("#divCheckPasswordLength").html("");
        return 1;
    }
    else {
        $("#divCheckPasswordLength").html("Password non valida, la lunghezza minima è di 5 caratteri!");
        return 0;
    }
}
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
    $("#txtConfirmPassword").keyup(checkPasswordMatch);
    $("#txtNewPassword").keyup(checkPasswordMatch);
    $("#fileInputImage").change(function () {
      if(validateImage()){
        $('#divCheckImage').html("");
        $('#cropPreviewDiv')
            .css({
                float:'left',
                position: 'relative',
                overflow: 'hidden',
                width: '150px',
                height: '150px'
            })
            .insertAfter($('#imagePreview'));
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





    /*$("#txtNewPassword").keyup(function(){
        var password = $("#txtNewPassword").val();
        var confirmPassword = $("#txtConfirmPassword").val();

        if (password != "" && confirmPassword == "") {
            $(".btn_save_edit").attr('disabled', true);
        }
        else if (confirmPassword == "" && password == "") {
            $("#divCheckPasswordMatch").html("");
            $(".btn_save_edit").attr('disabled', false);
        }
        else{
            $(".btn_save_edit").attr('disabled', false);
        }
    });*/

});
</script>

<section class="content-header">


    <h1>
      <?= __('Modifica profilo utente') ?>
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-user"></i> Profilo</a></li>
    </ol>
</section>

<section class="content  users form">
    <div class="row">
        <div class="col-md-12">
            <!--<button class="btn btn-flat btn-default btn-block">Congela settimana</button><br/>-->
            <div class="box box-solid">
                <div class="box-header with-border">
                    <i class="fa fa-edit"></i>
                    <h4 class="box-title">Modifica</h4>
                </div>
                <?= $this->Form->create($user, array('class' => 'form-horizontal','enctype' => 'multipart/form-data')) ?>
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
                                <label class="col-md-3 control-label required" for="inputCognome">Nuova password</label>
                                <div class="col-md-9">
                                 <?= $this->Form->input('new_password',array('type' => 'password', 'class' => 'form-control','label'=>false, 'id'=>'txtNewPassword')) ?>
                                 <div class="col-md-12" id="divCheckPasswordLength"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-md-3 control-label required" for="inputCognome">Ripeti password</label>
                                <div class="col-md-9">
                                 <?= $this->Form->input('retype_password',array('type' => 'password', 'class' => 'form-control','label'=>false, 'id'=>'txtConfirmPassword')) ?>
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
                                <?php /*
                                <div class="form-group">
                                    <a href="<?= $googleAuthLink ?>" class="" target="_blank" >
                                      <label class="col-md-3 control-label required" for="inputCognome" style="cursor:pointer !important;">
                                        <i class="fa fa-hand-pointer-o" ></i>
                                        Collega a google</label></a>
                                    <div class="col-md-9">
                                      <?= $this->Form->input('googleAuth', array('class' => 'form-control','label'=>false,
                                      'placeholder'=>(empty($user['googleAccessToken']) ? 'Codice google' : 'Account già collegato' ) )) ?>
                                    </div>
                                </div>
                                */ ?>
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
                          <div id="cropPreviewDiv">
                            <?php if(file_exists(WWW_ROOT .'img/user/'.$user['id'].'.jpg')): ?>
                              <?= $this->Html->image('user/'.$user['id'].'.jpg' , ['alt' => 'Crop preview','id'=>'cropPreview']); ?>
                            <?php else : ?>
                              <img id="cropPreview" src="<?php echo Router::url('/');?>img/user.png" alt="Crop preview" class="img-responsive" />
                            <?php endif ?>
                          </div>
                        </div>
                        <input type="hidden" name="x" value="" />
                        <input type="hidden" name="y" value="" />
                        <input type="hidden" name="width" value="" />
                        <input type="hidden" name="height" value="" />
                      </div>
                    </div>
                    <div class="col-md-6">
                    </div>
                  </div>
                </div>
                <div class="box-footer">
                    <?= $this->Form->button(__('Salva'), array('class' => 'btn btn-warning btn-flat pull-right btn_save_edit')); ?>
                </div>
                <?= $this->Form->end() ?>

            </div>

      </div>

    </div>
</section>
