<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->post('login/proses', 'Home::login_process');

$routes->group("home", ['filter' => 'auth'], function ($routes) {
    $routes->get('user', function () {
        return service('response')->setJSON([
            'status' => true,
            'message' => 'Can Access'
        ])->setStatusCode(200);
    });
    
    $routes->get('profile', 'Home::profile');
    $routes->get('logout', 'Home::logout');

});
