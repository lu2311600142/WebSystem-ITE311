<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false); // Better security

// ============================================
// PUBLIC ROUTES (No authentication required)
// ============================================

// Home routes
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// Authentication routes (public access)
$routes->match(['get', 'post'], 'register', 'Auth::register');
$routes->match(['get', 'post'], 'login', 'Auth::login');
$routes->get('logout', 'Auth::logout');

// ============================================
// PROTECTED ROUTES (Authentication required)
// ============================================

// Announcements (accessible to all logged-in users)
// Protected by 'auth' filter only
$routes->get('announcements', 'Announcement::index');

// General dashboard fallback (if needed)
$routes->get('dashboard', 'Auth::dashboard');

// ============================================
// ADMIN ROUTES (Admin role only)
// Protected by both 'auth' and 'roleauth' filters
// ============================================
$routes->group('admin', function($routes) {
    $routes->get('dashboard', 'Admin::dashboard');
    $routes->get('course/(:num)/upload', 'Materials::upload/$1');
    $routes->post('course/(:num)/upload', 'Materials::upload/$1');
});

// ============================================
// TEACHER ROUTES (Teacher + Admin roles)
// Protected by both 'auth' and 'roleauth' filters
// ============================================
$routes->group('teacher', function($routes) {
    $routes->get('dashboard', 'Teacher::dashboard');
});

// ============================================
// STUDENT ROUTES (Student role only)
// Protected by 'auth' filter
// ============================================
$routes->group('student', function($routes) {
    $routes->get('dashboard', 'Student::dashboard');
});

// ============================================
// COURSE & ENROLLMENT ROUTES
// ============================================
$routes->post('course/enroll', 'Course::enroll');
$routes->get('courses', 'Course::index');

// ============================================
// MATERIAL ROUTES
// ============================================
$routes->get('materials/view/(:num)', 'Materials::view/$1');
$routes->get('materials/download/(:num)', 'Materials::download/$1');
$routes->get('materials/delete/(:num)', 'Materials::delete/$1');