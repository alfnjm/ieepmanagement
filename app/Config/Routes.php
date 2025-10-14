<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->GET('/', 'Home::index');

$routes->GET('profile', 'Profile::index');

<<<<<<< HEAD
// Ensure NO leading slash here
$routes->match(['GET', 'POST'], 'auth/register', 'Auth::register'); 

// Ensure NO leading slash here
$routes->match(['GET', 'POST'], 'auth/login', 'Auth::login');

// Ensure NO leading slash here
$routes->GET('auth/logout', 'Auth::logout');


// User Dashboard
=======


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
>>>>>>> 272b757889987ba1722b44220c478f3eaebe9140
$routes->GET('user/dashboard', 'User::dashboard');
$routes->GET('user/registerEvent/(:num)', 'User::registerEvent/$1');
$routes->GET('user/printCertificate/(:num)', 'User::printCertificate/$1');

<<<<<<< HEAD
// Events: REMOVED leading slashes for consistency
// Original: $routes->GET('/events', 'Events::index');
$routes->GET('events', 'Events::index');
// Original: $routes->GET('/events/detail/(:num)', 'Events::detail/$1');
$routes->GET('events/detail/(:num)', 'Events::detail/$1');
// Original: $routes->GET('/events/register/(:num)', 'Events::register/$1');
$routes->GET('events/register/(:num)', 'Events::register/$1');
// Original: $routes->GET('/events/cancel/(:num)', 'Events::cancel/$1');
$routes->GET('events/cancel/(:num)', 'Events::cancel/$1');

=======
// Events
$routes->GET('/events', 'Events::index');
$routes->GET('/events/detail/(:num)', 'Events::detail/$1');
$routes->GET('/events/register/(:num)', 'Events::register/$1');
$routes->GET('/events/cancel/(:num)', 'Events::cancel/$1');
>>>>>>> 272b757889987ba1722b44220c478f3eaebe9140

$routes->group('admin', static function ($routes) {
    $routes->GET('dashboard', 'Admin::dashboard');
    $routes->POST('create', 'Admin::createUser');
    $routes->GET('edit/(:num)', 'Admin::editUser/$1');
    $routes->POST('edit/(:num)', 'Admin::editUser/$1');
    $routes->delete('delete/(:num)', 'Admin::deleteUser/$1');
});

<<<<<<< HEAD
$routes->GET('coordinator/dashboard', 'Coordinator::dashboard');
$routes->get('coordinator/proposals', 'Coordinator::proposals');
$routes->get('coordinator/registration', 'Coordinator::registrationControl');
$routes->get('coordinator/upcoming', 'Coordinator::upcomingEvents');
$routes->get('coordinator/approvals', 'Coordinator::approvals');
$routes->get('coordinator/approve/(:num)', 'Coordinator::approve/$1');
$routes->get('coordinator/reject/(:num)', 'Coordinator::reject/$1');
$routes->get('coordinator/approveProposal/(:num)', 'Coordinator::approveProposal/$1');
$routes->get('coordinator/rejectProposal/(:num)', 'Coordinator::rejectProposal/$1');


$routes->GET('organizer/dashboard', 'Organizer::dashboard');
$routes->get('organizer/create-event', 'Organizer::createEvent');
$routes->get('organizer/my-proposals', 'Organizer::myProposals');
$routes->get('organizer/participants', 'Organizer::participants');
$routes->get('organizer/certificates', 'Organizer::certificates');
$routes->get('organizer/attendance', 'Organizer::attendance');
$routes->post('organizer/submitProposal', 'Organizer::submitProposal');
=======
$routes->GET('/coordinator/dashboard', 'Coordinator::dashboard');

$routes->GET('/organizer/dashboard', 'Organizer::dashboard');












>>>>>>> 272b757889987ba1722b44220c478f3eaebe9140
