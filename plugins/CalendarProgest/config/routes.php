<?php
use Cake\Routing\Router;

Router::plugin('Calendar', function ($routes) {
	$routes->connect('/',['controller' => 'Home' , 'action' => 'index']);
    $routes->fallbacks('DashedRoute');
});
