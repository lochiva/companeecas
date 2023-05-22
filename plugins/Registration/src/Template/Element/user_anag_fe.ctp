<?php
/**
* Registration is a plugin for manage attachment
*
* Companee :    User Anag Fe  (https://www.companee.it)
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
<hr>
<?= $this->Form->input('nome') ?>
<?= $this->Form->input('cognome') ?>
<?php //echo $this->Form->input('data_nascita',['label' => 'Data di Nascita', 'type' => 'date', 'minYear' => 1900, 'maxYear' => date('Y')]); ?>
<?php //echo $this->Form->input('cf', ['label' => 'Codice Fiscale']); ?>