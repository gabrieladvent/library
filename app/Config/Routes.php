<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->post('login/proses', 'Home::login_process');

$routes->group("api", ['filter' => 'auth'], function ($routes) {
    $routes->get('user', function () {
        return service('response')->setJSON([
            'status' => true,
            'message' => 'Can Access'
        ])->setStatusCode(200);
    });
    
    $routes->post('logout', 'Home::logout');
});
