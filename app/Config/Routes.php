<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('profile', 'Profile::index');



// Auth
$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/register', 'Auth::register');
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/auth/logout', 'Auth::logout');

// User Dashboard
$routes->get('user/dashboard', 'User::dashboard');
$routes->get('user/registerEvent/(:num)', 'User::registerEvent/$1');
$routes->get('user/printCertificate/(:num)', 'User::printCertificate/$1');
 
// Events
$routes->get('/events', 'Events::index');
$routes->get('/events/detail/(:num)', 'Events::detail/$1');
$routes->get('/events/register/(:num)', 'Events::register/$1');
$routes->get('/events/cancel/(:num)', 'Events::cancel/$1');









