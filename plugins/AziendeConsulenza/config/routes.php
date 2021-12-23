<?php
use Cake\Routing\Router;

Router::plugin('Aziende', function ($routes) {
    $routes->connect('/',['controller' => 'Home' , 'action' => 'index']);
    $routes->fallbacks('DashedRoute');
});
