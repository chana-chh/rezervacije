<?php

use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;

$app->get('/', '\App\Controllers\HomeController:getHome')->setName('pocetna');
$app->get('/kalendar', '\App\Controllers\HomeController:getKalendar')->setName('kalendar');

//Sale
$app->get('/administracija/sale', '\App\Controllers\SalaController:getSale')->setName('sale');
$app->post('/administracija/sale/dodavanje', '\App\Controllers\SalaController:postSalaDodavanje')->setName('sale.dodavanje');
$app->post('/administracija/sale/brisanje', '\App\Controllers\SalaController:postSalaBrisanje')->setName('sale.brisanje');
$app->post('/administracija/sale/detalj', '\App\Controllers\SalaController:postSalaDetalj')->setName('sale.detalj');

$app->group('', function () {
    $this->get('/prijava', '\App\Controllers\AuthController:getPrijava')->setName('prijava');
    $this->post('/prijava', '\App\Controllers\AuthController:postPrijava');
})->add(new GuestMiddleware($container));

$app->group('', function () {
    $this->get('/odjava', '\App\Controllers\AuthController:getOdjava')->setName('odjava');
    $this->get('/admin/korisnik-lista', '\App\Controllers\KorisnikController:getKorisnikLista')->setName('admin.korisnik.lista');
})->add(new AuthMiddleware($container));
