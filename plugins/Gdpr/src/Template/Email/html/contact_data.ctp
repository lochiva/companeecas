<?php
/**
* Gdpr is a plugin for manage attachment
*
* Companee :    Contact Data  (https://www.companee.it)
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

<h2>Riepilogo dati del contatto <?= $data['nome'].' '.$data['cognome'] ?></h2>
<br />
<h3>Dati personali</h3>
<p><b>Cognome</b>: <?= $data['cognome'] ?></p>
<p><b>Nome</b>: <?= $data['nome'] ?></p>
<p><b>Indirizzo</b>: <?= !empty($data['indirizzo']) ? $data['indirizzo'] : 'Non inserito' ?></p>
<p><b>Numero civico</b>: <?= !empty($data['num_civico']) ? $data['num_civico'] : 'Non inserito' ?></p>
<p><b>CAP</b>: <?= !empty($data['cap']) ? $data['cap'] : 'Non inserito' ?></p>
<p><b>Comune</b>: <?= !empty($data['comune']) ? $data['comune'] : 'Non inserito' ?></p>
<p><b>Provincia</b>: <?= !empty($data['provincia']) ? $data['provincia'] : 'Non inserito' ?></p>
<p><b>Nazione</b>: <?= !empty($data['nazione']) ? $data['nazione'] : 'Non inserito' ?></p>
<p><b>Telefono</b>: <?= !empty($data['telefono']) ? $data['telefono'] : 'Non inserito' ?></p>
<p><b>Cellulare</b>: <?= !empty($data['cellulare']) ? $data['cellulare'] : 'Non inserito' ?></p>
<p><b>Fax</b>: <?= !empty($data['fax']) ? $data['fax'] : 'Non inserito' ?></p>
<p><b>Email</b>: <?= $data['email'] ?></p>
<p><b>Contatto Skype</b>: <?= !empty($data['skype']) ? $data['skype'] : 'Non inserito' ?></p>
<p><b>Codice fiscale</b>: <?= !empty($data['cf']) ? $data['cf'] : 'Non inserito' ?></p>
<p><b>Ruolo</b>: <?= $data['ruolo'] ?></p>
<br/>
<h3>Consensi per la privacy</h3>
<p>Dichiarazione di presa visione dell'informativa sulla privacy: <?= !empty($data['read_privacy']) ? 'Si' : 'No' ?></p>
<p>Consenso al trattamento dei dati con le modalità e per le finalità indicati nell'informativa: <?= !empty($data['accepted_privacy']) ? 'Si' : 'No' ?></p>
<p>Consenso al trattamento dei dati per le finalità di marketing: <?= !empty($data['marketing_privacy']) ? 'Si' : 'No' ?></p>
<p>Consenso alla comunicazione dei dati a terzi partner commerciali del Titolare del trattamento: <?= !empty($data['third_party_privacy']) ? 'Si' : 'No' ?></p>
<p>Consenso al trattamento dei dati per le finalità di profilazione: <?= !empty($data['profiling_privacy']) ? 'Si' : 'No' ?></p>
<p>Consenso alla comunicazione dei dati agli ambiti ed agli organi specificati nell'informativa: <?= !empty($data['spread_privacy']) ? 'Si' : 'No' ?></p>
<!-- <p>Consenso ad avvisare in caso di modifica dei dati appartenenti a questo indirizzo email: <?= !empty($data['notify_privacy']) ? 'Si' : 'No' ?></p> -->
