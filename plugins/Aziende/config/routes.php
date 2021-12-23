<?php
use Cake\Routing\Router;

Router::plugin('Aziende', function ($routes) {
    $routes->connect('/',['controller' => 'Home' , 'action' => 'index']);
    $routes->fallbacks('DashedRoute');
});
Router::prefix('admin', function ($routes) {
    $routes->plugin('Aziende', function ($routes) {
        $routes->connect('/',['controller' => 'Aziende' , 'action' => 'index']);
        $routes->connect('/home/index',['controller' => 'Aziende' , 'action' => 'index']);
        $routes->connect('/:controller');
        $routes->fallbacks();
    });

});
