<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setAutoRoute(true);

// Home routes
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('contact', 'Home::contact');

// Authentication routes
$routes->match(['get', 'post'], 'register', 'Auth::register');
$routes->match(['get', 'post'], 'login', 'Auth::login');
$routes->get('dashboard', 'Auth::dashboard');
$routes->get('logout', 'Auth::logout');

// Role-based dashboard routes
$routes->get('admin/dashboard', 'Admin::dashboard');
$routes->get('teacher/dashboard', 'Teacher::dashboard'); 
$routes->get('student/dashboard', 'Student::dashboard');

// Course enrollment routes
$routes->post('course/enroll', 'Course::enroll');
$routes->get('courses', 'Course::index');

