<?php

use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\UserLevelMiddleware;

$app->get('/', '\App\Controllers\HomeController:getHome')->setName('pocetna');
$app->get('/kalendar', '\App\Controllers\HomeController:getKalendar')->setName('kalendar');

$app->group('', function () {
    $this->get('/prijava', '\App\Controllers\AuthController:getPrijava')->setName('prijava');
    $this->post('/prijava', '\App\Controllers\AuthController:postPrijava');
})->add(new GuestMiddleware($container));

$app->group('', function () {
    $this->get('/odjava', '\App\Controllers\AuthController:getOdjava')->setName('odjava');
})->add(new AuthMiddleware($container));

// LEVELS: 0 - admin, 100 - pregled, 200 - izmena

// ADMIN
$app->group('', function () {
    // Korisnici
    $this->get('/admin/korisnik-lista', '\App\Controllers\KorisnikController:getKorisnikLista')->setName('admin.korisnik.lista');
    //Sale
    $this->get('/administracija/sale', '\App\Controllers\SalaController:getSale')->setName('sale');
    $this->post('/administracija/sale/dodavanje', '\App\Controllers\SalaController:postSalaDodavanje')->setName('sale.dodavanje');
    $this->post('/administracija/sale/brisanje', '\App\Controllers\SalaController:postSalaBrisanje')->setName('sale.brisanje');
    $this->post('/administracija/sale/detalj', '\App\Controllers\SalaController:postSalaDetalj')->setName('sale.detalj');
    $this->post('/administracija/sale/izmena', '\App\Controllers\SalaController:postSalaIzmena')->setName('sale.izmena');
    //Meniji
    $this->get('/administracija/meni', '\App\Controllers\MeniController:getMeni')->setName('meni');
    $this->get('/administracija/meni/dodavanje', '\App\Controllers\MeniController:getMeniDodavanje')->setName('meni.dodavanje.get');
    $this->post('/administracija/meni/dodavanje', '\App\Controllers\MeniController:postMeniDodavanje')->setName('meni.dodavanje.post');
    $this->post('/administracija/meni/brisanje', '\App\Controllers\MeniController:postMeniBrisanje')->setName('meni.brisanje');
    $this->get('/administracija/meni/detalj', '\App\Controllers\MeniController:getMeniDetalj')->setName('meni.detalj');
})->add(new UserLevelMiddleware($container, [0]));

// PREGLED
$app->group('', function () {
})->add(new UserLevelMiddleware($container, [100,200]));

// IZMENA
$app->group('', function () {
})->add(new UserLevelMiddleware($container, [200]));
