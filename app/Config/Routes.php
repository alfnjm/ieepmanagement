<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->GET('/', 'Home::index');

$routes->GET('profile', 'Profile::index');

// Ensure NO leading slash here
$routes->match(['GET', 'POST'], 'auth/register', 'Auth::register'); 

// Ensure NO leading slash here
$routes->match(['GET', 'POST'], 'auth/login', 'Auth::login');

// Ensure NO leading slash here
$routes->GET('auth/logout', 'Auth::logout');


// User Dashboard
// $routes->GET('user/dashboard', 'User::dashboard');
// In app/Config/Routes.php
// $routes->get('profile', 'Profile::index');
// $routes->post('updateProfile', 'User::updateProfile');
// $routes->GET('user/registerEvent/(:num)', 'User::registerEvent/$1');
// $routes->GET('user/printCertificate/(:num)', 'User::printCertificate/$1');

$routes->group('user', function ($routes) {
    // 1. Dashboard: Maps GET /user/dashboard -> User::dashboard()
    $routes->get('dashboard', 'User::dashboard');
    // 2. PROFILE EDIT (The Fix): Maps GET /user/profile -> User::editProfile()
    // NOTE: This assumes you have created the User::editProfile() method.
    $routes->get('profile', 'User::editProfile');
    // 3. PROFILE UPDATE: Maps POST /user/updateProfile -> User::updateProfile()
    $routes->post('updateProfile', 'User::updateProfile'); 
    // 4. Event Registration: Maps GET /user/registerEvent/5 -> User::registerEvent(5)
    $routes->get('registerEvent/(:num)', 'User::registerEvent/$1');
    // 5. Certificate Printing: Maps GET /user/printCertificate/5 -> User::printCertificate(5)
    $routes->get('printCertificate/(:num)', 'User::printCertificate/$1');
});

// Events: REMOVED leading slashes for consistency
// Original: $routes->GET('/events', 'Events::index');
$routes->GET('events', 'Events::index');
// Original: $routes->GET('/events/detail/(:num)', 'Events::detail/$1');
$routes->GET('events/detail/(:num)', 'Events::detail/$1');
// Original: $routes->GET('/events/register/(:num)', 'Events::register/$1');
$routes->GET('events/register/(:num)', 'Events::register/$1');
// Original: $routes->GET('/events/cancel/(:num)', 'Events::cancel/$1');
$routes->GET('events/cancel/(:num)', 'Events::cancel/$1');


// $routes->group('admin', static function ($routes) {
//     $routes->GET('dashboard', 'Admin::dashboard');
//     $routes->POST('create', 'Admin::createUser');
//     $routes->GET('edit/(:num)', 'Admin::editUser/$1');
//     $routes->POST('edit/(:num)', 'Admin::editUser/$1');
//     $routes->delete('delete/(:num)', 'Admin::deleteUser/$1');
// });

$routes->get('admin/dashboard', 'Admin::dashboard');
$routes->get('admin/users', 'Admin::users');
$routes->get('admin/events', 'Admin::events');
$routes->post('admin/createUser', 'Admin::createUser');
$routes->get('admin/edit/(:num)', 'Admin::editUser/$1');
$routes->post('admin/edit/(:num)', 'Admin::editUser/$1');
$routes->get('admin/deleteUser/(:num)', 'Admin::deleteUser/$1');

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
