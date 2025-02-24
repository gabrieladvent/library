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

    $routes->post('edit', 'UserController::editUser');
    $routes->get('delete', 'UserController::deleteUser');

    $routes->get('all-user', 'UserController::getAllUsers');
    $routes->get('class', 'UserController::getDataClassUser');
});

$routes->group("book", function ($routes) {
    $routes->get('dashboard', 'BookController::index');
    $routes->get('detail/(:segment)', 'BookController::viewDetailBook/$1');

    $routes->post('add', 'BookController::addBook');
    $routes->post('edit', 'BookController::editBook');
    $routes->get('delete', 'BookController::deleteBook');

    $routes->get('all-books', 'BookController::getAllBooks');
    $routes->get('available', 'BookController::getDataBooks');
});

$routes->group("class", ['filter' => 'auth'], function ($routes) {
    $routes->get('all', 'UserController::getAllClasses');
    $routes->post('add', 'UserController::addClass');
    $routes->post('edit', 'UserController::editClass');
    $routes->get('delete', 'UserController::deleteClass');
    $routes->get('list', 'UserController::viewDetailClass');
});

$routes->group("category", ['filter' => 'auth'], function ($routes) {
    $routes->get('all', 'BookController::getAllCategories');
    $routes->get('list', 'BookController::viewDetailCategory');
    $routes->post('add', 'BookController::addCategory');
    $routes->post('edit', 'BookController::editCategory');
    $routes->get('delete', 'BookController::deleteCategory');
});

$routes->group("loans", ['filter' => 'auth'], function ($routes) {
    $routes->get('list', 'LoansController::viewLoans');
    $routes->post('add', 'LoansController::addLoans');
    $routes->post('edit', 'LoansController::editLoans');
    $routes->get('delete', 'LoansController::deleteLoans');
    $routes->get('report', 'LoansController::reportLoans');
});


$routes->set404Override(function () {
    return view("errors/html/errorpage404");
});
