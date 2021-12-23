<?php
use Cake\Routing\Router;

Router::plugin('Progest', function ($routes) {
    $routes->connect('/',['controller' => 'Home' , 'action' => 'index']);
    $routes->fallbacks('DashedRoute');
    $routes->prefix('ws', function ($routes) {
        $routes->fallbacks('DashedRoute');
    });
});
Router::prefix('admin', function ($routes) {
    $routes->plugin('Progest', function ($routes) {
        //$routes->connect('/',['controller' => 'OffersStatus' , 'action' => 'index']);
        //$routes->connect('/home/index',['controller' => 'OffersStatus' , 'action' => 'index']);
        $routes->connect('/:controller');
        $routes->fallbacks();
    });
});

Router::scope('/', function ($routes) {
    $routes->connect('/', ['controller' => 'Home', 'action' => 'index' ,'plugin' => 'Progest']);
    $routes->connect('/home/index', ['controller' => 'Home', 'action' => 'index' ,'plugin' => 'Progest']);
});
