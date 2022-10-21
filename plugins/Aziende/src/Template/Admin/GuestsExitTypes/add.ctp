<nav class="col-lg-12 col-md-12 columns" id="actions-sidebar">
    <ul class="nav nav-tabs">
        <li class="active" ><a href="#"><?= __('Azioni') ?></a></li>
        <li><?= $this->Html->link(__('Lista Tipologie Uscite Ospiti'), ['action' => 'index']) ?></li>
    </ul>
</nav>
<div class="form col-lg-9 col-md-8 columns content">
    <?= $this->Form->create($guestsExitType,['class' => 'admin-form']) ?>
    <fieldset>
        <legend><?= __('Nuovo Tipologia Uscita Ospite') ?></legend>
        <?php
            echo $this->Form->input('name');
            echo '<div class="input checkbox">';
            echo $this->Form->label('required_request', 'Necessaria Richiesta');
            echo $this->Form->checkbox('required_request', ['class' => 'input-checkbox']);
            echo '</div>';
            echo '<div class="input checkbox">';
            echo $this->Form->label('required_request_file', 'Necessario Documento Per Richiesta');
            echo $this->Form->checkbox('required_request_file', ['class' => 'input-checkbox']);
            echo '</div>';
            echo '<div class="input checkbox">';
            echo $this->Form->label('required_request_note', 'Necessarie Note Per Richiesta');
            echo $this->Form->checkbox('required_request_note', ['class' => 'input-checkbox']);
            echo '</div>';
            echo '<div class="input checkbox">';
            echo $this->Form->label('required_confirmation', 'Necessaria Conferma');
            echo $this->Form->checkbox('required_confirmation', ['class' => 'input-checkbox']);
            echo '</div>';
            echo '<div class="input checkbox">';
            echo $this->Form->label('required_file','Necessario Documento');
            echo $this->Form->checkbox('required_file', ['class' => 'input-checkbox']);
            echo '</div>';
            echo '<div class="input checkbox">';
            echo $this->Form->label('required_note','Necessarie Note');
            echo $this->Form->checkbox('required_note', ['class' => 'input-checkbox']);
            echo '</div>';
            echo '<div class="input checkbox">';
            echo $this->Form->label('startable_by_ente', 'Avviabile Da Ente');
            echo $this->Form->checkbox('startable_by_ente', ['class' => 'input-checkbox']);
            echo '</div>';
            echo '<div class="input checkbox">';
            echo $this->Form->label('toSAI','Destinazione SAI');
            echo $this->Form->checkbox('toSAI', ['class' => 'input-checkbox']);
            echo '</div>';
            echo $this->Form->label('ente_type', 'Tipologia Ente');
            echo $this->Form->select('ente_type', $tipi);
            echo $this->Form->input('ordering');
            echo $this->Form->label('modello_decreto', 'Modello decreto');
            echo $this->Form->select('mdodello_decreto', $surveys, ['empty' => 'Selezionare']);
            echo $this->Form->label('modello_notifica', 'Modello Notifica');
            echo $this->Form->select('modello_notifica', $surveys, ['empty' => 'Selezionare']);
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
