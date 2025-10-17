<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');

// Home routes
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// Authentication routes
$routes->match(['get', 'post'], 'register', 'Auth::register');
$routes->match(['get', 'post'], 'login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// Announcements (accessible to all logged-in users)
$routes->get('announcements', 'Announcement::index');

// General dashboard (fallback)
$routes->get('dashboard', 'Auth::dashboard');

// TASK 4: Protected Admin routes with RoleAuth filter
$routes->group('admin', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('course/(:num)/upload', 'Materials::upload/$1');
    $routes->post('course/(:num)/upload', 'Materials::upload/$1');
});

// TASK 4: Protected Teacher routes with RoleAuth filter
$routes->group('teacher', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
});

// TASK 4: Protected Student routes with RoleAuth filter (if you have any)
$routes->group('student', ['filter' => 'roleauth'], function($routes) {
    $routes->get('dashboard', 'Student::dashboard');
});

// Course enrollment routes
$routes->post('course/enroll', 'Course::enroll');
$routes->get('courses', 'Course::index');

// Material routes (not in admin group)
$routes->get('materials/view/(:num)', 'Materials::view/$1');
$routes->get('materials/download/(:num)', 'Materials::download/$1');
$routes->get('materials/delete/(:num)', 'Materials::delete/$1');

// Turn off auto-routing for better control
$routes->setAutoRoute(false);