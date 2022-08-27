<?php

namespace Config;

use App\Controllers\Backend\BrandController;
use App\Controllers\Backend\DashboardController;
use App\Controllers\Backend\CategoryController;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

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
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.

// Backend
$routes->group('cms-admin', static function ($routes) {
    // Dashboard
    $routes->get('/', [DashboardController::class, 'index'], ['as' => 'admin.dashboard.index']);

    // Category
    $routes->group('category', static function ($routes) {
        // Index Page
        $routes->get('/', [CategoryController::class, 'index'], ['as' => 'admin.category.index']);
        $routes->get('get-list', [CategoryController::class, 'getList'], ['as' => 'admin.category.getList']);
        $routes->post('multi-status', [CategoryController::class, 'multiStatus'], ['as' => 'admin.category.multiStatus']);
        $routes->post('multi-delete', [CategoryController::class, 'multiDelete'], ['as' => 'admin.category.multiDelete']);

        // Create Page
        $routes->get('create', [CategoryController::class, 'create'], ['as' => 'admin.category.create']);
        $routes->post('store', [CategoryController::class, 'store'], ['as' => 'admin.category.store']);
        $routes->post('category-exist', [CategoryController::class, 'categoryExistSlug'], ['as' => 'admin.category.categoryExistSlug']);

        // Edit Page
        $routes->get('(:num)/edit', [CategoryController::class, 'edit'], ['as' => 'admin.category.edit']);
        $routes->post('(:num)/update', [CategoryController::class, 'update'], ['as' => 'admin.category.update']);

        // Recycle Page
        $routes->get('recycle', [CategoryController::class, 'recycle'], ['as' => 'admin.category.recycle']);
        $routes->get('get-list-recycle', [CategoryController::class, 'getListRecycle'], ['as' => 'admin.category.getListRecycle']);
    });

    // Brand
    $routes->group('brand', static function ($routes) {
        // Index Page
        $routes->get('/', [BrandController::class, 'index'], ['as' => 'admin.brand.index']);
        $routes->get('get-list', [BrandController::class, 'getList'], ['as' => 'admin.brand.getList']);
        $routes->post('multi-status', [BrandController::class, 'multiStatus'], ['as' => 'admin.brand.multiStatus']);
        $routes->post('multi-delete', [BrandController::class, 'multiDelete'], ['as' => 'admin.brand.multiDelete']);

        // Create Page
        $routes->get('create', [BrandController::class, 'create'], ['as' => 'admin.brand.create']);
        $routes->post('store', [BrandController::class, 'store'], ['as' => 'admin.brand.store']);
        $routes->post('category-exist', [BrandController::class, 'categoryExistSlug'], ['as' => 'admin.brand.categoryExistSlug']);

        // Edit Page
        $routes->get('(:num)/edit', [BrandController::class, 'edit'], ['as' => 'admin.brand.edit']);
        $routes->post('(:num)/update', [BrandController::class, 'update'], ['as' => 'admin.brand.update']);

        // Recycle Page
        $routes->get('recycle', [BrandController::class, 'recycle'], ['as' => 'admin.brand.recycle']);
        $routes->get('get-list-recycle', [BrandController::class, 'getListRecycle'], ['as' => 'admin.brand.getListRecycle']);
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
