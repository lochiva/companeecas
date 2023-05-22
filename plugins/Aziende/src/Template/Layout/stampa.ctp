<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    Stampa  (https://www.companee.it)
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
?>

<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            Companee: <?= __c($this->fetch('title')) ?>
        </title>
        <?= $this->Html->meta('icon') ?>

        <?= $this->Html->css('bootstrap.min.css') ?>
        <?= $this->Utils->templateCss() ?>
        <?= $this->Html->css('../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') ?>
        <?= $this->Html->css('AdminLTE.min.css') ?>
        <?= $this->Html->css('font-awesome.min.css') ?>
        <?= $this->Html->css('../fonts/ionicons/css/ionicons.min.css') ?>
        <?= $this->Html->css('companee-style.css')?>
        <?= $this->Html->css('Aziende.aziende')?>
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.0/themes/le-frog/jquery-ui.css">

        <?= $this->Html->script('jquery-2.2.4.min.js') ?>
        <?= $this->Html->script('jquery-ui.min.js') ?>
        <?= $this->Html->script('general.js') ?>
        <?= $this->Html->script('bootstrap.min.js') ?>
        <?= $this->Html->script('../plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') ?>
        <?= $this->Html->script('app.min.js') ?>
        <?= $this->Html->script('demo.js') ?>

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>
        <?= $this->fetch('script') ?>
        
    </head>
    <body>
    <style>
        @media print {
            @page { margin: 0; }
            body { margin: 1cm; }
        }
    </style>
        <?= $this->fetch('content') ?>
    </body>
</html>

