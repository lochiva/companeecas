<?php
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;
use Cake\Routing\Route\DashedRoute;

Router::plugin(
    'Surveys',
    ['path' => '/surveys'],
    function (RouteBuilder $routes) {
        $routes->fallbacks(DashedRoute::class);
    }
);

Router::scope('/', function ($routes) {
    $routes->connect(
        '/surveys/ws/viewImage/**',
        ['plugin' => 'Surveys', 'controller' => 'Ws', 'action' => 'viewImage']
    );
    $routes->fallbacks(DashedRoute::class);
});


Router::prefix('admin', function ($routes) {
    $routes->plugin('Surveys', function ($routes) {
        $routes->connect('/',['controller' => 'Surveys' , 'action' => 'index']);
        $routes->connect('/home/index',['controller' => 'Surveys' , 'action' => 'index']);
        $routes->connect('/:controller');
        $routes->fallbacks();
    });

});