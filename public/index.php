<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Belajar\App\Router;
use Belajar\Config\Database;
use Belajar\Controller\HomeController;
use Belajar\Controller\Auth\LoginController;
use Belajar\Controller\Auth\LogoutController;
use Belajar\Controller\Auth\RegisterController;


Database::getConnection('prod');

// Home Controller
Router::add('GET', '/', HomeController::class, 'index', []);


// User Controller
Router::add('GET', '/users/register', RegisterController::class, 'register', []);
Router::add('POST', '/users/register', RegisterController::class, 'postRegister', []);

Router::add('GET', '/users/login', LoginController::class, 'login', []);
Router::add('POST', '/users/login', LoginController::class, 'postLogin', []);

ROUTER::add('GET', '/users/logout', LogoutController::class, 'logout', []);
// ROUTER::add('GET', '/users/profile', UserController::class, 'updateProfile', [MustLoginMiddleware::class]);
// ROUTER::add('POST', '/users/profile', UserController::class, 'postUpdateProfile', [MustLoginMiddleware::class]);

// ROUTER::add('GET', '/users/password', UserController::class, 'updatePassword', [MustLoginMiddleware::class]);
// ROUTER::add('POST', '/users/password', UserController::class, 'postUpdatePassword', [MustLoginMiddleware::class]);


Router::run();