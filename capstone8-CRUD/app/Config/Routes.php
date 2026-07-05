<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('/', 'Home::index', ['filter' => 'auth']);

$routes->get('login', 'AuthController::login');
$routes->post('login', 'AuthController::login');
$routes->get('logout', 'AuthController::logout');

$routes->get('produk', 'ProdukController::index', ['filter' => 'auth']);
$routes->post('produk/edit/(:any)', 'ProdukController::edit/$1', ['filter' => 'auth']);
$routes->post('produk/edit/(:any)', 'ProdukController::edit/$1', ['filter' => 'auth']);
$routes->get('produk/delete/(:any)', 'ProdukController::delete/$1', ['filter' => 'auth']);

$routes->get('keranjang', 'TransaksiController::index', ['filter' => 'auth']);

$routes->get('faq', 'Home::faq', ['filter' => 'auth']);
$routes->get('profile', 'Home::profile', ['filter' => 'auth']);
$routes->get('contact', 'Home::contact', ['filter' => 'auth']);