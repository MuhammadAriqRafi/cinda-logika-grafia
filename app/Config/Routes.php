<?php

namespace Config;

$routes = Services::routes();

if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers\Frontend');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

$routes->get('/', 'Home::Index');
$routes->get('/about', 'Home::About');
$routes->get('/services', 'Home::Services');
$routes->get('/insight', 'Home::Insight');
$routes->get('/contact', 'Home::Contact');
$routes->get('/detail/(:any)', 'Home::Detail/$1');

// Backend Routes
$routes->group('backend', ['namespace' => 'App\Controllers\Backend'], function ($routes) {
    // Dashboard
    $routes->get('/', 'PageController::index');

    // Posts
    $routes->group('posts', function ($routes) {
        $routes->get('/', 'PostController::index', ['as' => 'backend.posts.index']);
        $routes->get('(:any)', 'PostController::$1');
        $routes->post('(:any)', 'PostController::$1');
        $routes->patch('(:any)', 'PostController::$1');
        $routes->delete('(:any)', 'PostController::$1');
    });
});

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
