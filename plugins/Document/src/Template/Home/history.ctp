<?php
/**
* Document is a plugin for manage attachment
*
* Companee :    History  (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
use Cake\Routing\Router;
use Cake\Core\Configure;

echo $this->Element('include');

?>




<section class="content-header">
    <h1>
        Gestione documentale
        <small>Revisioni</small>
    </h1>
    <ol class="breadcrumb">
        <li><a href="<?=Router::url('/');?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="<?=Router::url('/document/home');?>">Documenti</a></li>
        <li class="active">Revisioni</li>
    </ol>
</section>


<!-- Main content -->
<section class="content">
<!-- Small boxes (Stat box) -->
  <div class="row">
    <div class="col-md-12" >
         <div class="box box-warning">
            
            <div class="box-header with-border">
                <i class="fa fa-edit"></i><h3 class="box-title">Revisioni</h3>
            </div>
            <div class="box-body">
                <?php $back = 0; ?>
                <?php foreach ($revisions as $key => $rev) { ?>
                    
                    <?php
                    
                        $action = "view_rev";
                        $num = $key +1;
                        $id = $rev->id;
                        
                        if($back == 0){
                            $back = $rev->id;
                        }
                        
                        if($rev->created != ""){
                            $dal = $rev->created;
                        }else{
                            $dal = "<b>Sconosciuto</b>";
                        }
                        
                        if($rev->modified != ""){
                            if($rev->last_saved == 1){
                                $al = "<b>Attuale</b>";
                                $action = "edit";
                            }else{
                                $al = $rev->modified;
                            }
                            
                        }else{
                            $al = "<b>Sconosciuto</b>";
                        }
                        
                        
                    ?>
                    
                    <p><a href="<?=Router::url('/document/home/' . $action . '/' . $id)?>"><?php echo $num . ". Documento valido dal " . $dal . " al " . $al; ?></a></p>
                    
                <?php } ?>
            </div>
           
            
        </div>
    </div>
    <div class="col-md-12" >
        <a class="button" href="<?=Router::url('/document/home/edit/' . $back)?>">Torna indietro</a>
    </div>
  </div>
</section>

