<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->GET('/', 'Home::index');

$routes->GET('profile', 'Profile::index');



// Auth
// $routes->GET('/auth/register', 'Auth::register');
$routes->match(['GET', 'POST'], '/auth/register', 'Auth::register');
// $routes->POST('/auth/prosesregister', 'Auth::register');
//$routes->GET('/auth/login', 'Auth::login');
// $routes->POST('/auth/login', 'Auth::login');
$routes->match(['GET', 'POST'], '/auth/login', 'Auth::login');
$routes->GET('/auth/logout', 'Auth::logout');

// User Dashboard
// $routes->match(['GET', 'POST'], 'user/dashboard', 'User::dashboard');
$routes->GET('user/dashboard', 'User::dashboard');
$routes->GET('user/registerEvent/(:num)', 'User::registerEvent/$1');
$routes->GET('user/printCertificate/(:num)', 'User::printCertificate/$1');

// Events
$routes->GET('/events', 'Events::index');
$routes->GET('/events/detail/(:num)', 'Events::detail/$1');
$routes->GET('/events/register/(:num)', 'Events::register/$1');
$routes->GET('/events/cancel/(:num)', 'Events::cancel/$1');

$routes->group('admin', static function ($routes) {
    $routes->GET('dashboard', 'Admin::dashboard');
    $routes->POST('create', 'Admin::createUser');
    $routes->GET('edit/(:num)', 'Admin::editUser/$1');
    $routes->POST('edit/(:num)', 'Admin::editUser/$1');
    $routes->delete('delete/(:num)', 'Admin::deleteUser/$1');
});

$routes->GET('/coordinator/dashboard', 'Coordinator::dashboard');

$routes->GET('/organizer/dashboard', 'Organizer::dashboard');












