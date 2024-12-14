<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->post('login/proses', 'Home::login_process');

$routes->group("home", ['filter' => 'auth'], function ($routes) {

    $routes->get('dashboard', 'Home::Dashboard');




    
    $routes->get('logout', 'Home::logout');
});
