<?php
/**
* Surveys is a plugin for manage attachment
*
* Companee :    Routes (https://www.companee.it)
* Copyright (c) lochiva , (http://www.lochiva.it)
*
* Licensed under The GPL  License
* For full copyright and license information, please see the LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @copyright     Copyright (c) LOCHIVA , (https://www.lochiva.com)
* @link          https://www.companee.it Companee project
* @since         1.2.0
* @license       https://www.gnu.org/licenses/gpl-3.0.html GPL 3
*/
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