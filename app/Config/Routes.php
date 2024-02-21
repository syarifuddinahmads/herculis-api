<?php

namespace Config;

use App\Controllers\NewspaperPrice;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

// Api Mobile
$routes->group('api/v1', ["filter" => 'cors'],  function ($routes) {
    $routes->group('auth', [], function ($routes) {
        $routes->post('login', 'Login::login');
        $routes->post('register', 'Register::create');
        $routes->post('request-reset-password','ForgotPassword::requestResetPassword');
        $routes->post('update-new-password','ForgotPassword::updateNewPassword');
    });

    $routes->group('', ["filter" => 'auth'],  function ($routes) {

        $routes->resource('user');
        $routes->resource('publisher');
        $routes->resource('subscription');
        $routes->resource('transaction');
        $routes->resource('newspaper');

        // News Paper Price
        $routes->get('newspaper-price', 'NewspaperPrice::index');
        $routes->get('newspaper-price/:num', 'NewspaperPrice::show');
        $routes->post('newspaper-price', 'NewspaperPrice::index');
        $routes->get('newspaper-price', 'NewspaperPrice::index');
        $routes->get('newspaper-price', 'NewspaperPrice::index');
    });
});



/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
