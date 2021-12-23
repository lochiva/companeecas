<hr>
<?= $this->Form->input('nome') ?>
<?= $this->Form->input('cognome') ?>
<?= $this->Form->input('data_nascita',['label' => 'Data di nascita', 'type' => 'date', 'minYear' => 1900, 'maxYear' => date('Y')]) ?>
<?= $this->Form->input('cf', ['type' => 'text', 'label' => ['text' => __("Codice fiscale")]]) ?>
