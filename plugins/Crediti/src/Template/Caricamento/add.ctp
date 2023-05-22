<?php
/**
* Crediti is a plugin for manage attachment
*
* Companee :    Add  (https://www.companee.it)
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
$.fn.datepicker.dates['it'] = {
    days: ["Domenica", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
    daysShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
    daysMin: ["Do", "Lu", "Ma", "Me", "Gi", "Ve", "Sa"],
    months: ["Gennaio", "Febbraio", "Marzo", "Aprile", "Maggio", "Giugno", "Luglio", "Agosto", "Settembre", "Ottobre", "Novembre", "Dicembre"],
    monthsShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
    today: "Today",
    clear: "Clear",
    format: "dd/mm/yyyy",
    titleFormat: "MM yyyy",
    weekStart: 1
};


$(function() {


      $("#datepicker").datepicker({ language: 'it', format: 'dd/mm/yyyy', autoclose:true, todayHighlight:true});


  });

</script>
<section class="content-header">
    <h1>
        Crediti
        <small>Risultato importazione file</small>
    </h1>
    <ol class="breadcrumb">
        <li><a><i class="fa fa-bank"></i>Crediti</a></li>
        <li class="active">Risultato importazione</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div id="box-caricamento" class="box" style="padding:20px;">
              <div style="margin-top:30px;"></div>
              <?php if(isset($data)): ?>

                <?php if(is_array($result)): ?>
                  <?= '<h4>'.$result['error'].'</h4>' ?>
                  <h4>Numero righe cancellate: <?= $result['deleted']?></h4>
                <?php endif ?>

                <h4>Numero righe elaborate: <?= count($data)+1+count($errors)?> ( di cui non valide: <?=count($errors)?>  )</h4>

                <?php if($action == 'verify'): ?>
                  <h4>Numero righe verificate: <?= count($data)?> </h4>
                <?php else: ?>
                  <h4>Numero righe caricate: <?= count($data)?> </h4>
                <?php endif ?>

                <?php if($action == 'totals'): ?>
                  <h4>I crediti sono stati consolidati nel report.</h4>
                <?php endif ?>

                <h4>Elenco errori: </h4>
                <?php foreach($errors as $line => $error): ?>
                  <b>Errore linea <?= $line.': '.$error ?><br /> </b>
                <?php endforeach ?>

              <?php endif ?>
            </div>
        </div>
    </div>
</section>
