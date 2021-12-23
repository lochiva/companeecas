<?php
use Cake\Routing\Router;
?>
<!--Inclusioni-->
<?php echo $this->Html->css('Document.document'); ?>
<?php echo $this->Html->css('Document.jqtree'); ?>
<?php echo $this->Html->css('Document.themes/proton/style.min'); ?>

<?php //echo $this->Html->script('Document.jquery-2.1.4.min'); ?>
<?php echo $this->Html->script('Document.tinymce/jquery.tinymce.min');?>
<?php echo $this->Html->script('Document.tinymce'); ?>

<?php echo $this->Html->script('Document.jquery.tree'); ?>
<?php echo $this->Html->script('Document.jstree.min'); ?>

<?php //echo "<pre>"; print_r($parent); echo "</pre>"; ?>

<script>
    var basePath = "<?=Router::url('/')?>";

    var imgView = '<?php echo $this->Html->image('Document.view.png', ['alt' => 'Apri', 'style' => 'height:25px;']); ?>';
    var imgEdit = '<?php echo $this->Html->image('Document.edit.png', ['alt' => 'Modifica', 'style' => 'height:25px;']); ?>';
    var imgShow = '<?php echo $this->Html->image('Document.mostra.png', ['alt' => 'Mostra figli', 'style' => 'height:25px;']); ?>';
    var imgHide = '<?php echo $this->Html->image('Document.hide.png', ['alt' => 'Nascondi figli', 'style' => 'height:25px;']); ?>';
    var imgAdd = '<?php echo $this->Html->image('Document.add.png', ['alt' => 'Aggiungi figlio', 'style' => 'height:25px;']); ?>';
    var imgDelete = '<?php echo $this->Html->image('Document.delete.png', ['alt' => 'Elimina', 'style' => 'height:25px;']); ?>';
    /*
    var data = [
        {
            label: 'node1',
            children: [
                { label: 'child1' },
                { label: 'child2' }
            ]
        },
        {
            label: 'node2',
            children: [
                { label: 'child3' }
            ]
        }
    ];
    */
    var data = [];
    var parent = [];
    var idNode = "";
    var editedDoc = <?= (!empty($editedDoc)? $editedDoc:0) ?>;
    <?php if(isset($gerarchia) && isset($id)){ ?>
        data = <?php echo json_encode($gerarchia); ?>;
        idNode = <?php echo $id; ?>;
    <?php } ?>
    <?php if(isset($parent)){ ?>
        parent = <?php echo json_encode($parent); ?>;
    <?php } ?>
</script>
<?php echo $this->Html->script('Document.document'); ?>
