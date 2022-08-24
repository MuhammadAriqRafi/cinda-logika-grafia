<?php

namespace Config;

$routes = Services::routes();

if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();

// Frontend Routes
$routes->group('', ['namespace' => 'App\Controllers\Frontend'], function ($routes) {
    $routes->get('/', 'Home::Index');
    $routes->get('/about', 'Home::About');
    $routes->get('/services', 'Home::Services');
    $routes->get('/insight', 'Home::Insight');
    $routes->get('/contact', 'Home::Contact');
    $routes->get('/detail/(:any)', 'Home::Detail/$1');
});

// Backend Routes
$routes->group('backend', ['namespace' => 'App\Controllers\Backend'], function ($routes) {
    $routes->get('/', 'PageController::index', ['as' => 'backend.dashboard']);
    $routes->get('login', 'AuthController::index', ['as' => 'backend.login', 'filter' => 'guest']);
    $routes->post('authenticate', 'AuthController::authenticate', ['filter' => 'guest']);

    $routes->group('', ['filter' => 'auth', 'namespace' => 'App\Controllers\Backend'], function ($routes) {
        $routes->group('posts', function ($routes) {
            $routes->get('/', 'PostController::index', ['as' => 'backend.posts.index']);
            $routes->get('(:any)', 'PostController::$1');
            $routes->post('(:any)', 'PostController::$1');
            $routes->patch('(:any)', 'PostController::$1');
            $routes->delete('(:any)', 'PostController::$1');
        });

        $routes->group('categories', function ($routes) {
            $routes->get('/', 'CategoryController::index', ['as' => 'backend.categories.index']);
            $routes->get('(:any)', 'CategoryController::$1');
            $routes->post('(:any)', 'CategoryController::$1');
            $routes->patch('(:any)', 'CategoryController::$1');
            $routes->delete('(:any)', 'CategoryController::$1');
        });

        $routes->group('guestbooks', function ($routes) {
            $routes->get('/', 'GuestbookController::index', ['as' => 'backend.guestbooks.index']);
            $routes->get('(:any)', 'GuestbookController::$1');
            $routes->post('(:any)', 'GuestbookController::$1');
            $routes->patch('(:any)', 'GuestbookController::$1');
            $routes->delete('(:any)', 'GuestbookController::$1');
        });

        $routes->post('logout', 'AuthController::logout', ['as' => 'logout']);
    });
});

if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
