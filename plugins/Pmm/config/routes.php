<?php
use Cake\Routing\Router;
use Cake\Routing\Route\InflectedRoute;

Router::plugin('Pmm', function ($routes) {
	$routes->connect('/',['controller' => 'Home' , 'action' => 'index']);
    $routes->fallbacks('InflectedRoute');
});
