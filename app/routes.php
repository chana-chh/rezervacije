<?php

use App\Middlewares\AuthMiddleware;
use App\Middlewares\GuestMiddleware;
use App\Middlewares\UserLevelMiddleware;

$app->get('/', '\App\Controllers\HomeController:getHome')->setName('pocetna');
$app->get('/validation', '\App\Controllers\ValidationController:getValidation')->setName('valid');
$app->post('/validation', '\App\Controllers\ValidationController:postValidation');

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
    $this->get('/admin/meni/pretraga', '\App\Controllers\MeniController:getMeniPretraga')->setName('meni.pretraga');
    $this->post('/admin/meni/pretraga', '\App\Controllers\MeniController:postMeniPretraga');
    //Tipovi događaja
    $this->get('/admin/tip', '\App\Controllers\TipDogadjajaController:getTipove')->setName('tip_dogadjaja');
    $this->post('/admin/tip/dodavanje', '\App\Controllers\TipDogadjajaController:postTipDodavanje')->setName('tip_dogadjaja.dodavanje');
    $this->post('/admin/tip/brisanje', '\App\Controllers\TipDogadjajaController:postTipBrisanje')->setName('tip_dogadjaja.brisanje');
    $this->post('/admin/tip/detalj', '\App\Controllers\TipDogadjajaController:postTipDetalj')->setName('tip_dogadjaja.detalj');
    $this->post('/admin/tip/izmena', '\App\Controllers\TipDogadjajaController:postTipIzmena')->setName('tip_dogadjaja.izmena');
    //Logovi
    $this->get('/admin/logovi', '\App\Controllers\LogController:getLog')->setName('logovi');
    $this->get('/admin/logovi/pretraga', '\App\Controllers\LogController:getLogPretraga')->setName('logovi.pretraga');
    $this->post('/admin/logovi/pretraga', '\App\Controllers\LogController:postLogPretraga');
})->add(new UserLevelMiddleware($container, [0]));

// PREGLED
$app->group('', function () {
    $this->get('/kalendar[/{datum}]', '\App\Controllers\HomeController:getKalendar')->setName('kalendar');
})->add(new UserLevelMiddleware($container, [100,200]));

// IZMENA
$app->group('', function () {
    // Ugovori
    $this->get('/ugovori', '\App\Controllers\UgovorController:getUgovor')->setName('ugovori');
    $this->get('/ugovori/pretraga', '\App\Controllers\UgovorController:getUgovorPretraga')->setName('ugovori.pretraga');
    $this->post('/ugovori/pretraga', '\App\Controllers\UgovorController:postUgovorPretraga');
    $this->get('/ugovori/dodavanje', '\App\Controllers\UgovorController:getUgovorDodavanje')->setName('ugovor.dodavanje.get');
    $this->post('/ugovori/dodavanje', '\App\Controllers\UgovorController:postUgovorDodavanje')->setName('ugovor.dodavanje.post');
    $this->get('/ugovori/izmena/{id}', '\App\Controllers\UgovorController:getUgovorIzmena')->setName('ugovor.izmena.get');
    $this->post('/ugovori/izmena', '\App\Controllers\UgovorController:postUgovorIzmena')->setName('ugovor.izmena.post');
    $this->post('/ugovori/brisanje', '\App\Controllers\UgovorController:postUgovorBrisanje')->setName('ugovor.brisanje');
    $this->get('/ugovori/detalj/{id}', '\App\Controllers\UgovorController:getUgovorDetalj')->setName('ugovor.detalj');
    $this->post('/ugovori/uplata', '\App\Controllers\UgovorController:postUgovorUplata')->setName('ugovor.uplata');

    // Termini
    $this->get('/termin/ugovori/dodavanje/{termin_id}', '\App\Controllers\TerminUgovorController:getUgovorDodavanjeTermin')->setName('termin.dodavanje.ugovor');
    $this->post('/termin/ugovori/dodavanje', '\App\Controllers\TerminUgovorController:postUgovorDodavanjeTermin')->setName('termin.dodavanje.ugovor.post');
    $this->get('/termin/ugovori/izmena/{id}', '\App\Controllers\TerminUgovorController:getUgovorIzmenaTermin')->setName('termin.ugovor.izmena.get');
    $this->post('/termin/ugovori/izmena', '\App\Controllers\TerminUgovorController:postUgovorIzmenaTermin')->setName('termin.ugovor.izmena.post');
    $this->post('/termin/ugovori/brisanje', '\App\Controllers\TerminUgovorController:postUgovorBrisanjeTermin')->setName('termin.ugovor.brisanje');
    $this->get('/termin/ugovori/detalj/{id}', '\App\Controllers\TerminUgovorController:getUgovorDetaljTermin')->setName('termin.ugovor.detalj.get');
    $this->post('/ugovori/uplata/detalj', '\App\Controllers\TerminUgovorController:postUplataDetalj')->setName('ugovor.uplata.detalj');
    $this->post('/ugovori/izmena/uplata', '\App\Controllers\TerminUgovorController:postIzmenaUplata')->setName('ugovor.izmena.uplata');
    $this->post('/ugovori/brisanje/uplata', '\App\Controllers\TerminUgovorController:postUplataBrisanje')->setName('ugovor.brisanje.uplata');

    $this->post('/termin/ugovori/uplata', '\App\Controllers\TerminUgovorController:postUgovorUplataTermin')->setName('termin.ugovor.uplata');

    $this->post('/termin/dokument/dodavanje', '\App\Controllers\DokumentController:postDokumentDodavanje')->setName('dokument.dodavanje');


    $this->get('/termin/ugovori/uplate/{id}', '\App\Controllers\TerminUgovorController:getUgovorUplateLista')->setName('ugovor.uplate.lista');

    $this->get('/termin/pregled[/{datum}]', '\App\Controllers\TerminController:getTerminPregled')->setName('termin.pregled.get');
    $this->get('/termin/detalj[/{id}]', '\App\Controllers\TerminController:getTerminDetalj')->setName('termin.detalj.get');
    $this->get('/termin/dodavanje[/{datum}]', '\App\Controllers\TerminController:getTerminDodavanje')->setName('termin.dodavanje.get');
    $this->post('/termin/dodavanje', '\App\Controllers\TerminController:postTerminDodavanje')->setName('termin.dodavanje.post');
    $this->get('/termin/izmena/{id}', '\App\Controllers\TerminController:getTerminIzmena')->setName('termin.izmena.get');
    $this->post('/termin/izmena', '\App\Controllers\TerminController:postTerminIzmena')->setName('termin.izmena.post');
    $this->post('/termin/brisanje', '\App\Controllers\TerminController:postTerminBrisanje')->setName('termin.brisanje.post');
    $this->post('/termin/zakljucivanje', '\App\Controllers\TerminController:postTerminZakljucivanje')->setName('termin.zakljucivanje.post');
})->add(new UserLevelMiddleware($container, [200]));
