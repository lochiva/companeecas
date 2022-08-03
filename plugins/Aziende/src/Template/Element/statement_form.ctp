<<<<<<< HEAD
<?
    echo $this->Form->hidden('id');

    echo $this->Form->control('year', [
        'type' => 'number',
        'required' => true,
        'label' => ['text' => 'Anno', 'class' => 'col-sm-2 control-label required'],
    ]);

    echo $this->Form->control('period_id', [
        'type' => 'select',
        'multiple' => false,
        'options' => $periods,
        'empty' => 'Selezionare un periodo',
        'disabled' => [''],
        'required' => true,
        'label' => ['text' => 'Periodo', 'class' => 'col-sm-2 control-label required']
    ]);

    echo $this->Form->control('period_label', [
        'type' => 'text',
        'required' => true,
        'readonly' => true,
        'label' => ['text' => 'Etichetta Periodo', 'class' => 'col-sm-2 control-label required']
    ]);
=======
<?php
echo $this->Form->hidden('id');


echo $this->Form->control('year', [
    'type' => 'number',
    'required' => true,
    'label' => ['text' => 'Anno', 'class' => 'col-sm-2 control-label required'],
]);

echo $this->Form->control('period_id', [
    'type' => 'select',
    'multiple' => false,
    'options' => $periods,
    'empty' => 'Selezionare un periodo',
    'disabled' => [''],
    'required' => true,
    'label' => ['text' => 'Periodo', 'class' => 'col-sm-2 control-label required']
]);

echo $this->Form->control('period_label', [
    'type' => 'text',
    'required' => true,
    'readonly' => true,
    'label' => ['text' => 'Etichetta Periodo', 'class' => 'col-sm-2 control-label required']
]);
>>>>>>> 060540a6e6683b9d59852f0bc7d0ab8fe4d1bfdb

?>

<div class="form-group">

    <?= $this->Form->label('period_start_date', 'Inizio', ['class' => 'col-sm-2 control-label required']); ?>

    <div class="col-sm-10">
<<<<<<< HEAD
        <? echo $this->Form->text('period_start_date', [
=======

        <?php echo $this->Form->text('period_start_date', [
>>>>>>> 060540a6e6683b9d59852f0bc7d0ab8fe4d1bfdb
            'type' => 'date',
            'required' => true,
            'class' => 'form-control',
            'readonly' => true
        ]); ?>
    </div>

</div>

<div class="form-group">

    <?= $this->Form->label('stetement.period_end_date', 'Fine', ['class' => 'col-sm-2 control-label required']); ?>

    <div class="col-sm-10">
<<<<<<< HEAD
        <? echo $this->Form->text('period_end_date', [
=======

        <?php echo $this->Form->text('period_end_date', [
>>>>>>> 060540a6e6683b9d59852f0bc7d0ab8fe4d1bfdb
            'type' => 'date',
            'required' => true,
            'class' => 'form-control',
            'readonly' => true
        ]); ?>
    </div>

</div>

<<<<<<< HEAD
<?php if (count($companies) > 1) : ?>
    <?= $this->Form->control('companies.0.id', [
=======

<?php

if (count($companies) > 1) {
    echo $this->Form->control('statement.company.id', [
>>>>>>> 060540a6e6683b9d59852f0bc7d0ab8fe4d1bfdb
        'type' => 'select',
        'multiple' => false,
        'options' => $companies,
        'empty' => "Selezionare un'azienda",
        'value' => '',
        'disabled' => [''],
        'label' => ['text' => 'Report di', 'class' => 'col-sm-2 control-label required']
    ]); ?>

<<<<<<< HEAD
    <?= $this->Form->hidden('companies.0.id'); ?>
=======
    ]);
} else {
    echo $this->Form->hidden('statement.company.id');
}
?>
<div class="hidden" id="company_specific">

    <?php
>>>>>>> 060540a6e6683b9d59852f0bc7d0ab8fe4d1bfdb

    <div class="" id="company_specific">

    <?= $this->Form->control('companies.0.billing_reference', [
            'type' => 'text',
            'required' => true,
            'label' => ['text' => 'Riferimento Fattura', 'class' => 'col-sm-2 control-label required'],
        ]); 
    ?>
        
        <div class="form-group">

            <?= $this->Form->label('companies.0.billing_date', 'Data fattura', ['class' => 'col-sm-2 control-label required']); ?>

            <div class="col-sm-10">

                <?= $this->Form->text('companies.0.billing_date', [
                    'type' => 'date',
                    'required' => true,
                    'class' => 'form-control'
                ]); ?>
            </div>

<<<<<<< HEAD
=======
            <?php echo $this->Form->text('company.billing_date', [
                'type' => 'date',
                'required' => true,
                'class' => 'form-control'
            ]); ?>
>>>>>>> 060540a6e6683b9d59852f0bc7d0ab8fe4d1bfdb
        </div>

    <?php
        echo $this->Form->control('companies.0.billing_net_amount', [
            'type' => 'number',
            'required' => true,
            'label' => ['text' => 'Importo netto', 'class' => 'col-sm-2 control-label required'],
        ]);

        echo $this->Form->control('companies.0.billing_vat_amount', [
            'type' => 'number',
            'required' => true,
            'label' => ['text' => 'Importo iva', 'class' => 'col-sm-2 control-label required'],
        ]);

        echo $this->Form->hidden('companies.0.uploaded_path');

        echo $this->Form->control('file', [
            'type' => 'file',
            'required' => true,
            'disabled' => true,
            'label' => ['text' => 'Upload File fattura', 'class' => 'col-sm-2 control-label required'],
        ]);
    ?>

    </div>

<<<<<<< HEAD
<?php else : ?>
    <?= $this->Form->hidden('companies.0.id'); ?>
    <?= $this->Form->hidden('companies.0.company_id'); ?>
=======
    <?php
    echo $this->Form->control('company.billing_net_amount', [
        'type' => 'number',
        'required' => true,
        'label' => ['text' => 'Importo netto', 'class' => 'col-sm-2 control-label required'],
    ]);
>>>>>>> 060540a6e6683b9d59852f0bc7d0ab8fe4d1bfdb

    <div>

        <?php
            echo $this->Form->control('companies.0.billing_reference', [
                'type' => 'text',
                'required' => true,
                'label' => ['text' => 'Riferimento Fattura', 'class' => 'col-sm-2 control-label required'],
            ]); 
        ?>
        
        <div class="form-group">

        <?= $this->Form->label('companies.0.billing_date', 'Data fattura', ['class' => 'col-sm-2 control-label required']); ?>

            <div class="col-sm-10">

                <? echo $this->Form->text('companies.0.billing_date', [
                    'type' => 'date',
                    'required' => true,
                    'class' => 'form-control'
                ]); ?>
            </div>

        </div>

    <?
        echo $this->Form->control('companies.0.billing_net_amount', [
            'type' => 'number',
            'required' => true,
            'label' => ['text' => 'Importo netto', 'class' => 'col-sm-2 control-label required'],
        ]);

        echo $this->Form->control('companies.0.billing_vat_amount', [
            'type' => 'number',
            'required' => true,
            'label' => ['text' => 'Importo iva', 'class' => 'col-sm-2 control-label required'],
        ]);

        echo $this->Form->hidden('companies.0.uploaded_path');

        echo $this->Form->control('file', [
            'type' => 'file',
            'required' => true,
            'disabled' => true,
            'label' => ['text' => 'Upload File fattura', 'class' => 'col-sm-2 control-label required'],
        ]);
    ?>

    </div>

<?php endif ?>

