<?php
use Cake\Routing\Router;

Router::plugin('Crm', function ($routes) {
    $routes->connect('/',['controller' => 'Home' , 'action' => 'index']);
    $routes->fallbacks('DashedRoute');
    $routes->prefix('ws', function ($routes) {
        $routes->fallbacks('DashedRoute');
    });
});
Router::prefix('admin', function ($routes) {
    $routes->plugin('Crm', function ($routes) {
        $routes->connect('/',['controller' => 'OffersStatus' , 'action' => 'index']);
        $routes->connect('/home/index',['controller' => 'OffersStatus' , 'action' => 'index']);
        $routes->connect('/:controller');
        $routes->fallbacks();
    });

});
