<?php
use Cake\Routing\Router;

Router::plugin('Consulenza', function ($routes) {
	$routes->connect('/',['controller' => 'Home' , 'action' => 'index']);
    $routes->fallbacks('DashedRoute');
});

