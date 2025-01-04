<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->post('login/proses', 'Home::login_process');

$routes->group("home", ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Home::Dashboard');
    $routes->get('list', 'userController::listUser');
    $routes->get('logout', 'Home::logout');
});

$routes->group("user", ['filter' => 'auth'], function ($routes) {
    $routes->get('profile', 'UserController::index');
    // $routes->get('list', 'userController::listUser');
    $routes->get('detail', 'userController::viewDetailUser');

    $routes->post('add', 'userController::addUser');
    $routes->get('list/(:any)', 'userController::listUser/$1');
    $routes->post('edit/(:any)', 'userController::editUser/$1');
    $routes->get('delete/(:any)', 'userController::deleteUser/$1');
});

$routes->group("book", function ($routes) {
    $routes->get('dashboard', 'BookController::index');
    $routes->get('detail/(:any)', 'BookController::viewDetailBook/$1');

    $routes->post('add', 'BookController::addBook');
    $routes->post('edit/(:any)', 'BookController::editBook/$1');
    $routes->get('delete/(:any)', 'BookController::deleteBook/$1');
});

$routes->group("loans", ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Admin::LoansController');
});

$routes->group("class", ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Admin::userController');
});

$routes->group("category", ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Admin::userController');
});
