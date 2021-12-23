
<h1>SEGNALAZIONE CASO <?= $report['province_code'].$report['code'] ?></h1>
<h2>ANAGRAFICA SEGNALANTE</h2>
<table class="anagrafica-table-pdf">
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Tipologia segnalante</span><br />
				<span><?= $witness['type_reporter'] == 'victim' ? 'Vittima' : 'Testimone' ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Tipologia anagrafica</span><br />
				<span><?= $witness['type'] == 'person' ? 'Persona' : 'Ente/associazione' ?></span>
			</div>
		</td>
	</tr>
<?php if ($witness['type'] == 'person') { ?>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Cognome</span><br />
				<span><?= empty($witness['lastname']) ? '-' : $witness['lastname'] ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Nome</span><br />
				<span><?= empty($witness['firstname']) ? '-' : $witness['firstname'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Sesso</span><br />
				<span><?= empty($witness['gender_id']) ? '-' : (($witness['gender']['user_text'] && !empty($witness['gender_user_text'])) ? $witness['gender']['name'].' ('.$witness['gender_user_text'].')' : $witness['gender']['name']) ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Anno di nascita</span><br />
				<span><?= empty($witness['birth_year']) ? '-' : $witness['birth_year'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Nazione di nascita</span><br />
				<span><?= empty($witness['country_id']) ? '-' : $witness['country']['des_luo'] ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Cittadinanza</span><br />
				<span><?= empty($witness['citizenship_id']) ? '-' : $witness['citizenship']['des_luo'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">In Italia dall'anno</span><br />
				<span><?= empty($witness['in_italy_from_year']) ? '-' : $witness['in_italy_from_year'] ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Permesso di soggiorno</span><br />
				<span><?= empty($witness['residency_permit_id']) ? '-' : (($witness['residency_permit']['user_text'] && !empty($witness['residency_permit_user_text'])) ? $witness['residency_permit']['name'].' ('.$witness['residency_permit_user_text'].')' : $witness['residency_permit']['name']) ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Stato civile</span><br />
				<span><?= empty($witness['marital_status_id']) ? '-' : (($witness['marital_status']['user_text'] && !empty($witness['marital_status_user_text'])) ? $witness['marital_status']['name'].' ('.$witness['marital_status_user_text'].')' : $witness['marital_status']['name']) ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Vive in italia con</span><br />
				<span><?= empty($witness['lives_with']) ? '-' : $witness['lives_with'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Religione</span><br />
				<span><?= empty($witness['religion_id']) ? '-' : (($witness['religion']['user_text'] && !empty($witness['religion_user_text'])) ? $witness['religion']['name'].' ('.$witness['religion_user_text'].')' : $witness['religion']['name']) ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Titolo di studio</span><br />
				<span><?= empty($witness['educational_qualification_id']) ? '-' : (($witness['educational_qualification']['user_text'] && !empty($witness['educational_qualification_user_text'])) ? $witness['educational_qualification']['name'].' ('.$witness['educational_qualification_user_text'].')' : $witness['educational_qualification']['name']) ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Tipologia occupazione</span><br />
				<span><?= empty($witness['type_occupation_id']) ? '-' : (($witness['occupation_type']['user_text'] && !empty($witness['type_occupation_user_text'])) ? $witness['occupation_type']['name'].' ('.$witness['type_occupation_user_text'].')' : $witness['occupation_type']['name']) ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Tel. fisso</span><br />
				<span><?= empty($witness['telephoone']) ? '-' : $witness['telephoone'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Cellulare</span><br />
				<span><?= empty($witness['mobile']) ? '-' : $witness['mobile'] ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">E-mail</span><br />
				<span><?= empty($witness['email']) ? '-' : $witness['email'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Regione</span><br />
				<span><?= empty($witness['region_id']) ? '-' : $witness['region']['des_luo'] ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Provincia</span><br />
				<span><?= empty($witness['province_id']) ? '-' : $witness['province']['des_luo'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Comune</span><br />
				<span><?= empty($witness['city_id']) ? '-' : $witness['city']['des_luo'] ?></span>
			</div>
		</td>
		<td>
		</td>
	</tr>
<?php } else { ?>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Ragione sociale</span><br />
				<span><?= empty($witness['business_name']) ? '-' : $witness['business_name'] ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Partita IVA</span><br />
				<span><?= empty($witness['piva']) ? '-' : $witness['piva'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Regione della sede legale</span><br />
				<span><?= empty($witness['region_id_legal']) ? '-' : $witness['region_legal']['des_luo'] ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Provincia della sede legale</span><br />
				<span><?= empty($witness['province_id_legal']) ? '-' : $witness['province_legal']['des_luo'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Comune della sede legale</span><br />
				<span><?= empty($witness['city_id_legal']) ? '-' : $witness['city_legal']['des_luo'] ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Indirizzo della sede legale</span><br />
				<span><?= empty($witness['address_legal']) ? '-' : $witness['address_legal'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Regione della sede operativa</span><br />
				<span><?= empty($witness['region_id_operational']) ? '-' : $witness['region_operational']['des_luo'] ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Provincia della sede operativa</span><br />
				<span><?= empty($witness['province_id_operational']) ? '-' : $witness['province_operational']['des_luo'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Comune della sede operativa</span><br />
				<span><?= empty($witness['city_id_operational']) ? '-' : $witness['city_operational']['des_luo'] ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Indirizzo della sede operativa</span><br />
				<span><?= empty($witness['address_operational']) ? '-' : $witness['address_operational'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Legale rappresentante</span><br />
				<span><?= empty($witness['legal_representative']) ? '-' : $witness['legal_representative'] ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Tel. fisso (legale rappresentante)</span><br />
				<span><?= empty($witness['telephone_legal']) ? '-' : $witness['telephone_legal'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Cellulare (legale rappresentante)</span><br />
				<span><?= empty($witness['mobile_legal']) ? '-' : $witness['mobile_legal'] ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">E-mail (legale rappresentante)</span><br />
				<span><?= empty($witness['email_legal']) ? '-' : $witness['email_legal'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Referente operativo</span><br />
				<span><?= empty($witness['operational_contact']) ? '-' : $witness['operational_contact'] ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Tel. fisso (referente operativo)</span><br />
				<span><?= empty($witness['telephone_operational']) ? '-' : $witness['telephone_operational'] ?></span>
			</div>
		</td>
	</tr>
	<tr>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">Cellulare (referente operativo)</span><br />
				<span><?= empty($witness['mobile_operational']) ? '-' : $witness['mobile_operational'] ?></span>
			</div>
		</td>
		<td>
			<div class="question-div-pdf">
				<span class="question-text-pdf">E-mail (referente operativo)</span><br />
				<span><?= empty($witness['email_operational']) ? '-' : $witness['email_operational'] ?></span>
			</div>
		</td>
	</tr>
<?php }?>
</table>
<?php if($interview) { ?>
	<br />
	<h2>SCHEDA CASO</h2>
	<p><?= $interview['description'] ?></p>   
	<?php foreach($interview['answers'] as $index => $section) { ?>
		<?= $this->Utils->printSectionSurvey($section, $index, '') ?>
	<?php } ?>
<?php } ?>
<?php if(!empty($history)) { ?>
	<br />
	<h2>STORICO CASO</h2>
	<ul>
	<?php foreach($history as $event) { ?>
		<li><?= $event['message'] ?></li>
	<?php } ?>
	</ul>
<?php } ?>