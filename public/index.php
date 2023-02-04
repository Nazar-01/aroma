<?php

session_start();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once '../vendor/autoload.php';

use App\Middleware\Middleware;

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    $r->addGroup('/admin', function (FastRoute\RouteCollector $r) {
        $r->addRoute('GET', '', 'App\Admin\Controllers\ProductsController@index');

        $r->addRoute('GET', '/products', 'App\Admin\Controllers\ProductsController@index');
        $r->addRoute('GET', '/products/create', 'App\Admin\Controllers\ProductsController@create');
        $r->addRoute('GET', '/product/{id:\d+}/edit', 'App\Admin\Controllers\ProductsController@edit');
        $r->addRoute('POST', '/products/store', 'App\Admin\Controllers\ProductsController@store');
        $r->addRoute('POST', '/product/update', 'App\Admin\Controllers\ProductsController@update');
        $r->addRoute('GET', '/product/{id:\d+}/delete', 'App\Admin\Controllers\ProductsController@delete');

        $r->addRoute('GET', '/categories', 'App\Admin\Controllers\CategoriesController@index');
        $r->addRoute('GET', '/categories/create', 'App\Admin\Controllers\CategoriesController@create');
        $r->addRoute('GET', '/category/{id:\d+}/edit', 'App\Admin\Controllers\CategoriesController@edit');
        $r->addRoute('GET', '/category/{id:\d+}/delete', 'App\Admin\Controllers\CategoriesController@delete');
        $r->addRoute('POST', '/categories/store', 'App\Admin\Controllers\CategoriesController@store');
        $r->addRoute('POST', '/category/update', 'App\Admin\Controllers\CategoriesController@update');

        $r->addRoute('GET', '/orders', 'App\Admin\Controllers\OrdersController@index');

        $r->addRoute('GET', '/reviews', 'App\Admin\Controllers\ReviewsController@index');
        $r->addRoute('GET', '/reviews/{id}/approve', 'App\Admin\Controllers\ReviewsController@approve');
        $r->addRoute('GET', '/reviews/{id}/prohibit', 'App\Admin\Controllers\ReviewsController@prohibit');
        $r->addRoute('GET', '/review/{id}/delete', 'App\Admin\Controllers\ReviewsController@delete');

        $r->addRoute('GET', '/users', 'App\Admin\Controllers\UsersController@index');
        $r->addRoute('GET', '/users/create', 'App\Admin\Controllers\UsersController@create');
        $r->addRoute('POST', '/users/store', 'App\Admin\Controllers\UsersController@store');
        $r->addRoute('GET', '/user/{id:\d+}/delete', 'App\Admin\Controllers\UsersController@delete');
        $r->addRoute('GET', '/user/{id:\d+}/edit', 'App\Admin\Controllers\UsersController@edit');
        $r->addRoute('POST', '/user/update', 'App\Admin\Controllers\UsersController@update');

        $r->addRoute('GET', '/subs', 'App\Admin\Controllers\SubscriptionsController@index');
        $r->addRoute('GET', '/subs/create', 'App\Admin\Controllers\SubscriptionsController@create');
        $r->addRoute('GET', '/sub/{id:\d+}/edit', 'App\Admin\Controllers\SubscriptionsController@edit');
        $r->addRoute('POST', '/subs/store', 'App\Admin\Controllers\SubscriptionsController@store');
        $r->addRoute('GET', '/sub/{id:\d+}/delete', 'App\Admin\Controllers\SubscriptionsController@delete');
        $r->addRoute('POST', '/sub/update', 'App\Admin\Controllers\SubscriptionsController@update');
    });

    $r->addGroup('/', function (FastRoute\RouteCollector $r) {
        $r->addRoute('GET', '', 'App\Front\Controllers\IndexController@index');

        $r->addRoute('GET', 'products', 'App\Front\Controllers\ProductsController@getAllProducts');

        $r->addRoute('GET', 'products/{id:\d+}', 'App\Front\Controllers\ProductsController@getProductsFromCategory');

        $r->addRoute('GET', 'categories', 'App\Front\Controllers\CategoriesController@index');

        $r->addRoute('GET', 'orders', 'App\Front\Controllers\OrdersController@index');

        $r->addRoute('GET', 'product/{id:\d+}', 'App\Front\Controllers\ProductsController@getOneProduct');

        $r->addRoute('GET', 'checkout', 'App\Front\Controllers\CartController@checkout');

        $r->addRoute('GET', 'login', 'App\Front\Controllers\AuthController@loginPage');
        $r->addRoute('GET', 'logout', 'App\Front\Controllers\AuthController@logout');

        $r->addRoute('GET', 'register', 'App\Front\Controllers\AuthController@registerPage');
        $r->addRoute('POST', 'register/store', 'App\Front\Controllers\AuthController@registerStore');

        $r->addRoute('GET', 'cart', 'App\Front\Controllers\CartController@index');

        $r->addRoute('GET', 'profile', 'App\Front\Controllers\UsersController@profile');

        $r->addRoute('POST', 'entrance', 'App\Front\Controllers\AuthController@login');

        $r->addRoute('POST', 'update-photo', 'App\Front\Controllers\UsersController@updatePhoto');
        $r->addRoute('GET', 'delete-photo', 'App\Front\Controllers\UsersController@deletePhoto');

        $r->addRoute('POST', 'update-user', 'App\Front\Controllers\UsersController@update');
        $r->addRoute('GET', 'delete-user', 'App\Front\Controllers\UsersController@delete');

        $r->addRoute('POST', 'subscribe', 'App\Front\Controllers\SubscriptionsController@create');

        $r->addRoute('POST', 'add-to-cart', 'App\Front\Controllers\CartController@add');

        $r->addRoute('GET', 'clear-cart', 'App\Front\Controllers\CartController@getClear');

        $r->addRoute('POST', 'update-cart', 'App\Front\Controllers\CartController@update');

        $r->addRoute('POST', 'get-products-count', 'App\Front\Controllers\CartController@getProductsCount');

        $r->addRoute('POST', 'make-order', 'App\Front\Controllers\CartController@makeOrder');

        $r->addRoute('POST', 'leave-review', 'App\Front\Controllers\ReviewsController@create');

    });
});

$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
    header('Location: /');
    break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
    $allowedMethods = $routeInfo[1];

    break;
    case FastRoute\Dispatcher::FOUND:

    $middleware = new Middleware($routeInfo[1], $routeInfo[2], $httpMethod);
    $middleware->check();
    
    break;
}
