<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->post('/Home', 'Home::index');
$routes->get('/Home/logout', 'Home::logout');
$routes->post('/Home/Otp', 'Home::Otp');
$routes->post('/Home/OtpVerify', 'Home::OtpVerify');
$routes->get('/Cart', 'ShoppingCart::index');
$routes->get('/Menu', 'Menu::index');
$routes->get('/Book', 'Book::index');
$routes->get('/About', 'About::index');
$routes->get('/CreateAccount', 'CreateAccount::index');
$routes->post('/CreateAccount', 'CreateAccount::index');
