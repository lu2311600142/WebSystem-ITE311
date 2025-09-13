    <?php

    $routes->setDefaultController('Home');
    $routes->setDefaultMethod('index');

    $routes->get('/', 'Home::index');
    $routes->get('about', 'Home::about'); 
    $routes->get('contact', 'Home::contact');

    $routes->get('register', 'Auth::register');
    $routes->post('register', 'Auth::register');
    $routes->get('login', 'Auth::login');
    $routes->post('login', 'Auth::login');
    $routes->get('logout', 'Auth::logout');
    $routes->get('dashboard', 'Auth::dashboard');

