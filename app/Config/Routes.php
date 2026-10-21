<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Dashboard
$routes->get('/', 'Dashboard::index');

// Events
$routes->group('events', static function ($routes) {
    $routes->get('/', 'Events::index');
    $routes->get('new', 'Events::new');
    $routes->post('/', 'Events::create');
    $routes->get('(:num)', 'Events::show/$1');
    $routes->get('(:num)/edit', 'Events::edit/$1');
    $routes->post('(:num)', 'Events::update/$1');
    $routes->post('(:num)/delete', 'Events::delete/$1');

    // Event Provider assignments
    $routes->post('(:num)/providers', 'Events::addProvider/$1');
    $routes->post('(:num)/providers/(:num)', 'Events::updateProvider/$1/$2');
    $routes->post('(:num)/providers/(:num)/delete', 'Events::removeProvider/$1/$2');

    // Event Tasks
    $routes->post('(:num)/tasks', 'Events::addTask/$1');
    $routes->post('(:num)/tasks/(:num)/delete', 'Events::deleteTask/$1/$2');
    $routes->post('tasks/(:num)/toggle', 'Events::toggleTask/$1');
});

// Providers
$routes->group('providers', static function ($routes) {
    $routes->get('/', 'Providers::index');
    $routes->get('new', 'Providers::new');
    $routes->post('/', 'Providers::create');
    $routes->get('(:num)', 'Providers::show/$1');
    $routes->get('(:num)/edit', 'Providers::edit/$1');
    $routes->post('(:num)', 'Providers::update/$1');
    $routes->post('(:num)/delete', 'Providers::delete/$1');
});

// Categories
$routes->group('categories', static function ($routes) {
    $routes->get('/', 'Categories::index');
    $routes->post('/', 'Categories::create');
    $routes->post('(:num)/delete', 'Categories::delete/$1');
});
