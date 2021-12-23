<?php
use Cake\Routing\Router;

Router::plugin('Document', function ($routes) {
    $routes->connect('/',['controller' => 'Home' , 'action' => 'index']);
    $routes->fallbacks('DashedRoute');
});
