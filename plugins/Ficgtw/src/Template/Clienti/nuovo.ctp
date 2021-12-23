<?php
use Cake\Routing\Router;
use Cake\Core\Configure;

echo $this->Element('Document.include');

//echo "<pre>"; print_r($dcn); echo "</pre>";
//echo "<pre>"; print_r($drn); echo "</pre>";
//echo "<pre>"; print_r($documents); echo "</pre>";
echo $this->Html->script('Document.home-tree');
?>

<section class="content-header">
    <h1>
        Fatture In Cloud
        <small>inserisci cliente</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
<!-- Small boxes (Stat box) -->
    <div class="box box-info">

    </div>
</section>
