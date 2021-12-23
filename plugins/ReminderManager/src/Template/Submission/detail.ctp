<?php
use Cake\Routing\Router;
use Cake\Core\Configure;
?>

<?php $this->Html->css('ReminderManager.detail', ['block' => 'css']); ?>
<?php $this->Html->script('ReminderManager.functions', ['block' => 'script']); ?>
<?php $this->Html->script('ReminderManager.detail', ['block' => 'script']); ?>
<?php $this->Html->script('ReminderManager.tinymce/jquery.tinymce.min', ['block' => 'script']);?>
<?php $this->Html->script('ReminderManager.tinymce', ['block' => 'script']); ?>

<script>
  var idSubmission = '<?=$idSubmission?>';
  var attribute = '<?=strtoupper($attribute)?>';
  var emailTosend = [];


  var urlSubscriptionDetail = '<?=Router::url('/reminder_manager/ws/getSubmissionDetail/')?>';
  var urlTypeTemplate = '<?=Router::url('/reminder_manager/ws/getTypeTemplate')?>';
  var urlLoadTemplate = '<?=Router::url('/reminder_manager/ws/getTemplateByType')?>';
  var urlNewTypeTemplate = '<?=Router::url('/reminder_manager/ws/setNewTypeTemplate')?>';
  var urlSaveSubmission = '<?=Router::url('/reminder_manager/ws/saveSubmission')?>';
  var urlSubmissionList = '<?=Router::url('/reminder_manager/submission/')?>';
  var urlChangestatusSubmission = '<?=Router::url('/reminder_manager/ws/changeStatusSubmission/')?>';
  var urlCloneSubmission = '<?=Router::url('/reminder_manager/ws/cloneSubmission/')?>';
  var urlSetMailStatus = '<?=Router::url('/reminder_manager/ws/setMailStatus/')?>';
  var urlAttachmentEmail = '<?=Router::url('/reminder_manager/ws/getEmailAttachment/')?>';
  var urlGetSenderEmail = '<?=Router::url('/reminder_manager/ws/getSenderEmail/')?>';
  //var urlGetOffices = '<?=Router::url('/reminder_manager/ws/getOffices/')?>';
  //var urlGetPartners = '<?=Router::url('/reminder_manager/ws/getPartners/')?>';
  var urlGetPossibleRecipients = '<?=Router::url('/reminder_manager/ws/getPossibleRecipients/')?>';
  var urlDeleteAttachmentById = '<?=Router::url('/reminder_manager/ws/deleteAttachmentById/')?>';

</script>

<section class="content-header">
  <h1>
    Dettaglio Invio
    <small>Dettaglio dell'invio.</small>
  </h1>
  <ol class="breadcrumb">
    <li><a><i class="fa  fa-envelope"></i> Promemoria</a></li>
    <li class="active">Dettaglio</li>
  </ol>
</section>

<section class="content">

  <div class="row">
    <div class="col-md-12">

      <div id="invio-in-corso" class="callout callout-warning">
        <h4>ATTENZIONE!</h4>
        <p>Questo invio risulta in corso, pertanto non è possibile effettuare modifiche.</p>
      </div>

      <div id="invio-da-inviare" class="callout callout-purple">
        <h4>ATTENZIONE!</h4>
        <p>Questo invio risulta ancora da inviare, pertento, oltre a poterne bloccare l'invio, è ancora possibile riportarlo allo stato "salvato".</p>
      </div>

      <div id="invio-inviato" class="callout callout-success">
        <h4>ATTENZIONE!</h4>
        <p>Questo invio risulta già concluso, non è più possibile apportare modifiche.</p>
      </div>

      <div id="invio-da-inviare-schedini" class="callout callout-info">
        <h4>ATTENZIONE!</h4>
        <p>Questo invio risulta di tipo "SCHEDINI", pertanto saranno concesse solo modifiche al testo dell'invio e non ai destinatari che sono preimpostati da elenco file.</p>
      </div>

      <div id="invio-da-inviare-generic" class="callout callout-info">
        <h4>ATTENZIONE!</h4>
        <p>Questo invio risulta in stato "Salvato per Invio Futuro" pertanto è ancora possibile effettuare modifiche.</p>
      </div>

      <div id="invio-stop" class="callout callout-maroon">
        <h4>ATTENZIONE!</h4>
        <p>Questo invio risulta essere stato sospeso, è possibile farlo ripartire o clonarlo.</p>
      </div>

      <div id="invio-error" class="callout callout-danger">
        <h4><i class="fa fa-exclamation-triangle"></i> ATTENZIONE!</h4>
        <p>Questo invio risulta essere andato in errore. La causa dell'errore è il seguente: </br><i><span id="status_text"></span></i></p>
      </div>

    </div>
  </div>

    <div class="row">

      <div class="col-md-3">
        <div class="box box-solid  box-row-one-filter">
          <div class="box-header with-border">
              <i class="fa fa-filter"></i>
              <h4 class="box-title">Filtri Destinatari</h4>
          </div>
          <div class="box-body box-row-one">
            <!--
            <div class="form-group">
                <label class="control-label">Studio</label>
                <select multiple class="form-control select2 filter-recipients" id="idOffice" style="width: 100%;">

                </select>
            </div>

            <div class="form-group">
                <label class="control-label">Socio di Riferimento</label>
                <select multiple class="form-control select2 filter-recipients" id="socioRif" style="width: 100%;">

                </select>
            </div>
            -->
            <!--
            <div class="form-group">
                <label class="control-label">Stato Contabilità</label>
                <select multiple class="form-control select2 filter-recipients" id="statoCont" style="width: 100%;">

                </select>
            </div>
            -->
          </div>
          <div class="overlay overlay-filter">
            <!--<i class="fa fa-refresh fa-spin"></i>-->
            <p><i class="fa fa-ban"></i> Non utilizzabile per SCHEDINI</p>
          </div>
        </div>
      </div>


      <div class="col-md-4">
        <div class="box box-primary box-row-one-filter-results">
          <div class="box-header">
            <div class="col-xs-9">
              <h3  class="box-title" id="reportTitle" >
                Risultato del filtro <span id="num-recipient-found"></span>
              </h3>
            </div>

          </div><!-- /.box-header -->
          <div class="box-body box-table box-row-one box-row-one-scroll">
            <table id="table-recipent" class="table table-striped">
                <thead>
                  <tr>
                    <th style="width: 70%">Azienda</th>
                    <th style="width: 10%">Info</th>
                    <th style="width: 10%">Cont</th>
                    <th style="width: 10%">Soll</th>
                  </tr>
                </thead>
                <tbody>
                <tr>
                  <td colspan="100">Nessun risultato trovato ...</td>
                </tr>
              </tbody>
            </table>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div>

      <div class="col-md-5">
        <div class="box box-primary box-row-one-recipent">
          <div class="box-header">
            <div class="col-xs-9">
              <h3  class="box-title" id="reportTitle" >
                Destinatari selezionati
              </h3>
            </div>
            <div class="col-xs-3 text-right">
              <a class="btn-delete-all-email-to-send" title="Elimina tutti i destinatari"><span class="badge bg-red"><i class="fa fa-trash"></i></span></a>
            </div>
          </div><!-- /.box-header -->
          <div class="box-body box-table box-row-one box-row-one-scroll ">
            <form id="frm-list-clients">
              <table id="table-recipent-saved" class="table table-striped">
                  <thead>
                    <tr>
                      <th style="width: 5%">#</th>
                      <th style="width: 40%">Azienda</th>
                      <th style="width: 20%">Email</th>
                      <th style="width: 10%">Stato</th>
                      <th style="width: 15%">Azioni</th>
                    </tr>
                  </thead>
                  <tbody>
                  <tr>
                    <td id="tr-loading" colspan="100">Caricamento dati ...</td>
                  </tr>
                </tbody>
              </table>
            </form>
          </div><!-- /.box-body -->
        </div><!-- /.box -->
      </div>

    </div>

    <div class="row">
      <div class="col-md-12">
        <!--<div id="box-table-clients" class="box box-info">-->
        <div class="box box-info">
            <div class="box-header">
              <h3 class="box-title">Dati invio</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">

              <form id="form-submission" role="form" enctype="multipart/form-data">
                <div>
                  <input type="hidden" name="idSubmission" value="" />
                  <input type="hidden" name="attribute" value="" />
                </div>
                <div class="form-group">
                  <label>Tipologia Invio</label>

                  <select id="select-type-submission" name="typeSubmission" class="form-control select2 select2-hidden-accessible" style="width: 100%;" tabindex="-1" aria-hidden="true" disabled>

                  </select>
                </div>
                <div class="form-group">
                  <label>Titolo Invio *</label>
                  <input class="form-control" name="title" placeholder="Inserire titolo invio ..." type="text" required>
                  <span class="help-block">Campo obbligatorio</span>
                </div>
                <div class="form-group">
                  <label>Mittente mail *</label>
                  <input class="form-control" name="sender_email" placeholder="Inserire mittente ..." type="text"required>
                  <span class="help-block">Campo obbligatorio</span>
                </div>
                <div class="form-group">
                  <label>Oggetto della mail *</label>
                  <input class="form-control" name="object" placeholder="Inserire oggetto ..." type="text"required>
                  <span class="help-block">Campo obbligatorio</span>
                </div>
                <div class="form-group">
                  <label>Testo della Mail *</label>
                  <textarea id="compose-textarea" name="body" class="form-control" rows="10" placeholder="Inserire il testo dell'invio ..." required></textarea>
                  <span class="help-block">Campo obbligatorio</span>
                </div>
                <div class="form-group col-md-6">
                  <label for="file-attachment">Allega File</label>
                  <p id="file-attachment-file-name" class="attachment-name" ></p>
                  <input id="file-attachment" type="file" name="attachment">
                  <p class="help-info help-info-attachment">Seleziona o trascina qui il tuo file.</p>
                </div>
                <div class="form-group col-md-6">
                  <label for="template" class="col-md-12">Template</label>
                  <div class="radio col-md-6">
                    <label>
                      <input type="radio" name="template" id="template" value="default" checked="true">
                      <?php echo $this->Html->image('template_default_ico.png', ['alt' => "Bianco", 'title' => 'Template Bianco', 'fullBase' => true]); ?>
                    </label>
                  </div>
                  <?php /*
                  <div class="radio col-md-6">
                    <label>
                      <input type="radio" name="template" id="template" value="consulenza">
                      <?php echo $this->Html->image('template_consulenza_ico.png', ['alt' => "Consulenza", 'title' => 'Template Consulenza', 'fullBase' => true]); ?>
                    </label>
                  </div>
                  */ ?>
                </div>
              </form>
              
              <div class="box-footer col-md-12">
                <div class="pull-right btn-action-mailer">
                  <button id="save" type="button" class="btn btn-info" disabled><i class="fa  fa-save "></i> Salva per Invio futuro</button>
                  <button id="save-test" type="button" class="btn btn-info" disabled><i class="fa  fa-paper-plane-o "></i> Salva e Invia Test</button>
                  <button id="save-send" type="submit" class="btn btn-warning" disabled><i class="fa fa-envelope-o"></i> Salva e Invia</button>
                  <button id="return-saved" type="submit" class="btn btn-purple" disabled><i class="fa fa-fast-backward "></i> Riporta a Salvato</button>
                  <button id="stop" type="submit" class="btn btn-stop" disabled><i class="fa fa-stop "></i> Interrompi</button>
                  <button id="clone" type="submit" class="btn btn-info" disabled><i class="fa fa-clone "></i> Clona</button>
                  <button id="restart" type="submit" class="btn btn-success" disabled><i class="fa fa-play "></i> Riprendi</button>
                </div>
                <a id="close" type="reset" class="btn btn-default" href="<?=Router::url('/reminder_manager/submission/')?>"><i class="fa fa-times"></i> Chiudi</a>
                <button id="save-type" type="button" class="btn btn-default" disabled><i class="fa fa-pencil"></i> Salva come nuova tipologia</button>
              </div>

            </div>
            <!-- /.box-body -->
          </div>
      </div>
    </div>

</section>
