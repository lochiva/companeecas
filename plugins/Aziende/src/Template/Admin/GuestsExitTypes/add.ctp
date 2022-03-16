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
            echo $this->Form->label('Richiesta Conferma');
            echo $this->Form->checkbox('required_confirmation', ['class' => 'input-checkbox']);
            echo '</div>';
            echo '<div class="input checkbox">';
            echo $this->Form->label('Richieste Note');
            echo $this->Form->checkbox('required_note', ['class' => 'input-checkbox']);
            echo '</div>';
            echo $this->Form->input('ordering');
        ?>
    </fieldset>
    <?= $this->Form->button(__('Save'),['class' => 'btn btn-success']) ?>
    <?= $this->Form->end() ?>
</div>
