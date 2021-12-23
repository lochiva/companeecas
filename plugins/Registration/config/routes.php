<?php
use Cake\Routing\Router;

Router::plugin('Registration', function ($routes) {
    $routes->fallbacks('DashedRoute');
});

Router::prefix('admin', function ($routes) {
    $routes->plugin('Registration', function ($routes) {
        $routes->connect('/',['controller' => 'Users' , 'action' => 'index']);
        $routes->connect('/home/index',['controller' => 'Users' , 'action' => 'index']);
        $routes->connect('/:controller');
        $routes->fallbacks();
    });
    
});
