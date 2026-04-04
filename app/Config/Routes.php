<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Mapping URL ke Controller Artikel
$routes->get('/', 'Artikel::home');
$routes->get('/about', 'Artikel::about');
$routes->get('/contact', 'Artikel::contact');

// Artikel User
$routes->get('/artikel', 'Artikel::index');
$routes->get('/artikel/(:any)', 'Artikel::view/$1');

// --- TAMBAHAN ROUTE LOGIN (MODUL 4) ---
$routes->get('/user/login', 'User::login');
$routes->post('/user/login_action', 'User::login_action');
$routes->get('/user/logout', 'User::logout');

// --- GROUP ADMIN DENGAN PROTEKSI FILTER ---
// Kita tambahkan ['filter' => 'auth'] agar grup ini dijaga "Satpam"
$routes->group('admin', ['filter' => 'auth'], function($routes) {
    $routes->get('artikel', 'Artikel::admin_index');
    $routes->add('artikel/add', 'Artikel::add');
    $routes->add('artikel/edit/(:num)', 'Artikel::edit/$1');
    $routes->get('artikel/delete/(:num)', 'Artikel::delete/$1');
});