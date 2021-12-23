<?php
use Cake\Core\Configure;
use Cake\Routing\Router;

if(empty($this->Session->read('Auth.User'))){
  $this->layout = 'login';
}
//echo "<pre>",var_dump($this->response->statusCode());
if (Configure::read('debug')):
    $this->layout = 'dev_error';

    $this->assign('title', $message);
    $this->assign('templateName', 'error400.ctp');

    $this->start('file');
?>
<?php if (!empty($error->queryString)) : ?>
    <p class="notice">
        <strong>SQL Query: </strong>
        <?= h($error->queryString) ?>
    </p>
<?php endif; ?>
<?php if (!empty($error->params)) : ?>
        <strong>SQL Query Params: </strong>
        <?= Debugger::dump($error->params) ?>
<?php endif; ?>
<?= $this->element('auto_table_warning') ?>
<?php
    if (extension_loaded('xdebug')):
        xdebug_print_function_stack();
    endif;

    $this->end();
endif;
?>
<!--<h2><?= h($message) ?></h2>
<p class="error">
    <strong><?= __d('cake', 'Error') ?>: </strong>
    <?= sprintf(
        __d('cake', 'The requested address %s was not found on this server.'),
        "<strong>'{$url}'</strong>"
    ) ?>
</p>-->



 <!-- Main content -->
        <section class="content">
          <div class="error-page">
            <h2 class="headline text-yellow"> <?= $this->response->statusCode() ?></h2>
            <div class="error-content">
              <?php if($this->response->statusCode()==403): ?>
                <h3><i class="fa fa-warning text-yellow"></i> Oops! <?=__('You are not authorized to access that location.')?></h3>
              <?php else:?>
                <h3><i class="fa fa-warning text-yellow"></i> Oops! <?=__('Page not found.')?></h3>
              <?php endif ?>
              <p>
                We could not find the page you were looking for.
                Meanwhile, you may <a href="<?= Router::url(['action' => 'index','controller' => 'Home']) ?>">return to home</a> or try using the search form.
              </p>
              <form class="search-form">
                <div class="input-group">
                  <input type="text" name="search" class="form-control" placeholder="Search">
                  <div class="input-group-btn">
                    <button type="submit" name="submit" class="btn btn-warning btn-flat"><i class="fa fa-search"></i></button>
                  </div>
                </div><!-- /.input-group -->
              </form>
            </div><!-- /.error-content -->
          </div><!-- /.error-page -->
        </section><!-- /.content -->
