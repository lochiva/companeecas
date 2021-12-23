<?php
use Cake\Routing\Router;

Router::plugin('ReminderManager', function ($routes) {
    $routes->fallbacks('InflectedRoute');
});
