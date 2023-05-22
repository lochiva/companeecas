<?php
/**
* Reminder Manager is a plugin for manage attachment
*
* Companee :    New Schedini  (https://www.companee.it)
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
use Cake\Routing\Router;
?>

<script>
  // Da usare solo per settare variabili, il resto del js va messo in schedini.js

  var idSubmission = 0;
  var urlClientsSchedini = '<?=Router::url('/reminder_manager/ws/getClientsSchedini')?>';
  var urlTypeTemplate = '<?=Router::url('/reminder_manager/ws/getTypeTemplate')?>';
  var urlNewTypeTemplate = '<?=Router::url('/reminder_manager/ws/setNewTypeTemplate')?>';
  var urlEditProfiloAzienda = '<?=Router::url('/aziende/home/edit')?>';
  var urlLoadTemplate = '<?=Router::url('/reminder_manager/ws/getTemplateByType')?>';
  var urlDeleteFile = '<?=Router::url('/reminder_manager/ws/deleteFile')?>';
  var urlSaveSubmission = '<?=Router::url('/reminder_manager/ws/saveSubmission')?>';
  var urlSubmissionList = '<?=Router::url('/reminder_manager/submission/')?>';
  var urlGetSenderEmail = '<?=Router::url('/reminder_manager/ws/getSenderEmail/')?>';
  var urlDeleteAttachmentById = '<?=Router::url('/reminder_manager/ws/deleteAttachmentById/')?>';

</script>

<?php echo $this->Html->css('ReminderManager.detail', ['block' => 'css']); ?>
<?php echo $this->Html->script('ReminderManager.functions', ['block' => 'script']); ?>
<?php echo $this->Html->script('ReminderManager.schedini', ['block' => 'script']); ?>
<?php $this->Html->script('ReminderManager.tinymce/jquery.tinymce.min', ['block' => 'script']); ?>
<?php $this->Html->script('ReminderManager.tinymce', ['block' => 'script']); ?>


<section class="content-header">
  <h1>
    Promemoria Clienti
    <small>Generazione nuovo invio da elenco schedini.</small>
  </h1>
  <ol class="breadcrumb">
    <li><a><i class="fa  fa-envelope"></i> Promemoria</a></li>
    <li class="active">Schedini</li>
  </ol>
</section>

<section class="content">
    <div class="row">

      <div class="col-md-12">
        <div class="box box-primary">
            <div class="box-header row">
              <div class="col-xs-9">
                <h3  class="box-title" id="reportTitle" >
                  Invio Schedini
                </h3>
              </div>
              <div class="col-xs-3 text-right">
                <a class="btn btn-flat btn-warning" title="Indietro" href="<?=Router::url('/reminder_manager/submission/')?>">
                  <i class="fa fa-arrow-circle-left "></i>
                </a>
                <a id="action-reload" class="btn btn-flat btn-info" title="Ricarica" href="#">
                  <i class="fa  fa-refresh "></i>
                </a>
              </div>
            </div><!-- /.box-header -->

         </div><!-- /.box -->
       </div>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div id="box-table-clients" class="box">
            <div class="box-header">
              <div class="col-xs-9">
                <h3 class="box-title">Elenco destinatari </h3>
                <span class="error-msg"></span>
              </div>
              <div class="col-xs-3 text-right">
                <a class="btn-find-error" title="Trova errori"><span class="badge bg-red"><i class="fa fa-search"></i></span></a>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <form id="frm-list-clients">
                <table id="table-clients" class="table table-condensed">
                  <thead>
                    <tr>
                      <th style="width: 10px">#</th>
                      <th>Cliente</th>
                      <th>Sispac</th>
                      <th>Email</th>
                      <th>File</th>
                      <th>Stato</th>
                      <th>Azioni</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td id="tr-loading" colspan="7">Caricamento dati ...</td>
                    </tr>

                  </tbody>
                </table>
              </form>
            </div>
            <!-- /.box-body -->
          </div>
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

              <form id="form-submission" role="form">
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
                <div class="form-group">
                  <label for="file-attachment">Allega File</label>
                  <input id="file-attachment" type="file" name="attachment">
                  <p class="help-info">Seleziona o trascina qui il tuo file.</p>
                </div>
                <input type="hidden" name="template" id="template" value="default" >
              </form>

              <div class="box-footer">
                <div class="pull-right">
                  <button id="save" type="button" class="btn btn-info" disabled><i class="fa  fa-save "></i> Salva per Invio futuro</button>
                  <button id="save-test" type="button" class="btn btn-info" disabled><i class="fa  fa-paper-plane-o "></i> Salva e Invia Test</button>
                  <button id="save-send" type="submit" class="btn btn-warning" disabled><i class="fa fa-envelope-o"></i> Salva e Invia</button>
                </div>
                <a type="reset" class="btn btn-default" href="<?=Router::url('/reminder_manager/submission/')?>"><i class="fa fa-times"></i> Chiudi</a>
                <button id="save-type" type="button" class="btn btn-default"><i class="fa fa-pencil"></i> Salva come nuova tipologia</button>
              </div>

            </div>
            <!-- /.box-body -->
          </div>
      </div>
    </div>

</section>
