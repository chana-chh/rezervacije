<?php

use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\LekarMiddleware;

$app->get('/', '\App\Controllers\HomeController:getHome')->setName('pocetna');

$app->group('', function () {
    $this->get('/prijava', '\App\Controllers\AuthController:getPrijava')->setName('prijava');
    $this->post('/prijava', '\App\Controllers\AuthController:postPrijava');
})->add(new GuestMiddleware($container));

$app->group('', function () {
    $this->get('/odjava', '\App\Controllers\AuthController:getOdjava')->setName('odjava');
})->add(new AuthMiddleware($container));

// Autorizacija za razlicite korisnike (lekar, tehnicar, vlasnik, admin)
// TEHNICARI 100

// LEKARI 200
$app->group('', function () {
    $this->get('/lekar', '\App\Controllers\LekarController:getPocetna')->setName('lekar.pocetna');
})->add(new LekarMiddleware($container));

// VLASNICI 300

// ADMINISTRATORI 0
