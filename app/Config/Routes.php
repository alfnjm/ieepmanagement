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
    // 5. Certificate Download: Maps GET /user/downloadCertificate/5 -> User::downloadCertificate(5)
    $routes->get('downloadCertificate/(:num)', 'User::downloadCertificate/$1'); 
    // 6. My Certificates: Maps GET /user/certificates -> User::certificates()
    $routes->get('certificates', 'User::certificates');
});

// Events: REMOVED leading slashes for consistency
$routes->GET('events', 'Events::index');
$routes->GET('events/detail/(:num)', 'Events::detail/$1');
$routes->GET('events/register/(:num)', 'Events::register/$1');
$routes->GET('events/cancel/(:num)', 'Events::cancel/$1');
$routes->get('event/(:num)', 'Events::detail/$1');

// Admin Routes
$routes->get('admin/dashboard', 'Admin::dashboard');
$routes->get('admin/users', 'Admin::users');
$routes->get('admin/events', 'Admin::events');
$routes->post('admin/createUser', 'Admin::createUser');
$routes->get('admin/edit/(:num)', 'Admin::editUser/$1');
$routes->post('admin/edit/(:num)', 'Admin::editUser/$1');
$routes->get('admin/deleteUser/(:num)', 'Admin::deleteUser/$1');

// Coordinator Routes
$routes->GET('coordinator/dashboard', 'Coordinator::dashboard');
$routes->get('coordinator/proposals', 'Coordinator::proposals');
$routes->get('coordinator/registration', 'Coordinator::registrationControl');
$routes->get('coordinator/upcoming', 'Coordinator::upcomingEvents');
$routes->get('coordinator/approvals', 'Coordinator::approvals');
$routes->get('coordinator/approve/(:num)', 'Coordinator::approve/$1');
$routes->get('coordinator/reject/(:num)', 'Coordinator::reject/$1');
$routes->get('coordinator/approveProposal/(:num)', 'Coordinator::approveProposal/$1');
$routes->get('coordinator/rejectProposal/(:num)', 'Coordinator::rejectProposal/$1');
$routes->match(['GET', 'POST'], 'coordinator/attendance', 'Coordinator::attendance');
$routes->get('coordinator/certificates', 'Coordinator::certificates', ['filter' => 'coordinator']);
$routes->get('coordinator/publish_certificates/(:num)', 'Coordinator::publish_certificates/$1', ['filter' => 'coordinator']);
// --- ADDED --- Moved template management from Organizer to Coordinator
$routes->match(['GET', 'POST'], 'coordinator/templates', 'Coordinator::templates', ['filter' => 'coordinator']);


// Organizer Routes
$routes->group('organizer', ['filter' => 'organizer'], function($routes) {
    $routes->get('dashboard', 'Organizer::dashboard');
    $routes->get('create-event', 'Organizer::createEvent');
    $routes->post('submitProposal', 'Organizer::submitProposal');
    $routes->get('my-proposals', 'Organizer::myProposals');
    
    // This route lets you VIEW the page (GET request)
    $routes->get('participants', 'Organizer::participants');
    $routes->post('participants/save', 'Organizer::saveAttendance', ['filter' => 'organizer']);
});