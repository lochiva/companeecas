<?php
/**
* Aziende is a plugin for manage attachment
*
* Companee :    statement form  (https://www.companee.it)
* Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* 
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) IRES Piemonte , (https://www.ires.piemonte.it/)
* @link          https://www.ires.piemonte.it/ 
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
use Cake\Routing\Router;
?>

    <?= $this->Form->hidden('id'); ?>

    <div class="form-group">

    
    <?php echo $this->Form->control('period_id', [
        'type' => 'select',
        'multiple' => false,
        'options' => $periods,
        'empty' => 'Selezionare un periodo',
        'required' => true,
        'disabled' => true,
        'label' => ['text' => 'Periodo', 'class' => 'control-label required'],
    ]);

    echo $this->Form->control('period_label', [
        'type' => 'text',
        'required' => true,
        'readonly' => true,
        'label' => ['text' => 'Etichetta Periodo', 'class' => 'control-label required']
    ]);

    ?>

    </div>

    <div class="form-group">
        <div class="col-md-6">

            <?= $this->Form->label('period_start_date', 'Inizio', ['class' => 'control-label required']); ?>

            <?php echo $this->Form->text('period_start_date', [
                'type' => 'date',
                'required' => true,
                'class' => 'form-control',
                'readonly' => true
            ]); ?>

        </div>

        <div class="col-md-6">

            <?= $this->Form->label('stetement.period_end_date', 'Fine', ['class' => 'control-label required']); ?>

            <?php echo $this->Form->text('period_end_date', [
                'type' => 'date',
                'required' => true,
                'class' => 'form-control',
                'readonly' => true
            ]); ?>

        </div>
    </div>

<?php if ($ati) : ?>

        <?= $this->Form->hidden('companies.0.id'); ?>

        <?= $this->Form->hidden('companies.0.company_id'); ?>

        <div class="" id="company_specific">

            <div class="form-group">

                <?= $this->Form->control('companies.0.billing_reference', [
                        'type' => 'text',
                        'required' => true,
                        'label' => ['text' => 'Numero Fattura', 'class' => 'control-label required'],
                    ]); 
                ?>
            
                <div class="col-md-6">
                    <?= $this->Form->label('companies.0.billing_date', 'Data fattura', ['class' => 'control-label required']); ?>

                    <?= $this->Form->text('companies.0.billing_date', [
                        'type' => 'date',
                        'required' => true,
                        'class' => 'form-control'
                    ]); ?>
                </div>

            </div>

        <div class="form-group">
            <?php
                echo $this->Form->control('companies.0.billing_net_amount', [
                    'type' => 'number',
                    'required' => true,
                    'label' => ['text' => 'Importo netto', 'class' => 'control-label required'],
                ]);

                echo $this->Form->control('companies.0.billing_vat_amount', [
                    'type' => 'number',
                    'required' => true,
                    'label' => ['text' => 'Importo iva', 'class' => 'control-label required'],
                ]);

            ?>
        </div>

        <div class="form-group">
            <div class="col-md-6">
                <label class="control-label">File Fattura</label>
                <div class="d-flex">
                    <input type="file" name="file" class="form-control">
                    <a id="file_upload" class="btn btn-info" href="#" target="_blank" style="margin-left: 10px;">Download</a>
                </div>
            </div>
            <div class="col-md-6">
                <label class="control-label">Dichiarazione sostitutiva conformità documenti agli atti</label>
                <div class="d-flex">
                    <input type="file" name="file_compliance" class="form-control">
                    <a id="file_compliance_upload" class="btn btn-info" href="#" target="_blank" style="margin-left: 10px;">Download</a>
                </div>
            </div>
        </div>


    </div>

<?php else : ?>
    <?= $this->Form->hidden('companies.0.id'); ?>
    <?= $this->Form->hidden('companies.0.company_id'); ?>

    <div>

        <div class="form-group">
            <?php
                echo $this->Form->control('companies.0.billing_reference', [
                    'type' => 'text',
                    'required' => true,
                    'label' => ['text' => 'Numero Fattura', 'class' => 'control-label required'],
                ]); 
            ?>
        
            <div class="col-md-6">

            <?= $this->Form->label('companies.0.billing_date', 'Data fattura', ['class' => 'control-label required']); ?>

                <?php echo $this->Form->text('companies.0.billing_date', [
                    'type' => 'date',
                    'required' => true,
                    'class' => 'form-control'
                ]); ?>

            </div>
        </div>
        
        <div class="form-group">
        <?php
            echo $this->Form->control('companies.0.billing_net_amount', [
                'type' => 'number',
                'required' => true,
                'label' => ['text' => 'Importo netto', 'class' => 'control-label required'],
            ]);

            echo $this->Form->control('companies.0.billing_vat_amount', [
                'type' => 'number',
                'required' => true,
                'label' => ['text' => 'Importo iva', 'class' => 'control-label required'],
            ]);
        ?>
        </div>

        <div class="form-group">

            <div class="col-sm-6">
                <label class="control-label">File Fattura</label>
                <div class="d-flex">
                    <input type="file" name="file" class="form-control">
                    <?php if ($statement->companies[0]->uploaded_path) : ?>
                        <a id="file_upload" class="btn btn-info" href="<?= Router::url(['plugin' => 'Aziende', 'controller' => 'Ws', 'action' => 'downloadFileStatements', 'invoice',$statement->companies[0]->id ]) ?>" target="_blank" style="margin-left: 10px;">Download</a>
                    <?php endif ?>
                </div>
            </div>


            <div class="col-sm-6">
                <label class="control-label">Dichiarazione sostitutiva conformità documenti agli atti</label>
                <div class="d-flex">
                    <input type="file" name="file_compliance" class="form-control">
                    <?php if ($statement->companies[0]->compliance) : ?>
                        <a id="file_compliance_upload" class="btn btn-info" href="<?= Router::url(['plugin' => 'Aziende', 'controller' => 'Ws', 'action' => 'downloadFileStatements', 'compliance', $statement->companies[0]->id ]) ?>" target="_blank" style="margin-left: 10px;">Download</a>
                    <?php endif ?>
                </div>
            </div>

        </div>

    </div>

<?php endif ?>

