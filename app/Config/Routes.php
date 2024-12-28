<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index');
$routes->post('login/proses', 'Home::login_process');

$routes->group("home", ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Home::Dashboard');
    $routes->get('kategori', 'Home::Kategori');
    $routes->get('buku', 'Home::Buku');
    $routes->get('anggota', 'Home::Anggota');

    $routes->get('logout', 'Home::logout');
});

$routes->group("book", ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Admin::BookController');
});

$routes->group("user", function ($routes) {
    $routes->get('profile', 'UserController::index');
    $routes->get('list', 'userController::listUser');
    $routes->get('detail/(:any)', 'userController::viewDetailUser/$1');

    $routes->post('add', 'userController::addUser');
    $routes->post('edit/(:any)', 'userController::editUser/$1');
    $routes->get('delete/(:any)', 'userController::deleteUser/$1');
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
