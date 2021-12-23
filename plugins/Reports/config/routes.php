<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'Reports',
    ['path' => '/reports'],
    function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    }
);

Router::prefix('admin', function ($routes) {
    $routes->plugin('Reports', function ($routes) {
        $routes->connect('/:controller');
        $routes->fallbacks();
    });
});