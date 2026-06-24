<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Halaman utama
$routes->get('/', 'Artikel::home');
$routes->get('/about', 'Artikel::about');
$routes->get('/contact', 'Artikel::contact');

// Artikel User
$routes->get('/artikel', 'Artikel::index');
$routes->get('/artikel/(:any)', 'Artikel::view/$1');

// Route Login
$routes->get('/user/login', 'User::login');
$routes->post('/user/login_action', 'User::login_action');
$routes->get('/user/logout', 'User::logout');

// Group Admin dengan proteksi filter auth
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('artikel', 'Artikel::admin_index');
    $routes->match(['get', 'post'], 'artikel/add', 'Artikel::add');
    $routes->match(['get', 'post'], 'artikel/edit/(:num)', 'Artikel::edit/$1');
    $routes->get('artikel/delete/(:num)', 'Artikel::delete/$1');
});

$routes->get('/ajax', 'AjaxController::index');
$routes->get('/ajax/getData', 'AjaxController::getData');
$routes->post('/ajax/add', 'AjaxController::add'); // <-- Sudah ditambahkan untuk fitur tambah data
// ==========================================
// Route untuk RESTful API (Praktikum Modul 10)
// ==========================================
$routes->resource('post');
$routes->delete('/ajax/delete/(:num)', 'AjaxController::delete/$1');