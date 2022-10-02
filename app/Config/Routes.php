<?php

namespace Config;

use App\Controllers\Backend\AttributeController;
use App\Controllers\Backend\BrandController;
use App\Controllers\Backend\CategoryController;
use App\Controllers\Backend\DashboardController;
use App\Controllers\Backend\ProductController;
use App\Controllers\Backend\SliderController;
use App\Controllers\Backend\UsersController;

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
$routes->group(PATH_CMS_ADMIN, static function ($routes) {
    // CI4 Shield Package
    service('auth')->routes($routes);

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
        $routes->post('category-exist', [CategoryController::class, 'validateExistSlug'], ['as' => 'admin.category.validateExistSlug']);

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
        $routes->post('brand-exist', [BrandController::class, 'validateExistSlug'], ['as' => 'admin.brand.validateExistSlug']);

        // Edit Page
        $routes->get('(:num)/edit', [BrandController::class, 'edit'], ['as' => 'admin.brand.edit']);
        $routes->post('(:num)/update', [BrandController::class, 'update'], ['as' => 'admin.brand.update']);

        // Recycle Page
        $routes->get('recycle', [BrandController::class, 'recycle'], ['as' => 'admin.brand.recycle']);
        $routes->get('get-list-recycle', [BrandController::class, 'getListRecycle'], ['as' => 'admin.brand.getListRecycle']);
    });

    // Slider
    $routes->group('slider', static function ($routes) {
        // Index Page
        $routes->get('/', [SliderController::class, 'index'], ['as' => 'admin.slider.index']);
        $routes->get('get-list', [SliderController::class, 'getList'], ['as' => 'admin.slider.getList']);
        $routes->post('multi-status', [SliderController::class, 'multiStatus'], ['as' => 'admin.slider.multiStatus']);
        $routes->post('multi-delete', [SliderController::class, 'multiDelete'], ['as' => 'admin.slider.multiDelete']);

        // Create Page
        $routes->get('create', [SliderController::class, 'create'], ['as' => 'admin.slider.create']);
        $routes->post('store', [SliderController::class, 'store'], ['as' => 'admin.slider.store']);
        $routes->post('slider-exist', [SliderController::class, 'validateExistSlug'], ['as' => 'admin.slider.validateExistSlug']);

        // Edit Page
        $routes->get('(:num)/edit', [SliderController::class, 'edit'], ['as' => 'admin.slider.edit']);
        $routes->post('(:num)/update', [SliderController::class, 'update'], ['as' => 'admin.slider.update']);

        // Recycle Page
        $routes->get('recycle', [SliderController::class, 'recycle'], ['as' => 'admin.slider.recycle']);
        $routes->get('get-list-recycle', [SliderController::class, 'getListRecycle'], ['as' => 'admin.slider.getListRecycle']);
    });

    // Attribute
    $routes->group('attribute', static function ($routes) {
        // Index Page
        $routes->get('/', [AttributeController::class, 'index'], ['as' => 'admin.attribute.index']);
        $routes->get('get-list', [AttributeController::class, 'getList'], ['as' => 'admin.attribute.getList']);
        $routes->post('multi-status', [AttributeController::class, 'multiStatus'], ['as' => 'admin.attribute.multiStatus']);
        $routes->post('multi-delete', [AttributeController::class, 'multiDelete'], ['as' => 'admin.attribute.multiDelete']);

        // Create Page
        $routes->get('create', [AttributeController::class, 'create'], ['as' => 'admin.attribute.create']);
        $routes->post('store', [AttributeController::class, 'store'], ['as' => 'admin.attribute.store']);

        // Edit Page
        $routes->get('(:num)/edit', [AttributeController::class, 'edit'], ['as' => 'admin.attribute.edit']);
        $routes->post('(:num)/update', [AttributeController::class, 'update'], ['as' => 'admin.attribute.update']);

        // Recycle Page
        $routes->get('recycle', [AttributeController::class, 'recycle'], ['as' => 'admin.attribute.recycle']);
        $routes->get('get-list-recycle', [AttributeController::class, 'getListRecycle'], ['as' => 'admin.attribute.getListRecycle']);
    });

    // Product
    $routes->group('product', static function ($routes) {
        // Index Page
        $routes->get('/', [ProductController::class, 'index'], ['as' => 'admin.product.index']);
        $routes->get('get-list', [ProductController::class, 'getList'], ['as' => 'admin.product.getList']);
        $routes->post('multi-status', [ProductController::class, 'multiStatus'], ['as' => 'admin.product.multiStatus']);
        $routes->post('multi-delete', [ProductController::class, 'multiDelete'], ['as' => 'admin.product.multiDelete']);

        // Create Page
        $routes->get('create', [ProductController::class, 'create'], ['as' => 'admin.product.create']);
        $routes->post('store', [ProductController::class, 'store'], ['as' => 'admin.product.store']);
        $routes->post('product-exist', [ProductController::class, 'validateExistSlug'], ['as' => 'admin.product.validateExistSlug']);
        $routes->post('load-attribute', [ProductController::class, 'attributeLoadList'], ['as' => 'admin.product.attributeLoadList']);

        // Edit Page
        $routes->get('(:num)/edit', [ProductController::class, 'edit'], ['as' => 'admin.product.edit']);
        $routes->post('(:num)/update', [ProductController::class, 'update'], ['as' => 'admin.product.update']);

        // Recycle Page
        $routes->get('recycle', [ProductController::class, 'recycle'], ['as' => 'admin.product.recycle']);
        $routes->get('get-list-recycle', [ProductController::class, 'getListRecycle'], ['as' => 'admin.product.getListRecycle']);
    });

    // Users
    $routes->group('users', static function ($routes) {
        // Activity Page
        $routes->get('activity', [UsersController::class, 'activity'], ['as' => 'admin.users.activity']);

        // Change Password Page
        $routes->get('change-password', [UsersController::class, 'changePassword'], ['as' => 'admin.users.changePassword']);

        // Update Users
        $routes->post('(:num)/update', [UsersController::class, 'update'], ['as' => 'admin.users.update']);
        $routes->post('(:num)/change-password', [UsersController::class, 'postChangePassword'], ['as' => 'admin.users.postChangePassword']);
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
