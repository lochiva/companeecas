<?php
use Cake\Routing\Router;

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

<?php if ($ati) : ?>
    <?= $this->Form->control('companies.0.id', [
        'type' => 'select',
        'multiple' => false,
        'options' => $companies,
        'empty' => "Selezionare un'azienda",
        'value' => '',
        'disabled' => [''],
        'label' => ['text' => 'Report di', 'class' => 'col-sm-2 control-label required']
    ]); ?>

    <?= $this->Form->hidden('companies.0.company_id'); ?>

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

    ?>

        <div class="form-group">
            <label class="col-sm-2 control-label">File Fattura</label> 
            <div class="col-sm-2" id="file_upload"> 
                <a id="box-general-action" class="btn btn-info" href="<?= Router::url(['plugin' => 'Aziende', 'controller' => 'Ws', 'action' => 'downloadFileStatements', $statement->companies[0]->id ]) ?>" target="_blank">Download</a>
            </div>
            <div class="col-sm-3"> 
                <input type="file" name="file" class="form-control"> 
            </div>
        </div>


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
    ?>

    <?php if ($statement->companies[0]->uploaded_path) : ?>

        <div class="form-group">
            <label class="col-sm-2 control-label">File Fattura</label> 
            <div class="col-sm-2"> 
                <a id="box-general-action" class="btn btn-info" href="<?= Router::url(['plugin' => 'Aziende', 'controller' => 'Ws', 'action' => 'downloadFileStatements', $statement->companies[0]->id ]) ?>" target="_blank">Download</a>
            </div>
            <div class="col-sm-3"> 
                <input type="file" name="file" class="form-control"> 
            </div>
        </div>

    <?php else : ?>

        <?= $this->Form->control('file', [
            'type' => 'file',
            'label' => ['text' => 'Upload File fattura', 'class' => 'col-sm-2 control-label required'],
        ]); ?>

    <? endif ?>


    

    </div>

<?php endif ?>

