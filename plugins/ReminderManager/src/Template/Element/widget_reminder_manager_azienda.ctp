<?php
/**
* Reminder Manager is a plugin for manage attachment
*
* Companee :    Widget Reminder Manager Azienda  (https://www.companee.it)
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
 use Cake\Routing\Router; ?>
<script>
  var cKey = 'id_azienda';
  var cValue = '<?=$idAzienda?>';
  var urlGetSubmissionsByCustom = '<?=Router::url('/reminder_manager/ws/getSubmissionsByCustom/')?>';
</script>

<?php echo $this->Html->css('ReminderManager.widget_azienda', ['block' => 'css']); ?>
<?php echo $this->Html->script('ReminderManager.widget_azienda', ['block' => 'scriptBottom']); ?>

<div id="widget-plugin-reminder-manager" class="col-md-8">

  <div class="box box-info">
    <div class="box-header with-border">
        <h3 class="box-title">Comunicazioni inviate</h3>
        <div class="box-tools pull-right">
            <button data-widget="collapse" class="btn btn-box-tool"><i class="fa fa-minus"></i></button>
            <!--<button data-widget="remove" class="btn btn-box-tool"><i class="fa fa-times"></i></button>-->
        </div>
    </div><!-- /.box-header -->
    <div class="box-body">
        <div class="table-responsive">
            <table id="table-list-widget-reminder" class="table no-margin">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Titolo</th>
                        <th>Email</th>
                        <th>Stato</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                  <tr>
                    <td colspan="10">Caricamento ...</td>
                  </tr>
                </tbody>
            </table>
        </div><!-- /.table-responsive -->
    </div><!-- /.box-body -->

  </div>
</div>
