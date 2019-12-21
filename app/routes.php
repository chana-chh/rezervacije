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
    $this->post('/admin/korisnik-brisanje', '\App\Controllers\KorisnikController:postKorisnikBrisanje')->setName('admin.korisnik.brisanje');
    $this->post('/admin/korisnik-dodavanje', '\App\Controllers\KorisnikController:postKorisnikDodavanje')->setName('admin.korisnik.dodavanje');
    $this->post('/admin/korisnik-izmena', '\App\Controllers\KorisnikController:postKorisnikIzmena')->setName('admin.korisnik.izmena');
    $this->post('/admin/korisnik-detalj', '\App\Controllers\KorisnikController:postKorisnikDetalj')->setName('admin.korisnik.detalj');
    //Sale
    $this->get('/admin/sale', '\App\Controllers\SalaController:getSale')->setName('sale');
    $this->post('/admin/sale/dodavanje', '\App\Controllers\SalaController:postSalaDodavanje')->setName('sale.dodavanje');
    $this->post('/admin/sale/brisanje', '\App\Controllers\SalaController:postSalaBrisanje')->setName('sale.brisanje');
    $this->post('/admin/sale/detalj', '\App\Controllers\SalaController:postSalaDetalj')->setName('sale.detalj');
    $this->post('/admin/sale/izmena', '\App\Controllers\SalaController:postSalaIzmena')->setName('sale.izmena');
    //Meniji
    $this->get('/admin/meni', '\App\Controllers\MeniController:getMeni')->setName('meni');
    $this->get('/admin/meni/dodavanje', '\App\Controllers\MeniController:getMeniDodavanje')->setName('meni.dodavanje.get');
    $this->post('/admin/meni/dodavanje', '\App\Controllers\MeniController:postMeniDodavanje')->setName('meni.dodavanje.post');
    $this->get('/admin/meni/izmena/{id}', '\App\Controllers\MeniController:getMeniIzmena')->setName('meni.izmena.get');
    $this->post('/admin/meni/izmena', '\App\Controllers\MeniController:postMeniIzmena')->setName('meni.izmena.post');
    $this->post('/admin/meni/brisanje', '\App\Controllers\MeniController:postMeniBrisanje')->setName('meni.brisanje');
    $this->get('/admin/meni/detalj/{id}', '\App\Controllers\MeniController:getMeniDetalj')->setName('meni.detalj');
})->add(new UserLevelMiddleware($container, [0]));

// PREGLED
$app->group('', function () {
})->add(new UserLevelMiddleware($container, [100,200]));

// IZMENA
$app->group('', function () {
    $this->get('/termin', '\App\Controllers\TerminController:getTerminDodavanje')->setName('termin.dodavanje.get');
    $this->post('/termin', '\App\Controllers\TerminController:postTerminDodavanje')->setName('termin.dodavanje.post');
})->add(new UserLevelMiddleware($container, [200]));
