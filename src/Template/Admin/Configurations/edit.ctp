<?php
use Cake\Routing\Router;

$user = $this->request->session()->read('Auth.User');

if($user['level'] < 900){
    $opt = ['readonly' => true];
}else{
    $opt = [];
}

?>
<script>
    $(document).ready(function(){
        $('.delete-conf').click(function(e){

            if(!confirm("Si è sicuri di voler eliminare l'utente? L'operazione non sarà reversibile.")){
                e.preventDefault();
            }

        });
    });
</script>
<div>
    <h1><i class="glyphicon glyphicon-cog"></i> Gestione Configurazioni di sistema</h1>
    <h3>Da questa pagina è possibile gestire tutte le configurazioni di sistema.</h3>
</div>
<hr>
<div class="configurations form edit-config">
  <h3>Plugin: <?= ucfirst($configuration->plugin) ?></h3>
<?= $this->Form->create($configuration) ?>
    <fieldset>
        <?= $this->Form->input('key_conf',$opt) ?>
        <div style="clear:both"></div>
        <?= $this->Form->input('label',$opt) ?>
        <div style="clear:both"></div>
        <?= $this->Form->input('tooltip',$opt) ?>
        <div style="clear:both"></div>
        <?php if($configuration->value_type == 'checkbox'): ?>
          <label>Abilita</label>
        <?php endif ?>
        <?= $this->Form->input('value', ['type' => $configuration->value_type ,'maxYear' => '2040']) ?>
        <div style="clear:both"></div>

   </fieldset>
   <?php if($user['level'] > 900){ ?>
        <a class="btn btn-danger delete-conf" href="<?=Router::url('/admin/configurations/delete/' . $configuration->id)?>">Elimina</a>
    <?php } ?>
   <a class="btn btn-warning" href="<?=Router::url('/admin/configurations')?>">Indietro</a>
    <?= $this->Form->button(__('Salva'),['class' => 'btn btn-success']); ?>
    <?= $this->Form->end() ?>
</div>
