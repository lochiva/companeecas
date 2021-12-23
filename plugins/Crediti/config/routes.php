<?php
use Cake\Routing\Router;

Router::plugin('Crediti', function ($routes) {
    $routes->connect('/',['controller' => 'Home' , 'action' => 'index']);
    $routes->fallbacks('DashedRoute');
});
