<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'Leads',
    ['path' => '/leads'],
    function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    }
);

Router::prefix('admin', function ($routes) {
    $routes->plugin('Leads', function ($routes) {
        $routes->connect('/:controller');
        $routes->fallbacks();
    });
});