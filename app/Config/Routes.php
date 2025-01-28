<?php

use CodeIgniter\Router\RouteCollection;

use function PHPUnit\Framework\returnValueMap;

/**
 * @var RouteCollection $routes
 */


$routes->get('/', 'Home::index');
$routes->post('login/proses', 'Home::login_process');

$routes->group("home", ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Home::Dashboard');
    $routes->get('list', 'UserController::listUser');
    $routes->get('logout', 'Home::logout');
});

$routes->group("user", function ($routes) {
    $routes->get('profile', 'UserController::index');
    $routes->get('list/(:any)', 'userController::listUser/$1');
    $routes->get('detail', 'userController::viewDetailUser');

    $routes->post('add', 'UserController::addUser');
    $routes->get('list/(:any)', 'UserController::listUser/$1');
    $routes->post('edit', 'UserController::editUser');
    $routes->get('delete', 'UserController::deleteUser');
});

$routes->group("book", function ($routes) {
    $routes->get('dashboard', 'BookController::index');
    $routes->get('detail/(:segment)', 'BookController::viewDetailBook/$1');

    $routes->post('add', 'BookController::addBook');
    $routes->post('edit', 'BookController::editBook');
    $routes->get('delete', 'BookController::deleteBook');
});

$routes->group("class", ['filter' => 'auth'], function ($routes) {
    $routes->get('all', 'UserController::getAllClasses');
    $routes->post('add', 'UserController::addClass');
    $routes->post('edit', 'UserController::editClass');
    $routes->get('delete', 'UserController::deleteClass');
});

$routes->group("category", ['filter' => 'auth'], function ($routes) {
    $routes->get('all', 'BookController::getAllCategories');
    $routes->post('add', 'BookController::addCategory');
    $routes->post('edit', 'BookController::editCategory');
    $routes->get('delete', 'BookController::deleteCategory');
});

$routes->group("loans", ['filter' => 'auth'], function ($routes) {
    $routes->get('dashboard', 'Admin::LoansController');
});


$routes->set404Override(function () {
    return view("errors/html/errorpage404");
});
