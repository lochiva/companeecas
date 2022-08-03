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

?>

<div class="form-group">

    <?= $this->Form->label('period_start_date', 'Inizio', ['class' => 'col-sm-2 control-label required']); ?>

    <div class="col-sm-10">
        <?php echo $this->Form->text('period_start_date', [
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
        <?php echo $this->Form->text('period_end_date', [
            'type' => 'date',
            'required' => true,
            'class' => 'form-control',
            'readonly' => true
        ]); ?>
    </div>

</div>

<?php if (count($companies) > 1) : ?>
    <?= $this->Form->control('companies.0.id', [
        'type' => 'select',
        'multiple' => false,
        'options' => $companies,
        'empty' => "Selezionare un'azienda",
        'value' => '',
        'disabled' => [''],
        'label' => ['text' => 'Report di', 'class' => 'col-sm-2 control-label required']
    ]); ?>

    <?= $this->Form->hidden('companies.0.id'); ?>

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

<?php else : ?>
    <?= $this->Form->hidden('companies.0.id'); ?>
    <?= $this->Form->hidden('companies.0.company_id'); ?>

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

                <?php echo $this->Form->text('companies.0.billing_date', [
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

