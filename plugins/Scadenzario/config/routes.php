<?php
use Cake\Routing\Router;

Router::plugin('Scadenzario', function ($routes) {
    $routes->connect('/',['controller' => 'Home' , 'action' => 'index']);
    $routes->fallbacks('DashedRoute');
});
